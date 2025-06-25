<?php
include 'db.php';
$sql = "SELECT name, image, stars, text FROM testimonials ORDER BY id ASC";
$result = $conn->query($sql);
?>
<div class="testimonials">
    <div class="inner">
        <div class="row">
            <?php 
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $starsHtml = "";
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $row['stars']) {
                            $starsHtml .= '<i class="fas fa-star"></i>';
                        } else {
                            $starsHtml .= '<i class="far fa-star"></i>';
                        }
                    }
                    ?>
                    <div class="col">
                        <div class="testimonial">
                            <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="">
                            <div class="name"><?php echo htmlspecialchars($row['name']); ?></div>
                            <div class="stars"><?php echo $starsHtml; ?></div>
                            <p><?php echo htmlspecialchars($row['text']); ?></p>
                        </div>
                    </div>
                    <?php 
                }
            } else {
                echo "<p>No testimonials found.</p>";
            }
            ?>
        </div>
    </div>
</div>
