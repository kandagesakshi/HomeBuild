<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
  header("Location: admin_login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard | HomeBuild</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body {
      font-size: 20px !important;
      margin: 0;
      padding-top: 70px; 
      overflow-x: hidden; 
    }
    .sidebar a:hover {
      background-color:rgb(118, 205, 231);
    }
    .navbar-custom {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1030;
      padding-top: 1.5rem;
      padding-bottom: 1.5rem;
      background-color: #f8f9fa; 
      box-shadow: 0 2px 4px rgba(0,0,0,.1);
    }
    .navbar-center {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
    }
    .navbar-center .navbar-brand {
      font-size: 36px !important;
      font-weight: bold;
    }
    .nav-user-info {
      font-size: 22px !important;
    }
    .btn {
      font-size: 20px !important;
    }
    #sidebar {
      position: fixed;
      top: 85px; 
      bottom: 0;
      left: 0;
      width: 240px;
      padding-top: 1rem;
      background-color: #f8f9fa; 
      border-right: 1px solid #dee2e6;
      overflow-y: auto;
      height: calc(100vh - 70px);
      z-index: 1020;
    }
    .sidebar .nav-link.active {
      background-color: #0d6efd;
      color: white !important;
      font-weight: 600;
    }
    .sidebar .nav-link {
      color: #333;
      font-size: 18px;
    }
    main {
      margin-left: 240px;
      padding: 2rem 2rem;
      min-height: calc(100vh - 80px);
      overflow-y: auto;
    }
    iframe {
      width: 100%;
      height: calc(100vh - 100px);
      border: none;
    }
    @media (max-width: 768px) {
      #sidebar {
        width: 200px;
      }
      main {
        margin-left: 200px;
      }
    }
    @media (max-width: 576px) {
      #sidebar {
        display: none;
      }
      main {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light shadow-sm navbar-custom">
  <div class="container-fluid position-relative">
    <div class="navbar-center">
      <span class="navbar-brand mb-0">HomeBuild</span>
    </div>
    <div class="ms-auto d-flex align-items-center nav-user-info">
      <span class="me-4"><i class="fas fa-user-circle"></i> Admin</span>
      <a href="admin_logout.php" class="btn btn-outline-danger">Logout</a>
    </div>
  </div>
</nav>

<nav id="sidebar" class="sidebar">
  <ul class="nav flex-column" id="sidebarMenu">
    <li class="nav-item">
      <a class="nav-link" href="dashboard_content.php" target="mainContentFrame">
        <i class="fas fa-chart-line me-2"></i> Dashboard
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="nav_content.php" target="mainContentFrame">
        <i class="fas fa-bars me-2"></i> Nav Bar
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="image_content.php" target="mainContentFrame">
        <i class="fas fa-images me-2"></i> Image Slider
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="why_choose.php" target="mainContentFrame">
        <i class="fas fa-handshake me-2"></i> Why Choose Us?
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="awards.php" target="mainContentFrame">
        <i class="fas fa-trophy me-2"></i> Awards
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="locations.php" target="mainContentFrame">
        <i class="fas fa-map-marker-alt me-2"></i> Our Presence
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="features.php" target="mainContentFrame">
        <i class="fas fa-list-alt me-2"></i> Project Features
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="products.php" target="mainContentFrame">
        <i class="fas fa-home me-2"></i> Products
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="offers.php" target="mainContentFrame">
        <i class="fas fa-tag me-2"></i> Offers
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="amenities.php" target="mainContentFrame">
        <i class="fas fa-building me-2"></i> Amazing Amenities
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="explores.php" target="mainContentFrame">
        <i class="fas fa-compass me-2"></i> Explore More
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="testimonials.php" target="mainContentFrame">
        <i class="fas fa-comments me-2"></i> Testimonials
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="inquiry.php" target="mainContentFrame">
        <i class="fas fa-comment-dots"></i> Inquiry Contacts
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="privacy_policy.php" target="mainContentFrame">
        <i class="fas fa-user-shield me-2"></i> Terms & Conditions
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="disclaimer.php" target="mainContentFrame">
        <i class="fas fa-exclamation-triangle me-2"></i> Disclaimer
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="footer.php" target="mainContentFrame">
        <i class="fas fa-columns me-2"></i> Footer
      </a>
    </li>
  </ul>
</nav>

<main>
  <iframe name="mainContentFrame" src="dashboard_content.php"></iframe>
</main>

</body>
</html>
