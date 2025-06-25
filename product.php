<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "homebuild_landing";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>
<div class="inner">
    <div class="row">
        <?php while($row = $result->fetch_assoc()) { ?>
            <div class="col">
                <div class="product" onclick="openPopup(
                    '<?php echo $row['popup_image']; ?>', 
                    '<?php echo $row['name']; ?>', 
                    '<?php echo $row['carpet_area']; ?>', 
                    '<?php echo $row['agreement_value']; ?>', 
                    '<?php echo $row['stamp_duty']; ?>', 
                    '<?php echo $row['registration']; ?>', 
                    '<?php echo $row['gst']; ?>', 
                    '<?php echo $row['total_price']; ?>', 
                    '<?php echo $row['contact_number']; ?>')">
                    <img src="<?php echo $row['image_path']; ?>" alt="<?php echo $row['name']; ?>">
                    <div class="product-name"><?php echo $row['name']; ?></div>
                    <p><?php echo $row['description']; ?></p>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<!-- Only ONE Popup Block Required -->
<div id="popup" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closePopup()">&times;</span>
        <img id="popup-img" src="" alt="Product Image">
        <div class="popup-details">
            <h2 id="popup-title"></h2>
            <p id="popup-info"></p>
            <p id="popup-price"></p>
            <a id="call-btn" class="popup-btn">Call Us</a>
            <a id="whatsapp-btn" class="popup-btn" target="_blank">WhatsApp</a>
        </div>
    </div>
</div>
