<?php
include '../components/connnection.php';

if (isset($_POST['productId'])) {
    $productId = $_POST['productId'];

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM tests WHERE product_id LIKE CONCAT('%', ?, '%')");
    $stmt->bind_param("s", $productId);
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
