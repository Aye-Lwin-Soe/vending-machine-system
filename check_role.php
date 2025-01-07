<?php 
session_start();

function checkRole($requiredRole) {
    if (!isset($_SESSION['user_role'])) {
        header('Location: login.php');
        exit();
    }
    
    if ($_SESSION['user_role'] !== $requiredRole) {
        header('Location: /unauthorize');
        exit();
    }
}

?>