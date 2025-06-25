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
$message = "";

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);

    // Image upload handling
    $newImagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/amenities/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $filename = basename($_FILES['image']['name']);
        $targetFile = $uploadDir . time() . '_' . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $newImagePath = ltrim($targetFile, '../'); // Save path relative to root
        }
    }

    // Build query
    if ($newImagePath) {
        $updateSQL = "UPDATE amenities SET title = '$title', image_path = '$newImagePath' WHERE id = $id";
    } else {
        $updateSQL = "UPDATE amenities SET title = '$title' WHERE id = $id";
    }

    if ($conn->query($updateSQL) === TRUE) {
        $message = "Amenity updated successfully.";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Fetch updated data
$sql = "SELECT * FROM amenities WHERE id = $id LIMIT 1";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    die("Amenity not found.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Amenity</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      font-size: 18px;
      padding: 2rem;
      background-color: #f8f9fa;
    }
    .form-label {
      font-weight: bold;
    }
    .img-preview {
      max-height: 180px;
      border: 1px solid #ccc;
      border-radius: 5px;
      margin-top: 10px;
    }
  </style>
</head>
<body>

  <h2>Edit Amenity</h2>

  <?php if ($message): ?>
    <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Title</label>
      <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($data['title']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Current Image</label><br>
      <?php
        $imagePath = "../" . $data['image_path'];
        if (!empty($data['image_path']) && file_exists($imagePath)): ?>
          <img src="<?= $imagePath ?>" class="img-preview" alt="<?= htmlspecialchars($data['title']) ?>">
      <?php else: ?>
          <div class="text-muted">No image uploaded.</div>
      <?php endif; ?>
    </div>

    <div class="mb-3">
      <label class="form-label">Upload New Image</label>
      <input type="file" name="image" class="form-control" accept="image/*">
    </div>

    <button type="submit" class="btn btn-primary btn-lg">Update Amenity</button>
    <a href="amenities.php" class="btn btn-secondary btn-lg ms-2">Back</a>
  </form>

</body>
</html>
