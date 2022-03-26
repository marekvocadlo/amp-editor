<?php
include "config.php";
echo "feaeaff";
$data = json_decode(file_get_contents("php://input"));

$request = $data->request;

// Add new user
if($request == 2){
  $password = $data->password;
  $email = $data->email;

  $userData = mysqli_query($con,"SELECT * FROM user WHERE email='".$email."'");
  if(mysqli_num_rows($userData) == 0){
    mysqli_query($con,"INSERT INTO user(password,email) VALUES('".$password."','".$email."')");
    echo "Insert successfully";
  }else{
    echo "Username already exists.";
  }

  exit;
}