<?php
session_start();
include "config.php";
$data = json_decode(file_get_contents("php://input"));

// login
$email = $data->email;
$password = $data->password;
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$userData = mysqli_query($con,"SELECT * FROM user WHERE email='".$email."'");
$resultId = mysqli_fetch_row($userData);

if(password_verify($password, $resultId[2])) {
  echo "Successfully log";
  $_SESSION['user_id']=$resultId[0];
  $_SESSION['user_email']=$resultId[1];
  exit();
} else {
  echo "Wrong password or login";
}
