<?php
$host = "localhost";
$user = "root"; 
$pass = "";    
$dbname = "homebuild_landing";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Step 1: Fetch image path before deleting
    $imgSql = "SELECT image_url FROM slider_images WHERE id = $id";
    $imgResult = $conn->query($imgSql);

    if ($imgResult && $imgResult->num_rows > 0) {
        $row = $imgResult->fetch_assoc();
        $imagePath = "../" . $row['image_url'];

        // Step 2: Delete from database
        $sql = "DELETE FROM slider_images WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            // Step 3: Delete image file if exists
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            header("Location: image_content.php"); 
            exit;
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "Image not found.";
    }
} else {
    echo "Invalid ID.";
}

$conn->close();
?>
