<?php
session_start();
include "config.php";
$data = json_decode(file_get_contents("php://input"));

// login
$email = $data->email;
$password = $data->password;

$userData = mysqli_query($con,"SELECT * FROM user WHERE email='".$email."'");
$resultId = mysqli_fetch_row($userData);

if(password_verify($password, $resultId[2])) {
  echo "Successfully log";
  $_SESSION['user_id']=$resultId[0];
  $user_data = [];
  $user_data = array($resultId[0], $resultId[1], $resultId[3], $resultId[4]);
  $_SESSION['user'] = $user_data;
  exit();
} else {
  echo "Wrong password or login";
}
