<?php
include 'db.php';
$images = mysqli_query($conn, "SELECT * FROM slider_images ORDER BY id ASC");
// Fetch awards (limit to 4)
$awards = mysqli_query($conn, "SELECT * FROM awards LIMIT 4");
if (!$awards) {
    die("Awards query failed: " . mysqli_error($conn));
}
// Our Presence location
$locationQuery = mysqli_query($conn, "SELECT * FROM locations");
if (!$locationQuery) {
    die("Location query failed: " . mysqli_error($conn));
}
// Features
$featuresResult = $conn->query("SELECT * FROM features ORDER BY id ASC");
// Offers
$offersResult = $conn->query("SELECT * FROM offers ORDER BY id ASC");
if (!$offersResult) {
    die("Offers query failed: " . $conn->error);
}
// amenities
$amenitiesResult = $conn->query("SELECT * FROM amenities ORDER BY id ASC");
if (!$amenitiesResult) {
    die("Amenities query failed: " . $conn->error);
}
//Privacy Policy
$policy_title = '';
$policy_content = '';
$policyResult = $conn->query("SELECT * FROM privacy_policy ORDER BY id DESC LIMIT 1");
if ($policyResult && $row = $policyResult->fetch_assoc()) {
    $policy_title = $row['title'];
    $policy_content = nl2br(htmlspecialchars($row['content']));
}
//Disclaimer
$disclaimer_title = '';
$disclaimer_content = '';
$disclaimerResult = $conn->query("SELECT * FROM disclaimer ORDER BY id DESC LIMIT 1");
if ($disclaimerResult && $row = $disclaimerResult->fetch_assoc()) {
    $disclaimer_title = $row['title'];
    $disclaimer_content = nl2br(htmlspecialchars($row['content']));
}
// Brand Name (for use in WhatsApp link)
$brand_name = 'Your Brand';
$brandResult = $conn->query("SELECT brand_name FROM navbar_info LIMIT 1");
if ($brandResult && $row = $brandResult->fetch_assoc()) {
    $brand_name = $row['brand_name'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hombuild - Build Your Dream Home</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

    <!-- Navigation Bar start-->
    <?php include 'admin_navbar.php'; ?>
    <!-- Navigation Bar Ends-->
    
    <!-- Sliding Images start -->
    <div class="container mt-3 px-0" style="max-width: 1500px;">
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-indicators">
                <?php
                $count = 0;
                while ($row = mysqli_fetch_assoc($images)) {
                    $active = ($count === 0) ? 'active' : '';
                    echo '<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="' . $count . '" class="' . $active . '" aria-current="true" aria-label="Slide ' . ($count + 1) . '"></button>';
                    $count++;
                }
                mysqli_data_seek($images, 0); 
                ?>
            </div>
            <div class="carousel-inner">
                <?php
                $index = 0;
                while ($row = mysqli_fetch_assoc($images)) {
                    $active = ($index === 0) ? 'active' : '';
                    echo '<div class="carousel-item ' . $active . '">';
                    echo '<img src="' . $row['image_url'] . '" alt="' . htmlspecialchars($row['alt_text']) . '" class="d-block w-100" style="height: 600px; object-fit: cover;">';
                    echo '</div>';
                    $index++;
                }
                ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
    </div>
    <div class="text-center my-4">
        <a href="explore_more.php" class="btn btn-primary btn-lg">Explore More</a>
    </div>
    </div>
    <!-- Sliding Images Ends -->
     
    <!-- Why choose us starts -->
    <div class="dream-container" id="why-choose-us">
        Why Choose Us?
    </div>
    <?php include 'why_choose.php'; ?>
    <!-- Why choose us Ends -->

    <!-- Award section starts-->
    <div class="award-section">
    <div class="award-content">
        <div class="button">Awards & Recognitions</div>
        <div class="logo-container">
            <?php while($row = mysqli_fetch_assoc($awards)): ?>
                <div class="logo-box">
                    <img src="<?= $row['image_path']; ?>" alt="<?= $row['alt_text']; ?>">
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    </div>
    <!-- Awards section ends -->

    <!-- Our Presence section starts-->
    <div class="presence-container" id="our-presence">
        Our Presence
    </div>
    <div class="presence-section">
    <div class="slider-track">
        <div class="locations" id="locations">
            <?php while($loc = mysqli_fetch_assoc($locationQuery)): ?>
                <div class="location-card" style="background: url('<?= $loc['image_path']; ?>') no-repeat center center/cover;">
                    <div class="location-label"><?= htmlspecialchars($loc['location_name']); ?></div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
    const locations = document.getElementById('locations');

    const clone = locations.cloneNode(true);
    clone.removeAttribute('id');
    locations.parentElement.appendChild(clone);

    const totalWidth = locations.scrollWidth;
    const speed = 50; // pixels per second
    const duration = totalWidth / speed;

    const style = document.createElement("style");
    style.innerHTML = `
        @keyframes scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-${totalWidth}px); }
        }
        .slider-track {
            display: flex;
            flex-wrap: nowrap;
        }
        .slider-track > .locations {
            display: flex;
            gap: 15px;
            animation: scroll ${duration}s linear infinite;
        }
    `;
    document.head.appendChild(style);
   });
    </script>
    <!-- Presence section ends -->

    <!-- Project Features section starts-->
    <div class="feature-container" id="project-feature">
        Project Features
    </div>
    <div class="features-section">
    <div class="features-grid">
        <?php while ($feature = $featuresResult->fetch_assoc()) : ?>
            <div class="feature-card">
                <div class="ficon"><i class="<?= htmlspecialchars($feature['icon_class']); ?>"></i></div>
                <h5><?= htmlspecialchars($feature['title']); ?></h5>
            </div>
        <?php endwhile; ?>
    </div>
    </div>
    <!-- Features section ends -->

    <!-- Interested Product Section -->
    <div class="product-container" id="portfolio">Interesting Product Portfolio</div>
    <?php include 'product.php'; ?>
    <!-- Popup Form -->
    <script>
    function openPopup(img, title, carpet, agreement, stamp, registration, gst, total, contact) {
        document.getElementById("popup-img").src = img;
        document.getElementById("popup-title").innerText = title;
        document.getElementById("popup-info").innerHTML = `${carpet}<br>${agreement}<br>${stamp}<br>${registration}<br>${gst}`;
        document.getElementById("popup-price").innerText = total;
        // Set Call and WhatsApp links
        document.getElementById("call-btn").href = `tel:${contact}`;
        document.getElementById("whatsapp-btn").href = `https://wa.me/${contact}?text=Hi, I am interested in your property from <?php echo urlencode($brand_name); ?>.`;

        document.getElementById("popup").style.display = "flex";
    }
    function closePopup() {
        document.getElementById("popup").style.display = "none";
    }
    </script>
    <!-- Intresting product section ends -->

    <!-- Existing Offers section starts-->
    <div class="offers-container" id="offers">
        Exciting Current Offers
    </div>
    <div class="offer-section">
    <div class="slider-track-offer">
        <div class="Eoffers" id="Eoffers">
            <?php while ($offer = $offersResult->fetch_assoc()): ?>
                <div class="offer-card" style="background: url('<?php echo $offer['image_path']; ?>') no-repeat center center/cover;">
                    <div class="offer-label"><?php echo htmlspecialchars($offer['title']); ?></div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
    const offers = document.getElementById('Eoffers');
    // Clone for infinite scroll
    const clone = offers.cloneNode(true);
    clone.removeAttribute('id');
    offers.parentElement.appendChild(clone);

    const totalWidth = offers.scrollWidth;
    const speed = 50; // pixels/sec
    const duration = totalWidth / speed;

    const style = document.createElement("style");
    style.innerHTML = `
        @keyframes scrollOffers {
            0% { transform: translateX(0); }
            100% { transform: translateX(-${totalWidth}px); }
        }
        .slider-track-offer {
            display: flex;
            flex-wrap: nowrap;
        }
        .slider-track-offer > .Eoffers {
            display: flex;
            gap: 15px;
            animation: scrollOffers ${duration}s linear infinite;
        }
    `;
    document.head.appendChild(style);
    });
    </script>
    <!-- Offer section ends -->

    <!-- Amazing ametities section starts-->
    <div class="amazing-container" id="amenities">Amazing Amenities</div>
    <div class="amenities-container">
    <?php while ($amenity = $amenitiesResult->fetch_assoc()): ?>
        <div class="amenity-box">
            <img src="<?php echo $amenity['image_path']; ?>" alt="<?php echo htmlspecialchars($amenity['title']); ?>">
            <div class="amenity-label"><?php echo htmlspecialchars($amenity['title']); ?></div>
        </div>
    <?php endwhile; ?>
    </div>
    <!-- Amenities section ends -->

    <!-- Testimonials Starts -->
    <div class="testimonials-container" id="testimonials">
        Triumphant Testimonials
    </div>
    <?php include 'testimonial.php'; ?>
    <!-- Testimonials section Ends -->

    <!-- Form section starts -->
    <div class="heading-container">
        Book Your Schedule & Get The Quote
    </div>
    <div class="form-container">
        <div class="left-section">
            <form action="submit_form.php" method="POST" id="contactForm">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name">
                </div>
                <div class="form-group">
                    <label for="mobile">Mobile:</label>
                    <input type="text" id="mobile" name="mobile">
                </div>
                <div class="form-group">
                    <label for="city">City:</label>
                    <input type="text" id="city" name="city">
                </div>
                <div class="form-group">
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="3"></textarea>
                </div>
                <div class="submit-btn">
                    <a href="#" class="whatsapp-btn" id="whatsappBtn">WhatsApp</a>
                    <button type="submit">Submit</button>
                </div>
            </form>
        </div>
        <div class="right-section">
            <div class="google-map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3775.7234333540378!2d73.877643974246!3d18.854961158876446!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bdd35c1123c3331%3A0x72e9c42eb6e23bf1!2sHASHBEINGYOUNG%20IT%20Services%2C%20Design%20%26%20Marketing%20Agency!5e0!3m2!1smr!2sin!4v1740579401208!5m2!1smr!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
    <script>
    document.getElementById('contactForm').addEventListener('submit', function(event) {
    const name = document.getElementById('name').value.trim();
    const mobile = document.getElementById('mobile').value.trim();
    const city = document.getElementById('city').value.trim();
    const message = document.getElementById('message').value.trim();
    if (name === '') {
        alert('Please enter your name.');
        document.getElementById('name').focus();
        event.preventDefault();
        return false;
    }
    if (mobile === '') {
        alert('Please enter your mobile number.');
        document.getElementById('mobile').focus();
        event.preventDefault();
        return false;
    }
    if (!/^\d{10}$/.test(mobile)) {
        alert('Please enter a valid 10-digit mobile number.');
        document.getElementById('mobile').focus();
        event.preventDefault();
        return false;
    }
    if (city === '') {
        alert('Please enter your city.');
        document.getElementById('city').focus();
        event.preventDefault();
        return false;
    }
    if (message === '') {
        alert('Please enter your message.');
        document.getElementById('message').focus();
        event.preventDefault();
        return false;
    }
    });
    </script>
    <script>
    document.getElementById('whatsappBtn').addEventListener('click', function(event) {
    event.preventDefault(); 
    const name = encodeURIComponent(document.getElementById('name').value.trim());
    const mobile = encodeURIComponent(document.getElementById('mobile').value.trim());
    const city = encodeURIComponent(document.getElementById('city').value.trim());
    const message = encodeURIComponent(document.getElementById('message').value.trim());
    if (!name || !mobile || !city || !message) {
      alert('Please fill all fields before sending via WhatsApp.');
      return;
    }
    const whatsappMessage = 
      `Name: ${name}%0AMobile: ${mobile}%0ACity: ${city}%0AMessage: ${message}`;
    const whatsappNumber = "8605947621"; 
    const whatsappURL = `https://wa.me/${whatsappNumber}?text=${whatsappMessage}`;
    window.open(whatsappURL, '_blank');
    });
    </script>
    <!-- Form section Ends -->     

    <!-- Footer Section starts-->
    <?php include 'admin_footer.php'; ?>
    <script>
        document.getElementById("topButton").addEventListener("click", function(event) {
            event.preventDefault();
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    </script>
    <!-- Footer section ends -->

    <!-- Privacy Policy Pop-up Window Starts-->
    <div id="policy-overlay" class="policy-overlay" style="display: none;">
        <div class="policy-window">
            <div class="policy-header"><?= $policy_title ?></div>
            <div class="policy-content"><?= $policy_content ?></div>
            <div class="text-center mt-3" style="padding: 15px;">
                <input type="checkbox" id="agree-checkbox">
                <label for="agree-checkbox">I have read and agreed to the terms and conditions</label><br>
                <button class="policy-close-btn mt-2" id="close-policy" disabled>Close</button>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const policyOverlay = document.getElementById("policy-overlay");
        const openPolicy = document.getElementById("open-policy");
        const closePolicy = document.getElementById("close-policy");
        const agreeCheckbox = document.getElementById("agree-checkbox");

        // Disable background interactions
        function disableBackground() {
            document.body.style.overflow = "hidden";
            document.body.style.pointerEvents = "none";
            policyOverlay.style.pointerEvents = "auto";
        }

        // Enable background interactions
        function enableBackground() {
            document.body.style.overflow = "";
            document.body.style.pointerEvents = "";
        }

        // Show popup
        openPolicy.onclick = function (event) {
            event.preventDefault();
            policyOverlay.style.display = "block";
            closePolicy.disabled = true; // Ensure it's disabled initially
            agreeCheckbox.checked = false; // Reset checkbox
            disableBackground();
        };

        // Enable close button only when checkbox is checked
        agreeCheckbox.addEventListener("change", function () {
            closePolicy.disabled = !this.checked;
        });

        // Hide popup
        closePolicy.onclick = function () {
            if (!this.disabled) {
                policyOverlay.style.display = "none";
                enableBackground();
            }
        };

        // Close if clicked outside the popup
        window.onclick = function (event) {
            if (event.target === policyOverlay) {
                policyOverlay.style.display = "none";
                enableBackground();
            }
        };
    });
    </script>
    <!-- Privacy policy Pop-up Window ends-->

    <!-- Disclaimer Pop-up Window Starts-->
    <div id="info-overlay" class="info-overlay" style="display: none;">
        <div class="info-window" id="info-overlay">
            <div class="info-header"><?= $disclaimer_title ?></div>
            <div class="info-content"><?= $disclaimer_content ?></div>
            <div class="text-center mt-3" style="padding: 15px;">
                <input type="checkbox" id="agree-info-checkbox">
                <label for="agree-info-checkbox">I have read and agreed to the terms and conditions</label><br>
                <button class="close-btn mt-2" id="close-info" disabled>Close</button>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const infoOverlay = document.getElementById("info-overlay");
        const openInfo = document.getElementById("open-info");
        const closeInfo = document.getElementById("close-info");
        const agreeInfoCheckbox = document.getElementById("agree-info-checkbox");

        // Function to disable background interactions
        function disableBackground() {
            document.body.style.overflow = "hidden"; 
            document.body.style.pointerEvents = "none"; 
            infoOverlay.style.pointerEvents = "auto"; 
        }

        // Function to enable background interactions
        function enableBackground() {
            document.body.style.overflow = ""; 
            document.body.style.pointerEvents = ""; 
        }

        // Show Disclaimer Pop-up & Disable Background Interaction
        openInfo.onclick = function (event) {
            event.preventDefault();
            infoOverlay.style.display = "block";
            closeInfo.disabled = true;                 // Disable close button initially
            agreeInfoCheckbox.checked = false;         // Uncheck checkbox on open
            disableBackground(); 
        };

        // Enable close button only if checkbox is checked
        agreeInfoCheckbox.addEventListener("change", function () {
            closeInfo.disabled = !this.checked;
        });

        // Hide Disclaimer Pop-up & Enable Background Interaction
        closeInfo.onclick = function () {
            if (!this.disabled) {
                infoOverlay.style.display = "none";
                enableBackground(); 
            }
        };

        // Close pop-up when clicking outside of it
        window.onclick = function (event) {
            if (event.target === infoOverlay) {
                infoOverlay.style.display = "none";
                enableBackground(); 
            }
        };
    });
    </script>
    <!-- Disclaimer Pop-up Window ends-->
    
    <!-- Chatbot Button Starts-->
    <div class="chatbot-button" id="chatButton">
        <img src="assets/images/chat.png" alt="Chat Icon">
        <span>Let's Chat</span>
    </div>
    <div class="chat-container" id="chatContainer">
        <div class="chat-header">
            <h3>Chatbot</h3>
            <button id="closeChat">&times;</button>
        </div>
        <div class="chat-body" id="chatBody">
            <p class="bot-message">Hello! How can I assist you?</p>
        </div>
        <div class="chat-footer">
            <input type="text" id="userInput" placeholder="Type a message...">
            <button id="sendMessage">Send</button>
        </div>
    </div>
    <script>
    document.getElementById("chatButton").addEventListener("click", function() {
        document.getElementById("chatContainer").style.display = "flex";
    });
    document.getElementById("closeChat").addEventListener("click", function() {
        document.getElementById("chatContainer").style.display = "none";
    });
    document.getElementById("sendMessage").addEventListener("click", function() {
        let userText = document.getElementById("userInput").value;
        if (userText.trim() !== "") {
            let chatBody = document.getElementById("chatBody");

            // Display user's message
            let userMessage = document.createElement("p");
            userMessage.classList.add("user-message");
            userMessage.textContent = userText;
            chatBody.appendChild(userMessage);

            // Convert input to lowercase for checking (optional if you plan to match patterns)
            let lowerText = userText.toLowerCase();

            // Default response
            let botReply = document.createElement("p");
            botReply.classList.add("bot-message");

            // If input ends with a question mark or contains a question word, show contact response
            if (lowerText.includes("?") || 
                lowerText.includes("what") || 
                lowerText.includes("how") || 
                lowerText.includes("do you") || 
                lowerText.includes("can i") || 
                lowerText.includes("where") || 
                lowerText.includes("when") || 
                lowerText.includes("why")) {
                botReply.textContent = "Please Contact Us! You can email us at contact@rgrealestate.in";
            } else {
                botReply.textContent = "I'm here to help! Please ask a relevant question.";
            }

            // Show bot reply after short delay
            setTimeout(() => chatBody.appendChild(botReply), 500);
            document.getElementById("userInput").value = "";
            chatBody.scrollTop = chatBody.scrollHeight;
        }
    });
    </script>
    <!-- Chatbot Section ends  -->
</body>
</html>
