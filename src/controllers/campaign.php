<?php
session_start();
include "config.php";

$data = json_decode(file_get_contents("php://input"));
$request = $data->request;

// Send campaign
if($request === "sendCampaign"){
  $name = $data->name;
  $subject = $data->subject;
  $group_id = $data->group_id;
  $template_id = $data->template_id;

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

  // Sending via Emailkampane.cz
  $result = 1;
  $sendingError = 0;
  foreach ($contacts as $contact){
    if ($result === 0) {
      $sendingError = 1;
    }
    $data2 = array(
      'security'=>'UXTxb8SJJhKQhtjA',
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

  // Create campaign db record
  $id = $data->group_id;
  $contacts = $data->contacts;
  $contacts_arr = explode (",", $contacts);

  $query = $pdo->prepare("INSERT INTO campaign (name,email,subject,user_id,list_id,template_id) VALUES(?, ?, ?, ?, ?, ?)");
  $result = $query->execute(array(
    $name,
    $_SESSION['user'][1],
    $subject,
    $_SESSION['user'][0],
    $group_id,
    $template_id
  ));

  if($sendingError === 1) {
    echo "0";
  } else {
    echo "1";
  }
}