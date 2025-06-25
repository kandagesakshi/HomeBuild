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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $subtitle = $conn->real_escape_string($_POST['subtitle']);

    // Handle image upload if a new image is provided
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $fileName = basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . time() . "_" . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg','jpeg','png','gif'];

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                $image_path = substr($targetFilePath, 3); // remove ../
                $sql_update = "UPDATE explore_places SET name='$name', subtitle='$subtitle', image_path='$image_path' WHERE id=$id";
            } else {
                $message = "Error uploading image.";
            }
        } else {
            $message = "Only JPG, JPEG, PNG, GIF files are allowed.";
        }
    } else {
        // No new image uploaded
        $sql_update = "UPDATE explore_places SET name='$name', subtitle='$subtitle' WHERE id=$id";
    }

    // Execute update if no upload error occurred
    if (isset($sql_update) && empty($message)) {
        if ($conn->query($sql_update) === TRUE) {
            $message = "Explore place updated successfully.";
        } else {
            $message = "Error updating record: " . $conn->error;
        }
    }
}

// Fetch existing data
$sql = "SELECT * FROM explore_places WHERE id=$id LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $place = $result->fetch_assoc();
} else {
    die("Explore place not found.");
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Explore Place</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      font-size: 18px;
      padding: 2rem;
      background-color: #f8f9fa;
    }
    .form-label {
      font-weight: bold;
      font-size: 20px;
    }
    .form-control, .btn {
      font-size: 18px;
    }
    .img-preview {
      max-height: 120px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .message {
      font-size: 20px;
      margin-bottom: 20px;
      color: green;
    }
  </style>
</head>
<body>

  <h2 class="text-primary mb-4">Edit Explore Place</h2>

  <?php if ($message): ?>
    <div class="alert alert-info message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="name" class="form-label">Place Name</label>
      <input type="text" name="name" id="name" class="form-control" required value="<?= htmlspecialchars($place['name']) ?>">
    </div>

    <div class="mb-3">
      <label for="subtitle" class="form-label">Subtitle</label>
      <input type="text" name="subtitle" id="subtitle" class="form-control" required value="<?= htmlspecialchars($place['subtitle']) ?>">
    </div>

    <div class="mb-3">
      <label for="image" class="form-label">Image (upload new to replace)</label><br>
      <?php if (!empty($place['image_path']) && file_exists("../" . $place['image_path'])): ?>
        <img src="../<?= htmlspecialchars($place['image_path']) ?>" alt="Image" class="img-preview"><br>
      <?php endif; ?>
      <input type="file" name="image" id="image" accept="image/*" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary btn-lg">Update Place</button>
    <a href="explores.php" class="btn btn-secondary btn-lg ms-2">Back</a>
  </form>

</body>
</html>
