<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "homebuild_landing";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $promoter_name = $conn->real_escape_string($_POST['promoter_name']);
    $address = $conn->real_escape_string($_POST['address']);
    $office_name = $conn->real_escape_string($_POST['office_name']);
    $site_url = $conn->real_escape_string($_POST['site_url']);
    $email = $conn->real_escape_string($_POST['email']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $project_code = $conn->real_escape_string($_POST['project_code']);
    $qr_data = $conn->real_escape_string($_POST['qr_data']);

    $sql = "INSERT INTO footer_details (promoter_name, address, office_name, site_url, email, contact, project_code, qr_data) 
            VALUES ('$promoter_name', '$address', '$office_name', '$site_url', '$email', '$contact', '$project_code', '$qr_data')";

    if ($conn->query($sql) === TRUE) {
        $message = "Footer details added successfully.";
    } else {
        $message = "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Footer Details</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-size: 18px;
      padding: 2rem;
      background-color: #f8f9fa;
    }
    .form-label {
      font-weight: bold;
      font-size: 20px;
    }
    .form-control, .btn {
      font-size: 18px;
    }
    .message {
      font-size: 20px;
      margin-bottom: 20px;
      color: green;
    }
  </style>
</head>
<body>

  <h2>Add Footer Details</h2>

  <?php if ($message): ?>
    <div class="alert alert-info message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label class="form-label" for="promoter_name">Promoter Name</label>
      <input type="text" id="promoter_name" name="promoter_name" class="form-control" required placeholder="Enter promoter's full name">
    </div>

    <div class="mb-3">
      <label class="form-label" for="address">Address</label>
      <input type="text" id="address" name="address" class="form-control" required placeholder="e.g. XYZ Street, City">
    </div>

    <div class="mb-3">
      <label class="form-label" for="office_name">Office Name</label>
      <input type="text" id="office_name" name="office_name" class="form-control" required placeholder="e.g. HomeBuild Office">
    </div>

    <div class="mb-3">
      <label class="form-label" for="site_url">Site URL</label>
      <input type="text" id="site_url" name="site_url" class="form-control" required placeholder="e.g. www.example.com">
    </div>

    <div class="mb-3">
      <label class="form-label" for="email">Email</label>
      <input type="email" id="email" name="email" class="form-control" required placeholder="e.g. info@example.com">
    </div>

    <div class="mb-3">
      <label class="form-label" for="contact">Contact Number</label>
      <input type="tel" id="contact" name="contact" class="form-control" required placeholder="e.g. +91 9876543210">
    </div>

    <div class="mb-3">
      <label class="form-label" for="project_code">Project Code</label>
      <input type="text" id="project_code" name="project_code" class="form-control" required placeholder="e.g. P521000XXXX">
    </div>

    <div class="mb-3">
      <label class="form-label" for="qr_data">QR Code URL</label>
      <input type="url" id="qr_data" name="qr_data" class="form-control" required placeholder="e.g. https://www.maharera.mahaonline.gov.in/">
    </div>

    <button type="submit" class="btn btn-success btn-lg">Add Footer</button>
    <a href="footer.php" class="btn btn-secondary btn-lg ms-2">Back</a>
  </form>

</body>
</html>
