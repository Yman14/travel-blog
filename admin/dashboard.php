<?php
$page_title = "Dashboard";
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/admin-header.php';
?>

<!-- html -->
<h1>Dashboard</h1>
<p>You are logged in.</p>

<p><a href="posts.php">Manage Posts</a></p>

<a href="logout.php">Logout</a>

<?php 
require_once 'includes/admin-footer.php';
?>