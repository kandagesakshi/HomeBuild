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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle update form submission
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);

    $sql_update = "UPDATE privacy_policy SET title='$title', content='$content' WHERE id=$id";

    if ($conn->query($sql_update) === TRUE) {
        $message = "Privacy Policy updated successfully.";
    } else {
        $message = "Error updating record: " . $conn->error;
    }
}

// Fetch current privacy policy info
$sql = "SELECT * FROM privacy_policy WHERE id=$id LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $privacy = $result->fetch_assoc();
} else {
    die("Privacy Policy record not found.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Privacy Policy</title>
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

  <h2>Edit Terms & Conditions</h2>

  <?php if ($message): ?>
    <div class="alert alert-info message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label class="form-label" for="title">Title</label>
      <input type="text" id="title" name="title" class="form-control" required value="<?= htmlspecialchars($privacy['title']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label" for="content">Content</label>
      <textarea id="content" name="content" class="form-control" rows="10" required><?= htmlspecialchars($privacy['content']) ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary btn-lg">Update Privacy Policy</button>
    <a href="privacy_policy.php" class="btn btn-secondary btn-lg ms-2">Back</a>
  </form>

</body>
</html>
