<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "homebuild_landing";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM testimonials ORDER BY id ASC";
$result = $conn->query($sql);
$testimonials = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $testimonials[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Testimonials Section</title>
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
      border-radius: 50%;
      border: 1px solid #ccc;
      padding: 3px;
    }
    .star {
      color: gold;
    }
  </style>
</head>
<body>
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div class="header-title">Testimonials Section</div>
    <a href="add_testimonials.php" class="btn btn-success btn-lg">Add New</a>
  </div>
  <table class="table table-bordered shadow-sm bg-white">
    <thead>
      <tr>
        <th>Image</th>
        <th>Name</th>
        <th>Stars</th>
        <th>Text</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($testimonials)): ?>
        <?php foreach ($testimonials as $testimonial): ?>
          <tr>
            <td>
              <?php 
                $imagePath = "../" . $testimonial['image'];
                if (!empty($testimonial['image']) && file_exists($imagePath)): ?>
                <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($testimonial['name']) ?>" class="img-preview">
              <?php else: ?>
                <span class="text-muted">No image</span>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($testimonial['name']) ?></td>
            <td>
              <?php for ($i = 0; $i < intval($testimonial['stars']); $i++): ?>
                <span class="star">&#9733;</span>
              <?php endfor; ?>
            </td>
            <td><?= htmlspecialchars($testimonial['text']) ?></td>
            <td>
              <div class="d-grid gap-2">
                <a href="view_testimonial.php?id=<?= $testimonial['id'] ?>" class="btn btn-info btn-block">View</a>
                <a href="delete_testimonial.php?id=<?= $testimonial['id'] ?>" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to delete this testimonial?');">Delete</a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="5" class="text-center text-muted">No testimonials found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

</body>
</html>
