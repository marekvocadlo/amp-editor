<?php
session_start();
include "config.php";
$data = json_decode(file_get_contents("php://input"));

$request = $data->request;

// Read templates
if($request == 1){
  $id = $_SESSION['user_id'];
  $templateQuery = mysqli_query($con,"SELECT * FROM template WHERE user_id = '".$id."'");
  $templates = mysqli_fetch_all($templateQuery);

  echo json_encode($templates);
}