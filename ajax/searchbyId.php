<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../components/connnection.php';

$Id = $_POST["prdtId"];

if (empty($Id)) {
  $query = "
    SELECT p.product_id, p.product_name, p.product_type, p.revision, p.serial_no, p.manufacture_date,
      CASE 
        WHEN EXISTS (SELECT 1 FROM reworklog r WHERE r.product_id = p.product_id) THEN 'Rework'
        WHEN EXISTS (
          SELECT 1 FROM tests t 
          JOIN testflow tf ON tf.product_id = p.product_id AND tf.test_type_id = t.test_type_id 
          WHERE t.product_id = p.product_id AND t.status = 'Failed'
        ) THEN 'Failed'
        WHEN (
          SELECT COUNT(DISTINCT tf.test_type_id) FROM testflow tf WHERE tf.product_id = p.product_id
        ) = (
          SELECT COUNT(DISTINCT t.test_type_id) FROM tests t WHERE t.product_id = p.product_id AND t.status = 'Completed'
        ) THEN 'Ready for CPRI'
        ELSE 'Under Test'
      END AS status 
    FROM products p 
    ORDER BY p.product_id DESC";
    
  $stmt = $conn->prepare($query);

} else {
  $likeId = "%" . $Id . "%";
  $query = "
    SELECT p.product_id, p.product_name, p.product_type, p.revision, p.serial_no, p.manufacture_date,
      CASE 
        WHEN EXISTS (SELECT 1 FROM reworklog r WHERE r.product_id = p.product_id) THEN 'Rework'
        WHEN EXISTS (
          SELECT 1 FROM tests t 
          JOIN testflow tf ON tf.product_id = p.product_id AND tf.test_type_id = t.test_type_id 
          WHERE t.product_id = p.product_id AND t.status = 'Failed'
        ) THEN 'Failed'
        WHEN (
          SELECT COUNT(DISTINCT tf.test_type_id) FROM testflow tf WHERE tf.product_id = p.product_id
        ) = (
          SELECT COUNT(DISTINCT t.test_type_id) FROM tests t WHERE t.product_id = p.product_id AND t.status = 'Completed'
        ) THEN 'Ready for CPRI'
        ELSE 'Under Test'
      END AS status 
    FROM products p 
    WHERE p.product_id LIKE ? 
    ORDER BY p.product_id DESC";
  
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $likeId);
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['product_id']}</td>
            <td>{$row['product_name']}</td>
            <td>{$row['product_type']}</td>
            <td>{$row['revision']}</td>
            <td>{$row['serial_no']}</td>
            <td>{$row['manufacture_date']}</td>
            <td><span class='badge bg-info'>{$row['status']}</span></td>
            <td>
              <a class='text-warning m-1' href='edit_product.php?id={$row['product_id']}'><i class='fa-solid fa-pen-to-square'></i></a>
              <a class='text-danger m-1' href='delete_product.php?id={$row['product_id']}' onclick=\"return confirm('Are you sure you want to delete this product?')\"><i class='fa-solid fa-trash'></i></a>
            </td>
          </tr>";
}
?>
