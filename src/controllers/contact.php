<?php
session_start();
include "configNEW.php";

$data = json_decode(file_get_contents("php://input"));
$request = $data->request;

// Read group
if($request == 1){
  $id = $_SESSION['user_id'];
  $query = $pdo->prepare("SELECT * FROM list WHERE user_id = :user_id");
  $query->execute(array(
    ":user_id" => $id
  ));

  $contactGroups = $query->fetchAll();

  echo json_encode($contactGroups);
  exit();
}

// Create group
if($request == 2){
  $name = $data->name;
  $id = $_SESSION['user_id'];
  $query = $pdo->prepare("INSERT INTO list (name,user_id) VALUES(?, ?)");
  $result = $query->execute(array(
    $name,
    $id
  ));

  echo "Insert successfully";
  exit();
}

// Delete group
if($request == 3){
  $id = $data->group_id;
  $query = $pdo->prepare("DELETE FROM list WHERE id = ?");
  $result = $query->execute(array(
    $id
  ));
  echo "List deleted";
  exit();
}

// Create contacts
if($request == 4){
  $id = $data->group_id;
  $contacts = $data->contacts;
  $contacts_arr = explode (",", $contacts);

  foreach ($contacts_arr as $contact){
    $query = $pdo->prepare("INSERT INTO contact (list_id,email) VALUES(?, ?)");
    $result = $query->execute(array(
      $id,
      $contact
    ));
  }

  echo "Insert successfully";
  exit();
}

// Read contacts - bude potřeba sáhnout jen pro skupiny, které náleží danému uživateli