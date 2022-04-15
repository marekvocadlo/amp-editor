<?php
session_start();
include "configNEW.php";

$data = json_decode(file_get_contents("php://input"));
$cText = $data->cText;
$cCarousel = $data->cCarousel;

$ampTemplate = '<!DOCTYPE html>
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
  <div class="container">
    <amp-carousel
        width="'.$cCarousel->width.'"
        height="'.$cCarousel->height.'"
        layout="responsive"
        type="slides"
        role="region"
        loop
    >
      <amp-img width="'.$cCarousel->width.'" height="'.$cCarousel->height.'" src="'.$cCarousel->img[0]->src.'"></amp-img>
      <amp-img width="'.$cCarousel->width.'" height="'.$cCarousel->height.'" src="'.$cCarousel->img[1]->src.'"></amp-img>
      <amp-img width="'.$cCarousel->width.'" height="'.$cCarousel->height.'" src="'.$cCarousel->img[2]->src.'"></amp-img>
    </amp-carousel>
  </div>
  <div class="container">
    <div style="text-align: center; font-size: '.$cText->font_size.'px; line-height: '.$cText->line_height.'px; color: '.$cText->color.'">'.$cText->text.'</div>
  </div>
</body>
</html>
';

//Odebrání mezer kvůli optimalizaci při rozesílání
$ampTemplateOptimized = trim($ampTemplate);

//Uložení optimalizované AMP do šablony
$query = $pdo->prepare("UPDATE template SET amp = ? WHERE id = ?");
$result = $query->execute(array(
  $ampTemplateOptimized,
  2
));

echo "Template saved";