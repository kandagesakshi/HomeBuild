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
    $brand_name = $conn->real_escape_string($_POST['brand_name']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $phone_link = $conn->real_escape_string($_POST['phone_link']);
    $logo_path = "";

    if (!empty($_FILES['logo']['name'])) {
        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $fileName = basename($_FILES["logo"]["name"]);
        $targetFilePath = $targetDir . time() . "_" . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["logo"]["tmp_name"], $targetFilePath)) {
                $logo_path = substr($targetFilePath, 3); // remove ../
            } else {
                $message = "Error uploading the logo.";
            }
        } else {
            $message = "Only JPG, JPEG, PNG, GIF files are allowed.";
        }
    }

    if (empty($message)) {
        $sql_insert = "INSERT INTO navbar_info (brand_name, phone_number, phone_link, logo_path) 
                       VALUES ('$brand_name', '$phone_number', '$phone_link', '$logo_path')";

        if ($conn->query($sql_insert) === TRUE) {
            $message = "Navbar added successfully.";
        } else {
            $message = "Error adding navbar: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Add Navbar Info</title>
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

  <h2>Add Navbar Info</h2>

  <?php if ($message): ?>
    <div class="alert alert-info message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label" for="brand_name">Brand Name</label>
      <input type="text" id="brand_name" name="brand_name" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label" for="phone_number">Phone Number (Display)</label>
      <input type="text" id="phone_number" name="phone_number" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label" for="phone_link">Phone Link (tel:)</label>
      <input type="text" id="phone_link" name="phone_link" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label" for="logo">Logo</label>
      <input type="file" id="logo" name="logo" accept="image/*" class="form-control">
    </div>

    <button type="submit" class="btn btn-success btn-lg">Add Navbar</button>
    <a href="nav_content.php" class="btn btn-secondary btn-lg ms-2">Back</a>
  </form>

</body>
</html>
