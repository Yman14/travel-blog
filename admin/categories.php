<?php
$page_title = 'Categories';
require_once '../includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/admin-header.php';

// $categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$sql = "
    SELECT c.id, c.name, COUNT(p.id) AS post_count
    FROM categories c
    LEFT JOIN posts p ON p.category_id = c.id
    GROUP BY c.id
    ORDER BY c.name ASC
";
$categories = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="admin-section manage-categories">
    <h1>Manage Categories</h1>

    <!-- create new category -->
    <form method="post" action="<?=BASE_URL?>admin/create-category" class="create-category">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="text" name="name" placeholder="New category name" required>
        <button>Add</button>
    </form>

    <!-- check for notifcation -->
    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="notify-success">
            <?= htmlspecialchars($_SESSION['flash_success']); ?>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="alert-error">
            <?= htmlspecialchars($_SESSION['flash_error']); ?>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <table class="admin-table">
    <thead>
    <tr>
        <th>Name</th>
        <th>Posts</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>

    <?php foreach ($categories as $cat): ?>
    <tr>
        <td>
            <form method="post" action="update-category" class="inline-form">
                <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                <input type="text" name="name"
                    value="<?= htmlspecialchars($cat['name']) ?>"
                    required>
                <button type="submit">Save</button>
            </form>
        </td>

        <td><?= $cat['post_count'] ?></td>

        <td>
            <?php if ($cat['post_count'] == 0): ?>
                <form method="post" action="<?=BASE_URL?>admin/delete-category" class="inline-form">
                    <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <button class="danger"
                        onclick="return confirm('Delete this category?')">
                        Delete
                    </button>
                </form>
            <?php else: ?>
                <span class="muted">In use</span>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>

    </tbody>
    </table>
</section>
<?php require_once 'includes/admin-footer.php'; ?>
