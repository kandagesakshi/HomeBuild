<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "homebuild_landing";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM features ORDER BY id ASC";
$result = $conn->query($sql);
$features = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $features[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Features Section</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
    .feature-icon {
      font-size: 32px;
      color: #0d6efd;
    }
  </style>
</head>
<body>
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div class="header-title">Features Section</div>
    <a href="add_features.php" class="btn btn-success btn-lg">Add New</a>
  </div>
  <table class="table table-bordered shadow-sm bg-white">
    <thead>
      <tr>
        <th>Icon</th>
        <th>Title</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($features)): ?>
        <?php foreach ($features as $feature): ?>
          <tr>
            <td><i class="<?= htmlspecialchars($feature['icon_class']) ?> feature-icon"></i></td>
            <td><?= htmlspecialchars($feature['title']) ?></td>
            <td>
              <div class="d-grid gap-2">
                <a href="view_feature.php?id=<?= $feature['id'] ?>" class="btn btn-info btn-block">View</a>
                <a href="delete_feature.php?id=<?= $feature['id'] ?>" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to delete this feature?');">Delete</a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="4" class="text-center text-muted">No Features found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>
