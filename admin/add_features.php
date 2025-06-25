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
    $icon_class = $conn->real_escape_string($_POST['icon_class']);
    $title = $conn->real_escape_string($_POST['title']);

    $sql = "INSERT INTO features (icon_class, title) 
            VALUES ('$icon_class', '$title')";

    if ($conn->query($sql) === TRUE) {
        $message = "Feature added successfully.";
    } else {
        $message = "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Feature</title>
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

  <h2>Add Feature</h2>

  <?php if ($message): ?>
    <div class="alert alert-info message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label class="form-label" for="icon_class">Icon Class</label>
      <input type="text" id="icon_class" name="icon_class" class="form-control" placeholder="e.g., fas fa-home" required>
    </div>

    <div class="mb-3">
      <label class="form-label" for="title">Title</label>
      <input type="text" id="title" name="title" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success btn-lg">Add Feature</button>
    <a href="features.php" class="btn btn-secondary btn-lg ms-2">Back</a>
  </form>

</body>
</html>
