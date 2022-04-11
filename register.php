<?php
header("Access-Control-Allow-Origin: *");
session_start();
include "config.php";
$data = json_decode(file_get_contents("php://input"));

$request = $data->request;

// Register new user
if($request == 2){
  $email = $data->email;
  $password = $data->password;
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  $userData = mysqli_query($con,"SELECT * FROM user WHERE email='".$email."'");
  if(mysqli_num_rows($userData) == 0){
    mysqli_query($con,"INSERT INTO user(password,email) VALUES('".$hashed_password."','".$email."')");
    echo "Insert successfully";
    $userData2 = mysqli_query($con,"SELECT * FROM user WHERE email='".$email."'");
    $resultId2 = mysqli_fetch_row($userData2);
    $_SESSION['user_id']=$resultId2[0];
    $user_data = [];
    $user_data = array($resultId2[0], $resultId2[1], $resultId2[3], $resultId2[4]);
    $_SESSION['user'] = $user_data;

  }else{
    echo "Username already exists.";
  }
}

// Update user
if($request == 3){
  $id = $_SESSION['user_id'];
  $name = $data->name;
  $surname = $data->surname;

  mysqli_query($con,"UPDATE user SET name='".$name."',surname='".$surname."' WHERE id=".$id);

  echo "Update successfully";
  exit;
}

// Delete user
if($request == 4){
  $id = $data->id;

  mysqli_query($con,"DELETE FROM user WHERE id=".$id);

  echo "Delete successfully";
  exit;
}