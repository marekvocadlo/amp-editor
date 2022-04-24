<?php
session_start();
include "config.php";

$requestMethod = $_SERVER['REQUEST_METHOD'];

// Create user template
if ($requestMethod === "POST") {
  $_POST = json_decode(file_get_contents("php://input"),true);
  $name = htmlspecialchars($_POST["name"]);
  $template_id = $_POST["template_id"];
  $user_id = $_SESSION['user'][0];

  // Template settings
  $query = $pdo->prepare("SELECT settings FROM template WHERE id = :id");
  $query->execute(array(
    ":id" => $template_id
  ));
  $settings = $query->fetch();

  $query2 = $pdo->prepare("INSERT INTO user_template (name,user_id,settings,template_id) VALUES(:name, :user_id, :settings, :template_id)");
  $result = $query2->execute(array(
    ":name" => $name,
    ":user_id" => $user_id,
    ":settings" => $settings[0],
    ":template_id" => $template_id,
  ));
  echo "1";
  exit();
}

// Read prepared templates
if($requestMethod === "GET" && !isset($_GET['id'])){
  $user_id = $_SESSION['user'][0];
  $query = $pdo->prepare("SELECT * FROM user_template WHERE user_id = :user_id");
  $query->execute(array(
    ":user_id" => $user_id
  ));

  $templates = $query->fetchAll();

  echo json_encode($templates);
  exit();
}

// Read template by id
if($requestMethod === "GET" && isset($_GET['id'])){
  $template_id = $_GET['id'];
  $query = $pdo->prepare("SELECT settings FROM user_template WHERE id = :id");
  $query->execute(array(
    ":id" => $template_id
  ));

  $settings = $query->fetch();

  echo $settings[0];
  exit();
}

// Update user template
if($requestMethod === "PUT"){
  $_PUT = json_decode(file_get_contents("php://input"),true);
  $id = $_PUT["id"];
  $settings = json_encode($_PUT["settings"]);

  $query = $pdo->prepare("UPDATE user_template SET settings = :settings WHERE id = :id");
  $result = $query->execute(array(
    ":settings" => $settings,
    ":id" => $id
  ));

  echo "Template saved";
  exit();
}