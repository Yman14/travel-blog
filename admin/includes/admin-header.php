<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="<?=BASE_URL?>assets/css/admin.css">
</head>
<body>

<header class="admin-header">
    <nav>
        <a href="<?=BASE_URL?>admin/dashboard">Dashboard</a>
        <a href="<?=BASE_URL?>admin/posts">Posts</a>
        <a href="<?=BASE_URL?>admin/categories">Categories</a>
        <a href="<?=BASE_URL?>admin/logout">Logout</a>
    </nav>
</header>

<main class="admin-content">
