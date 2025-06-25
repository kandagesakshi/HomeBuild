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
    $brand_name = $conn->real_escape_string($_POST['brand_name']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $phone_link = $conn->real_escape_string($_POST['phone_link']);
    
    // Handle logo upload if a new file is uploaded
    if (!empty($_FILES['logo']['name'])) {
        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $fileName = basename($_FILES["logo"]["name"]);
        $targetFilePath = $targetDir . time() . "_" . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg','jpeg','png','gif'];

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["logo"]["tmp_name"], $targetFilePath)) {
                // Update logo path in DB
                $logo_path = substr($targetFilePath, 3); // remove ../ for db storage
                $sql_update = "UPDATE navbar_info SET brand_name='$brand_name', phone_number='$phone_number', phone_link='$phone_link', logo_path='$logo_path' WHERE id=$id";
            } else {
                $message = "Sorry, there was an error uploading your file.";
            }
        } else {
            $message = "Only JPG, JPEG, PNG, GIF files are allowed.";
        }
    } else {
        $sql_update = "UPDATE navbar_info SET brand_name='$brand_name', phone_number='$phone_number', phone_link='$phone_link' WHERE id=$id";
    }

    if (isset($sql_update)) {
        if ($conn->query($sql_update) === TRUE) {
            $message = "Navbar updated successfully.";
        } else {
            $message = "Error updating record: " . $conn->error;
        }
    }
}

// Fetch current navbar info
$sql = "SELECT * FROM navbar_info WHERE id=$id LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $navbar = $result->fetch_assoc();
} else {
    die("Navbar record not found.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Navbar Info</title>
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
      max-height: 120px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .message {
      font-size: 20px;
      margin-bottom: 20px;
      color: green;
    }
  </style>
</head>
<body>

  <h2>Edit Navbar Info</h2>

  <?php if ($message): ?>
    <div class="alert alert-info message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label" for="brand_name">Brand Name</label>
      <input type="text" id="brand_name" name="brand_name" class="form-control" required value="<?= htmlspecialchars($navbar['brand_name']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label" for="phone_number">Phone Number (Display)</label>
      <input type="text" id="phone_number" name="phone_number" class="form-control" required value="<?= htmlspecialchars($navbar['phone_number']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label" for="phone_link">Phone Link (tel:)</label>
      <input type="text" id="phone_link" name="phone_link" class="form-control" required value="<?= htmlspecialchars($navbar['phone_link']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label" for="logo">Logo (upload new to replace)</label><br>
      <?php if (!empty($navbar['logo_path']) && file_exists("../" . $navbar['logo_path'])): ?>
        <img src="../<?= htmlspecialchars($navbar['logo_path']) ?>" alt="Logo" class="img-preview"><br>
      <?php endif; ?>
      <input type="file" id="logo" name="logo" accept="image/*" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary btn-lg">Update Navbar</button>
    <a href="nav_content.php" class="btn btn-secondary btn-lg ms-2">Back</a>
  </form>

</body>
</html>
