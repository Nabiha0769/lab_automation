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
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add Users</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/seodashlogo.png" />
  <link rel="stylesheet" href="../../node_modules/simplebar/dist/simplebar.min.css" />
  <link rel="stylesheet" href="assets/css/styles.min.css" />
  <style>
    input:focus, select:focus {
  box-shadow: 0 0 5px rgba(30, 60, 114, 0.5);
  border-color: #1e3c72;

    }
  .card:hover {
    box-shadow: 0 0 25px rgba(13, 110, 253, 0.3) !important;
    transition: box-shadow 0.3s ease-in-out;

}

  </style>
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

      <!-- Centered Form Container -->
      <div class="d-flex justify-content-center align-items-center" style="height: 100vh; padding: 20px;">
        <div class="card border border-light shadow-lg" style="max-width: 720px; width: 100%; border-radius: 14px;">

          <div class="card-body">
           <h5 class="card-title text-center fw-semibold mb-4 text-uppercase">
  <iconify-icon icon="mdi:account-plus" style="font-size: 24px;"></iconify-icon> Add Users
</h5>

            <form method="post" novalidate>
              <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" required />
              </div>
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required />
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required />
              </div>
              <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-control" id="role" name="role" required>
                  <option value="">--Select Role--</option>
                  <option value="Tester">Tester</option>
                  <option value="Manufacturer">Manufacturer</option>
                  <option value="Admin">Admin</option>
                </select>
              </div>
              <input type="submit" class="btn btn-primary w-100" value="Add User" name="submit_user" />
            </form>

            <?php
            if (isset($_POST['submit_user'])) {
              // Get form values safely
              $name = trim($_POST['name']);
              $username = trim($_POST['username']);
              $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
              $role = $_POST['role'];

              // Insert user
              $stmt = $conn->prepare("INSERT INTO tbl_user (name, username, password, role) VALUES (?, ?, ?, ?)");
              $stmt->bind_param("ssss", $name, $username, $password, $role);

              if ($stmt->execute()) {
                $user_id = $stmt->insert_id;

                if ($role === 'Tester') {
                  $stmt_tester = $conn->prepare("INSERT INTO testers (name, u_id) VALUES (?, ?)");
                  $stmt_tester->bind_param("si", $name, $user_id);
                  $stmt_tester->execute();
                  $stmt_tester->close();
                }

                echo "<script>
                  alert('User added successfully');
                  window.location.href = 'users.php';
                  </script>";
              } else {
                echo "<div class='alert alert-danger mt-3'>Error: " . htmlspecialchars($stmt->error) . "</div>";
              }

              $stmt->close();
            }
            ?>
          </div>
        </div>
      </div>

      <div class="py-6 px-6 text-center">
        <p class="mb-0 fs-4">
          Design and Developed by
          <a href="https://adminmart.com/" target="_blank" class="pe-1 text-primary text-decoration-underline">AdminMart.com</a>
          Distributed by
          <a href="https://themewagon.com/" target="_blank" class="pe-1 text-primary text-decoration-underline">ThemeWagon</a>
        </p>
      </div>
    </div>
  </div>

  <script src="assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="assets/js/sidebarmenu.js"></script>
  <script src="assets/js/app.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>
