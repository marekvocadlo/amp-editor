<?php
$data = json_decode(file_get_contents("php://input"));

// login
$subject = $data->subject;

$data2 = array(
  'security'=>'UXTxb8SJJhKQhtjA',
  'recipient'=>'marekvocadlo5@gmail.com',
  'subject'=>$subject,
  'htmlbody'=>'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="utf-8">

</head>
<body style="width: 100%; font-family: Montserrat, Verdana, Arial, sans-serif; font-size: 12px; margin: 0; padding: 0; background-color: #f1f1f1">
  <h1>HTML part</h1>
</body>
</html>',
  'ampbody'=>'<!doctype html>
<html ⚡4email data-css-strict>
<head>
  <meta charset="utf-8">
  <script async src="https://cdn.ampproject.org/v0.js"></script>
  <style amp4email-boilerplate>body{visibility:hidden}</style>
  </head>
<body style="width: 100%; font-family: Montserrat, Verdana, Arial, sans-serif; font-size: 12px; margin: 0; padding: 0; background-color: #f1f1f1">
  <h1>AMP part</h1>
</body>
</html>'
);

$ch = curl_init('https://www.emailkampane.cz/global/function/amp_final.php');
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data2);
$result = @curl_exec($ch);
curl_close($ch);

if($result == 0) {
  echo "Security problem";
} else {
  echo "Successfully sent";
}