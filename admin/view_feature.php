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
    $icon_class = $conn->real_escape_string($_POST['icon_class']);
    $title = $conn->real_escape_string($_POST['title']);

    $updateSQL = "
        UPDATE features 
        SET icon_class='$icon_class', title='$title' 
        WHERE id=$id
    ";

    if ($conn->query($updateSQL) === TRUE) {
        $message = "Feature updated successfully.";
    } else {
        $message = "Error updating feature: " . $conn->error;
    }
}

// Fetch data again
$sql = "SELECT * FROM features WHERE id = $id LIMIT 1";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    die("Feature not found.");
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Feature</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
    .icon-preview {
      font-size: 50px;
      color: #0d6efd;
      margin-top: 10px;
    }
  </style>
</head>
<body>

  <h2>Edit Feature</h2>

  <?php if ($message): ?>
    <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label class="form-label">Icon Class</label>
      <input type="text" name="icon_class" class="form-control" value="<?= htmlspecialchars($data['icon_class']) ?>" required>
      <?php if (!empty($data['icon_class'])): ?>
        <i class="<?= htmlspecialchars($data['icon_class']) ?> icon-preview"></i>
      <?php endif; ?>
      <div class="form-text">Example: <code>fas fa-home</code></div>
    </div>

    <div class="mb-3">
      <label class="form-label">Title</label>
      <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($data['title']) ?>" required>
    </div>

    <button type="submit" class="btn btn-primary btn-lg">Update Feature</button>
    <a href="features.php" class="btn btn-secondary btn-lg ms-2">Back</a>
  </form>

</body>
</html>
