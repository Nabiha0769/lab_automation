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

  <style>
    body {
      background: url('assets/images/backgrounds/lab_auto.jpg') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .login-card {
      background: #ffffffcc;
      backdrop-filter: blur(10px);
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
      padding: 40px;
    }

    .login-title {
      font-size: 28px;
      font-weight: bold;
      color: #1e3c72;
      margin-bottom: 30px;
      text-align: center;
    }

    .form-label {
      font-weight: 600;
      color: #1e3c72;
    }

    .form-control {
      border-radius: 10px;
      background: #f9f9f9;
      color: #333;
    }

    .form-control:focus {
      box-shadow: 0 0 0 3px rgba(30, 60, 114, 0.2);
    }

    .btn-primary {
      background-color: #1e3c72;
      border-color: #1e3c72;
      border-radius: 10px;
      font-weight: 600;
    }

    .btn-primary:hover {
      background-color: #16305b;
      border-color: #16305b;
    }

    .logo-img {
      width: 100px;
      margin-bottom: 15px;
    }

    .alert {
      text-align: center;
    }

    @media (max-width: 576px) {
      .login-card {
        padding: 30px 20px;
      }
    }
  </style>
</head>

<body>
  <div class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="col-md-6 col-lg-5 col-xl-4">
      <div class="login-card">
        <div class="text-center">
          <img src="assets/images/logos/logo-light.png" alt="Logo" class="logo-img">
        </div>
        <div class="login-title">Login to Lab Automation</div>
        <form method="post">
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
          </div>
          <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <input type="submit" value="Sign In" class="btn btn-primary w-100 py-2 fs-5 mb-3" name="login">
        </form>

        <?php
          if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
            $username = trim($_POST["username"]);
            $password = $_POST["password"];

            $stmt = $conn->prepare("SELECT id, username, password, role FROM tbl_user WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
              $stmt->bind_result($id, $uname, $hashed_password, $role);
              $stmt->fetch();

              if (password_verify($password, $hashed_password)) {
                $_SESSION["id"] = $id;
                $_SESSION["username"] = $uname;
                $_SESSION["role"] = $role;
                header("Location: dashboard.php");
                exit;
              } else {
                echo "<div class='alert alert-danger mt-3'>Invalid password.</div>";
              }
            } else {
              echo "<div class='alert alert-danger mt-3'>Username not found.</div>";
            }

            $stmt->close();
            $conn->close();
          }
        ?>
      </div>
    </div>
  </div>

  <script src="assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>
</html>
