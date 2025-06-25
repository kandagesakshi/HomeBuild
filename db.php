<?php
// DB connection
$host = "localhost";
$user = "root"; 
$pass = "";    
$dbname = "homebuild_landing";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
