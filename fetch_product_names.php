<?php
include './components/connnection.php';

if (isset($_POST['product_type'])) {
  $product_type = $_POST['product_type'];

  $stmt = $conn->prepare("SELECT product_name FROM product_name_type WHERE product_type = ?");
  $stmt->bind_param("s", $product_type);
  $stmt->execute();
  $result = $stmt->get_result();

  echo "<option value=''>-- Select Product Name --</option>";
  while ($row = $result->fetch_assoc()) {
    echo "<option value='" . htmlspecialchars($row['product_name']) . "'>" . htmlspecialchars($row['product_name']) . "</option>";
  }

  $stmt->close();
}
?>
