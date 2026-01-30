<?php
require_once '../includes/config.php';
require_once '../includes/header.php';
//echo "Database connected successfully";

//the data target to be fetch
$sql = "SELECT posts.id, posts.slug, posts.title, posts.content, posts.featured_image, posts.created_at, posts.category_id
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

<div class="hero-section">
    <img src= "<?= BASE_URL ?>assets/images/lamitan.jpg" class="hero-image">
    <div class="hero-content">
        <h1 class="hero-title">One life. Let’s go.</h1>
        <p class="hero-description">Join my journey across countries as I share the stories and hidden gems.</p>
    </div>
</div>
<div class="main-content">
<!-- rendering the fetch data -->
<section class="post-list" role="main">
    <?php if ($posts): ?>
        <?php foreach ($posts as $post): ?>
            <article class="post-preview">
                <h2 class="post-title">
                    <a href="<?= BASE_URL ?>post/<?= $post['slug']; ?>">
                        <?= htmlspecialchars($post['title']); ?>
                    </a>
                </h2>
                <small class="post-meta">
                    [Category] · <?= htmlspecialchars((new DateTime($post['created_at']))->format('M d, Y')); ?>
                </small>
                <div class="post-featured">
                    <?php
                        if($post['featured_image']){
                            $image = UPLOAD_URL . $post['featured_image'];
                        }else{
                            $image = BASE_URL . 'assets/images/default-post.jpg';
                        }
                    ?>
                    <a href="<?= BASE_URL ?>post/<?= $post['slug']; ?>">
                        <img src="<?= $image; ?>" class="image">
                    </a>
                </div>
                <p class="post-excerpt">
                    <?php
                        $plainText = strip_tags($post['content']);
                        $limit = 150;
                        $snippet = mb_strimwidth($plainText, 0, $limit, "...");
                        echo htmlspecialchars($snippet, ENT_QUOTES, 'UTF-8');
                        // Logic: Only show link if the actual text is longer than the limit
                        if (mb_strlen($plainText) > $limit): ?>
                            <a href="<?= BASE_URL ?>post/<?= htmlspecialchars($post['slug'], ENT_QUOTES, 'UTF-8'); ?>" class="post-readmore">
                                Read more
                            </a>
                        <?php endif; 
                    ?>
                </p>
            </article>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-state">
            <p>No posts available.</p>
        </div>
    <?php endif; ?>
</section>

<aside class="sidebar" role="complementary">
    <section class="sidebar-block">
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
    </section>
    <section class="sidebar-block">
        <h3>Recent Posts</h3>
        <!-- recent posts -->
    </section>
</aside>
</div>
<?php
require_once '../includes/footer.php';
?>
