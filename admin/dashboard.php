<?php
$page_title = "Dashboard";
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/admin-header.php';

$postCount = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
$categoryCount = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
?>

<!-- html -->
 <section class="admin-section">
    <header class="admin-section-header">
        <h1>Dashboard</h1>
        <p>Manange account, posts, and categories. Display site statistics.</p>
    </header>

    <div class="admin-section-body">
        <ul class="count-section">
            <li>Total Posts: <?php echo $postCount; ?></li>
            <li>Total Categories: <?php echo $categoryCount; ?></li>
        </ul>
        <div class="feature-section">
            <a href="create-post.php">Create New Post</a> |
            <a href="categories.php">Manage Categories</a>
            <a href="posts.php">Manage Posts</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</section>

<?php 
require_once 'includes/admin-footer.php';
?>