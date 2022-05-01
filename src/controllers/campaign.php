<?php
session_start();
include "config.php";

$requestMethod = $_SERVER['REQUEST_METHOD'];

// Create campaign
if($requestMethod === "POST"){
  $_POST = json_decode(file_get_contents("php://input"),true);
  $action = htmlspecialchars($_POST["action"]);
  $name = htmlspecialchars($_POST["name"]);
  $senderName = htmlspecialchars($_POST["senderName"]);
  $senderEmail = htmlspecialchars($_POST["senderEmail"]);
  $subject = htmlspecialchars($_POST["subject"]);
  $group_id = htmlspecialchars($_POST["group_id"]);
  $template_id = htmlspecialchars($_POST["template_id"]);



  // Send campaign
  if ($action === "send") {
    // Select contact from chosen contact group
    $query = $pdo->prepare("SELECT * FROM contact WHERE list_id = ?");
    $query->execute(array(
      $group_id
    ));
    $contacts = $query->fetchAll();
    // Check if contact list contain contacts
    if (empty($contacts)){
      echo "2";
      exit();
    }

    // Select codes of selected template
    $query2 = $pdo->prepare("SELECT * FROM template WHERE id = ?");
    $query2->execute(array(
      $template_id
    ));
    $result2 = $query2->fetch();
    $templateHTML = $result2[2];
    $templateAMP = $result2[3];

    // Sending
    $result = 1;
    $sendingError = 0;
    $numberOfRecipients = 0;
    foreach ($contacts as $contact){
      $numberOfRecipients++;
      if ($result === 0) {
        $sendingError = 1;
      }
      $data2 = array(
        'security'=>$ek_password,
        'sender_name'=>$senderName,
        'sender_email'=>$senderEmail,
        'recipient'=>$contact[2],
        'subject'=>$subject,
        'htmlbody'=>$templateHTML,
        'ampbody'=>$templateAMP
      );

      $ch = curl_init('https://www.emailkampane.cz/global/function/amp_final.php');
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data2);
      $result = @curl_exec($ch); //value 1 if the mailing was successful
      curl_close($ch);
    }

    // Create campaign
    $query = $pdo->prepare("INSERT INTO campaign (name,senderName,email,subject,user_id,list_id,template_id,number_of_recipients) VALUES(:name, :senderName, :email, :subject, :user_id, :list_id, :template_id, :number_of_recipients)");
    $result = $query->execute(array(
      ":name" => $name,
      ":senderName" => $senderName,
      ":email" => $senderEmail,
      ":subject" => $subject,
      ":user_id" => $_SESSION['user'][0],
      ":list_id" => $group_id,
      ":template_id" => $template_id,
      ":number_of_recipients" => $numberOfRecipients
    ));

    if($sendingError === 1) {
      echo "0";
    } else {
      echo "1";
    }
  }
}

// Read campaigns
if ($requestMethod === "GET") {
  $user_id = $_SESSION['user'][0];
  $query = $pdo->prepare("SELECT * FROM campaign WHERE user_id = :user_id");
  $query->execute(array(
    ":user_id" => $user_id
  ));

  $campaigns = $query->fetchAll();

  echo json_encode($campaigns);
  exit();
}

// Delete campaign
if ($requestMethod === "DELETE") {
  $_DELETE = json_decode(file_get_contents("php://input"),true);
  $id = htmlspecialchars($_DELETE["campaignId"]);
  $query = $pdo->prepare("DELETE FROM campaign WHERE id = ?");
  $result = $query->execute(array(
    $id
  ));
  echo 1;
  exit();
}