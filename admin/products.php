<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "homebuild_landing";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM products ORDER BY id ASC";
$result = $conn->query($sql);
$products = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Products Section</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .table th, .table td {
      vertical-align: middle;
      font-size: 16px;
    }
    .table thead th {
      background-color: #0d6efd;
      color: white;
      font-size: 20px;
    }
    .header-title {
      font-size: 30px;
      font-weight: bold;
      color: #0d6efd;
      margin-bottom: 20px;
    }
    .img-preview {
      height: 80px;
      border-radius: 5px;
      border: 1px solid #ccc;
      padding: 3px;
    }
  </style>
</head>
<body>
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div class="header-title">Products Section</div>
    <a href="add_products.php" class="btn btn-success btn-lg">Add New</a>
  </div>
  <table class="table table-bordered shadow-sm bg-white">
    <thead>
      <tr>
        <th>Image</th>
        <th>Name</th>
        <th>Carpet Area</th>
        <th>Agreement Value</th>
        <th>Stamp Duty</th>
        <th>Registration</th>
        <th>GST</th>
        <th>Total Price</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($products)): ?>
        <?php foreach ($products as $product): ?>
          <tr>
            <td>
              <?php 
                $imagePath = "../" . $product['image_path'];
                if (!empty($product['image_path']) && file_exists($imagePath)): ?>
                <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-preview">
              <?php else: ?>
                <span class="text-muted">No image</span>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td><?= htmlspecialchars($product['carpet_area']) ?></td>
            <td><?= htmlspecialchars($product['agreement_value']) ?></td>
            <td><?= htmlspecialchars($product['stamp_duty']) ?></td>
            <td><?= htmlspecialchars($product['registration']) ?></td>
            <td><?= htmlspecialchars($product['gst']) ?></td>
            <td><?= htmlspecialchars($product['total_price']) ?></td>
            <td>
              <div class="d-grid gap-2">
                <a href="view_product.php?id=<?= $product['id'] ?>" class="btn btn-info btn-block">View</a>
                <a href="delete_product.php?id=<?= $product['id'] ?>" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="9" class="text-center text-muted">No Products found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>
