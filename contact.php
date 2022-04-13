<?php
session_start();
include "config.php";
$data = json_decode(file_get_contents("php://input"));

$request = $data->request;

// Read contact groups
if($request == 1){
  $id = $_SESSION['user_id'];
  $userData = mysqli_query($con,"SELECT * FROM contactGroup WHERE user_id = '".$id."'");
  $contactGroups = mysqli_fetch_all($userData);

  $groups = [];
  foreach ($contactGroups as $contactGroup){
    array_push($groups, $contactGroup[1]);
  }

  echo json_encode($groups);
}