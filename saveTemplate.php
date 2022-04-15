<?php
session_start();
include "configNEW.php";

$data = json_decode(file_get_contents("php://input"));
$cText = $data->cText;

$ampTemplate = '
<!DOCTYPE html>
  <html ⚡4email data-css-strict>
  <head>
    <meta charset="utf-8">
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <style amp4email-boilerplate>body{visibility:hidden}</style>
    </head>
  <body style="width: 100%; font-family: Poppins, Verdana, Arial, sans-serif; font-size: 12px; margin: 0; padding: 0; background-color: #f1f1f1">
    <div style="font-size: '.$cText->font_size.'px; line-height: '.$cText->line_height.'px; color: '.$cText->color.'">'.$cText->text.'</div>
    <div>AMP part</div>
  </body>
  </html>
';

$query = $pdo->prepare("UPDATE template SET amp = ? WHERE id = ?");
$result = $query->execute(array(
  $ampTemplate,
  2
));

echo json_encode($ampTemplate);