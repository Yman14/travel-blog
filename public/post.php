<?php
require_once '../includes/config.php';

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
$sql = "SELECT title, content, created_at, category_id, featured_image
        FROM posts
        WHERE slug = :slug AND status = 'published'
        LIMIT 1";

//fetch the data
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':slug', $slug, PDO::PARAM_STR);
$stmt->execute();
$post = $stmt->fetch(PDO::FETCH_ASSOC);
if(empty($post)){
    header('Location:' . BASE_URL . '404.php');
}

//fetch category
$catsql = "SELECT name FROM categories WHERE id = :id";
$stmt = $pdo->prepare($catsql);
$stmt->bindValue(':id', $post['category_id'], PDO::PARAM_INT);
$stmt->execute();
$cat = $stmt->fetch(PDO::FETCH_ASSOC);


//Fetcj gallery this time
$stmt = $pdo->prepare("
    SELECT file_path FROM post_images
    WHERE post_id = (
        SELECT id FROM posts WHERE slug = :slug LIMIT 1
    )
    ORDER BY sort_order
");
$stmt->execute([':slug' => $slug]);
$gallery = $stmt->fetchAll();


//display
require_once '../includes/header.php';
?>

<!-- html -->
<!-- display result -->
<div class="main-content">
<?php if ($post): ?>
    <article class="post-list">
        <header>
            <h1><?= htmlspecialchars($post['title']); ?></h1>
            <small class="post-meta">
                    <a href="<?=BASE_URL?>category.php?id=<?= $post['category_id']; ?>"><?= htmlspecialchars($cat['name']); ?></a>
                     · Published on <?= htmlspecialchars((new DateTime($post['created_at']))->format('F j, Y')); ?>
            </small>
        </header>
        <div class="post-featured">
            <?php
                if($post['featured_image']){
                    $image = UPLOAD_URL . $post['featured_image'];
                }else{
                    $image = BASE_URL . 'assets/images/default-post.jpg';
                }
            ?>
            <img src="<?= $image; ?>" class="image">
        </div>
        <p class="post-body"><?= nl2br(htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8')); ?></p>
        <?php if ($gallery): ?>
        <div class="post-gallery">
            <?php foreach ($gallery as $img): ?>
                <img src="<?=UPLOAD_URL . $img['file_path']; ?>" loading="lazy">
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </article>
<?php else: ?>
    <div class="empty-state">
        <p>Post not found.</p>
    </div>
<?php endif; ?>

</div>
<?php
require_once '../includes/footer.php';
?>