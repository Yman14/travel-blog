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
    <article>
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <small>Published on <?= htmlspecialchars($post['created_at']); ?></small>
        <p><?php echo nl2br(htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8')); ?></p>
        <p>
            Category:
            <a href="/travel-blog/public/category.php?id=<?= $post['category_id']; ?>">
                View more
            </a>
        </p>
    </article>
<?php else: ?>
    <?php http_response_code(404); ?>
    <p>Post not found.</p>
<?php endif; ?>

<?php
require_once '../includes/footer.php';
?>