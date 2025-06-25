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
$error = "";

if ($id > 0) {
    // Fetch record to ensure it exists
    $sql_get = "SELECT id FROM footer_details WHERE id = $id LIMIT 1";
    $result = $conn->query($sql_get);
    if ($result && $result->num_rows > 0) {
        $sql_delete = "DELETE FROM footer_details WHERE id = $id";
        if ($conn->query($sql_delete) === TRUE) {
            $conn->close();
            header("Location: footer.php?msg=deleted");
            exit;
        } else {
            $error = "Error deleting record: " . $conn->error;
        }
    } else {
        $error = "Footer record not found.";
    }
} else {
    $error = "Invalid footer ID.";
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Delete Footer</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <div class="container mt-5">
    <div class="alert alert-danger" role="alert">
      <?= htmlspecialchars($error) ?>
    </div>
    <a href="footer.php" class="btn btn-primary btn-lg">Back to Footer Details</a>
  </div>
</body>
</html>
