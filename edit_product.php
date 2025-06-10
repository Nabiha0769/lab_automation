<?php
include './components/connnection.php';
if (!isset($_SESSION['id'])) {
  header("location: index.php");
  exit;
}

// Get product ID from GET parameter
$product_id = $_GET['id'] ?? null;

if (!$product_id) {
  echo "<script>alert('No product selected.'); window.location.href = 'products.php';</script>";
  exit;
}

// Fetch product details
$stmt = $conn->prepare("SELECT product_name, product_type, revision, serial_no, manufacture_date FROM products WHERE product_id = ?");
$stmt->bind_param("s", $product_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
  echo "<script>alert('Product not found.'); window.location.href = 'products.php';</script>";
  exit;
}

$stmt->bind_result($product_name, $product_type, $revision, $serial_no, $manufacture_date);
$stmt->fetch();
$stmt->close();
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Product</title>
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
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
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
      <div class="container-fluid">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-center align-items-center text-uppercase">
              <h5 class="card-title fw-semibold mb-4">Edit Product</h5>
            </div>
            <div class="card">
              <div class="card-body">
                <form method="post">
                  <div class="mb-3">
                    <label for="product_name" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="product_name" name="product_name" value="<?= htmlspecialchars($product_name) ?>" required>
                  </div>

                  <div class="mb-3">
                    <label for="product_type" class="form-label">Product Type</label>
                    <input type="text" class="form-control" id="product_type" name="product_type" value="<?= htmlspecialchars($product_type) ?>" required>
                  </div>

                  <div class="mb-3">
                    <label for="revision" class="form-label">Revision</label>
                    <input type="text" class="form-control" id="revision" name="revision" maxlength="3" value="<?= htmlspecialchars($revision) ?>" required>
                  </div>

                  <div class="mb-3">
                    <label for="manufacture_date" class="form-label">Manufacture Date</label>
                    <input type="date" class="form-control" id="manufacture_date" name="manufacture_date" value="<?= htmlspecialchars($manufacture_date) ?>" required>
                  </div>

                  <input type="submit" class="btn btn-primary" value="Update Product" name="update_product">
                </form>

                <?php
                if (isset($_POST['update_product'])) {
                  $new_name = $_POST['product_name'];
                  $new_type = $_POST['product_type'];
                  $new_revision = $_POST['revision'];
                  $new_manufacture_date = $_POST['manufacture_date'];

                  $stmt = $conn->prepare("UPDATE products SET product_name = ?, product_type = ?, revision = ?, manufacture_date = ? WHERE product_id = ?");
                  $stmt->bind_param("sssss", $new_name, $new_type, $new_revision, $new_manufacture_date, $product_id);

                  if ($stmt->execute()) {
                    echo "<script>alert('Product updated successfully'); window.location.href = 'products.php';</script>";
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="assets/js/sidebarmenu.js"></script>
  <script src="assets/js/app.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>