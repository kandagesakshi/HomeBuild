<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "homebuild_landing";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM footer_details ORDER BY id ASC";
$result = $conn->query($sql);
$footers = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $footers[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Footer Details Section</title>
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
  </style>
</head>
<body>
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div class="header-title">Footer Details Section</div>
    <a href="add_footer.php" class="btn btn-success btn-lg">Add New</a>
  </div>
  <table class="table table-bordered shadow-sm bg-white">
    <thead>
      <tr>
        <th>Promoter Name</th>
        <th>Address</th>
        <th>Office</th>
        <th>Site</th>
        <th>Email</th>
        <th>Contact</th>
        <th>Project Code</th>
        <th>QR</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($footers)): ?>
        <?php foreach ($footers as $footer): ?>
          <tr>
            <td><?= htmlspecialchars($footer['promoter_name']) ?></td>
            <td><?= htmlspecialchars($footer['address']) ?></td>
            <td><?= htmlspecialchars($footer['office_name']) ?></td>
            <td><a href="http://<?= htmlspecialchars($footer['site_url']) ?>" target="_blank"><?= htmlspecialchars($footer['site_url']) ?></a></td>
            <td><a href="mailto:<?= htmlspecialchars($footer['email']) ?>"><?= htmlspecialchars($footer['email']) ?></a></td>
            <td><a href="tel:<?= htmlspecialchars($footer['contact']) ?>"><?= htmlspecialchars($footer['contact']) ?></a></td>
            <td><?= htmlspecialchars($footer['project_code']) ?></td>
            <td><img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=<?= urlencode($footer['qr_data']) ?>" class="img-preview" alt="QR"></td>
            <td>
              <div class="d-grid gap-2">
                <a href="view_footer.php?id=<?= $footer['id'] ?>" class="btn btn-info btn-block">View</a>
                <a href="delete_footer.php?id=<?= $footer['id'] ?>" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to delete this footer detail?');">Delete</a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="9" class="text-center text-muted">No Footer Details found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>
