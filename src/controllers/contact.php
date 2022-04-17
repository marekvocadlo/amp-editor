<?php
session_start();
include "configNEW.php";

$data = json_decode(file_get_contents("php://input"));
$request = $data->request;

// Create contacts
if($request === "createContact"){
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

  echo 1;
  exit();
}

// Read contacts
if ($request === "readContacts") {

  // Select all contact groups of logged user
  $id = $_SESSION['user'][0];
  $queryGroups = $pdo->prepare("SELECT * FROM list WHERE user_id = ?");
  $queryGroups->execute(array(
    $id
  ));
  $userGroups = $queryGroups->fetchAll();

  // Find all contact from user groups and connect them into 2dim array
  $contacts = [];
  foreach ($userGroups as $userGroup){
    $query = $pdo->prepare("SELECT * FROM contact WHERE list_id = ?");
    $query->execute(array(
      $userGroup[0]
    ));

    // Change group id to group name
    $tempContacts = $query->fetchAll();
    for ($i = 0; $i < count($tempContacts); $i++) {
      $tempContacts[$i][1] = $userGroup[1];
    }

    array_push($contacts, $tempContacts);
  }

  echo json_encode($contacts);
  exit();
}

// Delete contact
if ($request === "deleteContact") {
  $id = $data->contactId;
  $query = $pdo->prepare("DELETE FROM contact WHERE id = ?");
  $result = $query->execute(array(
    $id
  ));
  echo 1;
  exit();
}

// Read contacts - bude potřeba sáhnout jen pro skupiny, které náleží danému uživateli