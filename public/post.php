<?php
require_once '../includes/db.php';

//verify if id from url exist
// if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
//     echo "<p>Invalid post.</p>";
//     require_once '../includes/footer.php';
//     exit;
// }

//Get the slug from the URL (provided by .htaccess)
$slug = $_GET['slug'] ?? '';

// Slugs should only contain lowercase letters, numbers, and hyphens.
if ($slug === '' || !preg_match('/^[a-z0-9-]+$/', $slug)) {
    // If invalid, show an error and stop
    require_once '../includes/header.php';
    echo "<h1>Invalid Post</h1><p>The post link is malformed.</p>";
    require_once '../includes/footer.php';
    exit;
}

//SQL for single post
$sql = "SELECT title, content, created_at, category_id
        FROM posts
        WHERE slug = :slug AND status = 'published'
        LIMIT 1";

//fetch the data
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':slug', $slug, PDO::PARAM_STR);
$stmt->execute();
$post = $stmt->fetch(PDO::FETCH_ASSOC);


//display
require_once '../includes/header.php';
?>

<!-- html -->
<!-- display result -->
<?php if ($post): ?>
    <article class="post-full">
        <header>
            <h1><?= htmlspecialchars($post['title']); ?></h1>
            <small class="post-meta">
                    <a href="/travel-blog/public/category.php?id=<?= $post['category_id']; ?>">[Category]</a>
                     · Published on <?= htmlspecialchars((new DateTime($post['created_at']))->format('F j, Y')); ?>
            </small>
        </header>
        <p class="post-body"><?= nl2br(htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8')); ?></p>
    </article>
<?php else: ?>
    <?php http_response_code(404); ?>
    <div class="empty-state">
        <p>Post not found.</p>
    </div>
<?php endif; ?>

<?php
require_once '../includes/footer.php';
?>