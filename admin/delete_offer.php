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
$error = "";

if ($id > 0) {
    // Check if offer exists
    $sql_check = "SELECT id FROM offers WHERE id = $id LIMIT 1";
    $result = $conn->query($sql_check);
    if ($result && $result->num_rows > 0) {
        // Optionally, delete image file from server if you want
        $row = $result->fetch_assoc();
        $sql_get_image = "SELECT image_path FROM offers WHERE id = $id LIMIT 1";
        $image_result = $conn->query($sql_get_image);
        if ($image_result && $image_result->num_rows > 0) {
            $imageData = $image_result->fetch_assoc();
            $imagePath = "../" . $imageData['image_path'];
            if (file_exists($imagePath)) {
                @unlink($imagePath);  // delete image file, suppress errors
            }
        }

        // Delete the offer record
        $sql_delete = "DELETE FROM offers WHERE id = $id";
        if ($conn->query($sql_delete) === TRUE) {
            $conn->close();
            header("Location: offers.php?msg=deleted");
            exit;
        } else {
            $error = "Error deleting record: " . $conn->error;
        }
    } else {
        $error = "Offer not found.";
    }
} else {
    $error = "Invalid offer ID.";
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Delete Offer</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <div class="container mt-5">
    <div class="alert alert-danger" role="alert">
      <?= htmlspecialchars($error) ?>
    </div>
    <a href="offers.php" class="btn btn-primary btn-lg">Back to Offers</a>
  </div>
</body>
</html>
