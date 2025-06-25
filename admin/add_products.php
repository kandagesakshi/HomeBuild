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
    $name = $conn->real_escape_string($_POST['name']);
    $contact_number = $conn->real_escape_string($_POST['contact_number']);
    $description = $conn->real_escape_string($_POST['description']);
    $carpet_area = $conn->real_escape_string($_POST['carpet_area']);
    $agreement_value = $conn->real_escape_string($_POST['agreement_value']);
    $stamp_duty = $conn->real_escape_string($_POST['stamp_duty']);
    $registration = $conn->real_escape_string($_POST['registration']);
    $gst = $conn->real_escape_string($_POST['gst']);
    $total_price = $conn->real_escape_string($_POST['total_price']);

    $image_path = "";
    $popup_image = "";

    $targetDir = "../uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Upload main image
    if (!empty($_FILES['image_path']['name'])) {
        $fileName = time() . "_img_" . basename($_FILES["image_path"]["name"]);
        $targetPath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));

        if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES["image_path"]["tmp_name"], $targetPath)) {
                $image_path = substr($targetPath, 3); // remove ../ for DB path
            } else {
                $message = "Failed to upload main image.";
            }
        } else {
            $message = "Invalid file type for main image.";
        }
    } else {
        $message = "Main image is required.";
    }

    // Upload popup image
    if (!$message && !empty($_FILES['popup_image']['name'])) {
        $fileName = time() . "_popup_" . basename($_FILES["popup_image"]["name"]);
        $targetPath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));

        if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES["popup_image"]["tmp_name"], $targetPath)) {
                $popup_image = substr($targetPath, 3); // remove ../ for DB path
            } else {
                $message = "Failed to upload popup image.";
            }
        } else {
            $message = "Invalid file type for popup image.";
        }
    } elseif (!$message) {
        $message = "Popup image is required.";
    }

    // Insert into DB if no error message so far
    if (empty($message)) {
        $sql = "INSERT INTO products 
            (name, description, image_path, popup_image, carpet_area, agreement_value, stamp_duty, registration, gst, total_price, contact_number) 
            VALUES 
            ('$name', '$description', '$image_path', '$popup_image', '$carpet_area', '$agreement_value', '$stamp_duty', '$registration', '$gst', '$total_price', '$contact_number')";

        if ($conn->query($sql) === TRUE) {
            $message = "Product added successfully.";
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
  <meta charset="UTF-8" />
  <title>Add Product</title>
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

  <h2>Add Product</h2>

  <?php if ($message): ?>
    <div class="alert alert-info message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label" for="name">Name</label>
      <input type="text" id="name" name="name" class="form-control" placeholder="e.g,1 BHK"required />
    </div>

    <div class="mb-3">
      <label class="form-label" for="contact_number">Name</label>
      <input type="text" id="contact_number" name="contact_number" class="form-control"required />
    </div>

    <div class="mb-3">
      <label class="form-label" for="description">Description</label>
      <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label" for="image_path">Main Image (Width:100px Height: 100px;)</label>
      <input type="file" id="image_path" name="image_path" accept="image/*" class="form-control" required />
    </div>

    <div class="mb-3">
      <label class="form-label" for="popup_image">Popup Image (Width:400px Height:300px)</label>
      <input type="file" id="popup_image" name="popup_image" accept="image/*" class="form-control" required />
    </div>

    <div class="mb-3">
      <label class="form-label" for="carpet_area">Carpet Area</label>
      <input type="text" id="carpet_area" name="carpet_area" class="form-control" placeholder="e.g,Carpet Area: 500 sq.ft"required />
    </div>

    <div class="mb-3">
      <label class="form-label" for="agreement_value">Agreement Value</label>
      <input type="text"  id="agreement_value" name="agreement_value" class="form-control" placeholder="e.g,Agreement Value: ₹50L"required />
    </div>

    <div class="mb-3">
      <label class="form-label" for="stamp_duty">Stamp Duty</label>
      <input type="text" id="stamp_duty" name="stamp_duty" class="form-control" placeholder="e.g,Stamp Duty: 5%"required />
    </div>

    <div class="mb-3">
      <label class="form-label" for="registration">Registration</label>
      <input type="text" id="registration" name="registration" class="form-control" placeholder="e.g,Registration: ₹30K"required />
    </div>

    <div class="mb-3">
      <label class="form-label" for="gst">GST</label>
      <input type="text" id="gst" name="gst" class="form-control" placeholder="e.g,GST: 5%"required />
    </div>

    <div class="mb-3">
      <label class="form-label" for="total_price">Total Price</label>
      <input type="text" id="total_price" name="total_price" class="form-control" placeholder="e.g,Total Price: ₹52.8L"required />
    </div>

    <button type="submit" class="btn btn-success btn-lg">Add Product</button>
    <a href="products.php" class="btn btn-secondary btn-lg ms-2">Back</a>
  </form>

</body>
</html>
