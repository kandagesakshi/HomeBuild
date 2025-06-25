<?php
include 'config.php';  
?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <img src="<?php echo htmlspecialchars($navbar['logo_path']); ?>" alt="Logo" height="40">
      <span class="brand-text"><?php echo htmlspecialchars($navbar['brand_name']); ?></span>
    </a>
    <div class="ms-auto fw-bold">
        <a href="tel:<?php echo htmlspecialchars($navbar['phone_link']); ?>" class="call-btn">
            <i class="fas fa-phone"></i> Call Us
        </a>
        <span class="phone-number"><?php echo htmlspecialchars($navbar['phone_number']); ?></span>
    </div>
  </div>
</nav>
