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
    $alt_text = $conn->real_escape_string($_POST['alt_text']);
    $updateImageSQL = "";
    $uploadDir = "../uploads/";

    // Create upload directory if not exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (!empty($_FILES['image_path']['name'])) {
        $imageName = time() . "_award_" . basename($_FILES['image_path']['name']);
        $imagePath = $uploadDir . $imageName;

        if (move_uploaded_file($_FILES['image_path']['tmp_name'], $imagePath)) {
            $imageDBPath = substr($imagePath, 3); // Remove ../ for DB
            $updateImageSQL = ", image_path='$imageDBPath'";
        } else {
            $message = "Failed to upload image.";
        }
    }

    $sql_update = "
        UPDATE awards 
        SET alt_text='$alt_text' $updateImageSQL
        WHERE id=$id
    ";

    if ($conn->query($sql_update) === TRUE) {
        $message = "Award updated successfully.";
    } else {
        $message = "Error updating award: " . $conn->error;
    }
}

// Fetch existing data
$sql = "SELECT * FROM awards WHERE id = $id LIMIT 1";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    die("Award not found.");
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Award</title>
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
      max-height: 180px;
      border: 1px solid #ccc;
      border-radius: 5px;
      margin-top: 10px;
    }
    .message {
      font-size: 20px;
      color: green;
    }
  </style>
</head>
<body>

  <h2>Edit Award</h2>

  <?php if ($message): ?>
    <div class="alert alert-info message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Current Image</label><br>
      <?php
        $imagePath = "../" . $data['image_path'];
        if (!empty($data['image_path']) && file_exists($imagePath)):
      ?>
        <img src="<?= $imagePath ?>" class="img-preview" alt="<?= htmlspecialchars($data['alt_text']) ?>">
      <?php else: ?>
        <div class="text-muted">No image uploaded.</div>
      <?php endif; ?>
    </div>

    <div class="mb-3">
      <label class="form-label">Change Image (optional)</label>
      <input type="file" name="image_path" class="form-control" accept="image/*">
    </div>

    <div class="mb-3">
      <label class="form-label">Alt Text</label>
      <input type="text" name="alt_text" class="form-control" required value="<?= htmlspecialchars($data['alt_text']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Created At</label>
      <input type="text" class="form-control" value="<?= htmlspecialchars($data['created_at']) ?>" readonly>
    </div>

    <button type="submit" class="btn btn-primary btn-lg">Update Award</button>
    <a href="awards.php" class="btn btn-secondary btn-lg ms-2">Back</a>
  </form>

</body>
</html>
