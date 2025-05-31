<?php
include './components/connnection.php';
if (!isset($_SESSION['id'])) {
  header("location: index.php");
  exit;
}

// Handle adding a new test type
if (isset($_POST['add_test_type'])) {
  $name = trim($_POST['name']);
  $description = trim($_POST['description']);
  if (!empty($name) && !empty($description)) {
    $stmt = $conn->prepare("INSERT INTO testtypes (name,description) VALUES (?,?)");
    $stmt->bind_param("ss", $name,$description);
    $stmt->execute();
    $stmt->close();
  }
  header("Location: test_type.php");
  exit;
}

// Handle editing a test type
if (isset($_POST['edit_test_type'])) {
  $id = $_POST['id'];
  $name = trim($_POST['name']);
  $description = trim($_POST['description']);
  if (!empty($name) && !empty($description)) {
    $stmt = $conn->prepare("UPDATE testtypes SET name = ?, description = ? WHERE test_type_id = ?");
    $stmt->bind_param("ssi", $name, $description, $id);
    $stmt->execute();
    $stmt->close();
  }
  header("Location: test_type.php");
  exit;
}

// Handle delete request
if (isset($_GET['delete_id'])) {
  $id = $_GET['delete_id'];
  $stmt = $conn->prepare("DELETE FROM testtypes WHERE test_type_id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->close();
  header("Location: test_type.php");
  exit;
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Test Types</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/seodashlogo.png" />
  <link rel="stylesheet" href="assets/css/styles.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
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
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="card-title fw-semibold">Test Types</h5>
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">+ Add Test Type</button>
            </div>

            <table class="table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Options</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $result = $conn->query("SELECT test_type_id, name, description FROM testtypes ORDER BY test_type_id DESC");
                while ($row = $result->fetch_assoc()) {
                  echo "<tr>
                    <td>{$row['test_type_id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['description']}</td>
                    <td>
                      <button class='btn btn-sm btn-warning' data-bs-toggle='modal' data-bs-target='#editModal{$row['test_type_id']}'><i class='fa fa-edit'></i></button>
                      <a href='test_types.php?delete_id={$row['test_type_id']}' onclick=\"return confirm('Are you sure you want to delete this test type?')\" class='btn btn-sm btn-danger'><i class='fa fa-trash'></i></a>
                    </td>
                  </tr>

                  <!-- Edit Modal -->
                  <div class='modal fade' id='editModal{$row['test_type_id']}' tabindex='-1' aria-labelledby='editLabel{$row['test_type_id']}' aria-hidden='true'>
                    <div class='modal-dialog'>
                      <div class='modal-content'>
                        <form method='post'>
                          <div class='modal-header'>
                            <h5 class='modal-title' id='editLabel{$row['test_type_id']}'>Edit Test Type</h5>
                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                          </div>
                          <div class='modal-body'>
                            <input type='hidden' name='id' value='{$row['test_type_id']}'>
                            <input type='text' class='form-control mb-3' name='name' value='{$row['name']}' required>
                            <input type='text' class='form-control' name='description' value='{$row['description']}' required>
                          </div>
                          <div class='modal-footer'>
                            <button type='submit' name='edit_test_type' class='btn btn-primary'>Update</button>
                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>";
                }
                ?>
              </tbody>
            </table>

            <!-- Add Modal -->
            <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form method="post">
                    <div class="modal-header">
                      <h5 class="modal-title" id="addModalLabel">Add New Test Type</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <input type="text" class="form-control mb-3" name="name" placeholder="Enter test type name" required>
                      <input type="text" class="form-control" name="description" placeholder="Enter test description" required>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" name="add_test_type" class="btn btn-success">Add</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/app.min.js"></script>
</body>

</html>