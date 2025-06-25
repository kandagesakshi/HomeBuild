<?php
$host = "localhost";
$user = "root"; 
$pass = "";    
$dbname = "homebuild_landing";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM contacts WHERE id = $id LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $inquiry = $result->fetch_assoc();
} else {
    die("Inquiry record not found.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>View Inquiry Details</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      font-size: 18px;
      padding: 2rem;
      background-color: #f8f9fa;
    }
    .label-title {
      font-weight: bold;
      font-size: 20px;
      color: #0d6efd;
    }
    .content-text {
      font-size: 18px;
      padding-left: 10px;
    }
    .container-box {
      background: white;
      padding: 20px 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      max-width: 700px;
      margin: auto;
    }
    .back-btn {
      margin-top: 20px;
      font-size: 18px;
    }
  </style>
</head>
<body>

  <div class="container-box">
    <h2>Inquiry Details</h2>

    <div class="mb-3">
      <span class="label-title">Name:</span>
      <span class="content-text"><?= htmlspecialchars($inquiry['name']) ?></span>
    </div>

    <div class="mb-3">
      <span class="label-title">Mobile:</span>
      <span class="content-text"><?= htmlspecialchars($inquiry['mobile']) ?></span>
    </div>

    <div class="mb-3">
      <span class="label-title">City:</span>
      <span class="content-text"><?= htmlspecialchars($inquiry['city']) ?></span>
    </div>

    <div class="mb-3">
      <span class="label-title">Message:</span>
      <div class="content-text"><?= nl2br(htmlspecialchars($inquiry['message'])) ?></div>
    </div>

    <div class="mb-3">
      <span class="label-title">Received On:</span>
      <span class="content-text"><?= date('d-m-Y h:i A', strtotime($inquiry['created_at'])) ?></span>
    </div>

    <a href="inquiry.php" class="btn btn-secondary back-btn">Back to Inquiry List</a>
  </div>

</body>
</html>
