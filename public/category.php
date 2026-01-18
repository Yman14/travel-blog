<?php
require_once '../includes/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    require_once '../includes/header.php';
    echo ('Invalid category');
    require_once '../includes/footer.php';
    exit;
}

$categoryId = (int) $_GET['id'];

//Fetch Category Name FIRST
$sqlCat = "SELECT name FROM categories WHERE id = :id LIMIT 1";
$stmtCat = $pdo->prepare($sqlCat);
$stmtCat->bindValue(':id', $categoryId, PDO::PARAM_INT);
$stmtCat->execute();
$category = $stmtCat->fetch(PDO::FETCH_ASSOC);

// If category doesn't exist in DB, stop
if (!$category) {
    require_once '../includes/header.php';
    echo "<h1>Category Not Found</h1>";
    require_once '../includes/footer.php';
    exit;
}

//fetch the posts based on that category
$sql = "SELECT posts.id, posts.title, posts.slug
        FROM posts
        WHERE posts.category_id = :id
        AND posts.status = 'published'
        ORDER BY posts.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $categoryId, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

//set title page to category
$page_title = $category['name'];

require_once '../includes/header.php';
?>

<!-- display -->
<h1 class="title"><?= htmlspecialchars($category['name']); ?> Category Posts</h1>

<?php if (!$posts): ?>
    <div class="empty-state">
        <p>No posts available.</p>
    </div>
<?php endif; ?>

<section class="category-post-list">
    <?php foreach ($posts as $post): ?>
        <h3 class="category-post">
            <a href="post.php?slug=<?= $post['slug']; ?>">
                <?= htmlspecialchars($post['title']); ?>
            </a>
        </h3>
    <?php endforeach; ?>
</section>

<?php require_once '../includes/footer.php'; ?>
