<?php
include '../components/connnection.php'; // Ensure the connection file is correctly named

$from = $_POST['from'] ?? '';
$to = $_POST['to'] ?? '';

if (!empty($from) && !empty($to)) {
   $query = "
    SELECT
tests.test_id,
tests.product_id,
tests.test_date,
tests.result,
tests.remarks,
tests.status,
testers.name AS tester_name, 
testtypes.name AS test_type_name
FROM
tests
JOIN
testers ON tests.tester_id = testers.tester_id 
JOIN
testtypes ON tests.test_type_id = testtypes.test_type_id 
where tests.test_date between ? AND ?";

  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $from, $to);
} else {
   $query = "
    SELECT
tests.test_id,
tests.product_id,
tests.test_date,
tests.result,
tests.remarks,
tests.status,
testers.name AS tester_name, 
testtypes.name AS test_type_name
FROM
tests
JOIN
testers ON tests.tester_id = testers.tester_id 
JOIN
testtypes ON tests.test_type_id = testtypes.test_type_id ";

  $stmt = $conn->prepare($query);
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
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
}
?>
