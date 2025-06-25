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
    // Get image paths
    $sql_get = "SELECT image_path, popup_image FROM products WHERE id = $id LIMIT 1";
    $result = $conn->query($sql_get);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Delete main image
        if (!empty($row['image_path']) && file_exists("../" . $row['image_path'])) {
            unlink("../" . $row['image_path']);
        }

        // Delete popup image
        if (!empty($row['popup_image']) && file_exists("../" . $row['popup_image'])) {
            unlink("../" . $row['popup_image']);
        }

        // Delete the product record
        $sql_delete = "DELETE FROM products WHERE id = $id";
        if ($conn->query($sql_delete) === TRUE) {
            $conn->close();
            header("Location: products.php?msg=deleted");
            exit;
        } else {
            $error = "Error deleting product: " . $conn->error;
        }
    } else {
        $error = "Product not found.";
    }
} else {
    $error = "Invalid product ID.";
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Delete Product</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <div class="container mt-5">
    <div class="alert alert-danger" role="alert">
      <?= htmlspecialchars($error) ?>
    </div>
    <a href="products.php" class="btn btn-primary btn-lg">Back to Products</a>
  </div>
</body>
</html>
