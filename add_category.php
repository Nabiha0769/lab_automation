<?php
ob_start();
session_start();
include './components/connnection.php'
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SeoDash Free Bootstrap Admin Template by Adminmart</title>
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
      
      <?php
      // Start session and database connection


      // Handle Insert
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
      <!-- HTML starts -->
      <div class="container-fluid">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="card-title fw-semibold mb-4">
                <?php echo $edit_mode ? "Edit Category" : "Add Category"; ?>
              </h5>
            </div>
            <div class="card">
              <div class="card-body">
                <form method="post">
                  <div class="mb-3">
                    <label for="category_name" class="form-label">Category Name</label>
                    <input type="text" class="form-control" id="category_name" name="category_name"
                      value="<?php echo htmlspecialchars($edit_category); ?>">
                    <?php if ($edit_mode): ?>
                      <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                    <?php endif; ?>
                  </div>
                  <input type="submit" class="btn btn-primary" value="<?php echo $edit_mode ? 'Update' : 'Submit'; ?>" name="submit">
                </form>
                
              </div>
            </div>

            <!-- Display existing categories -->
            <h5 class="card-title fw-semibold my-4">Existing Categories</h5>
            <table class="table table-bordered">
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
                          <a href='?edit={$row['id']}' class='btn btn-sm btn-warning'>Edit</a>
                          <a href='?delete={$row['id']}' onclick='return confirm(\"Are you sure?\")' class='btn btn-sm btn-danger'>Delete</a>
                        </td>";
                    echo "</tr>";
                  }
                } else {
                  echo "<tr><td colspan='3'>No categories found.</td></tr>";
                }
                ?>
              </tbody>
            </table>
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