<?php

$siteurl = "http://localhost/training/test_exam/";
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "online_exam";

// Create connection
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>