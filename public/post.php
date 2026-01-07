<?php
require_once '../includes/db.php';
require_once '../includes/header.php';

//verify if id from url exist
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>Invalid post.</p>";
    require_once '../includes/footer.php';
    exit;
}

//get the id from url
$postId = (int) $_GET['id'];

//SQL for single post
$sql = "SELECT title, content, created_at
        FROM posts
        WHERE id = :id AND status = 'published'
        LIMIT 1";

//fetch the data
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $postId, PDO::PARAM_INT);
$stmt->execute();
$post = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!-- display result -->
<?php if ($post): ?>
    <article>
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <small>Published on <?php echo $post['created_at']; ?></small>
        <p><?php echo nl2br(htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8')); ?></p>
    </article>
<?php else: ?>
    <p>Post not found.</p>
<?php endif; ?>

<?php
require_once '../includes/footer.php';
