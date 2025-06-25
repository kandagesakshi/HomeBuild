<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "homebuild_landing";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alt_text = $conn->real_escape_string($_POST['alt_text']);

    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $fileName = basename($_FILES['image']['name']);
        $targetFilePath = $targetDir . time() . "_" . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                $image_url = substr($targetFilePath, 3); // remove "../" for DB path

                $sql = "INSERT INTO slider_images (image_url, alt_text) VALUES ('$image_url', '$alt_text')";
                if ($conn->query($sql) === TRUE) {
                    $message = "Image added successfully.";
                } else {
                    $message = "Database error: " . $conn->error;
                }
            } else {
                $message = "Error uploading file.";
            }
        } else {
            $message = "Only JPG, JPEG, PNG, GIF files are allowed.";
        }
    } else {
        $message = "Please select an image to upload.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Add Slider Image</title>
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
    .message {
      font-size: 20px;
      margin-bottom: 20px;
      color: green;
    }
  </style>
</head>
<body>
  <h2>Add Slider Image</h2>
  <?php if ($message): ?>
    <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label" for="image">Select Image (Width:1500px Height:600px)</label>
      <input type="file" id="image" name="image" accept="image/*" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label" for="alt_text">Alt Text</label>
      <input type="text" id="alt_text" name="alt_text" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary btn-lg">Upload</button>
    <a href="image_content.php" class="btn btn-secondary btn-lg ms-2">Back</a>
  </form>

</body>
</html>
