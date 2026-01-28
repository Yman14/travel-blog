<?php
session_start();

if (!isset($_SESSION['admin_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location:' . BASE_URL . 'admin/login');
    exit;
}


//title formattnig logic
if (empty($page_title)) {
    $page_title = "Admin | Travel Blog";
} else {
    $page_title = "Admin | " . $page_title;
}