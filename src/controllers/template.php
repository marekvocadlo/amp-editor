<?php
session_start();
include "config.php";

$requestMethod = $_SERVER['REQUEST_METHOD'];

$data = json_decode(file_get_contents("php://input"));
$request = $data->request;

// Read templates
if($requestMethod === "GET"){
  $user_id = $_SESSION['user'][0];
  $query = $pdo->prepare("SELECT * FROM template WHERE user_id = :user_id");
  $query->execute(array(
    ":user_id" => $user_id
  ));

  $templates = $query->fetchAll();

  echo json_encode($templates);
  exit();
}