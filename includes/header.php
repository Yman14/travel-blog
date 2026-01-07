<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
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
    ?>
    <title><?= htmlspecialchars($title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($desc, ENT_QUOTES); ?>">
    <link rel="stylesheet" href="/travel-blog/assets/css/style.css">
</head>
<body>

<header>
    <nav>
        <a href="/travel-blog/public/index.php">Home</a>
        <a href="/travel-blog/public/blog.php">Blog</a>
        <a href="#">About</a>
        <a href="#">Contact</a>
    </nav>
</header>

<main>
