<?php
include './components/connnection.php';
if (!isset($_SESSION['id'])) {
  header("location: index.php");
}
?>
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Product Type & Name</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/seodashlogo.png" />
  <link rel="stylesheet" href="../../node_modules/simplebar/dist/simplebar.min.css">
  <link rel="stylesheet" href="assets/css/styles.min.css" />
   <style>
    .form-wrapper {
      max-width: 600px;
      margin: 50px auto;
      background: #ffffff;
      border: 2px solid #e0e0e0;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      border-radius: 12px;
      padding: 30px;
    }
    .form-wrapper h5 {
      font-weight: 600;
      color: #1e3c72;
    }
    .btn-primary {
      background-color: #1e3c72;
      border: none;
    }
    .btn-primary:hover {
      background-color: #16325c;
    }
  </style>
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"
    data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    
    <!-- Sidebar -->
    <?php include 'components/sidebar.php'; ?>

    <!-- Body -->
    <div class="body-wrapper">
      <?php include 'components/header.php'; ?>
      <div class="container-fluid">

        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-center align-items-center text-uppercase">
              <h5 class="card-title fw-semibold mb-4">Add Product Type & Name</h5>
            </div>

           <?php
            if (isset($_POST['submit'])) {
              $type = trim($_POST['product_type']);
              $name = trim($_POST['product_name']);

              // Check if already exists
              $check = $conn->prepare("SELECT id FROM product_name_type WHERE product_type = ? AND product_name = ?");
              $check->bind_param("ss", $type, $name);
              $check->execute();
              $check->store_result();

              if ($check->num_rows > 0) {
                echo "<div class='alert alert-warning mt-3'>This product type & name already exists.</div>";
              } else {
                $insert = $conn->prepare("INSERT INTO product_name_type (product_type, product_name) VALUES (?, ?)");
                $insert->bind_param("ss", $type, $name);
                if ($insert->execute()) {
                  echo "<script>alert('Product Type & Name added successfully!'); window.location.href = 'add_product.php';</script>";
                } else {
                  echo "<div class='alert alert-danger mt-3'>Error: " . htmlspecialchars($insert->error) . "</div>";
                }
              }
            }
            ?>

            <form method="post">
              <div class="mb-3">
                <label for="product_type" class="form-label">Product Type</label>
                <input type="text" class="form-control" id="product_type" name="product_type" placeholder="e.g., CAP, FUSE, SWG" required>
              </div>

              <div class="mb-3">
                <label for="product_name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="product_name" name="product_name" placeholder="e.g., Capacitor 50V" required>
              </div>

               <button type="submit" name="submit" class="btn btn-primary">Add Entry</button>
              <a href="add_product.php" class="btn btn-secondary ms-2">Back to Add Product</a>
            </form>
          </div>
        </div>

      
      </div>
    </div>
  </div>

  <script src="assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="assets/js/sidebarmenu.js"></script>
  <script src="assets/js/app.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>

