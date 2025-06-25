<?php
$host = "localhost";
$user = "root"; 
$pass = "";    
$dbname = "homebuild_landing";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM disclaimer LIMIT 1";
$result = $conn->query($sql);

$disclaimer = null;
if ($result && $result->num_rows > 0) {
    $disclaimer = $result->fetch_assoc();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Disclaimer Info</title>
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
    .action-buttons a {
      margin-right: 10px;
      font-size: 18px;
      padding: 10px 18px;
    }
  </style>
</head>
<body>
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div class="header-title">Disclaimer Details</div>
    <a href="add_disclaimer.php" class="btn btn-success btn-lg">Add New</a>
  </div>

  <?php if ($disclaimer): ?>
  <table class="table table-bordered shadow-sm bg-white">
    <thead>
      <tr>
        <th>Title</th>
        <th>Content</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?= htmlspecialchars($disclaimer['title']) ?></td>
        <td><?= nl2br(htmlspecialchars($disclaimer['content'])) ?></td>
        <td class="action-buttons">
          <a href="view_disclaimer.php?id=<?= $disclaimer['id'] ?>" class="btn btn-info btn-lg">View</a>
          <a href="delete_disclaimer.php?id=<?= $disclaimer['id'] ?>" class="btn btn-danger btn-lg"
             onclick="return confirm('Are you sure you want to delete this entry?');">
             Delete
          </a>
        </td>
      </tr>
    </tbody>
  </table>
  <?php else: ?>
    <div class="alert alert-warning">No Disclaimer entry found. Please add one.</div>
  <?php endif; ?>
</body>
</html>
