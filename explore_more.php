<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Hombuild - Build Your Dream Home</title>
  <!-- Bootstrap & Font Awesome -->
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body {
      background-color:rgb(212, 217, 223); 
    }
    .explore-card {
      overflow: hidden;
      position: relative;
    }
    .explore-card img {
      height: 250px;
      object-fit: cover;
      width: 100%;
    }
    .explore-card .card-img-overlay {
      background: rgba(0, 0, 0, 0.4);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      font-weight: bold;
    }
    /* 3D rotation animation for popup image */
    @keyframes rotate3d {
      from {
        transform: rotateY(0deg);
      }
      to {
        transform: rotateY(360deg);
      }
    }
  </style>
</head>
<body>

<?php include 'admin_navbar.php'; ?>

<?php include 'explore.php';?>

</body>
</html>
