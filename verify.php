<?php
session_start();
include "config.php";
$data = json_decode(file_get_contents("php://input"));

if (!empty($_SESSION['user_id'])){
  //user is already login
  echo $_SESSION['user_id'];
}