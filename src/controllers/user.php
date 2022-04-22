<?php
session_start();
include "config.php";

$requestMethod = $_SERVER['REQUEST_METHOD'];
$_POST = json_decode(file_get_contents("php://input"),true);
$action = htmlspecialchars($_POST["action"]);

// Create user
if ($action === "register") {
  $email = htmlspecialchars($_POST["email"]);
  $password = htmlspecialchars($_POST["password"]);
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  $query = $pdo->prepare("SELECT * FROM user WHERE email = :email");
  $query->execute(array(
    ":email" => $email
  ));

  // Check if user account already exist
  if($query->rowCount() == 0) {
    $query = $pdo->prepare("INSERT INTO user (email,password) VALUES(:email, :password)");
    $query->execute(array(
      ":email" => $email,
      ":password" => $hashed_password
    ));
    $insertId = $pdo->lastInsertId(); //last inserted item id

    $user_data = [];
    $user_data = array($insertId, $email, "", "", date("d. m. Y H:i:s"),date("d. m. Y H:i:s"));
    $_SESSION['user'] = $user_data;
    echo "1";
  } else {
    echo "0";
  }
  exit();
}

// Login user
if ($action === "login") {
  $email = htmlspecialchars($_POST["email"]);
  $password = htmlspecialchars($_POST["password"]);
  $query = $pdo->prepare("SELECT * FROM user WHERE email = :email");
  $query->execute(array(
    ":email" => $email
  ));
  $result = $query->fetch();
  if (password_verify($password, $result[2])) {
    $user_data = [];
    $user_data = array($result[0], $email, $result[3], $result[4], $result[5], $result[6]);
    $_SESSION['user'] = $user_data;
    echo "1";
  } else {
    echo "0";
  }
  exit();
}

// Logout user
if ($action === "logout") {
  // remove all session variables
  session_unset();
  // destroy the session
  session_destroy();
  exit();
}

// Read user
if ($requestMethod === "GET") {
  $id = $_SESSION['user'][0];
  $query = $pdo->prepare("SELECT * FROM user WHERE id = :id");
  $query->execute(array(
    ":id" => $id
  ));
  $userData = $query->fetch();
  echo json_encode($userData);
  exit();
}

// Update user
if ($requestMethod === "PUT") {
  $_PUT = json_decode(file_get_contents("php://input"),true);
  $id = $_SESSION['user'][0];
  $email = htmlspecialchars($_PUT["email"]);
  $name = htmlspecialchars($_PUT["name"]);
  $surname = htmlspecialchars($_PUT["surname"]);

  $query = $pdo->prepare("SELECT * FROM user WHERE id = :id");
  $query->execute(array(
    ":id" => $id
  ));
  $query2 = $pdo->prepare("SELECT * FROM user WHERE email = :email");
  $query2->execute(array(
    ":email" => $email
  ));
  $result = $query->fetch();
  if ($query2->rowCount() == 0 || $result[1] == $email) {
    $query = $pdo->prepare("UPDATE user SET email = :email, name = :name, surname = :surname WHERE id = :id");
    $query->execute(array(
      ":email" => $email,
      ":name" => $name,
      ":surname" => $surname,
      ":id" => $id
    ));
    $_SESSION['user'][1] = $email;
    $_SESSION['user'][2] = $name;
    $_SESSION['user'][3] = $surname;
    echo "1";
  } else {
    echo "0";
  }
  exit;
}

// Delete user
if ($requestMethod === "DELETE") {
  $id = $_SESSION['user'][0];
  $query = $pdo->prepare("DELETE FROM user WHERE id = ?");
  $query->execute(array(
    $id
  ));
  session_unset();
  session_destroy();
  echo 1;
  exit;
}