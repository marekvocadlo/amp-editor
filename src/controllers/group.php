<?php
session_start();
include "configNEW.php";

$data = json_decode(file_get_contents("php://input"));
$request = $data->request;

// Create group
if ($request === "createGroup") {
  $name = $data->name;
  $id = $_SESSION['user'][0];

  $queryD = $pdo->prepare("SELECT * FROM list WHERE name = :name");
  $queryD->execute(array(
    ":name" => $name
  ));
  if($queryD->rowCount() == 0) {
    $query = $pdo->prepare("INSERT INTO list (name,user_id) VALUES(?, ?)");
    $result = $query->execute(array(
      $name,
      $id
    ));
    echo "1";
  } else {
    echo "0";
  }
  exit();
}

// Read group
if ($request === "readGroup") {
  $id = $_SESSION['user'][0];
  $query = $pdo->prepare("SELECT * FROM list WHERE user_id = :user_id");
  $query->execute(array(
    ":user_id" => $id
  ));

  $contactGroups = $query->fetchAll();

  echo json_encode($contactGroups);
  exit();
}

// Delete group
if ($request === "deleteGroup") {
  $id = $data->group_id;
  $query = $pdo->prepare("DELETE FROM list WHERE id = ?");
  $result = $query->execute(array(
    $id
  ));
  echo 1;
  exit();
}