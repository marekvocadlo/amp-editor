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
  $id = $_SESSION['user'][0];
  $query = $pdo->prepare("SELECT * FROM contact");
  $query->execute();

  $contacts = $query->fetchAll();

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