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
    $name = $conn->real_escape_string($_POST['name']);
    $stars = floatval($_POST['stars']);
    $text = $conn->real_escape_string($_POST['text']);

    $newImagePath = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/testimonials/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename = basename($_FILES['image']['name']);
        $targetFile = $uploadDir . time() . '_' . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $newImagePath = ltrim($targetFile, '../'); // Save relative path
        }
    }

    if ($newImagePath) {
        $sql = "UPDATE testimonials SET name='$name', stars=$stars, text='$text', image='$newImagePath' WHERE id=$id";
    } else {
        $sql = "UPDATE testimonials SET name='$name', stars=$stars, text='$text' WHERE id=$id";
    }

    if ($conn->query($sql) === TRUE) {
        $message = "Testimonial updated successfully.";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Fetch data for form
$sql = "SELECT * FROM testimonials WHERE id = $id LIMIT 1";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    die("Testimonial not found.");
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Testimonial</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-size: 18px;
      padding: 2rem;
      background-color: #f8f9fa;
    }
    .form-label {
      font-weight: bold;
    }
    .img-preview {
      height: 100px;
      width: 100px;
      border-radius: 50%;
      border: 1px solid #ccc;
      margin-top: 10px;
    }
  </style>
</head>
<body>

<h2>Edit Testimonial</h2>

<?php if ($message): ?>
  <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
  <div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($data['name']) ?>" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Stars (0 to 5, decimal allowed)</label>
    <input type="number" step="0.1" min="0" max="5" name="stars" class="form-control" value="<?= htmlspecialchars($data['stars']) ?>" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Testimonial Text</label>
    <textarea name="text" class="form-control" rows="4" required><?= htmlspecialchars($data['text']) ?></textarea>
  </div>

  <div class="mb-3">
    <label class="form-label">Current Image</label><br>
    <?php
      $imagePath = "../" . $data['image'];
      if (!empty($data['image']) && file_exists($imagePath)):
    ?>
      <img src="<?= $imagePath ?>" class="img-preview" alt="<?= htmlspecialchars($data['name']) ?>">
    <?php else: ?>
      <div class="text-muted">No image uploaded.</div>
    <?php endif; ?>
  </div>

  <div class="mb-3">
    <label class="form-label">Upload New Image</label>
    <input type="file" name="image" class="form-control" accept="image/*">
  </div>

  <button type="submit" class="btn btn-primary btn-lg">Update</button>
  <a href="testimonials.php" class="btn btn-secondary btn-lg ms-2">Back</a>
</form>

</body>
</html>
