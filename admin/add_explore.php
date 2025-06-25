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
    $name = $conn->real_escape_string($_POST['name']);
    $subtitle = $conn->real_escape_string($_POST['subtitle']);
    $image_path = "";

    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $fileName = basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . time() . "_" . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                $image_path = substr($targetFilePath, 3); 
            } else {
                $message = "Error uploading the image.";
            }
        } else {
            $message = "Only JPG, JPEG, PNG, GIF files are allowed.";
        }
    }

    if (empty($message)) {
        $sql_insert = "INSERT INTO explore_places (name, subtitle, image_path) 
                       VALUES ('$name', '$subtitle','$image_path')";

        if ($conn->query($sql_insert) === TRUE) {
            $message = "Explore place added successfully.";
        } else {
            $message = "Error adding explore place: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Add Explore Place</title>
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

  <h2>Add Explore Place</h2>

  <?php if ($message): ?>
    <div class="alert alert-info message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label" for="name">Place Name</label>
      <input type="text" id="name" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label" for="subtitle">Subtitle</label>
      <input type="text" id="subtitle" name="subtitle" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label" for="image">Image</label>
      <input type="file" id="image" name="image" accept="image/*" class="form-control">
    </div>

    <button type="submit" class="btn btn-success btn-lg">Add Place</button>
    <a href="explores.php" class="btn btn-secondary btn-lg ms-2">Back</a>
  </form>

</body>
</html>
