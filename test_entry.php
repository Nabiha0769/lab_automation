<?php
include './components/connnection.php';
if (!isset($_SESSION['id'])){
  header("location: index.php");
  exit;
}

$tester_id = $_SESSION['id'];
// Split product_id and test_type_id early
if (isset($_POST['product_id_test_type'])) {
  list($product_id, $test_type_id) = explode('_', $_POST['product_id_test_type']);
}

// Handle test submission
if (isset($_POST['submit_test'])) {
  // Generate test_id: e.g., PRD01TT01XXXXXXX
$product_code = strtoupper(substr(preg_replace("/[^A-Z0-9]/", '', $product_id), 0, 5));
$test_code = str_pad($test_type_id, 2, '0', STR_PAD_LEFT);

// Get last serial
$stmt = $conn->prepare("SELECT RIGHT(test_id, 7) FROM tests WHERE product_id = ? AND test_type_id = ? ORDER BY test_id DESC LIMIT 1");
$stmt->bind_param("si", $product_id, $test_type_id);
$stmt->execute();
$stmt->bind_result($last_serial);
$stmt->fetch();
$stmt->close();

$serial = str_pad((int)$last_serial + 1, 7, '0', STR_PAD_LEFT);
$test_id = $product_code . $test_code . $serial;

  $result = $_POST['result'];
  $remarks = $_POST['remarks'];
  $status = $_POST['status'];
  $test_date = date('Y-m-d');

  $stmt = $conn->prepare("INSERT INTO tests (test_id, product_id, test_type_id, tester_id, test_date, result, remarks, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssiissss", $test_id, $product_id, $test_type_id, $tester_id, $test_date, $result, $remarks, $status);
  $stmt->execute();
  $stmt->close();

  echo "<script>alert('Test submitted successfully.'); window.location.href = 'test_entry.php';</script>";
  exit;
}

// Get available products and test types assigned in testflow
$available = $conn->query("SELECT tf.product_id, p.product_name, tt.test_type_id AS test_type_id, tt.name
                          FROM testflow tf
                          JOIN products p ON tf.product_id = p.product_id
                          JOIN testtypes tt ON tf.test_type_id = tt.test_type_id
                          WHERE tf.test_type_id NOT IN (
                            SELECT test_type_id FROM tests WHERE product_id = tf.product_id AND status = 'Completed'
                          )
                          ORDER BY tf.product_id, tf.sequence");
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Test Entry</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/seodashlogo.png" />
  <link rel="stylesheet" href="../../node_modules/simplebar/dist/simplebar.min.css">
  <link rel="stylesheet" href="assets/css/styles.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    
      <!-- Sidebar navigation-->
        <?php include 'components/sidebar.php' ?>
        <!-- End Sidebar navigation -->
    <div class="body-wrapper">
      <?php include 'components/header.php'; ?>
      <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title fw-semibold">Enter Test Results</h5>

          <form method="post" class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Product & Test Type</label>
              <select class="form-control" name="product_id_test_type" required>
                <option value="">-- Select Product & Test --</option>
                <?php
                while ($row = $available->fetch_assoc()) {
                  $value = $row['product_id'] . "_" . $row['test_type_id'];
                  echo "<option value='{$value}'>{$row['product_id']} - {$row['product_name']} ({$row['name']})</option>";
                }
                ?>
              </select>
            </div>

            <div class="col-md-3">
              <label class="form-label">Result</label>
              <input type="text" class="form-control" name="result" required>
            </div>

            <div class="col-md-3">
              <label class="form-label">Status</label>
              <select class="form-control" name="status" required>
                <option value="Completed">Completed</option>
                <option value="Failed">Failed</option>
                <option value="Pending">Pending</option>
              </select>
            </div>

            <div class="col-12">
              <label class="form-label">Remarks</label>
              <textarea class="form-control" name="remarks" rows="3"></textarea>
            </div>

            <div class="col-12">
              <button type="submit" name="submit_test" class="btn btn-primary">Submit Test</button>
            </div>
          </form>

        </div>
      </div>
        
      </div>
    </div>
  </div>

  <script src="assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="assets/js/sidebarmenu.js"></script>
  <script src="assets/js/app.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>