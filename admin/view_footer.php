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
$sql = "SELECT * FROM footer_details WHERE id = $id LIMIT 1";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    die("Footer detail not found.");
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>View Footer Detail</title>
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
      margin-top: 15px;
    }
    .value-box {
      font-size: 18px;
      padding: 8px 12px;
      background-color: #fff;
      border: 1px solid #dee2e6;
      border-radius: 5px;
    }
    .img-preview {
      max-height: 180px;
      border: 1px solid #ccc;
      border-radius: 5px;
      margin-top: 10px;
    }
  </style>
</head>
<body>

  <h2>Footer Details</h2>

  <div class="mb-3">
    <div class="label-title">Promoter Name</div>
    <div class="value-box"><?= htmlspecialchars($data['promoter_name']) ?></div>
  </div>

  <div class="mb-3">
    <div class="label-title">Address</div>
    <div class="value-box"><?= htmlspecialchars($data['address']) ?></div>
  </div>

  <div class="mb-3">
    <div class="label-title">Office Name</div>
    <div class="value-box"><?= htmlspecialchars($data['office_name']) ?></div>
  </div>

  <div class="mb-3">
    <div class="label-title">Site URL</div>
    <div class="value-box"><a href="http://<?= htmlspecialchars($data['site_url']) ?>" target="_blank"><?= htmlspecialchars($data['site_url']) ?></a></div>
  </div>

  <div class="mb-3">
    <div class="label-title">Email</div>
    <div class="value-box"><a href="mailto:<?= htmlspecialchars($data['email']) ?>"><?= htmlspecialchars($data['email']) ?></a></div>
  </div>

  <div class="mb-3">
    <div class="label-title">Contact</div>
    <div class="value-box"><a href="tel:<?= htmlspecialchars($data['contact']) ?>"><?= htmlspecialchars($data['contact']) ?></a></div>
  </div>

  <div class="mb-3">
    <div class="label-title">Project Code</div>
    <div class="value-box"><?= htmlspecialchars($data['project_code']) ?></div>
  </div>

  <div class="mb-3">
    <div class="label-title">QR Code</div>
    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= urlencode($data['qr_data']) ?>" class="img-preview" alt="QR Code">
  </div>

  <a href="footer.php" class="btn btn-secondary btn-lg">Back</a>

</body>
</html>
