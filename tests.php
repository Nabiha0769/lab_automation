<?php
include './components/connnection.php';
if (!isset($_SESSION['id'])){
  header("location: index.php");
  exit;
}
$query="select*from tests";
$tests= $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
          <h5 class="card-title fw-semibold">TESTS</h5>
          <table class="table">
            <thead>
              <tr>
                <th>TEST ID</th>
                <th>PRODUCT ID</th>
                <th>TEST TYPE ID</th>
                <th>TESTER ID</th>
                <th>TEST Date</th>
                <th>RESULT</th>
                <th>REMARKS</th>
                <th>STATUS</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $tests->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['test_id']}</td>
                        <td>{$row['product_id']}</td>
                        <td>{$row['test_type_id']}</td>
                        <td>{$row['tester_id']}</td>
                        <td>{$row['test_date']}</td>
                        <td>{$row['result']}</td>
                        <td>{$row['remarks']}</td>
                         <td><span class='badge bg-info'>{$row['status']}</span></td>
                        <td>
                          <a class='text-warning m-1' href='edit_tests.php?id={$row['test_id']}'><i class='fa-solid fa-pen-to-square'></i></a>
                          <a class='text-danger m-1' href='delete_tests.php?id={$row['test_id']}' onclick=\"return confirm('Are you sure you want to delete this product?')\"><i class='fa-solid fa-trash'></i></a>
                        </td>
                      </tr>
                       ";
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

