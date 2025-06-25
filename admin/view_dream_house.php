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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $heading = $conn->real_escape_string($_POST['heading']);
    $paragraph = $conn->real_escape_string($_POST['paragraph']);
    $stat1_title = $conn->real_escape_string($_POST['stat1_title']);
    $stat1_desc = $conn->real_escape_string($_POST['stat1_desc']);
    $stat2_title = $conn->real_escape_string($_POST['stat2_title']);
    $stat2_desc = $conn->real_escape_string($_POST['stat2_desc']);
    $stat3_title = $conn->real_escape_string($_POST['stat3_title']);
    $stat3_desc = $conn->real_escape_string($_POST['stat3_desc']);

    $updateImageSQL = "";
    $uploadDir = "../uploads/";

    // Ensure upload directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Handle Main Image Upload
    if (!empty($_FILES['image_main']['name'])) {
        $mainImageName = time() . "_main_" . basename($_FILES['image_main']['name']);
        $mainImagePath = $uploadDir . $mainImageName;
        if (move_uploaded_file($_FILES['image_main']['tmp_name'], $mainImagePath)) {
            $mainImageDBPath = substr($mainImagePath, 3); // remove ../ for db
            $updateImageSQL .= ", image_main='$mainImageDBPath'";
        } else {
            $message = "Failed to upload Main Image.";
        }
    }

    // Handle Overlay Image Upload
    if (!empty($_FILES['image_overlay']['name'])) {
        $overlayImageName = time() . "_overlay_" . basename($_FILES['image_overlay']['name']);
        $overlayImagePath = $uploadDir . $overlayImageName;
        if (move_uploaded_file($_FILES['image_overlay']['tmp_name'], $overlayImagePath)) {
            $overlayImageDBPath = substr($overlayImagePath, 3); // remove ../ for db
            $updateImageSQL .= ", image_overlay='$overlayImageDBPath'";
        } else {
            $message = "Failed to upload Overlay Image.";
        }
    }

    // Final update query
    $sql_update = "
        UPDATE dream_house_section SET 
            heading='$heading', paragraph='$paragraph',
            stat1_title='$stat1_title', stat1_desc='$stat1_desc',
            stat2_title='$stat2_title', stat2_desc='$stat2_desc',
            stat3_title='$stat3_title', stat3_desc='$stat3_desc'
            $updateImageSQL
        WHERE id=$id
    ";

    if ($conn->query($sql_update) === TRUE) {
        $message = "Dream House section updated successfully.";
    } else {
        $message = "Error updating record: " . $conn->error;
    }
}

// Fetch existing data
$sql = "SELECT * FROM dream_house_section WHERE id = $id LIMIT 1";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    die("Dream House section not found.");
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Dream House Section</title>
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
      max-height: 150px;
      border: 1px solid #ccc;
      margin-top: 10px;
      border-radius: 5px;
    }
    .message {
      font-size: 20px;
      color: green;
    }
  </style>
</head>
<body>

  <h2>Edit Dream House Section</h2>

  <?php if ($message): ?>
    <div class="alert alert-info message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Heading</label>
      <input type="text" name="heading" class="form-control" required value="<?= htmlspecialchars($data['heading']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Paragraph</label>
      <textarea name="paragraph" class="form-control" rows="5" required><?= htmlspecialchars($data['paragraph']) ?></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Main Image</label><br>
      <?php if (!empty($data['image_main']) && file_exists("../" . $data['image_main'])): ?>
        <img src="../<?= htmlspecialchars($data['image_main']) ?>" class="img-preview"><br>
      <?php endif; ?>
      <input type="file" name="image_main" class="form-control" accept="image/*">
    </div>

    <div class="mb-3">
      <label class="form-label">Overlay Image</label><br>
      <?php if (!empty($data['image_overlay']) && file_exists("../" . $data['image_overlay'])): ?>
        <img src="../<?= htmlspecialchars($data['image_overlay']) ?>" class="img-preview"><br>
      <?php endif; ?>
      <input type="file" name="image_overlay" class="form-control" accept="image/*">
    </div>

    <?php for ($i = 1; $i <= 3; $i++): ?>
      <div class="mb-3">
        <label class="form-label">Stat <?= $i ?> Title</label>
        <input type="text" name="stat<?= $i ?>_title" class="form-control" required value="<?= htmlspecialchars($data["stat{$i}_title"]) ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Stat <?= $i ?> Description</label>
        <input type="text" name="stat<?= $i ?>_desc" class="form-control" required value="<?= htmlspecialchars($data["stat{$i}_desc"]) ?>">
      </div>
    <?php endfor; ?>

    <button type="submit" class="btn btn-primary btn-lg">Update Section</button>
    <a href="why_choose.php" class="btn btn-secondary btn-lg ms-2">Back</a>
  </form>

</body>
</html>
