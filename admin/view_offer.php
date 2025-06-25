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
$sql = "SELECT * FROM offers WHERE id = $id LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    die("Offer not found.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>View Offer</title>
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
      max-width: 300px;
      border-radius: 5px;
      border: 1px solid #ccc;
      padding: 5px;
      margin-top: 10px;
      display: block;
    }
  </style>
</head>
<body>

  <h2>Offer Details</h2>

  <div class="mb-3">
    <div class="label-title">Title</div>
    <div class="value-box"><?= htmlspecialchars($data['title']) ?></div>
  </div>

  <div class="mb-3">
    <div class="label-title">Image</div>
    <?php
      $imagePath = "../" . $data['image_path'];
      if (!empty($data['image_path']) && file_exists($imagePath)): ?>
        <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($data['title']) ?>" class="img-preview" />
    <?php else: ?>
        <div class="text-muted">No image available.</div>
    <?php endif; ?>
  </div>

  <a href="offers.php" class="btn btn-secondary btn-lg">Back</a>

</body>
</html>
