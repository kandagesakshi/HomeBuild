<?php
$host = "localhost";
$user = "root"; 
$pass = "";    
$dbname = "homebuild_landing";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch navbar data (assuming only one row)
$sql = "SELECT * FROM navbar_info LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $navbar = $result->fetch_assoc();
} else {
    $navbar = [
        "logo_path" => "assets/images/nav.png",
        "brand_name" => "HomeBuild",
        "phone_number" => "+123 456 7890",
        "phone_link" => "+1234567890"
    ];
}

$conn->close();
?>
