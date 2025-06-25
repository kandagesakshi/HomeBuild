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
    $title = $conn->real_escape_string($_POST['title']);
    $image_path = "";

    $targetDir = "../uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    if (!empty($_FILES['offer_image']['name'])) {
        $fileName = time() . "_offer_" . basename($_FILES["offer_image"]["name"]);
        $targetPath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));

        if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES["offer_image"]["tmp_name"], $targetPath)) {
                $image_path = substr($targetPath, 3); // remove ../
            } else {
                $message = "Failed to upload offer image.";
            }
        } else {
            $message = "Invalid file type for offer image.";
        }
    }

    if (empty($message)) {
        $sql = "INSERT INTO offers (title, image_path) VALUES ('$title', '$image_path')";

        if ($conn->query($sql) === TRUE) {
            $message = "Offer added successfully.";
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
  <title>Add Offer</title>
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

  <h2>Add Offer</h2>

  <?php if ($message): ?>
    <div class="alert alert-info message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label" for="title">Offer Title</label>
      <input type="text" id="title" name="title" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label" for="offer_image">Offer Image (Width:500px Height:310px)</label>
      <input type="file" id="offer_image" name="offer_image" accept="image/*" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success btn-lg">Add Offer</button>
    <a href="offers.php" class="btn btn-secondary btn-lg ms-2">Back</a>
  </form>

</body>
</html>
