<?php
require_once '../includes/db.php';
$page_title = "Categories";
require_once '../includes/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid category');
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
?>

<!-- display -->
<h1><?= htmlspecialchars($category['name']); ?> Category Posts</h1>

<?php if (!$posts): ?>
    <p>No posts found.</p>
<?php endif; ?>

<?php foreach ($posts as $post): ?>
    <h3>
        <a href="post.php?slug=<?= $post['slug']; ?>">
            <?= htmlspecialchars($post['title']); ?>
        </a>
    </h3>
<?php endforeach; ?>

<?php require_once '../includes/footer.php'; ?>
