<?php
include 'db.php';
$sql = "SELECT * FROM dream_house_section LIMIT 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
?>
<section class="dream-house">
    <div class="image-container">
        <img src="<?php echo $row['image_main']; ?>" alt="Business Man" class="main-img">
        <img src="<?php echo $row['image_overlay']; ?>" alt="House Magnifying Glass" class="overlay-img">
    </div>
    <div class="content">
        <h2><?php echo $row['heading']; ?></h2>
        <p><?php echo $row['paragraph']; ?></p>
        <div class="stats">
            <div>
                <h3><?php echo $row['stat1_title']; ?></h3>
                <p><?php echo $row['stat1_desc']; ?></p>
            </div>
            <div>
                <h3><?php echo $row['stat2_title']; ?></h3>
                <p><?php echo $row['stat2_desc']; ?></p>
            </div>
            <div>
                <h3><?php echo $row['stat3_title']; ?></h3>
                <p><?php echo $row['stat3_desc']; ?></p>
            </div>
        </div>
    </div>
</section>
<?php
}
$conn->close();
?>
