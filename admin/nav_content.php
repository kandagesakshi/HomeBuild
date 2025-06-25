<?php
$host = "localhost";
$user = "root"; 
$pass = "";    
$dbname = "homebuild_landing";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM navbar_info LIMIT 1";
$result = $conn->query($sql);

$navbar = null;
if ($result && $result->num_rows > 0) {
    $navbar = $result->fetch_assoc();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Navbar Info</title>
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
      height: 60px;
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
    <div class="header-title">Navbar Details</div>
    <a href="add_navbar.php" class="btn btn-success btn-lg">Add New</a>
  </div>

  <?php if ($navbar): ?>
  <table class="table table-bordered shadow-sm bg-white">
    <thead>
      <tr>
        <th>Logo</th>
        <th>Brand Name</th>
        <th>Phone Number</th>
        <th>Phone Link</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          <?php if (!empty($navbar['logo_path']) && file_exists("../" . $navbar['logo_path'])): ?>
            <img src="../<?= htmlspecialchars($navbar['logo_path']) ?>" alt="Logo" class="img-preview">
          <?php else: ?>
            <span class="text-muted">No logo</span>
          <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($navbar['brand_name']) ?></td>
        <td><?= htmlspecialchars($navbar['phone_number']) ?></td>
        <td>
          <a href="tel:<?= htmlspecialchars($navbar['phone_link']) ?>" class="btn btn-outline-primary btn-sm">
            <?= htmlspecialchars($navbar['phone_link']) ?>
          </a>
        </td>
        <td class="action-buttons">
          <a href="view_navbar.php?id=<?= $navbar['id'] ?>" class="btn btn-info btn-lg">View</a>
          <a href="delete_navbar.php?id=<?= $navbar['id'] ?>" class="btn btn-danger btn-lg"
             onclick="return confirm('Are you sure you want to delete this entry?');">
             Delete
          </a>
        </td>
      </tr>
    </tbody>
  </table>
  <?php else: ?>
    <div class="alert alert-warning">No Navbar entry found. Please add one.</div>
  <?php endif; ?>
</body>
</html>
