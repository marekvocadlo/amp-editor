<?php
session_start();
include "configNEW.php";

$data = json_decode(file_get_contents("php://input"));
$request = $data->request;

// Read templates
if($request == 1){
  $id = $_SESSION['user_id'];
  $query = $pdo->prepare("SELECT * FROM template WHERE user_id = :user_id");
  $query->execute(array(
    ":user_id" => $id
  ));

  $templates = $query->fetchAll();

  echo json_encode($templates);
  exit();
}

// Read template
if($request == 2){
  $query = $pdo->prepare("SELECT * FROM template WHERE id = :template_id");
  $query->execute(array(
    ":template_id" => 2
  ));

  $templates = $query->fetch();

  echo json_encode($templates);
  exit();
}