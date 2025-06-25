<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "homebuild_landing"; 
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM footer_details LIMIT 1";
$result = $conn->query($sql);
$data = $result->fetch_assoc();
?>
<div class="footer">
    <div class="footer-content">
        <div class="footer-section">
            <h1><?= htmlspecialchars($data['promoter_name']) ?></h1>
            <p><strong>Address:</strong> <?= htmlspecialchars($data['address']) ?></p>
            <p><strong>Office:</strong> <?= htmlspecialchars($data['office_name']) ?></p>
            <p><strong>Site:</strong> <a href="http://<?= htmlspecialchars($data['site_url']) ?>" target="_blank"><?= htmlspecialchars($data['site_url']) ?></a></p>
            <p><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($data['email']) ?>"><?= htmlspecialchars($data['email']) ?></a></p>
            <p><strong>Contact:</strong> <a href="tel:<?= htmlspecialchars($data['contact']) ?>"><?= htmlspecialchars($data['contact']) ?></a></p>
            <!-- MahaRERA Logo & QR Code -->
            <div class="maharera-container">
                <div class="maharera-logo">
                    <img src="assets/images/Maharera.png" alt="MahaRERA Logo">
                </div>
                <div class="maharera-text">
                    <p>Maharera</p>
                    <p><strong>Project Code</strong></p><br><br>
                    <p><?= htmlspecialchars($data['project_code']) ?></p>
                </div>
                <div class="maharera-qr">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=<?= urlencode($data['qr_data']) ?>" alt="QR Code">
                </div>
            </div>
        </div>
        <!-- Middle Section: Site Map -->
        <div class="footer-section">
            <h1>Site Map</h1>
            <ul>
                <li><a href="#why-choose-us"><i class="fas fa-check-circle" style="color: green;"></i> Why choose us?</a></li>
                <li><a href="#our-presence"><i class="fas fa-check-circle" style="color: green;"></i> Our Presence</a></li>
                <li><a href="#project-feature"><i class="fas fa-check-circle" style="color: green;"></i> Project Features</a></li>
                <li><a href="#portfolio"><i class="fas fa-check-circle" style="color: green;"></i> Portfolio</a></li>
                <li><a href="#offers"><i class="fas fa-check-circle" style="color: green;"></i> Offers</a></li>
                <li><a href="#amenities"><i class="fas fa-check-circle" style="color: green;"></i> Amenities</a></li>
                <li><a href="#testimonials"><i class="fas fa-check-circle" style="color: green;"></i> Testimonials</a></li>
            </ul>
        </div>
        <!-- Right Section: Legal -->
        <div class="footer-section">
            <h1>Legal</h1>
            <ul>
                <li><a href="#" id="open-policy"><i class="fas fa-check-circle" style="color: green;"></i> Terms & Conditions </a></li>
                <li><a href="#" id="open-info"><i class="fas fa-check-circle" style="color: green;"></i> Disclaimer</a></li>
                <li><a href="#"><i class="fas fa-check-circle" style="color: green;"></i> Help & Support</a></li>
            </ul>
            <p class="designer" style="font-size: 1.00rem; white-space: nowrap;">Designed by <a href="#">@hashbeingyoung | 9404914529</a></p>
        </div>
    </div>
    <!-- Back to Top Section -->
    <div class="back-to-top">
        <a href="#" id="topButton">
            <i class="fas fa-arrow-up"></i>
        </a>
    </div>
    <p style="text-align: center;">Copyright © 2025 HomeBuild. All Rights Reserved.</p>
</div>