<?php
session_start();
include "config.php";

$requestMethod = $_SERVER['REQUEST_METHOD'];

// Create user template
if ($requestMethod === "POST") {
  $_POST = json_decode(file_get_contents("php://input"),true);
  $name = htmlspecialchars($_POST["name"]);
  $user_id = $_SESSION['user'][0];
  $defaultSettings = '{"logo":{"used":true,"display":"none","paddingTop":10,"paddingRight":50,"paddingBottom":10,"paddingLeft":50,"align":"center","width":150,"height":150,"alt":"Logo","src":"https:\/\/img.freepik.com\/free-vector\/illustration-circle-stamp-banner-vector_53876-27183.jpg?t=st=1650813257~exp=1650813857~hmac=cee7b6217f8190ead609263d99e2ef872c2b29d02c6fb5f6fe54964d0d138481&w=826"},"carousel":{"used":true,"display":"none","width":600,"height":200,"autoplay":"","delay":"","loop":true,"img":[{"src":"https:\/\/picsum.photos\/600\/200?random=1"}]},"title":{"used":true,"display":"none","align":"center","paddingTop":30,"paddingRight":50,"paddingBottom":30,"paddingLeft":50,"text":"Galerie obr\u00e1zk\u016f","font_size":"22","line_height":"28","color":"#000000"},"text":{"used":true,"display":"none","align":"justify","paddingTop":0,"paddingRight":50,"paddingBottom":30,"paddingLeft":50,"text":"Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.","font_size":"14","line_height":"22","color":"#000000"},"accordion":{"used":false,"display":"none","align":"left","font_size":"14","line_height":"22","color":"#00000","animate":true,"expandSingleSection":true,"section":[{"title":"Nadpis sekce 1","text":"Obsah v sekci 1"}]},"timeago":{"used":false,"display":"none","align":"center","paddingTop":0,"paddingRight":50,"paddingBottom":30,"paddingLeft":50,"font_size":"22","line_height":"28","color":"#000000","date":"1995-07-30","time":"22:00"},"form":{"used":false,"display":"none","align":"auto","width":300,"paddingY":30,"url":"","successSend":"V po\u0159\u00e1dku odesl\u00e1no.","errorSend":"Do\u0161lo k chyb\u011b.","input":[{"label":"Jm\u00e9no","type":"text","required":false},{"label":"E-mail","type":"email","required":true}],"button":"Odeslat"}}';

  $query2 = $pdo->prepare("INSERT INTO template (name,user_id,settings) VALUES(:name, :user_id, :settings)");
  $result = $query2->execute(array(
    ":name" => $name,
    ":user_id" => $user_id,
    ":settings" => $defaultSettings,
  ));
  $insertId = $pdo->lastInsertId();
  echo $insertId;
  exit();
}

// Read prepared templates
if($requestMethod === "GET" && !isset($_GET['id'])){
  $user_id = $_SESSION['user'][0];
  $query = $pdo->prepare("SELECT * FROM template WHERE user_id = :user_id");
  $query->execute(array(
    ":user_id" => $user_id
  ));

  $templates = $query->fetchAll();

  echo json_encode($templates);
  exit();
}

// Read template by id
if($requestMethod === "GET" && isset($_GET['id']) && !isset($_GET['auth'])){
  $template_id = $_GET['id'];
  $query = $pdo->prepare("SELECT settings FROM template WHERE id = :id");
  $query->execute(array(
    ":id" => $template_id
  ));

  $settings = $query->fetch();

  echo $settings[0];
  exit();
}

// Check template owner
if($requestMethod === "GET" && isset($_GET['id']) && isset($_GET['auth'])){
  $template_id = $_GET['id'];
  $user_id = $_SESSION['user'][0];

  $query = $pdo->prepare("SELECT * FROM template WHERE id = :id");
  $query->execute(array(
    ":id" => $template_id
  ));
  $result = $query->fetch();

  if ($result[6] === $user_id) {
    echo "1";
  } else {
    echo "0";
  }
  exit();
}

