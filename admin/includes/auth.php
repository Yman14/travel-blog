<?php
session_start();

if (!isset($_SESSION['admin_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    $_SESSION['flash_error'] = 'Sign in to access Admin.';
    header('Location:' . BASE_URL . 'admin/login');
    exit;
}


//title formattnig logic
if (empty($page_title)) {
    $page_title = "Admin | Travel Blog";
} else {
    $page_title = "Admin | " . $page_title;
}