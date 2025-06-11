<?php
include './components/connnection.php';
if (!isset($_SESSION['id'])) {
    header("location: index.php");
    exit;
}

// fot Test Type Name
$stmt = $conn->query("select name from testtypes");



//query to join with testers and testtypes tables
$query = "
SELECT
tests.test_id,
tests.product_id,
tests.test_date,
tests.result,
tests.remarks,
tests.status,
testers.name AS tester_name, -- Get the tester's name
testtypes.name AS test_type_name -- Get the test type's name
FROM
tests
JOIN
testers ON tests.tester_id = testers.tester_id -- Join with testers table using tester_id
JOIN
testtypes ON tests.test_type_id = testtypes.test_type_id -- Join with testtypes table using test_type_id
";
$tests = $conn->query($query);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tests</title>
    <link rel="shortcut icon" type="image/png" href="assets/images/logos/seodashlogo.png" />
    <link rel="stylesheet" href="../../node_modules/simplebar/dist/simplebar.min.css">
    <link rel="stylesheet" href="assets/css/styles.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                        <div class="row mb-3">
                            <div class="col-md-3 col-sm-6">
                                <label for="">Search <br>By Id:</label>
                                <input type="text" class="form-control" placeholder="Enter Product Id" id="testId">
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <label for=""><br>By Type:</label>
                                <select type="text" class="form-control" placeholder="Enter Product Name" id="testType">
                                    <option value="">Select Type</option>
                                    <?php while ($row = $stmt->fetch_assoc()) {
                                    echo "<option>$row[name]</option>";
                                } ?>
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-12 row">
                                <label for="">Search by Date:</label>
                                <div class="col-md-6">
                                    <label for="">From</label>
                                    <input type="date" class="form-control" id="fromDate">
                                </div>
                                <div class="col-md-6">
                                    <label for="">To</label>
                                    <input type="date" class="form-control" id="toDate">
                                    <button id="search" value="search" class="btn btn-primary mt-3">Search</button>
                                </div>
                            </div>
                        </div>
                        <h5 class="card-title fw-semibold">Tests</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>TEST ID</th>
                                    <th>PRODUCT ID</th>
                                    <th>TEST TYPE</th> <!-- Changed to TEST TYPE -->
                                    <th>TESTER NAME</th> <!-- Changed to TESTER NAME -->
                                    <th>TEST Date</th>
                                    <th>RESULT</th>
                                    <th>REMARKS</th>
                                    <th>STATUS</th>
                                    <th>ACTIONS</th> <!-- Added for edit/delete actions -->
                                </tr>
                            </thead>
                            <tbody id="testTable">
                                <?php while ($row = $tests->fetch_assoc()) {
                                    echo "<tr>
                                            <td>{$row['test_id']}</td>
                                            <td>{$row['product_id']}</td>
                                            <td>{$row['test_type_name']}</td> <!-- Display test type name -->
                                            <td>{$row['tester_name']}</td> <!-- Display tester name -->
                                            <td>{$row['test_date']}</td>
                                            <td>{$row['result']}</td>
                                            <td>{$row['remarks']}</td>
                                            <td><span class='badge bg-info'>{$row['status']}</span></td>
                                            <td>
                                                <a class='text-warning m-1' href='edit_tests.php?id={$row['test_id']}'><i class='fa-solid fa-pen-to-square'></i></a>
                                                <a class='text-danger m-1' href='delete_tests.php?id={$row['test_id']}' onclick=\"return confirm('Are you sure you want to delete this product?')\"><i class='fa-solid fa-trash'></i></a>
                                            </td>
                                        </tr>";
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="assets/js/sidebarmenu.js"></script>
    <script src="assets/js/app.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>
<script>
    $(document).ready(function() {
        let debounceTimer;

        $("#testId").on("keyup", function() {
            clearTimeout(debounceTimer);
            let testId = $(this).val();

            debounceTimer = setTimeout(function() {
                // ✅ Always call AJAX, whether input is empty or not
                $.ajax({
                    url: "ajax/searchbyId_test.php",
                    type: "POST",
                    data: {
                        tstId: testId
                    }, // Send empty string too
                    success: function(data) {
                        $("#testTable").html(data);
                    },
                    error: function() {
                        $("#testTable").html("<tr><td colspan='8'>Error fetching data</td></tr>");
                    }
                });
            }, 300); // delay in ms
        });
        $("#testType").on("change", function() {
            (debounceTimer);
            let testType = $(this).val();

            debounceTimer = setTimeout(function() {
                // ✅ Always call AJAX, whether input is empty or not
                $.ajax({
                    url: "ajax/searchbytesttype.php", // changed file
                    type: "POST",
                    data: {
                        test_Type: testType
                    }, // changed key
                    success: function(data) {
                        $("#testTable").html(data);
                    },
                    error: function() {
                        $("#testTable").html("<tr><td colspan='8'>Error fetching data</td></tr>");
                    }
                });
            }, 300); // delay in ms
        });

        $("#search").on("click", function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function() {
                let fromDate = $("#fromDate").val();
                let toDate = $("#toDate").val();

                $.ajax({
                    url: "ajax/searchbytestdate.php", // PHP file to handle date filter
                    type: "POST",
                    data: {
                        from: fromDate,
                        to: toDate
                    },
                    success: function(response) {
                        $("#testTable").html(response);
                    },
                    error: function() {
                        $("#testTable").html("<tr><td colspan='8'>Error loading data</td></tr>");
                    }
                });
            }, 300); // ⏱ Debounce delay in milliseconds
        });

    });
</script>

</html>