<?php
include './components/connnection.php';
if (!isset($_SESSION['id'])) {
  header("location: index.php");
  exit;
}

// Fetch product list with status
$query = "
SELECT p.product_id, p.product_name, p.product_type, p.revision, p.serial_no, p.manufacture_date,
  CASE 
    WHEN EXISTS (SELECT 1 FROM reworklog r WHERE r.product_id = p.product_id) THEN 'Rework'
    WHEN EXISTS (
      SELECT 1 FROM tests t 
      JOIN testflow tf ON tf.product_id = p.product_id AND tf.test_type_id = t.test_type_id
      WHERE t.product_id = p.product_id AND t.status = 'Failed') THEN 'Failed'
    WHEN (
      SELECT COUNT(DISTINCT tf.test_type_id) FROM testflow tf WHERE tf.product_id = p.product_id
    ) = (
      SELECT COUNT(DISTINCT t.test_type_id) FROM tests t WHERE t.product_id = p.product_id AND t.status = 'Completed'
    ) THEN 'Ready for CPRI'
    ELSE 'Under Test'
  END AS status
FROM products p
ORDER BY p.product_id DESC";
$products = $conn->query($query);
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Products</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/seodashlogo.png" />
  <link rel="stylesheet" href="../../node_modules/simplebar/dist/simplebar.min.css">
  <link rel="stylesheet" href="assets/css/styles.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
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
          <h5 class="card-title fw-semibold">Products</h5>
          <table class="table">
            <thead>
              <tr>
                <th>Product ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Revision</th>
                <th>Serial No</th>
                <th>Manufacture Date</th>
                <th>Status</th>
                <th>Options</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $products->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['product_id']}</td>
                        <td>{$row['product_name']}</td>
                        <td>{$row['product_type']}</td>
                        <td>{$row['revision']}</td>
                        <td>{$row['serial_no']}</td>
                        <td>{$row['manufacture_date']}</td>
                        <td><span class='badge bg-info'>{$row['status']}</span></td>
                        <td>
                          <a class='text-warning m-1' href='edit_product.php?id={$row['product_id']}'><i class='fa-solid fa-pen-to-square'></i></a>
                          <a class='text-danger m-1' href='delete_product.php?id={$row['product_id']}' onclick=\"return confirm('Are you sure you want to delete this product?')\"><i class='fa-solid fa-trash'></i></a>
                        </td>
                      </tr>";
              } ?>
            </tbody>
          </table>
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