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
  $insertId = $pdo->lastInsertId();
  echo $insertId;
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
  $data = json_decode(file_get_contents("php://input"));
  $gallery = $data->settings;

  //helpers
  $loop = ($gallery->carousel->loop) ? "loop" : "";
  $images = "";
  for ($i = 0; $i < count($gallery->carousel->img); $i++) {
    $img = '<amp-img width="'.$gallery->carousel->width.'" height="'.$gallery->carousel->height.'" src="'.$gallery->carousel->img[$i]->src.'"></amp-img>';
    $images .= $img;
  }

  $amp = '<!DOCTYPE html>
<html ⚡4email data-css-strict>
<head>
  <meta charset="utf-8">
  <script async src="https://cdn.ampproject.org/v0.js"></script>
  <script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>
  <style amp4email-boilerplate>body{visibility:hidden}</style>
  <style amp-custom>
    body {
      margin: 0;
      padding: 0;
      width: 100%;
      background-color: #f3f5f7;
      font-family: Poppins, Verdana, Arial, sans-serif;
      font-size: 16px;
      line-height: 1.4;
      color: #000000;
    }
    p {
      margin: 0;
    }
    a {
      color: #1976d2;
      text-decoration: underline;
    }
    .container {
      width: 100%;
      max-width: 700px;
      margin: 0 auto;
      background-color: #ffffff;
    }
  </style>
</head>
<body>
<div style="padding: 20px 0;">
  <div class="container">
    <div style="text-align: '.$gallery->logo->align.'; padding: '.$gallery->logo->paddingTop.'px '.$gallery->logo->paddingRight.'px '.$gallery->logo->paddingBottom.'px '.$gallery->logo->paddingLeft.'px;">
      <amp-img
        alt="'.$gallery->logo->alt.'"
        src="'.$gallery->logo->src.'"
        width="'.$gallery->logo->width.'"
        height="'.$gallery->logo->height.'"
      >
      </amp-img>
    </div>
  </div>
  <div class="container" style="text-align: center;">
    <amp-carousel
        width="'.$gallery->carousel->width.'"
        height="'.$gallery->carousel->height.'"
        type="slides"
        role="region"
        '.$loop.'
    >'.$images.'</amp-carousel>
  </div>
  <div class="container">
    <div style="text-align: '.$gallery->title->align.'; font-size: '.$gallery->title->font_size.'px; line-height: '.$gallery->title->line_height.'px; color: '.$gallery->title->color.'; padding: '.$gallery->title->paddingTop.'px '.$gallery->title->paddingRight.'px '.$gallery->title->paddingBottom.'px '.$gallery->title->paddingLeft.'px;">
      '.$gallery->title->text.'
    </div>
  </div>
  <div class="container">
    <div style="text-align: '.$gallery->text->align.'; font-size: '.$gallery->text->font_size.'px; line-height: '.$gallery->text->line_height.'px; color: '.$gallery->text->color.'; padding: '.$gallery->text->paddingTop.'px '.$gallery->text->paddingRight.'px '.$gallery->text->paddingBottom.'px '.$gallery->text->paddingLeft.'px;">
    '.$gallery->text->text.'
    </div>
  </div>
 </div>
</body>
</html>
';


  $html = '<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
</head>
<body style="width: 100%; font-family: Poppins, Verdana, Arial, sans-serif; font-size: 14px; margin: 0; padding: 0; background-color: #f3f5f7">

<table align="center" border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin:0 auto; border-collapse:collapse; border: none;">
  <tr>
    <td align="center" style="padding: 20px 0; border-collapse: collapse;">
      <table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 700px; margin:0 auto; border-collapse:collapse; border: none;">
        <tr>
          <td style="text-align: '.$gallery->logo->align.'; padding: '.$gallery->logo->paddingTop.'px '.$gallery->logo->paddingRight.'px '.$gallery->logo->paddingBottom.'px '.$gallery->logo->paddingLeft.'px;">
            <img
                alt="'.$gallery->logo->alt.'"
                src="'.$gallery->logo->src.'"
                width="'.$gallery->logo->width.'"
                height="'.$gallery->logo->height.'"
            />
          </td>
        </tr>
      </table>

      <table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 700px; margin:0 auto; border-collapse:collapse; border: none;">
        <tr>
          <td align="center">
            <img
                alt=""
                src="'.$gallery->carousel->img[0]->src.'"
                width="'.$gallery->carousel->width.'"
                height="'.$gallery->carousel->height.'"
            />
          </td>
        </tr>
      </table>

      <table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 700px; margin:0 auto; border-collapse:collapse; border: none;">
        <tr>
          <td style="font-family: Poppins, Verdana, Arial, sans-serif; text-align: '.$gallery->title->align.'; font-size: '.$gallery->title->font_size.'px; line-height: '.$gallery->title->line_height.'px; color: '.$gallery->title->color.'; padding: '.$gallery->title->paddingTop.'px '.$gallery->title->paddingRight.'px '.$gallery->title->paddingBottom.'px '.$gallery->title->paddingLeft.'px;">
            '.$gallery->title->text.'
          </td>
        </tr>
      </table>

      <table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 700px; margin:0 auto; border-collapse:collapse; border: none;">
        <tr>
          <td style="font-family: Poppins, Verdana, Arial, sans-serif; text-align: '.$gallery->text->align.'; font-size: '.$gallery->text->font_size.'px; line-height: '.$gallery->text->line_height.'px; color: '.$gallery->text->color.'; padding: '.$gallery->text->paddingTop.'px '.$gallery->text->paddingRight.'px '.$gallery->text->paddingBottom.'px '.$gallery->text->paddingLeft.'px;">
            '.$gallery->text->text.'
          </td>
        </tr>
      </table>

    </td>
  </tr>
</table>

</body>
</html>';

  // Remove spaces for optimized AMP
  $ampOptimized = trim($amp);
  $htmlOptimized = trim($html);

  // Create AMP and HTML e-mail file
  $AMP_file = fopen("../files/index_amp".$id.".html", "w") or die("Soubor nelze otevřít!");
  fwrite($AMP_file, $ampOptimized);
  fclose($AMP_file);
  $HTML_file = fopen("../files/index_html".$id.".html", "w") or die("Soubor nelze otevřít!");
  fwrite($HTML_file, $htmlOptimized);
  fclose($HTML_file);

  $query = $pdo->prepare("UPDATE user_template SET html = :html, amp = :amp, settings = :settings WHERE id = :id");
  $result = $query->execute(array(
    ":html" => $htmlOptimized,
    ":amp" => $ampOptimized,
    ":settings" => $settings,
    ":id" => $id
  ));

  echo "Template saved";
  exit();
}

// Delete group
if ($requestMethod === "DELETE") {
  $_DELETE = json_decode(file_get_contents("php://input"),true);
  $id = htmlspecialchars($_DELETE["id"]);
  $query = $pdo->prepare("DELETE FROM user_template WHERE id = :id");
  $result = $query->execute(array(
    ":id" => $id
  ));
  echo 1;
  exit();
}