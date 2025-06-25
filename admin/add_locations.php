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
    $location_name = $conn->real_escape_string($_POST['location_name']);
    $image_path = "";

    $targetDir = "../uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    if (!empty($_FILES['location_image']['name'])) {
        $fileName = time() . "_location_" . basename($_FILES["location_image"]["name"]);
        $targetPath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));

        if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES["location_image"]["tmp_name"], $targetPath)) {
                $image_path = substr($targetPath, 3); // remove ../
            } else {
                $message = "Failed to upload location image.";
            }
        } else {
            $message = "Invalid file type for location image.";
        }
    }

    if (empty($message)) {
        $sql = "INSERT INTO locations (location_name, image_path) VALUES ('$location_name', '$image_path')";

        if ($conn->query($sql) === TRUE) {
            $message = "Location added successfully.";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Location</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
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

  <h2>Add Location</h2>

  <?php if ($message): ?>
    <div class="alert alert-info message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label" for="location_name">Location Name</label>
      <input type="text" id="location_name" name="location_name" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label" for="location_image">Location Image (Width:500px Height:310px)</label>
      <input type="file" id="location_image" name="location_image" accept="image/*" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success btn-lg">Add Location</button>
    <a href="locations.php" class="btn btn-secondary btn-lg ms-2">Back</a>
  </form>

</body>
</html>
