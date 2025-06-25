<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "homebuild_landing";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM dream_house_section ORDER BY id ASC";
$result = $conn->query($sql);
$sections = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sections[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dream House Section</title>
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
      margin-bottom: 20px;
    }
    .img-preview {
      height: 80px;
      border-radius: 5px;
      border: 1px solid #ccc;
      padding: 3px;
    }
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .action-buttons a {
      margin-right: 10px;
      font-size: 18px;
      padding: 10px 18px;
    }
    .stat-title {
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div class="header-title">Dream House Section</div>
    <a href="add_choose.php" class="btn btn-success btn-lg">Add New</a>
  </div>
  <table class="table table-bordered shadow-sm bg-white">
    <thead>
      <tr>
        <th>Heading</th>
        <th>Paragraph</th>
        <th>Main Image</th>
        <th>Overlay Image</th>
        <th>Stats</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($sections)): ?>
        <?php foreach ($sections as $sec): ?>
          <tr>
            <td><?= htmlspecialchars($sec['heading']) ?></td>
            <td><?= htmlspecialchars($sec['paragraph']) ?></td>
            <td>
              <?php if (!empty($sec['image_main']) && file_exists("../" . $sec['image_main'])): ?>
                <img src="../<?= htmlspecialchars($sec['image_main']) ?>" alt="Main Image" class="img-preview">
              <?php else: ?>
                <span class="text-muted">No image</span>
              <?php endif; ?>
            </td>
            <td>
              <?php if (!empty($sec['image_overlay']) && file_exists("../" . $sec['image_overlay'])): ?>
                <img src="../<?= htmlspecialchars($sec['image_overlay']) ?>" alt="Overlay Image" class="img-preview">
              <?php else: ?>
                <span class="text-muted">No image</span>
              <?php endif; ?>
            </td>
            <td>
              <div class="stat-title"><?= htmlspecialchars($sec['stat1_title']) ?></div>
              <div><?= htmlspecialchars($sec['stat1_desc']) ?></div>
              <div class="stat-title mt-2"><?= htmlspecialchars($sec['stat2_title']) ?></div>
              <div><?= htmlspecialchars($sec['stat2_desc']) ?></div>
              <div class="stat-title mt-2"><?= htmlspecialchars($sec['stat3_title']) ?></div>
              <div><?= htmlspecialchars($sec['stat3_desc']) ?></div>
            </td>
            <td>
                <div class="d-grid gap-2">
                    <a href="view_dream_house.php?id=<?= $sec['id'] ?>" class="btn btn-info btn-block">View</a>
                    <a href="delete_dream_house.php?id=<?= $sec['id'] ?>" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to delete this section?');">Delete</a>
                </div>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="6" class="text-center text-muted">No Dream House content found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>
