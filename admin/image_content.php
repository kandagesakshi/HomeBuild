<?php
$host = "localhost";
$user = "root"; 
$pass = "";    
$dbname = "homebuild_landing";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM slider_images ORDER BY id ASC";
$result = $conn->query($sql);

$slider_images = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $slider_images[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Slider Images</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .table th, .table td {
      vertical-align: middle;
      font-size: 18px;
    }
    .table thead th {
      background-color: #0d6efd;
      color: white;
      font-size: 22px;
    }
    .header-title {
      font-size: 30px;
      font-weight: bold;
      color: #0d6efd;
    }
    .img-preview {
      height: 100px;
      border-radius: 5px;
      border: 1px solid #ccc;
      padding: 3px;
    }
    .action-buttons a {
      margin-right: 10px;
      font-size: 18px;
      padding: 10px 18px;
    }
  </style>
</head>
<body>
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div class="header-title">Slider Images</div>
    <a href="add_slider.php" class="btn btn-success btn-lg">Add New</a>
  </div>

  <?php if (!empty($slider_images)): ?>
    <table class="table table-bordered shadow-sm bg-white">
      <thead>
        <tr>
          <th>Image</th>
          <th>Alt Text</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($slider_images as $img): ?>
          <tr>
            <td>
              <?php if (!empty($img['image_url']) && file_exists("../" . $img['image_url'])): ?>
                <img src="../<?= htmlspecialchars($img['image_url']) ?>" alt="Slider Image" class="img-preview">
              <?php else: ?>
                <span class="text-muted">No image</span>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($img['alt_text']) ?></td>
            <td class="action-buttons">
              <a href="view_slider.php?id=<?= $img['id'] ?>" class="btn btn-info btn-lg">View</a>
              <a href="delete_slider.php?id=<?= $img['id'] ?>" class="btn btn-danger btn-lg"
                 onclick="return confirm('Are you sure you want to delete this image?');">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-warning">No slider images found. Please add one.</div>
  <?php endif; ?>
</body>
</html>
