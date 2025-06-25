<div class="container my-5">
  <h2 class="text-center mb-4 fw-bold">Explore More</h2>

  <div class="row g-4 justify-content-center">
    <?php
      // DB Connection
      $host = "localhost";
      $user = "root";
      $pass = "";
      $dbname = "homebuild_landing";

      $conn = new mysqli($host, $user, $pass, $dbname);
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      // Fetch data
      $sql = "SELECT name, subtitle, image_path FROM explore_places";
      $result = $conn->query($sql);

      if ($result->num_rows > 0):
          while ($row = $result->fetch_assoc()):
    ?>
    <div class="col-10 col-sm-6 col-md-4 col-lg-3"> 
      <div class="card explore-card text-white">
        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" 
        class="card-img" 
        style="filter: brightness(1.2);" 
        alt="<?php echo htmlspecialchars($row['name']); ?>">
        <div class="card-img-overlay d-flex align-items-center justify-content-center text-center">
          <div>
            <small class="card-subtitle d-block" style="font-size: 2.5rem;"><?php echo htmlspecialchars($row['subtitle']); ?></small>
            <h5 class="card-title fw-bold mb-0"><?php echo htmlspecialchars($row['name']); ?></h5>
          </div>
        </div>
      </div>
    </div>
    <?php
          endwhile;
      else:
          echo "<p class='text-center text-muted'>No places to explore yet.</p>";
      endif;

      $conn->close();
    ?>
  </div>
</div>

<!-- Modal for 3D image popup -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark">
      <div class="modal-header border-0">
        <h5 class="modal-title text-white" id="imageModalLabel"></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <img id="popupImage" src="" alt="" style="width: 100%; max-height: 80vh; object-fit: contain; transform-style: preserve-3d; animation: rotate3d 10s linear infinite;">
      </div>
    </div>
  </div>
</div>

<script>
  const imageModal = document.getElementById('imageModal');
  const popupImage = document.getElementById('popupImage');
  const modalTitle = document.getElementById('imageModalLabel');

  imageModal.addEventListener('show.bs.modal', event => {
    const card = event.relatedTarget;
    const imgSrc = card.querySelector('img').src;
    const imgAlt = card.querySelector('img').alt || '';

    popupImage.src = imgSrc;
    popupImage.alt = imgAlt;
    modalTitle.textContent = imgAlt;
  });

  // Add modal trigger attributes to all .explore-card elements dynamically
  document.querySelectorAll('.explore-card').forEach(card => {
    card.setAttribute('data-bs-toggle', 'modal');
    card.setAttribute('data-bs-target', '#imageModal');
  });
</script>