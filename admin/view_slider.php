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
    $alt_text = $conn->real_escape_string($_POST['alt_text']);
    
    // Handle image upload if a new file is uploaded
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $fileName = basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . time() . "_" . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg','jpeg','png','gif'];

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                // Remove old image file if exists
                $oldSql = "SELECT image_url FROM slider_images WHERE id=$id LIMIT 1";
                $oldResult = $conn->query($oldSql);
                if ($oldResult && $oldResult->num_rows > 0) {
                    $oldData = $oldResult->fetch_assoc();
                    if (!empty($oldData['image_url']) && file_exists("../" . $oldData['image_url'])) {
                        unlink("../" . $oldData['image_url']);
                    }
                }

                // Update image_url and alt_text in DB
                $image_url = substr($targetFilePath, 3); // remove ../ for db
                $sql_update = "UPDATE slider_images SET image_url='$image_url', alt_text='$alt_text' WHERE id=$id";
            } else {
                $message = "Sorry, there was an error uploading your file.";
            }
        } else {
            $message = "Only JPG, JPEG, PNG, GIF files are allowed.";
        }
    } else {
        // Only update alt_text if no new image uploaded
        $sql_update = "UPDATE slider_images SET alt_text='$alt_text' WHERE id=$id";
    }

    if (isset($sql_update)) {
        if ($conn->query($sql_update) === TRUE) {
            $message = "Slider image updated successfully.";
        } else {
            $message = "Error updating record: " . $conn->error;
        }
    }
}

// Fetch current slider image info
$sql = "SELECT * FROM slider_images WHERE id=$id LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $slider = $result->fetch_assoc();
} else {
    die("Slider image record not found.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Slider Image</title>
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

  <h2>Edit Slider Image</h2>

  <?php if ($message): ?>
    <div class="alert alert-info message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label" for="alt_text">Alt Text</label>
      <input type="text" id="alt_text" name="alt_text" class="form-control" required value="<?= htmlspecialchars($slider['alt_text']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label" for="image">Image (upload new to replace)</label><br>
      <?php if (!empty($slider['image_url']) && file_exists("../" . $slider['image_url'])): ?>
        <img src="../<?= htmlspecialchars($slider['image_url']) ?>" alt="Slider Image" class="img-preview"><br>
      <?php endif; ?>
      <input type="file" id="image" name="image" accept="image/*" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary btn-lg">Update Slider Image</button>
    <a href="image_content.php" class="btn btn-secondary btn-lg ms-2">Back</a>
  </form>

</body>
</html>
