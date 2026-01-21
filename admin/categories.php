<?php
$page_title = 'Categories';
require_once '../includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/admin-header.php';

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Manage Categories</h1>

<p><a href="create-category.php">Add Category</a></p>

<!-- check for error -->
<?php if (isset($_SESSION['flash_error'])): ?>
    <div class="alert-error">
        <?= htmlspecialchars($_SESSION['flash_error']); ?>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<ul>
<?php foreach ($categories as $cat): ?>
    <li>
        <?php echo htmlspecialchars($cat['name']); ?>
        <form method="post" action="delete-category.php" style="display:inline;">
            <input type="hidden" name="id" value="<?php echo $cat['id']; ?>">
            <button type="submit" onclick="return confirm('Delete category?')">Delete</button>
        </form>
    </li>
<?php endforeach; ?>
</ul>

<?php require_once 'includes/admin-footer.php'; ?>
