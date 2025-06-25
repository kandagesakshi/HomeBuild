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
    $stars = intval($_POST['stars']);
    $text = $conn->real_escape_string($_POST['text']);
    $image_path = "";

    if ($stars < 1 || $stars > 5) {
        $message = "Stars must be between 1.0 and 5.0";
    } else {
        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        if (!empty($_FILES['testimonial_image']['name'])) {
            $fileName = time() . "_testimonial_" . basename($_FILES["testimonial_image"]["name"]);
            $targetPath = $targetDir . $fileName;
            $fileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));

            if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                if (move_uploaded_file($_FILES["testimonial_image"]["tmp_name"], $targetPath)) {
                    $image_path = substr($targetPath, 3); // remove ../
                } else {
                    $message = "Failed to upload testimonial image.";
                }
            } else {
                $message = "Invalid file type for testimonial image.";
            }
        }

        if (empty($message)) {
            $sql = "INSERT INTO testimonials (name, image, stars, text) VALUES ('$name', '$image_path', $stars, '$text')";

            if ($conn->query($sql) === TRUE) {
                $message = "Testimonial added successfully.";
            } else {
                $message = "Error: " . $conn->error;
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Testimonial</title>
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

  <h2>Add Testimonial</h2>

  <?php if ($message): ?>
    <div class="alert alert-info message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label" for="name">Name</label>
      <input type="text" id="name" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label" for="testimonial_image">Select Image (Width:100px Height:100px)</label>
      <input type="file" id="testimonial_image" name="testimonial_image" accept="image/*" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label" for="stars">Stars (1–5)</label>
      <input type="number" id="stars" name="stars" min="1" max="5" step="0.1" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label" for="text">Testimonial Text</label>
      <textarea id="text" name="text" rows="5" class="form-control" required></textarea>
    </div>

    <button type="submit" class="btn btn-success btn-lg">Add Testimonial</button>
    <a href="testimonials.php" class="btn btn-secondary btn-lg ms-2">Back</a>
  </form>

</body>
</html>
