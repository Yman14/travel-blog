<?php
require_once '../includes/config.php';

//pagination
$limit = 10;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

// total count needed for the Next button logic
$totalPosts = $pdo->query("SELECT COUNT(*) FROM posts WHERE status = 'published'")->fetchColumn();
$totalPages = ceil($totalPosts / $limit);

//fetch data
$stmt = $pdo->prepare("
    SELECT posts.*, categories.name AS category
    FROM posts
    LEFT JOIN categories on posts.category_id = categories.id
    WHERE status = 'published'
    ORDER BY posts.created_at DESC
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../includes/header.php';
?>

<div class="hero-section">
    <img src= "<?= BASE_URL ?>assets/images/hero.jpg" class="hero-image" loading="lazy">
    <div class="hero-content">
        <!-- <h1 class="hero-title">One life. Let’s go.</h1>
        <p class="hero-description">Join my journey across countries as I share the stories and hidden gems.</p> -->
        <h1 class="hero-title"><?= htmlspecialchars($settings['hero_title']) ?></h1>
        <p class="hero-description"><?= htmlspecialchars($settings['hero_subtitle']) ?></p>
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
                    <a href="<?=BASE_URL?>category.php?id=<?= $post['category_id']; ?>">[<?= htmlspecialchars($post['category']); ?>]</a>
                     • <?= htmlspecialchars((new DateTime($post['created_at']))->format('M d, Y')); ?>
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
                        <img src="<?= $image; ?>" class="image" loading="lazy">
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
        <h3>Travel</h3>
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
    <!-- <section class="sidebar-block">
        <h3>Recent Posts</h3>
    </section> -->
</aside>
</div>

<!-- pagination -->
<div class="pagination-container">
    <div class="pagination-pills">
        
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>" class="pill arrow">←</a>
        <?php endif; ?>

        <?php
        // Only show 2 pages before and 2 pages after current page
        $start = max(1, $page - 2);
        $end = min($totalPages, $page + 2);

        for ($i = $start; $i <= $end; $i++): ?>
            <a href="?page=<?= $i ?>" class="pill <?= ($i == $page) ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>" class="pill arrow">→</a>
        <?php endif; ?>

    </div>
</div>
<?php
require_once '../includes/footer.php';
?>
