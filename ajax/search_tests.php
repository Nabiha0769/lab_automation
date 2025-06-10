<?php
include '../components/connnection.php';

if (isset($_POST['from']) && isset($_POST['to'])) {
    $fromDate = $_POST['from'];
    $toDate = $_POST['to'];

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM tests WHERE test_date BETWEEN ? AND ?");
    $stmt->bind_param("ss", $fromDate, $toDate);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are results and output them
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['test_id']}</td>
                    <td>{$row['product_id']}</td>
                    <td>{$row['test_type_id']}</td>
                    <td>{$row['tester_id']}</td>
                    <td>{$row['test_date']}</td>
                    <td>{$row['result']}</td>
                    <td>{$row['remarks']}</td>
                    <td><span class='badge bg-info'>{$row['status']}</span></td>
                    <td>
                        <a class='text-warning m-1' href='edit_tests.php?id={$row['test_id']}'><i class='fa-solid fa-pen-to-square'></i></a>
                        <a class='text-danger m-1' href='delete_tests.php?id={$row['test_id']}' onclick=\"return confirm('Are you sure you want to delete this test?')\"><i class='fa-solid fa-trash'></i></a>
                    </td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='9'>No results found</td></tr>";
    }

    $stmt->close();
}
?>
<?php


$testId = $_POST['testId'] ?? '';
$productId = $_POST['productId'] ?? '';
$testerName = $_POST['testerName'] ?? '';
$fromDate = $_POST['fromDate'] ?? '';
$toDate = $_POST['toDate'] ?? '';

$query = "SELECT * FROM tests WHERE 1=1";

if (!empty($testId)) {
    $query .= " AND test_id LIKE '%" . mysqli_real_escape_string($conn, $testId) . "%'";
}
if (!empty($productId)) {
    $query .= " AND product_id LIKE '%" . mysqli_real_escape_string($conn, $productId) . "%'";
}
if (!empty($testerName)) {
    $query .= " AND tester_name LIKE '%" . mysqli_real_escape_string($conn, $testerName) . "%'";
}
if (!empty($fromDate) && !empty($toDate)) {
    $query .= " AND test_date BETWEEN '" . mysqli_real_escape_string($conn, $fromDate) . "' AND '" . mysqli_real_escape_string($conn, $toDate) . "'";
}

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    echo '<table class="table table-bordered">
            <thead>
                <tr>
                    <th>Test ID</th>
                    <th>Product ID</th>
                    <th>Test Type</th>
                    <th>Tester Name</th>
                    <th>Test Date</th>
                    <th>Result</th>
                    <th>Remarks</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>
                <td>'.$row['test_id'].'</td>
                <td>'.$row['product_id'].'</td>
                <td>'.$row['test_type'].'</td>
                <td>'.$row['tester_name'].'</td>
                <td>'.$row['test_date'].'</td>
                <td>'.$row['result'].'</td>
                <td>'.$row['remarks'].'</td>
                <td>'.$row['status'].'</td>
              </tr>';
    }
    echo '</tbody></table>';
} else {
    echo '<p>No records found</p>';
}
?>
