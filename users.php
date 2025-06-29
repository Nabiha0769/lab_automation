<?php
include './components/connnection.php';

if (!isset($_SESSION['username'])) {
  header("location:index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User List</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/seodashlogo.png" />
  <link rel="stylesheet" href="assets/css/styles.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
  .table-custom {
    border: 3px solid #1e2a40;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
    background-color: #ffffff;
  }

  .table-custom th {
    background: linear-gradient(to right, #1e3c72, #2a5298);
    color: #fff;
    font-weight: 600;
    border-bottom: 2px solid #ccc;
    text-transform: uppercase;
    font-size: 14px;
  }

  .table-custom td, .table-custom th {
    border: 1px solid #ddd;
    padding: 12px;
  }

  .table-custom tbody tr:hover {
    background-color: #f0f6ff;
    transition: background-color 0.3s ease;
  }

  .card {
    border: none;
    border-radius: 14px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  }

  h4.fw-semibold {
    font-size: 22px;
    color: #2a2f45;
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
      <?php include 'components/header.php' ?>
      <!--  Header End -->

      <div class="container-fluid mt-4">
        <div class="card">
          <div class="card-body">
            <h4 class="fw-semibold mb-4">User List</h4>
            <div class="table-responsive">
              <table class="table table-bordered table-hover align-middle text-center table-custom">
             <thead>
                  <tr>
                    <th>U_ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Option</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $stmt = $conn->prepare("SELECT id, name, username, role FROM tbl_user");
                  if ($stmt) {
                    $stmt->execute();
                    $stmt->bind_result($user_id, $name, $username, $department);

                   while ($stmt->fetch()) {
                        echo "
                        <tr>
                        <td class='align-middle'>$user_id</td>
                            <td class='align-middle'>$name</td>
                            <td class='align-middle'>$username</td>
                            <td class='align-middle'>$department</td>
                            <td class='align-middle'>
                            <button class='btn btn-sm btn-warning me-1 edit-btn' data-userid='$user_id' title='Edit'>
                                <i class='fa fa-pencil-alt'></i>
                            </button>
                            <button class='btn btn-sm btn-danger delete-btn' data-userid='$user_id' title='Delete'>
                                <i class='fa fa-trash'></i>
                            </button>
                            </td>
                        </tr>";
                        }


                    $stmt->close();
                  } else {
                    echo "<tr><td colspan='6' class='text-danger'>Error preparing statement: " . htmlspecialchars($conn->error) . "</td></tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
  <!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this user?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button id="deleteConfirmBtn" type="button" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Confirmation Modal -->
<div class="modal fade" id="confirmEditModal" tabindex="-1" aria-labelledby="confirmEditLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmEditLabel">Confirm Edit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to edit this user?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button id="editConfirmBtn" type="button" class="btn btn-primary">Edit</button>
      </div>
    </div>
  </div>
</div>


  <!-- JS Libraries -->
 
  <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/app.min.js"></script>
  <script src="assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="assets/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="assets/js/sidebarmenu.js"></script>
  <script src="assets/js/dashboard.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  <script>
  // Bootstrap 5 modal instances
  var deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
  var editModal = new bootstrap.Modal(document.getElementById('confirmEditModal'));

  let selectedUserId;

  // Delete buttons click
  document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      selectedUserId = this.getAttribute('data-userid');
      deleteModal.show();
    });
  });

  // Edit buttons click
  document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      selectedUserId = this.getAttribute('data-userid');
      editModal.show();
    });
  });

  // Confirm delete button click
  document.getElementById('deleteConfirmBtn').addEventListener('click', function () {
    window.location.href = 'delete_user.php?id=' + selectedUserId;
  });

  // Confirm edit button click
  document.getElementById('editConfirmBtn').addEventListener('click', function () {
    window.location.href = 'edit_user.php?id=' + selectedUserId;
  });
</script>

</body>
</html>
