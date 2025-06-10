<?php
ob_start();
session_start();
include './components/connnection.php';
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add Category - SeoDash Admin</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/seodashlogo.png" />
  <link rel="stylesheet" href="../../node_modules/simplebar/dist/simplebar.min.css" />
  <link rel="stylesheet" href="assets/css/styles.min.css" />
  <style>
    /* Dark mode adjustments to match sidebar */
    body,
    .body-wrapper {
      background: #121f40;
      color: #e0e6f1;
      min-height: 100vh;
    }

    .card {
      background: #1e3c72; /* dark blue card background */
      border: none;
      box-shadow: 0 4px 12px rgb(14 42 84 / 0.7);
      border-radius: 10px;
      margin-bottom: 1.5rem;
    }

    .card-title {
      color: #f0f6ff;
      font-weight: 700;
      font-size: 1.5rem;
    }

    label.form-label {
      color: #cfd8f7;
      font-weight: 600;
      font-size: 1rem;
    }

    input.form-control {
      background: #274b8f;
      color: #e0e6f1;
      border: 1px solid #3b5fa7;
      border-radius: 6px;
      font-size: 1rem;
      transition: border-color 0.3s ease;
    }

    input.form-control:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 6px #0d6efd;
      color: white;
      background: #1e3c72;
    }

    .btn-primary {
      background: #0d6efd;
      border: none;
      font-weight: 600;
      padding: 8px 22px;
      font-size: 1rem;
      border-radius: 6px;
      transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
      background: #084bcc;
    }

    .table {
      background: #274b8f;
      color: #e0e6f1;
      border-radius: 10px;
      overflow: hidden;
      font-size: 1rem;
    }

    .table thead {
      background: #0d6efd;
      color: #fff;
      font-weight: 700;
      font-size: 1.1rem;
    }

    .table thead th {
      border: none;
      padding: 12px 15px;
    }

    .table tbody tr {
      border-bottom: 1px solid #3b5fa7;
      transition: background-color 0.3s ease;
    }

    .table tbody tr:nth-child(even) {
      background: #1a3173;
    }

    .table tbody tr:hover {
      background: #0d6efd;
      color: white;
      cursor: pointer;
    }

    .table tbody td {
      padding: 12px 15px;
      vertical-align: middle;
      color: #e0e6f1;
    }

    .btn-warning {
      background: #ffc107;
      color: #1e293b;
      font-weight: 600;
      padding: 5px 12px;
      border-radius: 6px;
      transition: background-color 0.3s ease;
    }

    .btn-warning:hover {
      background: #e0a800;
      color: #fff;
    }

    .btn-danger {
      background: #dc3545;
      color: white;
      font-weight: 600;
      padding: 5px 12px;
      border-radius: 6px;
      transition: background-color 0.3s ease;
    }

    .btn-danger:hover {
      background: #b02a37;
    }

    /* Responsive spacing for container */
    .container-fluid {
      padding: 30px 40px;
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

      <?php
      // Start session and database connection

      // Handle Insert or Update
      if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
        $category_name = trim($_POST["category_name"]);

        if (!empty($category_name)) {
          if (isset($_POST['edit_id'])) {
            // Update
            $edit_id = $_POST['edit_id'];
            $stmt = $conn->prepare("UPDATE tbl_category SET category_name = ? WHERE id = ?");
            $stmt->bind_param("si", $category_name, $edit_id);
          } else {
            // Insert
            $stmt = $conn->prepare("INSERT INTO tbl_category (category_name) VALUES (?)");
            $stmt->bind_param("s", $category_name);
          }

          if ($stmt->execute()) {
            header("Location: add_products.php");
            exit;
          } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
          }
          $stmt->close();
        }
      }

      // Handle Delete
      if (isset($_GET['delete'])) {
        $delete_id = $_GET['delete'];
        $stmt = $conn->prepare("DELETE FROM tbl_category WHERE id = ?");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();
        header("Location: add_products.php");
        exit;
      }

      // Fetch data for editing
      $edit_mode = false;
      $edit_category = '';
      $edit_id = 0;

      if (isset($_GET['edit'])) {
        $edit_id = $_GET['edit'];
        $stmt = $conn->prepare("SELECT category_name FROM tbl_category WHERE id = ?");
        $stmt->bind_param("i", $edit_id);
        $stmt->execute();
        $stmt->bind_result($edit_category);
        if ($stmt->fetch()) {
          $edit_mode = true;
        }
        $stmt->close();
      }
      ob_end_flush();
      ?>

      <!-- Main content container -->
      <div class="container-fluid">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="card-title fw-semibold mb-4">
                <?php echo $edit_mode ? "Edit Category" : "Add Category"; ?>
              </h5>
            </div>
            <div class="card mb-4">
              <div class="card-body">
                <form method="post" autocomplete="off">
                  <div class="mb-3">
                    <label for="category_name" class="form-label">Category Name</label>
                    <input type="text" class="form-control" id="category_name" name="category_name"
                      value="<?php echo htmlspecialchars($edit_category); ?>" required />
                    <?php if ($edit_mode): ?>
                      <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                    <?php endif; ?>
                  </div>
                  <input type="submit" class="btn btn-primary" value="<?php echo $edit_mode ? 'Update' : 'Submit'; ?>"
                    name="submit" />
                </form>
              </div>
            </div>

            <!-- Existing Categories Table -->
            <h5 class="card-title fw-semibold my-4">Existing Categories</h5>
            <div class="table-responsive">
              <table class="table table-bordered mb-5">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th style="width: 150px;">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $result = $conn->query("SELECT * FROM tbl_category ORDER BY id DESC");
                  if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      echo "<tr>";
                      echo "<td>{$row['id']}</td>";
                      echo "<td>" . htmlspecialchars($row['category_name']) . "</td>";
                      echo "<td>
                          <a href='?edit={$row['id']}' class='btn btn-sm btn-warning me-2'>Edit</a>
                          <a href='?delete={$row['id']}' onclick='return confirm(\"Are you sure you want to delete this category?\")' class='btn btn-sm btn-danger'>Delete</a>
                        </td>";
                      echo "</tr>";
                    }
                  } else {
                    echo "<tr><td colspan='3' class='text-center'>No categories found.</td></tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- JS Files -->
    <script src="assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="assets/js/sidebarmenu.js"></script>
    <script src="assets/js/app.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>
