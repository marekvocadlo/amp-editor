<?php
session_start();
include "config.php";

// Data from app
$data = json_decode(file_get_contents("php://input"));
$request = $data->request;

// Create user
if ($request === "createUser") {
  $email = $data->email;
  $password = $data->password;
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  $query = $pdo->prepare("SELECT * FROM user WHERE email = :email");
  $query->execute(array(
    ":email" => $email
  ));
  if($query->rowCount() == 0) {
    $query = $pdo->prepare("INSERT INTO user (email,password) VALUES(?, ?)");
    $query->execute(array(
      $email,
      $hashed_password
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
if ($request === "loginUser") {
  $email = $data->email;
  $password = $data->password;
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
if ($request === "logoutUser") {
  // remove all session variables
  session_unset();
  // destroy the session
  session_destroy();
  exit();
}

// Read user
if ($request === "readUser") {
  if (!empty($_SESSION['user'])){
    echo json_encode($_SESSION['user']);
  }
}

// Control logged user
if (!empty($_SESSION['user'])) {

  // Read user data
    if ($request === "readUserData") {
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
    if ($request === "updateUser") {
      $id = $_SESSION['user'][0];
      $email = $data->email;
      $name = $data->name;
      $surname = $data->surname;

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
        $query = $pdo->prepare("UPDATE user SET email = ?, name = ?, surname = ? WHERE id = ?");
        $query->execute(array(
          $email,
          $name,
          $surname,
          $id
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
    if ($request === "deleteUser") {
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

}