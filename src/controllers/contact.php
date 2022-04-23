<?php
session_start();
include "config.php";

$requestMethod = $_SERVER['REQUEST_METHOD'];

// Create contact
if ($requestMethod === "POST") {
  $_POST = json_decode(file_get_contents("php://input"),true);
  $groupId = htmlspecialchars($_POST["group_id"]);
  $contacts = htmlspecialchars($_POST["contacts"]);

  $contacts_arr = explode(",", $contacts);

  foreach ($contacts_arr as $contact) {
    $query = $pdo->prepare("INSERT INTO contact (list_id,email) VALUES(:group_id, :email)");
    $result = $query->execute(array(
      ":group_id" => $groupId,
      ":email" => $contact
    ));
  }

  echo 1;
  exit();
}

// Read contacts from group
if ($requestMethod === "GET" && isset($_GET['id'])) {
  $groupId = $_GET['id'];

  // Find all contact from user groups and connect them into 2dim array
  $contacts = [];
  $query = $pdo->prepare("SELECT * FROM contact WHERE list_id = :group_id");
  $query->execute(array(
    ":group_id" => $groupId
  ));

  // Change group id to group name
  $tempContacts = $query->fetchAll();
  for ($i = 0; $i < count($tempContacts); $i++) {
    $tempContacts[$i][1] = $userGroup[1];
  }

  array_push($contacts, $tempContacts);

  echo json_encode($contacts);
  exit();
}

// Read contacts
//if ($requestMethod === "GET") {
//
//  // Select all contact groups of logged user
//  $userId = $_SESSION['user'][0];
//  $queryGroups = $pdo->prepare("SELECT * FROM list WHERE user_id = :user_id");
//  $queryGroups->execute(array(
//    ":user_id" => $userId
//  ));
//  $userGroups = $queryGroups->fetchAll();
//
//  // Find all contact from user groups and connect them into 2dim array
//  $contacts = [];
//  foreach ($userGroups as $userGroup) {
//    $query = $pdo->prepare("SELECT * FROM contact WHERE list_id = ?");
//    $query->execute(array(
//      $userGroup[0]
//    ));
//
//    // Change group id to group name
//    $tempContacts = $query->fetchAll();
//    for ($i = 0; $i < count($tempContacts); $i++) {
//      $tempContacts[$i][1] = $userGroup[1];
//    }
//
//    array_push($contacts, $tempContacts);
//  }
//
//  echo json_encode($contacts);
//  exit();
//}


// Delete contact
if ($requestMethod === "DELETE") {
  $_DELETE = json_decode(file_get_contents("php://input"),true);
  $contacts = $_DELETE["contacts"];

  foreach ($contacts as $contact) {
    $query = $pdo->prepare("DELETE FROM contact WHERE id = :id");
    $result = $query->execute(array(
      ":id" => $contact["id"]
    ));
  }
  echo 1;
  exit();
}