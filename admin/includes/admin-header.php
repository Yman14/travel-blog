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
        <div class="log"><a href="<?=BASE_URL?>admin/logout" class='logout'>Logout</a></div>
        <div class="more">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <title>More</title>
                <path d="M12,16A2,2 0 0,1 14,18A2,2 0 0,1 12,20A2,2 0 0,1 10,18A2,2 0 0,1 12,16M12,10A2,2 0 0,1 14,12A2,2 0 0,1 12,14A2,2 0 0,1 10,12A2,2 0 0,1 12,10M12,4A2,2 0 0,1 14,6A2,2 0 0,1 12,8A2,2 0 0,1 10,6A2,2 0 0,1 12,4Z" />
        </svg>
        </div>
    </div>
</header>

<main class="admin-content container">
