<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("location:index.php");
}
include 'components/connnection.php';
$prod_type = "select * from tbl_product";
$result = mysqli_query($conn, $prod_type);
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Manufactured Items</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/seodashlogo.png" />
  <link rel="stylesheet" href="../../node_modules/simplebar/dist/simplebar.min.css">
  <link rel="stylesheet" href="assets/css/styles.min.css" />
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="./dashboard.php" class="text-nowrap logo-img">
            <img src="assets/images/logos/logo-light.svg" alt="" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <?php include 'components/sidebar.php' ?>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      <?php include 'components/header.php' ?>
      <!--  Header End -->
      <div class="container-fluid">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="card-title fw-semibold mb-4">Add Manufactured Items</h5>
              <a class="mb-4 btn btn-success" href="add_prod_type.php">Add Product Type</a>
            </div>
            <form method="post" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="prod_type" class="form-label">Product Type</label>
                <select name="prod_type" id="prod_type" class="form-control">
                  <option value="">Select Product Type</option>
                  <?php
                  foreach ($result as $type) {
                    echo "
                          <option value='$type[prod_type]'>$type[prod_type]</option>
                          ";
                  }
                  ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="qty" class="form-label">Qty</label>
                <input type="number" class="form-control" id="qty" name="qty">
              </div>
              <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date">
              </div>

              <input type="submit" value="Add Product" class="btn btn-primary w-100" name="submit">
            </form>
            <?php
            if (isset($_POST['submit'])) {
              // Generate a 10-digit unique ID
              $id = mt_rand(1000000000, 9999999999); // Random 10-digit number

              $product_type = $_POST['prod_type'];
              $qty = $_POST['qty'];
              $date = $_POST['date'];

              // Optional: Check if ID already exists (recommended for uniqueness)
              $check = mysqli_query($conn, "SELECT * FROM tbl_manufacture WHERE id = '$id'");
              while (mysqli_num_rows($check) > 0) {
                $id = mt_rand(1000000000, 9999999999); // Generate a new one if exists
                $check = mysqli_query($conn, "SELECT * FROM tbl_manufacture WHERE id = '$id'");
              }

              $query = "INSERT INTO tbl_manufacture (id, product_type, qty, date) VALUES ('$id', '$product_type', '$qty', '$date')";
              $result = mysqli_query($conn, $query);

              if ($result) {
                echo "
                <script>
                  alert('Product added successfully');
                  window.location.href = 'products.php';
                </script>
              ";
              }
            }
            ?>

          </div>
        </div>
        
      </div>
    </div>
  </div>
  <script src="assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="assets/js/sidebarmenu.js"></script>
  <script src="assets/js/app.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>