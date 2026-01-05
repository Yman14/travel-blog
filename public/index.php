<?php
require_once '../includes/db.php';
require_once '../includes/header.php';
//echo "Database connected successfully";
/* 1. Write SQL */
$sql = "SELECT posts.title, posts.content, posts.created_at
        FROM posts
        WHERE posts.status = 'published'
        ORDER BY posts.created_at DESC";

/* 2. Prepare query */
$stmt = $pdo->prepare($sql);

/* 3. Execute query */
$stmt->execute();

/* 4. Fetch results */
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Latest Posts</h1>

<?php if ($posts): ?>
    <?php foreach ($posts as $post): ?>
        <article>
            <h2><?php echo htmlspecialchars($post['title']); ?></h2>
            <p><?php echo htmlspecialchars($post['content']); ?></p>
            <small>Published on <?php echo $post['created_at']; ?></small>
        </article>
        <hr>
    <?php endforeach; ?>
<?php else: ?>
    <p>No posts found.</p>
<?php endif; ?>

<h1>Welcome to the Travel Blog</h1>
<p>This is the homepage.</p>



<?php
require_once '../includes/footer.php';
