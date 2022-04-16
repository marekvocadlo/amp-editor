<?php
$host = "a043um.forpsi.com"; /* Host name */
$user = "f145366"; /* User */
$password = "QDFHxx64"; /* Password */
$dbname = "f145366"; /* Database name */

$dsn = 'mysql:host='.$host.';dbname='.$dbname.';charset=utf8';

try {
  $pdo = new PDO($dsn, $user, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die('Connection failed: ' . $e->getMessage());
}