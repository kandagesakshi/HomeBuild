<?php
$host = "localhost";
$user = "root"; 
$pass = "";    
$dbname = "homebuild_landing";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM contacts ORDER BY created_at DESC";
$result = $conn->query($sql);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Inquiry Contacts</title>
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
      padding: 8px 14px;
    }
  </style>
</head>
<body>
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div class="header-title">Inquiry Contact Details</div>
    <!-- Optional Add Button -->
    <!-- <a href="add_inquiry.php" class="btn btn-success btn-lg">Add New</a> -->
  </div>

  <?php if ($result && $result->num_rows > 0): ?>
  <table class="table table-bordered shadow-sm bg-white">
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Mobile</th>
        <th>City</th>
        <th>Message</th>
        <th>Date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['mobile']) ?></td>
          <td><?= htmlspecialchars($row['city']) ?></td>
          <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
          <td><?= date('d-m-Y h:i A', strtotime($row['created_at'])) ?></td>
          <td class="action-buttons">
            <a href="view_inquiry.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">View</a>
            <a href="delete_inquiry.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
               onclick="return confirm('Are you sure you want to delete this inquiry?');">
               Delete
            </a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <?php else: ?>
    <div class="alert alert-warning">No inquiry records found.</div>
  <?php endif; ?>
</body>
</html>
