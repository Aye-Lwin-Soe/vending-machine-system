<?php
session_start();

// if (!isset($_SESSION['user_id'])) {
//     header('Location: login.php');
//     exit();
// }
require_once 'check_role.php';

// if ($_SESSION['user_role'] !== 'admin') {
//     echo "Access denied. Admins only.";
//     exit();
// }
checkRole('admin');


echo "Welcome, Admin " . $_SESSION['user_name'] . "!<br>";
echo '<a href="logout.php">Logout</a>';

?>