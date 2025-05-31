<?php
include './components/connnection.php';

if (!isset($_SESSION['id'])) {
  header("location: index.php");
  exit;
}

$u_id = $_SESSION['id'];
$name = $department = $contact = ""; // Default empty

// Fetch tester info if exists
$stmt = $conn->prepare("SELECT name, department, contact FROM testers WHERE u_id = ?");
$stmt->bind_param("i", $u_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
  $stmt->bind_result($name, $department, $contact);
  $stmt->fetch();
}
$stmt->close();
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tester Profile</title>
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
        <?php
        include 'components/sidebar.php'
        ?>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      <?php
      include 'components/header.php'
      ?>
      <!--  Header End -->
      <div class="container-fluid">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-center align-items-center text-uppercase">
              <h5 class="card-title fw-semibold mb-4">Tester Profile</h5>
            </div>
            <div class="card">
              <div class="card-body">
                <form method="post">
                  <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="department" class="form-label">Department</label>
                    <input type="text" class="form-control" id="department" name="department" value="<?= htmlspecialchars($department) ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="contact" class="form-label">Contact</label>
                    <input type="text" class="form-control" id="contact" name="contact" value="<?= htmlspecialchars($contact) ?>" required>
                  </div>
                  <input type="submit" class="btn btn-primary" value="Update" name="update">
                </form>
                <?php
                if (isset($_POST['update'])) {
                  $name = $_POST['name'];
                  $department = $_POST['department'];
                  $contact = $_POST['contact'];

                  // Check if record exists
                  $stmt = $conn->prepare("SELECT u_id FROM testers WHERE u_id = ?");
                  $stmt->bind_param("i", $u_id);
                  $stmt->execute();
                  $stmt->store_result();

                  if ($stmt->num_rows > 0) {
                    // Record exists, perform update
                    $stmt->close();
                    $stmt = $conn->prepare("UPDATE testers SET name = ?, department = ?, contact = ? WHERE u_id = ?");
                    $stmt->bind_param("sssi", $name, $department, $contact, $u_id);
                  } else {
                    // No record, perform insert
                    $stmt->close();
                    $stmt = $conn->prepare("INSERT INTO testers (name, department, contact, u_id) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("sssi", $name, $department, $contact, $u_id);
                  }

                  if ($stmt->execute()) {
                    echo "<script>
                      alert('Profile updated successfully');
                      window.location.href = 'tester_profile.php';
                    </script>";
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="assets/js/sidebarmenu.js"></script>
  <script src="assets/js/app.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>