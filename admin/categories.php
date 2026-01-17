<?php
$page_title = 'Categories';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/admin-header.php';

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Manage Categories</h1>

<p><a href="create-category.php">Add Category</a></p>

<ul>
<?php foreach ($categories as $cat): ?>
    <li>
        <?php echo htmlspecialchars($cat['name']); ?>
        <a href="delete-category.php?id=<?php echo $cat['id']; ?>" onclick="return confirm('Delete?')">Delete</a>
    </li>
<?php endforeach; ?>
</ul>

<?php require_once 'includes/admin-footer.php'; ?>
