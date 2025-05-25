<?php
session_start();
include './components/connnection.php';

if (!isset($_SESSION['username'])) {
  header("location:index.php");
  exit();
}

// Get user ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header("location:user.php");
  exit();
}

$user_id = (int) $_GET['id'];

// Initialize variables for form
$name = $username = $department = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Process form submission
  $name = $_POST['fullname'] ?? '';
  $username = $_POST['username'] ?? '';
  $department = $_POST['role'] ?? '';
  $password = $_POST['password'] ?? '';

  if (empty($name) || empty($username) || empty($department)) {
    $error = "Please fill all required fields.";
  } else {
    if ($password !== '') {
      // Password provided, hash it
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      $stmt = $conn->prepare("UPDATE tbl_user SET name=?, username=?, role=?, password=? WHERE id=?");
      $stmt->bind_param("ssssi", $name, $username, $department, $hashedPassword, $user_id);
    } else {
      // No password change
      $stmt = $conn->prepare("UPDATE tbl_user SET name=?, username=?, role=? WHERE id=?");
      $stmt->bind_param("sssi", $name, $username, $department, $user_id);
    }

    if ($stmt->execute()) {
      $stmt->close();
      echo "<script>
              alert('User updated successfully');
              window.location.href = 'user.php';
            </script>";
      exit();
    } else {
      $error = "Error updating user: " . htmlspecialchars($stmt->error);
      $stmt->close();
    }
  }
} else {
  // Fetch current data to prefill form
  $stmt = $conn->prepare("SELECT name, username, role FROM tbl_user WHERE id = ?");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $stmt->bind_result($name, $username, $department);
  if (!$stmt->fetch()) {
    // No user found, redirect
    $stmt->close();
    header("location:user.php");
    exit();
  }
  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Edit User</title>
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

      <div class="container mt-5">
        <div class="card">
          <div class="card-body">
            <h4 class="mb-4">Edit User</h4>
            <?php if ($error): ?>
              <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form method="post">
              <div class="mb-3">
                <label for="fullname" class="form-label">Full Name</label>
                <input type="text" id="fullname" name="fullname" class="form-control" required value="<?= htmlspecialchars($name) ?>">
              </div>
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" required value="<?= htmlspecialchars($username) ?>">
              </div>
              <div class="mb-3">
                <label for="department" class="form-label">Role</label>
                <select id="department" name="department" class="form-select" required>
                  <option value="">Select Role</option>
                  <option value="taster" <?= $department === 'taster' ? 'selected' : '' ?>>Taster</option>
                  <option value="CPRI" <?= $department === 'CPRI' ? 'selected' : '' ?>>CPRI</option>
                  <option value="manufacturer" <?= $department === 'manufacturer' ? 'selected' : '' ?>>Manufacturer</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password <small>(Leave blank to keep current password)</small></label>
                <input type="password" id="password" name="password" class="form-control" placeholder="New password (optional)">
              </div>
              <button type="submit" class="btn btn-primary">Update User</button>
              <a href="user.php" class="btn btn-secondary ms-2">Cancel</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/app.min.js"></script>
</body>
</html>
