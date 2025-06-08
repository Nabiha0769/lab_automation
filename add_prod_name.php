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
  <title>Add Product Type</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/seodashlogo.png" />
  <link rel="stylesheet" href="../../node_modules/simplebar/dist/simplebar.min.css">
  <link rel="stylesheet" href="assets/css/styles.min.css" />
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
  <div class="card">
    <div class="card-body">
      <h5 class="card-title fw-semibold mb-4">Add Product Name</h5>

      <!-- Add Product Name -->
      <form method="post">
        <div class="mb-3">
          <label for="prod_name" class="form-label">Product Name</label>
          <input type="text" class="form-control" name="prod_name" id="prod_name" placeholder="e.g., Capacitor 50V" required>
        </div>
        <input type="submit" value="Add Product Name" class="btn btn-secondary w-100" name="submit_name">
      </form>

      <?php
     if (isset($_POST['submit_name'])) {
        $prod_name = $_POST['prod_name'];
        $query = "INSERT INTO products (product_name) VALUES ('$prod_name')";
        $result = mysqli_query($conn, $query);
        if ($result) {
          echo "<script>alert('Product Name added successfully'); window.location.href='add_product.php';</script>";
        }
      }
      ?>
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

