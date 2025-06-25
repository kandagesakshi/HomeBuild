<?php
session_start();

// DB connection
$host = "localhost";
$user = "root"; 
$pass = "";    
$dbname = "homebuild_landing";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];
$hashedPassword = hash('sha256', $password);

$sql = "SELECT * FROM admin_users WHERE email = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $hashedPassword);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $_SESSION['admin_email'] = $email;
    header("Location: admin_dashboard.php");
    exit;
} else {
    header("Location: admin_login.php?error=Invalid email or password");
    exit;
}
