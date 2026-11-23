<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireLogin($role = null) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../login.php');
        exit;
    }

    if ($role !== null && $_SESSION['role'] !== $role) {
        header('Location: ../login.php');
        exit;
    }
}
?>