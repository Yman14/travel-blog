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
<section class="post-list" role="main">
    <?php if ($posts): ?>
        <?php foreach ($posts as $post): ?>
            <article class="post-preview">
                <h2 class="post-title">
                    <a href="/travel-blog/post/<?= $post['slug']; ?>">
                        <?= htmlspecialchars($post['title']); ?>
                    </a>
                </h2>
                <small class="post-meta">
                    [Category] · Published on <?= htmlspecialchars((new DateTime($post['created_at']))->format('M d, Y')); ?>
                </small>
                <p class="post-excerpt">
                    <?php
                        $plainText = strip_tags($post['content']);
                        $limit = 150;
                        $snippet = mb_strimwidth($plainText, 0, $limit, "...");
                        echo htmlspecialchars($snippet, ENT_QUOTES, 'UTF-8');
                        // Logic: Only show link if the actual text is longer than the limit
                        if (mb_strlen($plainText) > $limit): ?>
                            <a href="/travel-blog/post/<?= $post['slug']; ?>"> Read more</a>
                        <?php endif; 
                    ?>
                </p>
            </article>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No posts found.</p>
    <?php endif; ?>
</section>

<aside class="sidebar" role="complementary">
    <h3>Categories</h3>
    <ul>
        <?php foreach ($navCategories as $cat): ?>
            <li>
                <a href="category.php?id=<?php echo $cat['id']; ?>">
                    <?php echo htmlspecialchars($cat['name']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</aside>

<?php
require_once '../includes/footer.php';
?>
