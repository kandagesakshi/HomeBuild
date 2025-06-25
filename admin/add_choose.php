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
    $heading = $conn->real_escape_string($_POST['heading']);
    $paragraph = $conn->real_escape_string($_POST['paragraph']);
    $stat1_title = $conn->real_escape_string($_POST['stat1_title']);
    $stat1_desc = $conn->real_escape_string($_POST['stat1_desc']);
    $stat2_title = $conn->real_escape_string($_POST['stat2_title']);
    $stat2_desc = $conn->real_escape_string($_POST['stat2_desc']);
    $stat3_title = $conn->real_escape_string($_POST['stat3_title']);
    $stat3_desc = $conn->real_escape_string($_POST['stat3_desc']);

    $image_main = "";
    $image_overlay = "";

    $targetDir = "../uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Handle image_main upload
    if (!empty($_FILES['image_main']['name'])) {
        $mainFileName = time() . "_main_" . basename($_FILES["image_main"]["name"]);
        $mainTargetPath = $targetDir . $mainFileName;
        $mainType = strtolower(pathinfo($mainTargetPath, PATHINFO_EXTENSION));
        if (in_array($mainType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES["image_main"]["tmp_name"], $mainTargetPath)) {
                $image_main = substr($mainTargetPath, 3); // remove ../
            } else {
                $message = "Failed to upload main image.";
            }
        } else {
            $message = "Invalid file type for main image.";
        }
    }

    // Handle image_overlay upload
    if (!empty($_FILES['image_overlay']['name'])) {
        $overlayFileName = time() . "_overlay_" . basename($_FILES["image_overlay"]["name"]);
        $overlayTargetPath = $targetDir . $overlayFileName;
        $overlayType = strtolower(pathinfo($overlayTargetPath, PATHINFO_EXTENSION));
        if (in_array($overlayType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES["image_overlay"]["tmp_name"], $overlayTargetPath)) {
                $image_overlay = substr($overlayTargetPath, 3);
            } else {
                $message = "Failed to upload overlay image.";
            }
        } else {
            $message = "Invalid file type for overlay image.";
        }
    }

    if (empty($message)) {
        $sql = "INSERT INTO dream_house_section 
            (image_main, image_overlay, heading, paragraph, stat1_title, stat1_desc, stat2_title, stat2_desc, stat3_title, stat3_desc, created_at) 
            VALUES 
            ('$image_main', '$image_overlay', '$heading', '$paragraph', '$stat1_title', '$stat1_desc', '$stat2_title', '$stat2_desc', '$stat3_title', '$stat3_desc', NOW())";

        if ($conn->query($sql) === TRUE) {
            $message = "Why Choose section added successfully.";
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
  <title>Add Why Choose Info</title>
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

  <h2>Add Why Choose Section</h2>

  <?php if ($message): ?>
    <div class="alert alert-info message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label" for="image_main">Main Image (Width:450px Height:300px)</label>
      <input type="file" id="image_main" name="image_main" accept="image/*" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label" for="image_overlay">Overlay Image (Width:247.5px Height:165px)</label>
      <input type="file" id="image_overlay" name="image_overlay" accept="image/*" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label" for="heading">Heading</label>
      <input type="text" id="heading" name="heading" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label" for="paragraph">Paragraph</label>
      <textarea id="paragraph" name="paragraph" class="form-control" rows="3" required></textarea>
    </div>

    <h4>Statistics</h4>

    <div class="mb-3">
      <label class="form-label" for="stat1_title">Stat 1 Title</label>
      <input type="text" id="stat1_title" name="stat1_title" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label" for="stat1_desc">Stat 1 Description</label>
      <input type="text" id="stat1_desc" name="stat1_desc" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label" for="stat2_title">Stat 2 Title</label>
      <input type="text" id="stat2_title" name="stat2_title" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label" for="stat2_desc">Stat 2 Description</label>
      <input type="text" id="stat2_desc" name="stat2_desc" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label" for="stat3_title">Stat 3 Title</label>
      <input type="text" id="stat3_title" name="stat3_title" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label" for="stat3_desc">Stat 3 Description</label>
      <input type="text" id="stat3_desc" name="stat3_desc" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success btn-lg">Add Section</button>
    <a href="why_choose.php" class="btn btn-secondary btn-lg ms-2">Back</a>
  </form>

</body>
</html>
