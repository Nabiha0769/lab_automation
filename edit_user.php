<?php
include './components/connnection.php';
if(!isset($_SESSION['id'])){
  header("location: index.php");
}

$id = $_GET['id'] ?? null;
$name = $username = $role = '';

if ($id) {
  $stmt = $conn->prepare("SELECT name, username, role FROM tbl_user WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->bind_result($name, $username, $role);
  $stmt->fetch();
  $stmt->close();
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Users</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/seodashlogo.png" />
  <link rel="stylesheet" href="../../node_modules/simplebar/dist/simplebar.min.css">
  <link rel="stylesheet" href="assets/css/styles.min.css" />
  <style>
    .edit-form-wrapper {
      max-width: 720px;
      margin: auto;
      margin-top: 40px;
      padding: 30px;
      background-color: #ffffff;
      border-radius: 12px;
      border: 2px solid #004080;
      box-shadow: 0 0 25px rgba(0, 64, 128, 0.15);
    }

    .card-title {
      font-size: 24px;
      color: #004080;
      font-weight: bold;
      text-align: center;
      text-transform: uppercase;
      margin-bottom: 30px;
    }

    .form-control:focus {
      border-color: #004080;
      box-shadow: 0 0 0 0.2rem rgba(0, 64, 128, 0.25);
    }

    label {
      font-weight: 600;
    }
  </style>
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
   
       <!-- Sidebar navigation-->
        <?php include 'components/sidebar.php' ?>
        <!-- End Sidebar navigation -->
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
              <h5 class="card-title fw-semibold mb-4">Add Users</h5>
            </div>
            <div class="card">
              <div class="card-body">
                <form method="post">
                  <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

                  <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
                  </div>

                  <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($username) ?>" required>
                  </div>

                  <div class="mb-3">
                    <label for="password" class="form-label">New Password (Leave blank to keep current)</label>
                    <input type="password" class="form-control" id="password" name="password">
                  </div>

                  <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-control" id="role" name="role" required>
                      <option value="">--Select Role--</option>
                      <option value="Tester" <?= $role === "Tester" ? "selected" : "" ?>>Tester</option>
                      <option value="Manufacturer" <?= $role === "Manufacturer" ? "selected" : "" ?>>Manufacturer</option>
                      <option value="Admin" <?= $role === "Admin" ? "selected" : "" ?>>Admin</option>
                    </select>
                  </div>

                  <input type="submit" class="btn btn-primary" value="Update User" name="update_user">
                </form>

                <?php
                if (isset($_POST['update_user'])) {
                  $id = $_POST['id'];
                  $name = $_POST['name'];
                  $username = $_POST['username'];
                  $role = $_POST['role'];
                  $password = $_POST['password'];

                  if (!empty($password)) {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE tbl_user SET name = ?, username = ?, password = ?, role = ? WHERE id = ?");
                    $stmt->bind_param("ssssi", $name, $username, $hashedPassword, $role, $id);
                  } else {
                    $stmt = $conn->prepare("UPDATE tbl_user SET name = ?, username = ?, role = ? WHERE id = ?");
                    $stmt->bind_param("sssi", $name, $username, $role, $id);
                  }

                  if ($stmt->execute()) {
                    echo "<script>alert('User updated successfully'); window.location.href = 'users.php';</script>";
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