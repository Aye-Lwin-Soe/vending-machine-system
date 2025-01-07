<?php
session_start();
if ($_SESSION['user_role'] != 'admin') {
    header("Location: unauthorize.php"); 
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: products_list.php");
    exit();
}

$product_id = $_GET['id'];

require_once 'ProductsController.php';
$db = new ProductsController();
$db->deleteProduct($product_id);

?>