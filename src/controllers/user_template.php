<?php
session_start();
include "config.php";

$requestMethod = $_SERVER['REQUEST_METHOD'];

// Create user template
if ($requestMethod === "POST") {
  $_POST = json_decode(file_get_contents("php://input"),true);
  $name = htmlspecialchars($_POST["name"]);
  $user_id = $_SESSION['user'][0];

  $query2 = $pdo->prepare("INSERT INTO user_template (name,user_id) VALUES(:name, :user_id)");
  $result = $query2->execute(array(
    ":name" => $name,
    ":user_id" => $user_id,
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
  $component = $data->settings;

  // Used components for AMP email
  // Logo
  if ($component->logo->used) {
    $logo = '<div class="container">
      <div style="text-align: '.$component->logo->align.'; padding: '.$component->logo->paddingTop.'px '.$component->logo->paddingRight.'px '.$component->logo->paddingBottom.'px '.$component->logo->paddingLeft.'px;">
        <amp-img
          alt="'.$component->logo->alt.'"
          src="'.$component->logo->src.'"
          width="'.$component->logo->width.'"
          height="'.$component->logo->height.'"
        >
        </amp-img>
      </div>
    </div>';
  } else {
    $logo = "";
  }
  // Carousel
  if ($component->carousel->used) {

    // Add carousel to email header
    $carouselImport = '<script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>';

    // Create carousel images
    $loop = ($component->carousel->loop) ? "loop" : "";
    $images = "";
    for ($i = 0; $i < count($component->carousel->img); $i++) {
      $img = '<amp-img width="'.$component->carousel->width.'" height="'.$component->carousel->height.'" src="'.$component->carousel->img[$i]->src.'"></amp-img>';
      $images .= $img;
    }

    $carousel = '<div class="container" style="text-align: center;">
    <amp-carousel
        width="'.$component->carousel->width.'"
        height="'.$component->carousel->height.'"
        type="slides"
        role="region"
        '.$loop.'
    >'.$images.'</amp-carousel>
  </div>';
  } else {
    $carousel = "";
    $carouselImport = "";
  }
  // Title
  if ($component->title->used) {
    $title = '<div class="container">
    <div style="text-align: '.$component->title->align.'; font-size: '.$component->title->font_size.'px; line-height: '.$component->title->line_height.'px; color: '.$component->title->color.'; padding: '.$component->title->paddingTop.'px '.$component->title->paddingRight.'px '.$component->title->paddingBottom.'px '.$component->title->paddingLeft.'px;">
      '.$component->title->text.'
    </div>
  </div>';
  } else {
    $title = "";
  }
  // Text
  if ($component->text->used) {
    $text = '<div class="container">
    <div style="text-align: '.$component->text->align.'; font-size: '.$component->text->font_size.'px; line-height: '.$component->text->line_height.'px; color: '.$component->text->color.'; padding: '.$component->text->paddingTop.'px '.$component->text->paddingRight.'px '.$component->text->paddingBottom.'px '.$component->text->paddingLeft.'px;">
    '.$component->text->text.'
    </div>
  </div>';
  } else {
    $text = "";
  }
  // Accordion
  if ($component->accordion->used) {

    // Add accordion to email header
    $accordionImport = '<script async custom-element="amp-accordion" src="https://cdn.ampproject.org/v0/amp-accordion-0.1.js"></script>';

    // Create accordion sections
    $animate = ($component->accordion->animate) ? "animate" : "";
    $expandSingleSection = ($component->accordion->expandSingleSection) ? "expand-single-section" : "";
    $sections = "";
    for ($i = 0; $i < count($component->accordion->section); $i++) {
      $section = '
        <section>
          <h2 style="font-weight: normal; font-size: 15px;padding: 10px 20px;background-color: #ffffff">'.$component->accordion->section[$i]->title.'</h2>
          <p style="font-size: '.$component->accordion->font_size.'px; line-height: '.$component->accordion->line_height.'px; padding: 10px 20px;">'.$component->accordion->section[$i]->text.'</p>
        </section>';
      $sections .= $section;
    }

    $accordion = '
      <div class="container" style="padding-bottom: 30px;">
        <amp-accordion style="width:600px; margin: 0 auto;color: '.$component->accordion->color.'" animate expand-single-section>
          '.$sections.'
        </amp-accordion>
      </div>';
  } else {
    $accordion = "";
    $accordionImport = "";
  }


  $amp = '<!DOCTYPE html>
<html ⚡4email data-css-strict>
<head>
  <meta charset="utf-8">
  <script async src="https://cdn.ampproject.org/v0.js"></script>
  '.$carouselImport.'
  '.$accordionImport.'
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
  '.$logo.$carousel.$title.$text.$accordion.'
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
          <td style="text-align: '.$component->logo->align.'; padding: '.$component->logo->paddingTop.'px '.$component->logo->paddingRight.'px '.$component->logo->paddingBottom.'px '.$component->logo->paddingLeft.'px;">
            <img
                alt="'.$component->logo->alt.'"
                src="'.$component->logo->src.'"
                width="'.$component->logo->width.'"
                height="'.$component->logo->height.'"
            />
          </td>
        </tr>
      </table>

      <table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 700px; margin:0 auto; border-collapse:collapse; border: none;">
        <tr>
          <td align="center">
            <img
                alt=""
                src="'.$component->carousel->img[0]->src.'"
                width="'.$component->carousel->width.'"
                height="'.$component->carousel->height.'"
            />
          </td>
        </tr>
      </table>

      <table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 700px; margin:0 auto; border-collapse:collapse; border: none;">
        <tr>
          <td style="font-family: Poppins, Verdana, Arial, sans-serif; text-align: '.$component->title->align.'; font-size: '.$component->title->font_size.'px; line-height: '.$component->title->line_height.'px; color: '.$component->title->color.'; padding: '.$component->title->paddingTop.'px '.$component->title->paddingRight.'px '.$component->title->paddingBottom.'px '.$component->title->paddingLeft.'px;">
            '.$component->title->text.'
          </td>
        </tr>
      </table>

      <table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 700px; margin:0 auto; border-collapse:collapse; border: none;">
        <tr>
          <td style="font-family: Poppins, Verdana, Arial, sans-serif; text-align: '.$component->text->align.'; font-size: '.$component->text->font_size.'px; line-height: '.$component->text->line_height.'px; color: '.$component->text->color.'; padding: '.$component->text->paddingTop.'px '.$component->text->paddingRight.'px '.$component->text->paddingBottom.'px '.$component->text->paddingLeft.'px;">
            '.$component->text->text.'
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

  echo "1";
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
  echo "1";
  exit();
}