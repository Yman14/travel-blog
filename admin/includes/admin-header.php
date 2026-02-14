<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
    <link rel="stylesheet" href="<?=BASE_URL?>assets/css/admin.css">
</head>
<body>

<header class="admin-header container">
    <div class="header-content">
        <div class="logo">x</div>
        <nav>
            <a href="<?=BASE_URL?>admin/dashboard">Dashboard</a>
            <a href="<?=BASE_URL?>admin/posts">Posts</a>
            <a href="<?=BASE_URL?>admin/categories">Categories</a>
        </nav>
        <div class="log"><a href="<?=BASE_URL?>admin/logout">Logout</a></div>
    </div>
</header>

<main class="admin-content container">
