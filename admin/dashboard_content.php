<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "homebuild_landing";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | HomeBuild</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap & FontAwesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .card {
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    }
    .card-title {
        font-size: 1.2rem;
    }
  </style>
</head>
<body>

<div class="container py-4">
  <h2 class="mb-6">Welcome to HomeBuild</h2>
  <!-- Info Cards -->
  <div class="row g-4">
    <?php
      $countProducts = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];
      $countTestimonials = $conn->query("SELECT COUNT(*) AS total FROM testimonials")->fetch_assoc()['total'];
      $countLocations = $conn->query("SELECT COUNT(*) AS total FROM locations")->fetch_assoc()['total'];
    ?>
    <div class="col-md-4">
      <div class="card text-bg-primary">
        <div class="card-body">
          <h5 class="card-title">Total Products</h5>
          <p class="display-6"><?= $countProducts ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-bg-primary">
        <div class="card-body">
          <h5 class="card-title">Total Testimonials</h5>
          <p class="display-6"><?= $countTestimonials ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-bg-warning">
        <div class="card-body">
          <h5 class="card-title">Total Locations</h5>
          <p class="display-6"><?= $countLocations ?></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Graph Section -->
  <div class="row mt-5">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header">
          <strong>Weekly Activity Overview</strong>
        </div>
        <div class="card-body">
          <canvas id="activityChart" height="150"></canvas>
        </div>
      </div>
    </div>

    <!-- Device Usage -->
    <div class="col-lg-4">
      <div class="card">
        <div class="card-header">
          <strong>Device Status</strong>
        </div>
        <div class="card-body">
          <canvas id="deviceChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Testimonials Table -->
  <div class="card mt-5">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Recent Testimonials</h5>
      <a href="testimonials.php" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body p-0">
      <table class="table table-hover mb-0">
        <thead>
          <tr>
            <th>Name</th>
            <th>Stars</th>
            <th>Text</th>
          </tr>
        </thead>
        <tbody>
        <?php
          $res = $conn->query("SELECT * FROM testimonials ORDER BY id DESC LIMIT 5");
          while ($row = $res->fetch_assoc()):
        ?>
          <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['stars']) ?> ⭐</td>
            <td><?= htmlspecialchars(substr($row['text'], 0, 50)) ?>...</td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Chart.js Graphs -->
<script>
const activityChart = new Chart(document.getElementById('activityChart'), {
  type: 'line',
  data: {
    labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
    datasets: [{
      label: 'Site Visits',
      data: [120, 150, 170, 130, 180, 220, 200],
      borderColor: 'blue',
      backgroundColor: 'rgba(0,0,255,0.1)',
      tension: 0.3,
      fill: true
    },
    {
      label: 'Inquiries',
      data: [30, 25, 40, 35, 50, 45, 55],
      borderColor: 'green',
      backgroundColor: 'rgba(0,128,0,0.1)',
      tension: 0.3,
      fill: true
    }]
  }
});

const deviceChart = new Chart(document.getElementById('deviceChart'), {
  type: 'doughnut',
  data: {
    labels: ['Mobile', 'Desktop', 'Tablet'],
    datasets: [{
      data: [55, 35, 10],
      backgroundColor: ['#0d6efd', '#198754', '#ffc107'],
    }]
  },
  options: {
    plugins: {
      legend: {
        position: 'bottom'
      }
    }
  }
});
</script>

</body>
</html>