// Update user template
if($requestMethod === "PUT"){
  $_PUT = json_decode(file_get_contents("php://input"),true);
  $id = $_PUT["id"];
  $settings = json_encode($_PUT["settings"]);
  $data = json_decode(file_get_contents("php://input"));
  $component = $data->settings;

  #region e-mail components

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
    $logoHTML = '
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
    ';
  } else {
    $logo = "";
    $logoHTML = "";
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

    $carouselHTML = '
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
    ';
  } else {
    $carousel = "";
    $carouselImport = "";
    $carouselHTML = "";
  }

  // Title
  if ($component->title->used) {
    $title = '<div class="container">
    <div style="text-align: '.$component->title->align.'; font-size: '.$component->title->font_size.'px; line-height: '.$component->title->line_height.'px; color: '.$component->title->color.'; padding: '.$component->title->paddingTop.'px '.$component->title->paddingRight.'px '.$component->title->paddingBottom.'px '.$component->title->paddingLeft.'px;">
      '.$component->title->text.'
    </div>
  </div>';

    $titleHTML = '
      <table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 700px; margin:0 auto; border-collapse:collapse; border: none;">
        <tr>
          <td style="font-family: Poppins, Verdana, Arial, sans-serif; text-align: '.$component->title->align.'; font-size: '.$component->title->font_size.'px; line-height: '.$component->title->line_height.'px; color: '.$component->title->color.'; padding: '.$component->title->paddingTop.'px '.$component->title->paddingRight.'px '.$component->title->paddingBottom.'px '.$component->title->paddingLeft.'px;">
            '.$component->title->text.'
          </td>
        </tr>
      </table>
    ';
  } else {
    $title = "";
    $titleHTML = "";
  }

  // Text
  if ($component->text->used) {
    $text = '<div class="container">
    <div style="text-align: '.$component->text->align.'; font-size: '.$component->text->font_size.'px; line-height: '.$component->text->line_height.'px; color: '.$component->text->color.'; padding: '.$component->text->paddingTop.'px '.$component->text->paddingRight.'px '.$component->text->paddingBottom.'px '.$component->text->paddingLeft.'px;">
    '.$component->text->text.'
    </div>
  </div>';

    $textHTML = '
      <table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 700px; margin:0 auto; border-collapse:collapse; border: none;">
        <tr>
          <td style="font-family: Poppins, Verdana, Arial, sans-serif; text-align: '.$component->text->align.'; font-size: '.$component->text->font_size.'px; line-height: '.$component->text->line_height.'px; color: '.$component->text->color.'; padding: '.$component->text->paddingTop.'px '.$component->text->paddingRight.'px '.$component->text->paddingBottom.'px '.$component->text->paddingLeft.'px;">
            '.$component->text->text.'
          </td>
        </tr>
      </table>
    ';
  } else {
    $text = "";
    $textHTML = "";
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

    $sectionsHTML = "";
    for ($i = 0; $i < count($component->accordion->section); $i++) {
      $sectionHTML = '
        <tr>
          <td style="border: 1px #cccccc solid; font-family: Poppins, Verdana, Arial, sans-serif; text-align: justify; font-size: 14px; line-height: 22px; color: '.$component->accordion->color.';  padding: 10px 20px;">
            '.$component->accordion->section[$i]->title.'
          </td>
        </tr>
        <tr>
          <td style="font-family: Poppins, Verdana, Arial, sans-serif; text-align: justify; font-size: '.$component->accordion->font_size.'px; line-height: '.$component->accordion->line_height.'px; color: '.$component->accordion->color.';  padding: 10px 20px;">
            '.$component->accordion->section[$i]->text.'
          </td>
        </tr>';
      $sectionsHTML .= $sectionHTML;
    }

    $accordionHTML = '
      <table bgcolor="#ffffff" style="width: 100%; max-width: 700px; margin:0 auto; border-collapse:collapse; border: none;">
        <tr>
          <td style="font-family: Poppins, Verdana, Arial, sans-serif; text-align: justify; font-size: 14px; line-height: 22px; color: '.$component->accordion->color.'; padding: 0px 50px 30px 50px;">
            <table bgcolor="#ffffff" style="width: 100%; max-width: 700px; margin:0 auto; border-collapse:collapse; border: none;">
              '.$sectionsHTML.'
            </table>
          </td>
        </tr>
      </table>';
  } else {
    $accordion = "";
    $accordionImport = "";
    $accordionHTML = "";
  }

  // Timeago
  if ($component->timeago->used) {

    // Add accordion to email header
    $timeagoImport = '<script async custom-element="amp-timeago" src="https://cdn.ampproject.org/v0/amp-timeago-0.1.js"></script>';
    $timeagoWidth= 700 - $component->timeago->paddingRight - $component->timeago->paddingLeft;

    $timeago = '
      <div class="container">
        <amp-timeago
          layout="fixed"
          width="'.$timeagoWidth.'"
          height="'.$component->timeago->line_height.'"
          datetime="'.$component->timeago->date.'T'.$component->timeago->time.':00.809Z"
          locale="cs"
          style="width: '.$timeagoWidth.'px; text-align: '.$component->timeago->align.'; font-size: '.$component->timeago->font_size.'px; line-height: '.$component->timeago->line_height.'px; color: '.$component->timeago->color.'; padding: '.$component->timeago->paddingTop.'px '.$component->timeago->paddingRight.'px '.$component->timeago->paddingBottom.'px '.$component->timeago->paddingLeft.'px;"
        >
          '.$component->timeago->date.' '.$component->timeago->time.'
        </amp-timeago>
      </div>';

    $orgDate = $component->timeago->date;
    $timeagoDateFormat = date("d. m. Y", strtotime($orgDate));

    $timeagoHTML = '
      <table bgcolor="#ffffff" style="width: 100%; max-width: 700px; margin:0 auto; border-collapse:collapse; border: none;">
        <tr>
          <td style="font-family: Poppins, Verdana, Arial, sans-serif; text-align: '.$component->timeago->align.'; font-size: '.$component->timeago->font_size.'px; line-height: '.$component->timeago->line_height.'px; color: '.$component->timeago->color.'; padding: '.$component->timeago->paddingTop.'px '.$component->timeago->paddingRight.'px '.$component->timeago->paddingBottom.'px '.$component->timeago->paddingLeft.'px;">
            '.$timeagoDateFormat.' '.$component->timeago->time.'
          </td>
        </tr>
      </table>
    ';
  } else {
    $timeago = "";
    $timeagoImport = "";
    $timeagoHTML = "";
  }

  // Form
  if ($component->form->used) {

    // Add form to email header
    $formImport = '<script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script><script async custom-template="amp-mustache" src="https://cdn.ampproject.org/v0/amp-mustache-0.2.js"></script>';

    // Create form inputs
    $inputs = "";
    for ($i = 0; $i < count($component->form->input); $i++) {
      $inputRequired = ($component->form->input[$i]->required) ? "required" : "";
      $input = '
         <label>
          <input type="'.$component->form->input[$i]->type.'"
                 name="'.$component->form->input[$i]->label.'"
                 style="width: '.$component->form->width.'px; height: 25px; padding: 5px 10px; font-family: Poppins, Verdana, Arial, sans-serif; font-size: 16px;"
                 placeholder="'.$component->form->input[$i]->label.'"
                 '.$inputRequired.'>
        </label>
        <br><br>';
      $inputs .= $input;
    }

    $form = '
      <div class="container" style="text-align: center;">
        <form method="post" action-xhr="'.$component->form->url.'" style="padding: 30px 0">
          <fieldset style="border: none;">
            '.$inputs.'
            <input type="submit"
               style="padding: 8px 25px; background-color: #1976d2; border: none; border-radius: 4px; color: #ffffff; text-transform: uppercase; font-family: Poppins, Verdana, Arial, sans-serif; font-size: 15px; line-height: 22px;"
               value="'.$component->form->button.'">
            </fieldset>
            <div submit-success>
              <template type="amp-mustache">
                '.$component->form->successSend.'
              </template>
            </div>
            <div submit-error>
              <template type="amp-mustache">
                '.$component->form->errorSend.'
              </template>
            </div>
          </form>
        </div>';

    $formHTML = '
      <table bgcolor="#ffffff" style="width: 100%; max-width: 700px; margin:0 auto; border-collapse:collapse; border: none;">
        <tr>
          <td style="font-family: Poppins, Verdana, Arial, sans-serif; text-align: center; font-size: 22px; line-height: 28px; color: #000000; padding: 30px 50px 30px 50px;">
            <table style="margin:0 auto; border-collapse:collapse; border: none;">
              <tr>
                <td style="padding: 10px 20px; font-family: Poppins, Verdana, Arial, sans-serif; text-align: justify; font-size: 14px; line-height: 22px; color: #ffffff; background-color: #1976d2; border-radius: 4px; text-transform: uppercase;">
                  <a href="'.$component->form->url.'" style="color: #ffffff; text-decoration: none;" target="_blank">VYPLNIT FORMULÁŘ</a>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    ';
  } else {
    $form = "";
    $formImport = "";
    $formHTML = "";
  }
  #endregion e-mail components


  $amp = '<!DOCTYPE html>
<html ⚡4email data-css-strict>
<head>
  <meta charset="utf-8">
  <script async src="https://cdn.ampproject.org/v0.js"></script>
  '.$carouselImport.'
  '.$accordionImport.'
  '.$timeagoImport.'
  '.$formImport.'
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
  '.$logo.$carousel.$title.$text.$accordion.$timeago.$form.'
 </div>
</body>
</html>
';


  $html = '<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title></title>
  <style>
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
  </style>
</head>
<body style="width: 100%; font-family: Poppins, Verdana, Arial, sans-serif; font-size: 14px; margin: 0; padding: 0; background-color: #f3f5f7">

<table align="center" border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin:0 auto; border-collapse:collapse; border: none;">
  <tr>
    <td align="center" style="padding: 20px 0; border-collapse: collapse;">
      '.$logoHTML.$carouselHTML.$titleHTML.$textHTML.$accordionHTML.$timeagoHTML.$formHTML.'
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

  $query = $pdo->prepare("UPDATE template SET html = :html, amp = :amp, settings = :settings WHERE id = :id");
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
  $query = $pdo->prepare("DELETE FROM template WHERE id = :id");
  $result = $query->execute(array(
    ":id" => $id
  ));
  echo "1";
  exit();
}