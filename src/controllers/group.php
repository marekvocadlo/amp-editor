<?php
session_start();
include "config.php";

$requestMethod = $_SERVER['REQUEST_METHOD'];

// Create group
if ($requestMethod === "POST") {
  $_POST = json_decode(file_get_contents("php://input"),true);
  $groupName = htmlspecialchars($_POST["groupName"]);
  $user_id = $_SESSION['user'][0];

  $queryD = $pdo->prepare("SELECT * FROM list WHERE name = :name");
  $queryD->execute(array(
    ":name" => $groupName
  ));
  if($queryD->rowCount() == 0) {
    $query = $pdo->prepare("INSERT INTO list (name,user_id) VALUES(:name, :user_id)");
    $result = $query->execute(array(
      ":name" => $groupName,
      ":user_id" => $user_id
    ));
    echo "1";
  } else {
    echo "0";
  }
  exit();
}

// Read group
if ($requestMethod === "GET") {
  $user_id = $_SESSION['user'][0];
  $query = $pdo->prepare("SELECT * FROM list WHERE user_id = :user_id");
  $query->execute(array(
    ":user_id" => $user_id
  ));
  $contactGroups = $query->fetchAll();

  // Add number of contacts
  for ($i = 0; $i < count($contactGroups); $i++) {
    $query2 = $pdo->prepare("SELECT * FROM contact WHERE list_id = :group_id");
    $query2->execute(array(
      ":group_id" => $contactGroups[$i][0]
    ));
    $numberOfContact = $query2->rowCount();
    $contactGroups[$i][3] = $numberOfContact;
  }


  echo json_encode($contactGroups);
  exit();
}

// Update group
if ($requestMethod === "PUT") {
  $_PUT = json_decode(file_get_contents("php://input"),true);
  $id = htmlspecialchars($_PUT["id"]);
  $name = htmlspecialchars($_PUT["name"]);

  $query = $pdo->prepare("UPDATE list SET name = :name WHERE id = :id");
  $query->execute(array(
    ":name" => $name,
    ":id" => $id
  ));
  echo "1";
} else {
  echo "0";
}

// Delete group
if ($requestMethod === "DELETE") {
  $_DELETE = json_decode(file_get_contents("php://input"),true);
  $id = htmlspecialchars($_DELETE["group_id"]);
  $query = $pdo->prepare("DELETE FROM list WHERE id = :id");
  $result = $query->execute(array(
    ":id" => $id
  ));
  echo 1;
  exit();
}