<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | Travel Blog</title>
    <link rel="stylesheet" href="travel-blog/assets/css/admin.css">
</head>
<body>

<header class="admin-header">
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="posts.php">Posts</a>
        <a href="categories.php">Categories</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main class="admin-content">
