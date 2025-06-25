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
    // Step 1: Fetch image paths before deleting
    $imgSql = "SELECT image_main, image_overlay FROM dream_house_section WHERE id = $id";
    $imgResult = $conn->query($imgSql);

    if ($imgResult && $imgResult->num_rows > 0) {
        $row = $imgResult->fetch_assoc();
        $mainImagePath = "../" . $row['image_main'];
        $overlayImagePath = "../" . $row['image_overlay'];

        // Step 2: Delete from database
        $sql = "DELETE FROM dream_house_section WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            // Step 3: Delete image files if they exist
            if (file_exists($mainImagePath)) {
                unlink($mainImagePath);
            }
            if (file_exists($overlayImagePath)) {
                unlink($overlayImagePath);
            }

            header("Location: why_choose.php"); 
            exit;
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "Record not found.";
    }
} else {
    echo "Invalid ID.";
}

$conn->close();
?>
