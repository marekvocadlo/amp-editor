<?php
session_start();
include "config.php";

$requestMethod = $_SERVER['REQUEST_METHOD'];

$data = json_decode(file_get_contents("php://input"));
$request = $data->request;

// Read templates
if($requestMethod === "GET"){
  $query = $pdo->prepare("SELECT * FROM template");
  $query->execute();
  $templates = $query->fetchAll();

  echo json_encode($templates);
  exit();
}