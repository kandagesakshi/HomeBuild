<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "homebuild_landing";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM offers ORDER BY id ASC";
$result = $conn->query($sql);
$offers = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $offers[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Offers Section</title>
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
    <div class="header-title">Offers Section</div>
    <a href="add_offers.php" class="btn btn-success btn-lg">Add New</a>
  </div>
  <table class="table table-bordered shadow-sm bg-white">
    <thead>
      <tr>
        <th>Image</th>
        <th>Title</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($offers)): ?>
        <?php foreach ($offers as $offer): ?>
          <tr>
            <td>
              <?php 
                $imagePath = "../" . $offer['image_path'];
                if (!empty($offer['image_path']) && file_exists($imagePath)): ?>
                <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($offer['title']) ?>" class="img-preview">
              <?php else: ?>
                <span class="text-muted">No image</span>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($offer['title']) ?></td>
            <td>
              <div class="d-grid gap-2">
                <a href="view_offer.php?id=<?= $offer['id'] ?>" class="btn btn-info btn-block">View</a>
                <a href="delete_offer.php?id=<?= $offer['id'] ?>" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to delete this offer?');">Delete</a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="3" class="text-center text-muted">No Offers found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>
