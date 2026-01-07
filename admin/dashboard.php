<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$page_title = "Dashboard";
require_once '../includes/header.php';
?>

<!-- html -->
<h1>Dashboard</h1>
<p>You are logged in.</p>

<p><a href="posts.php">Manage Posts</a></p>

<a href="logout.php">Logout</a>

<?php 
require_once '../includes/footer.php';
?>