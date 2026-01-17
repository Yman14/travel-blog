<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="/travel-blog/assets/css/admin.css">
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
