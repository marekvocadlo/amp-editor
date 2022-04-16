<?php
include "config.php";
$data = json_decode(file_get_contents("php://input"));

$result = 0;

$subject = $data->subject;
$group_id = $data->group_id;
$template_id = $data->template_id;

//$q = mysqli_query($con,"SELECT * FROM contactGroup WHERE name = '".$contactGroup."'");
//$contactGroupIDResult = mysqli_fetch_row($q);
//$contactGroupID = $contactGroupIDResult[0];

$userData = mysqli_query($con,"SELECT * FROM contact WHERE list_id = '".$group_id."'");
$contacts = mysqli_fetch_all($userData);

$templateQuery = mysqli_query($con,"SELECT * FROM template WHERE id = '".$template_id."'");
$template = mysqli_fetch_row($templateQuery);
$templateHTML = $template[2];
$templateAMP = $template[3];

if (empty($contacts)){
  echo "Contact list is empty";
  exit();
}

foreach ($contacts as $contact){
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
  $result = @curl_exec($ch);
  curl_close($ch);

}

if($result == 0) {
  echo "Security problem";
} else {
  echo "Successfully sent";
}
