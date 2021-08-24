<?php

function OpenCon()
 {
 	$dbhost = "localhost:1234";
 	$dbuser = "root";
 	$dbpass = "";
 	$db = "jumbo_db";
// Create connection
 	$conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);
  return $conn;
 }
 
function CloseCon($conn)
 {
 	$conn -> close();
 }
 
?>