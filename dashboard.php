<?php
include './components/connnection.php';

if (!isset($_SESSION['username'])) {
  header("location:index.php");
  exit();
}

// Fetch counters
$totalManufactured = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM products"))['total'];
$totalPassed = mysqli_fetch_assoc(mysqli_query($conn, "
  SELECT COUNT(DISTINCT t.product_id) as passed
  FROM tests t
  JOIN testflow tf ON t.test_type_id = tf.test_type_id AND t.product_id = tf.product_id
  WHERE t.status = 'Completed'
  GROUP BY t.product_id
  HAVING COUNT(DISTINCT t.test_type_id) = (SELECT COUNT(DISTINCT tf2.test_type_id) FROM testflow tf2 WHERE tf2.product_id = t.product_id)
"))['passed'] ?? 0;

$totalFailed = mysqli_fetch_assoc(mysqli_query($conn, "
  SELECT COUNT(DISTINCT t.product_id) as failed
  FROM tests t
  JOIN testflow tf ON t.test_type_id = tf.test_type_id AND t.product_id = tf.product_id
  WHERE t.status = 'Failed'
"))['failed'] ?? 0;

$totalMarket = mysqli_fetch_assoc(mysqli_query($conn, "
  SELECT COUNT(*) as ready
  FROM products p
  WHERE (
    SELECT COUNT(DISTINCT tf.test_type_id) FROM testflow tf WHERE tf.product_id = p.product_id
  ) = (
    SELECT COUNT(DISTINCT t.test_type_id) FROM tests t WHERE t.product_id = p.product_id AND t.status = 'Completed'
  )
  AND NOT EXISTS (
    SELECT 1 FROM reworklog r WHERE r.product_id = p.product_id
  )
  AND NOT EXISTS (
    SELECT 1 FROM tests t2 
    JOIN testflow tf2 ON tf2.product_id = p.product_id AND tf2.test_type_id = t2.test_type_id
    WHERE t2.product_id = p.product_id AND t2.status = 'Failed'
  )
"))['ready'] ?? 0;

// Fetch item list
$itemList = mysqli_query($conn, "
SELECT 
  p.product_id, 
  p.product_name, 
  p.serial_no, 
  (
    SELECT COUNT(*) 
    FROM tests t 
    WHERE t.product_id = p.product_id AND t.status = 'Completed'
  ) AS passed_tests,
  CASE 
    WHEN EXISTS (SELECT 1 FROM reworklog r WHERE r.product_id = p.product_id) THEN 'Rework'
    WHEN EXISTS (
      SELECT 1 FROM tests t 
      JOIN testflow tf ON tf.product_id = p.product_id AND tf.test_type_id = t.test_type_id
      WHERE t.product_id = p.product_id AND t.status = 'Failed'
    ) THEN 'Failed'
    WHEN (
      SELECT COUNT(DISTINCT tf.test_type_id) FROM testflow tf WHERE tf.product_id = p.product_id
    ) = (
      SELECT COUNT(DISTINCT t.test_type_id) FROM tests t WHERE t.product_id = p.product_id AND t.status = 'Completed'
    ) THEN 'Sent to Market'
    ELSE 'Under Test'
  END AS status
FROM products p
ORDER BY p.product_id DESC
");
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/seodashlogo.png" />
  <link rel="stylesheet" href="assets/css/styles.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

 
</head>

<body>
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"
     data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">

  <aside class="left-sidebar">
    <div>
      <div class="brand-logo d-flex align-items-center justify-content-between">
        <a href="./dashboard.php" class="text-nowrap logo-img">
          <img src="assets/images/logos/logo-light.svg" alt="" />
        </a>
        <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
          <i class="ti ti-x fs-8"></i>
        </div>
      </div>
      <?php include 'components/sidebar.php'; ?>
    </div>
  </aside>

  <div class="body-wrapper">
    <?php include 'components/header.php'; ?>

    <div class="container-fluid">

      <div class="row g-3 mb-4">
        <div class="col-lg-3 col-sm-6">
          <div class="card text-center shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Total Manufactured</h5>
              <h2 class="text-primary"><?= $totalManufactured ?></h2>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-sm-6">
          <div class="card text-center shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Total Passed</h5>
              <h2 class="text-success"><?= $totalPassed ?></h2>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-sm-6">
          <div class="card text-center shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Total Failed</h5>
              <h2 class="text-danger"><?= $totalFailed ?></h2>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-sm-6">
          <div class="card text-center shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Sent to Market</h5>
              <h2 class="text-warning"><?= $totalMarket ?></h2>
            </div>
          </div>
        </div>
      </div>

    <!-- Bar Chart -->
<div class="row mb-4">
  <div class="col-10 mx-auto">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title mb-3">Product Status Overview</h5>
        <div id="statusChart" style="height: 400px;"></div>
      </div>
    </div>
  </div>
</div>


      <!-- Table -->
      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="card-title mb-3">Product Details</h5>
              <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                  <thead class="table-light">
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Serial No</th>
                      <th>Passed Tests</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while ($row = mysqli_fetch_assoc($itemList)) { ?>
                      <tr>
                        <td><?= $row['product_id'] ?></td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><?= htmlspecialchars($row['serial_no']) ?></td>
                        <td><?= $row['passed_tests'] ?></td>
                        <td>
                          <?php 
                            $status = $row['status'];
                            $badgeClass = 'info';
                            if ($status == 'Failed') $badgeClass = 'danger';
                            elseif ($status == 'Sent to Market') $badgeClass = 'success';
                            elseif ($status == 'Rework') $badgeClass = 'warning';
                          ?>
                          <span class="badge bg-<?= $badgeClass ?>"><?= $status ?></span>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
  var options = {
    series: [{
      name: 'Count',
      data: [
        <?= $totalPassed ?>,
        <?= $totalFailed ?>,
        <?= $totalMarket ?>,
        <?= ($totalManufactured - $totalPassed - $totalFailed) ?>
      ]
    }],
    chart: {
      type: 'bar',
      height: 400,
      toolbar: {
        show: false
      }
    },
    plotOptions: {
      bar: {
        borderRadius: 6,
        columnWidth: '50%',
      }
    },
    dataLabels: {
      enabled: true,
      style: {
        fontSize: '16px',
        fontWeight: 'bold',
        colors: ['#1e3c72']  // DARK BLUE (readable on white)
      }
    },
    colors: ['#0d6efd', '#dc3545', '#ffc107', '#6c757d'],
    xaxis: {
      categories: ['Passed', 'Failed', 'Sent to Market', 'Others'],
      labels: {
        style: {
          colors: '#1e3c72',
          fontSize: '15px',
          fontWeight: 600
        }
      },
      axisBorder: {
        color: '#1e3c72'
      },
      axisTicks: {
        color: '#1e3c72'
      }
    },
    yaxis: {
      title: {
        text: 'Number of Products',
        style: {
          color: '#1e3c72',
          fontSize: '15px',
          fontWeight: 600
        }
      },
      labels: {
        style: {
          colors: '#1e3c72',
          fontSize: '15px',
          fontWeight: 600
        }
      }
    },
    grid: {
      borderColor: '#1e3c72',
      strokeDashArray: 3
    },
    tooltip: {
      style: {
        fontSize: '14px'
      },
      y: {
        formatter: function (val) {
          return val + " products";
        }
      }
    },
    legend: {
      show: false
    }
  };

  var chart = new ApexCharts(document.querySelector("#statusChart"), options);
  chart.render();
</script>




<script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="assets/js/sidebarmenu.js"></script>
  <script src="assets/js/app.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>
</html>
