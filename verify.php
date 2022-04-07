<?php
session_start();
include "config.php";
$data = json_decode(file_get_contents("php://input"));

if (!empty($_SESSION['user'])){
  //user is already login
  echo json_encode($_SESSION['user']);
}