<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../includes/header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>

<h1>Dashboard</h1>
<p>You are logged in.</p>

<p><a href="posts.php">Manage Posts</a></p>

<a href="logout.php">Logout</a>

</body>
</html>

<?php 
require_once '../includes/footer.php';
