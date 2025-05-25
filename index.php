<?php
session_start();
include './components/connnection.php';
if(isset($_SESSION['username'])){
  header("location:dashboard.php");
}


?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SeoDash Free Bootstrap Admin Template by Adminmart</title>
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
                <a href="./dashboard.php" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="assets/images/logos/logo-light.svg" alt="">
                </a>
                <form method="post">
                  <div class="mb-3">
                    <label for="exampleInputUsername" class="form-label">Username</label>
                    <input type="text" class="form-control" id="exampleInputUsername" name="username">
                  </div>
                  <div class="mb-4">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" name="password">
                  </div>
                  <input type="submit" value="Sign In" class="btn btn-primary w-100 py-8 fs-4 mb-4" name="submit">
                </form>
                <?php
                  if(isset($_POST['submit'])){
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    $query = "select * from tbl_user where username = '$username'";
                    $result = mysqli_query($conn,$query);
                    if(mysqli_num_rows($result)>0){
                      
                      $row = $result->fetch_assoc();
                      if(password_verify($password, $row['password'])){
                        $_SESSION['role'] = $row['role'];
                        $_SESSION['u_id'] = $row['id'];
                        $_SESSION['username'] = $row['username'];
                       echo "
                        <script>
                          alert('Login Successful')
                          window.location.href = 'dashboard.php'
                        </script>
                        ";
                      }
                      else{
                        echo "
                        <script>
                          alert('Invalid Password')
                        </script>
                        ";
                      }
                    }
                    else{
                      echo "
                        <script>
                          alert('Incorrect Username and Password')
                        </script>
                        ";
                    }
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