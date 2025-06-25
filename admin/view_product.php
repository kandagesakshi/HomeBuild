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

// Update on form submit
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

    $updateSQL = "
        UPDATE products SET
            name = '$name',
            description = '$description',
            carpet_area = '$carpet_area',
            agreement_value = '$agreement_value',
            stamp_duty = '$stamp_duty',
            registration = '$registration',
            gst = '$gst',
            total_price = '$total_price',
            contact_number = '$contact_number'
        WHERE id = $id
    ";

    if ($conn->query($updateSQL) === TRUE) {
        $message = "Product updated successfully.";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Fetch latest data
$sql = "SELECT * FROM products WHERE id = $id LIMIT 1";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    die("Product not found.");
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Product</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
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
      max-height: 180px;
      margin-top: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .form-control, .btn {
      font-size: 18px;
    }
  </style>
</head>
<body>

  <h2>Edit Product</h2>

  <?php if ($message): ?>
    <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label class="form-label">Product Name</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($data['name']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Contact Number</label>
      <input type="text" name="contact_number" class="form-control" value="<?= htmlspecialchars($data['contact_number']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($data['description']) ?></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Carpet Area</label>
      <input type="text" name="carpet_area" class="form-control" value="<?= htmlspecialchars($data['carpet_area']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Agreement Value</label>
      <input type="text" name="agreement_value" class="form-control" value="<?= htmlspecialchars($data['agreement_value']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Stamp Duty</label>
      <input type="text" name="stamp_duty" class="form-control" value="<?= htmlspecialchars($data['stamp_duty']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Registration</label>
      <input type="text" name="registration" class="form-control" value="<?= htmlspecialchars($data['registration']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">GST</label>
      <input type="text" name="gst" class="form-control" value="<?= htmlspecialchars($data['gst']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Total Price</label>
      <input type="text" name="total_price" class="form-control" value="<?= htmlspecialchars($data['total_price']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Product Image</label><br>
      <?php
        $imagePath = "../" . $data['image_path'];
        if (!empty($data['image_path']) && file_exists($imagePath)):
      ?>
        <img src="<?= $imagePath ?>" class="img-preview" alt="<?= htmlspecialchars($data['name']) ?>">
      <?php else: ?>
        <div class="text-muted">No image uploaded.</div>
      <?php endif; ?>
    </div>

    <div class="mb-3">
      <label class="form-label">Popup Image</label><br>
      <?php
        $popupPath = "../" . $data['popup_image'];
        if (!empty($data['popup_image']) && file_exists($popupPath)):
      ?>
        <img src="<?= $popupPath ?>" class="img-preview" alt="<?= htmlspecialchars($data['name']) ?> Popup">
      <?php else: ?>
        <div class="text-muted">No popup image uploaded.</div>
      <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary btn-lg">Update Product</button>
    <a href="products.php" class="btn btn-secondary btn-lg ms-2">Back</a>
  </form>

</body>
</html>
