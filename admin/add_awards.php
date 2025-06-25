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
    $image_path = "";

    $targetDir = "../uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    if (!empty($_FILES['award_image']['name'])) {
        $fileName = time() . "_award_" . basename($_FILES["award_image"]["name"]);
        $targetPath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));

        if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES["award_image"]["tmp_name"], $targetPath)) {
                $image_path = substr($targetPath, 3); // remove ../
            } else {
                $message = "Failed to upload award image.";
            }
        } else {
            $message = "Invalid file type for award image.";
        }
    }

    if (empty($message)) {
        $sql = "INSERT INTO awards (image_path, alt_text, created_at) VALUES ('$image_path', '$alt_text', NOW())";

        if ($conn->query($sql) === TRUE) {
            $message = "Award added successfully.";
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
  <title>Add Award</title>
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

  <h2>Add Award</h2>

  <?php if ($message): ?>
    <div class="alert alert-info message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label" for="award_image">Award Image (Width:160px Height:60px)</label>
      <input type="file" id="award_image" name="award_image" accept="image/*" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label" for="alt_text">Alt Text</label>
      <input type="text" id="alt_text" name="alt_text" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success btn-lg">Add Award</button>
    <a href="awards.php" class="btn btn-secondary btn-lg ms-2">Back</a>
  </form>

</body>
</html>
