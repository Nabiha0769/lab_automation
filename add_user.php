<?php
session_start();
include './components/connnection.php';

if (!isset($_SESSION['username'])) {
  header("location:index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Deshboard</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/seodashlogo.png" />
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
  <div class="container-fluid mt-5">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-center align-item-center text-uppercase">
         <h5 class="card-title fw-semibold mb-4">Add Users</h4>
          </div>
          <div class="card">
          <div class="card-body">
            <form method="post">
              <div class="mb-3">
                <label for="fullname" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullname" name="fullname" required>
              </div>
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
              <div class="mb-3">
                <label for="department" class="form-label">Department</label>
                <select class="form-select" id="role" name="role" required>
                  <option value="">Select Role</option>
                <option value="admin">Admin</option>
                <option value="taster">Taster</option>
                  <option value="CPRI">CPRI</option>
                  <option value="manufacturer">Manufacturer</option>
                </select>
              </div>
              <button type="submit" name="submit_user" class="btn btn-primary w-100">Add User</button>
            </form>

            <?php
            if (isset($_POST['submit_user'])) {
              $fullname = $_POST['fullname'];
              $username = $_POST['username'];
              $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
              $department = $_POST['role'];

              // Insert into tbl_user
              $stmt = $conn->prepare("INSERT INTO tbl_user (name, username, password, role) VALUES (?, ?, ?, ?)");
              if ($stmt) {
                $stmt->bind_param("ssss", $fullname, $username, $password, $department);
                if ($stmt->execute()) {
                  $newUserId = $stmt->insert_id;
                  $stmt->close();

                  // If department is taster, insert into taster table
                            if ($department === "taster") {
            $stmtTaster = $conn->prepare("INSERT INTO taster (name, user_id) VALUES (?, ?)");
            if ($stmtTaster) {
                $stmtTaster->bind_param("si",$fullname,$newUserId);
                $stmtTaster->execute();
                $stmtTaster->close();
            }
            }


                  echo "<script>
                          alert('User added successfully');
                          window.location.href = 'user.php';
                        </script>";
                } else {
                  echo '<div class="alert alert-danger mt-3">Error: ' . htmlspecialchars($stmt->error) . '</div>';
                  $stmt->close();
                }
              } else {
                echo '<div class="alert alert-danger mt-3">Error preparing statement: ' . htmlspecialchars($conn->error) . '</div>';
              }
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="assets/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="assets/js/sidebarmenu.js"></script>
  <script src="assets/js/app.min.js"></script>
  <script src="assets/js/dashboard.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>
</html>
