<?php 
// Use the post title if it exists, otherwise use a default
if (isset($post['title'])) {
    $title = $post['title'];
} elseif (isset($page_title)) {
    // Otherwise, use the custom variable set in the file
    $title = $page_title;
} else {
    // Default title if nothing else is found
    $title = "Travel Blog";
}
        
// Use the post content for description if it exists, otherwise use a default
if (isset($post['content'])) {
    $desc = mb_strimwidth(strip_tags($post['content']), 0, 155, "...");
} else {
    $desc = "Welcome to my travel blog where I share my latest adventures.";
}

$sql = "SELECT id, name FROM categories ORDER BY name ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$navCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title><?= htmlspecialchars($title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($desc, ENT_QUOTES); ?>">
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>assets/images/favicon.ico">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
</head>
<body>

<header class="site-header container">
    <div class="site-brand">
        <h1><a href="<?= BASE_URL ?>index.php">Travel Blog</a></h1>
    </div>
    <nav class="site-nav">   
        <div class="dropdown">
            <span>Categories ▾</span>
            <div class="dropdown-content">
                <?php foreach ($navCategories as $cat): ?>
                    <a href="<?= BASE_URL ?>category.php?id=<?php echo $cat['id']; ?>">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    
        <a href="<?= BASE_URL ?>about.php">About</a>
        <a href="<?= BASE_URL ?>contact.php">Contact</a>
    </nav>
</header>

<main class="container main-layout">
