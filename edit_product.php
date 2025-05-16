<?php
session_start();
if(!isset($_SESSION['username'])){
  header("location:index.php");
}
include 'components/connnection.php';
$id = $_GET['id'];
$query = "Select * from tbl_products where p_id = '$id'";
$result = mysqli_query($conn,$query);
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SeoDash Free Bootstrap Admin Template by Adminmart</title>
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
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                <i class="ti ti-bell-ringing"></i>
                <div class="notification bg-primary rounded-circle"></div>
              </a>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <a href="#" target="_blank"
                class="btn btn-primary me-2"><span class="d-none d-md-block">Check Pro Version</span> <span class="d-block d-md-none">Pro</span></a>
              <a href="#" target="_blank"
                class="btn btn-success"><span class="d-none d-md-block">Download Free </span> <span class="d-block d-md-none">Free</span></a>
              <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <img src="assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i>
                      <p class="mb-0 fs-3">My Profile</p>
                    </a>
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-mail fs-6"></i>
                      <p class="mb-0 fs-3">My Account</p>
                    </a>
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-list-check fs-6"></i>
                      <p class="mb-0 fs-3">My Task</p>
                    </a>
                    <a href="./index.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!--  Header End -->
      <div class="container-fluid">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Edit Products</h5>
                
                <?php
                foreach($result as $row){
                  echo "
                  <form method='post' enctype='multipart/form-data'>
                  <div class='mb-3'>
                    <label for='product_name' class='form-label'>Product Name</label>
                    <input type='text' class='form-control' id='product_name' value='".$row['p_name']."' name='p_name'>
                  </div>
                  <div class='mb-3'>
                    <label for='product_price' class='form-label'>Price</label>
                    <input type='number' class='form-control' id='product_price' value='".$row['p_price']."' name='p_price'>
                  </div>
                  <div class='mb-3'>
                    <label for='product_desc' class='form-label'>Description</label>
                    <input type='text' class='form-control' id='product_desc' value='".$row['p_desc']."' name='p_desc'>
                  </div>
                  <div class='mb-3'>
                    <label for='product_image' class='form-label'>Image</label>
                    <input type='file' class='form-control' id='product_image' name='p_image'>
                  </div>
                  <input type='submit' value='Add Product' class='btn btn-primary w-100' name='submit'>
                </form>
                  ";
                }

                if(isset($_POST['submit'])){
                  if($_FILES['p_image']['size']>0){
                    $image_name = $_FILES['p_image']['name'];
                    $img_name = rand(1,999).$image_name;
                    $tmp_name = $_FILES['p_image']['tmp_name'];
                    $size = $_FILES['p_image']['size'];
                    $folder = "assets/images/products/".$img_name;
                    move_uploaded_file($tmp_name,$folder);
                  }
                  else{
                    foreach($result as $row){
                      $img_name = $row['p_image'];
                    }
                  }
                  $p_name = $_POST['p_name'];
                  $p_price = $_POST['p_price'];
                  $p_desc = $_POST['p_desc'];
                  $query = "update tbl_products set p_name='$p_name',p_price='$p_price',p_desc='$p_desc',p_image='$img_name' where p_id = '$id'";
                  $result = mysqli_query($conn,$query);
                  if($result){
                    echo "
                    <script>
                    alert('product Updated successfully');
                    window.location.href = 'products.php';
                    </script>
                    ";
                  }
                }
                ?>
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
  <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="assets/js/sidebarmenu.js"></script>
  <script src="assets/js/app.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>