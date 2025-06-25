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
    // Get image path
    $sql_get = "SELECT image_path FROM amenities WHERE id = $id LIMIT 1";
    $result = $conn->query($sql_get);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (!empty($row['image_path']) && file_exists("../" . $row['image_path'])) {
            unlink("../" . $row['image_path']);
        }
        $sql_delete = "DELETE FROM amenities WHERE id = $id";
        if ($conn->query($sql_delete) === TRUE) {
            $conn->close();
            header("Location: amenities.php?msg=deleted");
            exit;
        } else {
            $error = "Error deleting record: " . $conn->error;
        }
    } else {
        $error = "Amenity not found.";
    }
} else {
    $error = "Invalid amenity ID.";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Delete Amenity</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <div class="container mt-5">
    <div class="alert alert-danger" role="alert">
      <?= htmlspecialchars($error) ?>
    </div>
    <a href="amenities.php" class="btn btn-primary btn-lg">Back to Amenities</a>
  </div>
</body>
</html>
