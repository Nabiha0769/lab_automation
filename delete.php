<?php
include 'components/connnection.php';
$id = $_GET['id'];
$query = "delete from tbl_products where p_id='$id'";
$result = mysqli_query($conn,$query);
if($result){
    header("location:products.php");
}
?>