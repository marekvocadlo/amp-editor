<?php
session_start();
include "config.php";
$data = json_decode(file_get_contents("php://input"));

$request = $data->request;

// Read list
if($request == 1){
  $id = $_SESSION['user_id'];
  $query = mysqli_query($con,"SELECT * FROM list WHERE user_id = '".$id."'");
  $contactGroups = mysqli_fetch_all($query);

  echo json_encode($contactGroups);
  exit();
}

// Create list
if($request == 2){
  $name = $data->name;
  $id = $_SESSION['user_id'];
  mysqli_query($con,"INSERT INTO list(name,user_id) VALUES('".$name."','".$id."')");
  echo "Insert successfully";
  exit();
}

// Delete list
if($request == 3){
  $id = $data->group_id;
  mysqli_query($con,"DELETE FROM list WHERE id= '".$id."'");
  echo "List deleted";
  exit();
}