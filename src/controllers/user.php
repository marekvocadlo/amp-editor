<?php
session_start();
include "configNEW.php";

// Data from app
$data = json_decode(file_get_contents("php://input"));
$request = $data->request;

// Create user
if($request === "createUser") {
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
}

// Update user
if($request == 3){
  $id = $_SESSION['user_id'];
  $email = $data->email;
  $name = $data->name;
  $surname = $data->surname;

  mysqli_query($con,"UPDATE user SET email='".$email."',name='".$name."',surname='".$surname."' WHERE id=".$id);

  $_SESSION['user'][1] = $email;
  $_SESSION['user'][2] = $name;
  $_SESSION['user'][3] = $surname;
  echo "Update successfully";
  exit;
}

// Delete user
if($request == 4){
  $id = $_SESSION['user_id'];

  mysqli_query($con,"DELETE FROM user WHERE id=".$id);

  session_unset();
  session_destroy();
  echo "Delete successfully";
  exit;
}