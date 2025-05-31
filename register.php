<?php
include './components/connnection.php'; 

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register | Address Book</title>
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
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name">
                  </div>
                  <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username">
                  </div>
                  <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                  </div>
                  <input type="submit" value="Register" class="btn btn-primary w-100 py-8 fs-4 mb-4">
                  <div class="d-flex align-items-center justify-content-center">
                    <p class="fs-4 mb-0 fw-bold">Already have an Account?</p>
                    <a class="text-primary fw-bold ms-2" href="./index.php">Sign In</a>
                  </div>
                </form>
                <?php
                  if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Sanitize and fetch inputs
                    $name = trim($_POST["name"]);
                    $username = trim($_POST["username"]);
                    $password = $_POST["password"];
                
                    // Check if username already exists
                    $checkStmt = $conn->prepare("SELECT id FROM tbl_user WHERE username = ?");
                    $checkStmt->bind_param("s", $username);
                    $checkStmt->execute();
                    $checkStmt->store_result();
                
                    if ($checkStmt->num_rows > 0) {
                        echo "<div class='alert alert-danger'>Username already taken. Please choose another.</div>";
                    } else {
                        // Username is available; proceed to register
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                        $insertStmt = $conn->prepare("INSERT INTO tbl_user (name, username, password) VALUES (?, ?, ?)");
                        $insertStmt->bind_param("sss", $name, $username, $hashedPassword);
                
                        if ($insertStmt->execute()) {
                            echo "<div class='alert alert-success'>Registration successful. <a href='index.php'>Login here</a>.</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Error: " . $insertStmt->error . "</div>";
                        }
                
                        $insertStmt->close();
                    }
                
                    $checkStmt->close();
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