<?php
include './components/connnection.php';
if(!isset($_SESSION['id'])) {
    header("location: index.php");
    exit;
}

// Add flow entry
if (isset($_POST['add_flow'])) {
    $product_id = $_POST['product_id'];
    $test_type_id = $_POST['test_type_id'];
    $sequence = $_POST['sequence'];

    $stmt = $conn->prepare("INSERT INTO testflow (product_id, test_type_id, sequence) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $product_id, $test_type_id, $sequence);
    $stmt->execute();
    $stmt->close();

    header("Location: test_flow_setup.php");
    exit;
}

// Fetch product list
$product_result = $conn->query("SELECT product_id, product_name FROM products");

// Fetch test type list
$type_result = $conn->query("SELECT test_type_id,name FROM testtypes");

// Fetch existing flows
$flows = $conn->query("SELECT tf.flow_id, tf.product_id, p.product_name, tt.name, tf.sequence
                       FROM testflow tf
                       JOIN products p ON tf.product_id = p.product_id
                       JOIN testtypes tt ON tf.test_type_id = tt.test_type_id
                       ORDER BY tf.product_id, tf.sequence");
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Users</title>
    <link rel="shortcut icon" type="image/png" href="assets/images/logos/seodashlogo.png" />
    <link rel="stylesheet" href="../../node_modules/simplebar/dist/simplebar.min.css">
    <link rel="stylesheet" href="assets/css/styles.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
            <?php
            include 'components/header.php'
            ?>
            <!--  Header End -->
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold">Test Flow Setup</h5>

                        <form method="post" class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Select Product</label>
                                <select class="form-control" name="product_id" required>
                                    <option value="">-- Select Product --</option>
                                    <?php while ($p = $product_result->fetch_assoc()) {
                                        echo "<option value='{$p['product_id']}'>{$p['product_id']} - {$p['product_name']}</option>";
                                    } ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Test Type</label>
                                <select class="form-control" name="test_type_id" required>
                                    <option value="">-- Select Test Type --</option>
                                    <?php while ($t = $type_result->fetch_assoc()) {
                                        echo "<option value='{$t['test_type_id']}'>{$t['name']}</option>";
                                    } ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Sequence</label>
                                <input type="number" class="form-control" name="sequence" min="1" required>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" name="add_flow" class="btn btn-primary w-100">Add</button>
                            </div>
                        </form>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product ID</th>
                                    <th>Product Name</th>
                                    <th>Test Type</th>
                                    <th>Sequence</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $flows->fetch_assoc()) {
                                    echo "<tr>
                                    <td>{$row['product_id']}</td>
                                    <td>{$row['product_name']}</td>
                                    <td>{$row['name']}</td>
                                    <td>{$row['sequence']}</td>
                                </tr>";
                                } ?>
                            </tbody>
                        </table>
                    </div>
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