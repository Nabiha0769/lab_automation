<?php
session_start();
include './components/connnection.php';

if (!isset($_SESSION['username'])) {
  header("location:index.php");
  exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header("location:user.php");
  exit();
}

$user_id = (int) $_GET['id'];

// Optional: Delete from related tables manually if no ON DELETE CASCADE set on foreign keys

// Delete from tbl_user
$stmt = $conn->prepare("DELETE FROM tbl_user WHERE id = ?");
if ($stmt) {
  $stmt->bind_param("i", $user_id);
  if ($stmt->execute()) {
    $stmt->close();
    echo "<script>
            alert('User deleted successfully');
            window.location.href = 'user.php';
          </script>";
    exit();
  } else {
    $error = "Error deleting user: " . htmlspecialchars($stmt->error);
    $stmt->close();
  }
} else {
  $error = "Error preparing statement: " . htmlspecialchars($conn->error);
}

if (isset($error)) {
  echo "<script>
          alert('$error');
          window.location.href = 'user.php';
        </script>";
  exit();
}
