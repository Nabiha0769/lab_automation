<?php
include './components/connnection.php';
if (!isset($_SESSION['id'])) {
  header("location: index.php");
  exit;
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Product</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/seodashlogo.png" />
  <link rel="stylesheet" href="../../node_modules/simplebar/dist/simplebar.min.css">
  <link rel="stylesheet" href="assets/css/styles.min.css" />
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
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
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-center align-items-center text-uppercase">
              <h5 class="card-title fw-semibold mb-4">Add Product</h5>
            </div>
            <div class="card">
              <div class="card-body">
                <form method="post">
                  <div class="mb-3">
                    <label for="product_name" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="product_name" name="product_name"
                      placeholder="e.g., Capacitor 50V" required>
                  </div>

                  <div class="mb-3">
                    <label for="product_type" class="form-label">Product Type</label>
                    <input type="text" class="form-control" id="product_type" name="product_type"
                      placeholder="e.g., CAP, FUSE, SWG" required>
                  </div>

                  <div class="mb-3">
                    <label for="revisions" class="form-label">Revision</label>
                    <input type="text" class="form-control" id="revisions" name="revision"
                      placeholder="e.g., A1, B2" maxlength="3" required>
                  </div>

                  <div class="mb-3">
                    <label for="manufacture_date" class="form-label">Manufacture Date</label>
                    <input type="date" class="form-control" id="manufacture_date" name="manufacture_date"
                      placeholder="Select the manufacturing date" required>
                  </div>

                  <input type="submit" class="btn btn-primary" value="Add Product" name="submit_product">
                </form>


                <?php
                if (isset($_POST['submit_product'])) {
                  $product_name = $_POST['product_name'];
                  $product_type = $_POST['product_type'];
                  $revisions = $_POST['revision'];
                  $manufacture_date = $_POST['manufacture_date'];
                  $created_by = $_SESSION['id'];

                  // Get the latest serial number for this type + revision
                  $stmt = $conn->prepare("SELECT serial_no FROM products WHERE product_type = ? AND revision = ? ORDER BY product_id DESC LIMIT 1");
                  $stmt->bind_param("ss", $product_type, $revisions);
                  $stmt->execute();
                  $stmt->bind_result($last_serial);
                  $stmt->fetch();
                  $stmt->close();

                  $serial_no = str_pad((int)$last_serial + 1, 5, '0', STR_PAD_LEFT);

                  // Construct the 10-digit product ID
                  $product_code = substr($product_type, 0, 3); // Simplified product code
                  $product_code = strtoupper(preg_replace("/[^A-Z0-9]/", '', $product_code));
                  $product_id = $product_code . strtoupper($revisions) . $serial_no;

                  // Insert product
                  $stmt = $conn->prepare("INSERT INTO products (product_id, product_name, product_type, revision, serial_no, manufacture_date, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
                  $stmt->bind_param("ssssssi", $product_id, $product_name, $product_type, $revisions, $serial_no, $manufacture_date, $created_by);

                  if ($stmt->execute()) {
                    echo "<script>alert('Product added successfully'); window.location.href = 'products.php';</script>";
                  } else {
                    echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($stmt->error) . "</div>";
                  }
                  $stmt->close();
                }
                ?>
              </div>
            </div>
          </div>
        </div>
        <div class="py-6 px-6 text-center">
          <p class="mb-0 fs-4">Design and Developed by <a href="https://adminmart.com/" target="_blank"
              class="pe-1 text-primary text-decoration-underline">AdminMart.com</a> Distributed by <a href="https://themewagon.com/" target="_blank"
              class="pe-1 text-primary text-decoration-underline">ThemeWagon</a></p>
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