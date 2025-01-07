<?php

function enforceSessionSecurity() {
    session_start();
    session_regenerate_id(true);

    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
        session_unset(); 
        session_destroy(); 
        header('Location: login.php');
        exit;
    }
    $_SESSION['last_activity'] = time();
}

function checkAccess($requiredRole) {
    enforceSessionSecurity();

    // if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== $requiredRole) {
    //     header('Location: login.php');
    //     exit;
    // }
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    if ($_SESSION['role'] !== $requiredRole) {
        logUnauthorizedAccess($_SESSION['user_id'], $requiredRole);
        header('Location: unauthorize.php');
        exit;
    }
}

function checkMultipleAccess($allowedRoles) {
    enforceSessionSecurity();

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    if (!in_array($_SESSION['role'], $allowedRoles)) {
        logUnauthorizedAccess($_SESSION['user_id'], implode(', ', $allowedRoles));
        header('Location: unauthorize.php');
        exit;
    }
}

function logUnauthorizedAccess($userId, $requiredRole) {
    $logMessage = "[" . date('Y-m-d H:i:s') . "] User ID: $userId attempted to access a page requiring role: $requiredRole\n";
    file_put_contents('unauthorized_access.log', $logMessage, FILE_APPEND);
}
?>
