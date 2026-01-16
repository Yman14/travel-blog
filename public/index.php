<?php
require_once '../includes/db.php';
require_once '../includes/header.php';
//echo "Database connected successfully";

//the data target to be fetch
$sql = "SELECT posts.id, posts.slug, posts.title, posts.content, posts.created_at
        FROM posts
        WHERE posts.status = 'published'
        ORDER BY posts.created_at DESC";

//for security
$stmt = $pdo->prepare($sql);

//actual excecution
$stmt->execute();

//fetch result
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- rendering the fetch data -->
<h1>Latest Posts</h1>

<?php if ($posts): ?>
    <?php foreach ($posts as $post): ?>
        <article>
            <h2>
                <a href="/travel-blog/post/<?= $post['slug']; ?>">
                    <?= htmlspecialchars($post['title']); ?>
                </a>
            </h2>
            <p>
                <?= substr(strip_tags($post['content']), 0, 150); ?>...
                <a href="/travel-blog/post/<?= $post['slug']; ?>"> Read more</a>
            </p>
            <small>Published on <?= htmlspecialchars($post['created_at']); ?></small>
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
?>
