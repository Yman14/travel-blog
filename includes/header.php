<?php 
require_once __DIR__ . '/db.php';
define('BASE_URL', '/travel-blog');

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
    <title><?= htmlspecialchars($title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($desc, ENT_QUOTES); ?>">
    <!-- <link rel="stylesheet" href="/travel-blog/assets/css/style.css"> -->
     <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
</head>
<body>

<header>
    <nav>
        <a href="/travel-blog/public/index.php">Home</a>
        
        <div class="dropdown">
            <span>Categories ▾</span>
            <div class="dropdown-content">
                <?php foreach ($navCategories as $cat): ?>
                    <a href="/public/category.php?id=<?php echo $cat['id']; ?>">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <a href="/travel-blog/public/about.php">About</a>
        <a href="/travel-blog/public/contact.php">Contact</a>
        <a href="/travel-blog/public/privacy-policy.php">Privacy Policy</a>
    </nav>
</header>

<main>
