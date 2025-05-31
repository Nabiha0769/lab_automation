<?php
include './components/connnection.php'; 
if (isset($_SESSION['username'])) {
  header("location:dashboard.php");
  exit();
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | Lab Automation</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/seodashlogo.png" />
  <link rel="stylesheet" href="assets/css/styles.min.css" />
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <div class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="assets/images/logos/logo-light.svg" alt="">
                </div>
                <form method="post">
                  <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username">
                  </div>
                  <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                  </div>
                  <input type="submit" value="Sign In" class="btn btn-primary w-100 py-8 fs-4 mb-4" name="login">
                </form>
                <?php
                  // Handle login
                  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
                    $username = trim($_POST["username"]);
                    $password = $_POST["password"];
 
                    $stmt = $conn->prepare("SELECT id, username, password, role FROM tbl_user WHERE username = ?");
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $stmt->store_result();

                    // Check if user exists
                    if ($stmt->num_rows == 1) {
                        $stmt->bind_result($id, $uname, $hashed_password, $role);
                        $stmt->fetch();

                        // Verify password
                        if (password_verify($password, $hashed_password)) {
                            // Set session variables
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $uname;
                            $_SESSION["role"] = $role;

                            // Redirect to dashboard
                            header("Location: dashboard.php");
                            exit;
                        } else {
                            echo "<div class='alert alert-danger'>Invalid password.</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Username not found.</div>";
                    }

                    $stmt->close();
                    $conn->close();
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
  <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>