<?php

$host = "a043um.forpsi.com"; /* Host name */
$user = "f145366"; /* User */
$password = "QDFHxx64"; /* Password */
$dbname = "f145366"; /* Database name */

$con = mysqli_connect($host, $user, $password,$dbname);
// Check connection
if (!$con) {
  die("Connection failed: " . mysqli_connect_error());
}