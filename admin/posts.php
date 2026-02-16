<?php
$page_title = "Manage Posts";
require_once '../includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/admin-header.php';

// handle filter status logic
$statusFilter = $_GET['status'] ?? 'all';
$whereClauses = [];
$params = [];

if (in_array($statusFilter, ['draft', 'published'], true)) {
    $whereClauses[] = 'posts.status = :status';
    $params[':status'] = $statusFilter;
}

$whereSql = !empty($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

// sql qyery
$sql = "SELECT posts.id, posts.title, posts.status, posts.created_at, categories.name AS category
        FROM posts
        LEFT JOIN categories ON posts.category_id = categories.id
        $whereSql
        ORDER BY posts.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- html -->
<section class="admin-section">
    <h1>Manage All Posts</h1>
    <div class="dashboard-actions">
        <a class="btn primary" href="<?= BASE_URL ?>admin/create-post">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
            New Post
        </a>
        <a class="btn" href="<?= BASE_URL ?>admin/categories">Manage Categories</a>
        <a class="btn" href="<?=BASE_URL?>admin/settings">Site Settings</a>
    </div>

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

    <!-- filter status -->
    <nav class="admin-filters">
        <a href="?status=all" class="<?= $statusFilter === 'all' ? 'active' : '' ?>">All</a>
        <a href="?status=published" class="<?= $statusFilter === 'published' ? 'active' : '' ?>">Published</a>
        <a href="?status=draft" class="<?= $statusFilter === 'draft' ? 'active' : '' ?>">Draft</a>
    </nav>

    <!-- table -->
    <table border="1" cellpadding="8">
        <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($posts as $post): ?>
            <tr>
                <td><?= htmlspecialchars($post['title']); ?></td>
                <td><?= htmlspecialchars($post['category']); ?></td>
                <td><?= htmlspecialchars($post['status']); ?></td>
                <td><?= htmlspecialchars($post['created_at']); ?></td>
                <td>
                    <a href="<?=BASE_URL?>admin/edit-post?id=<?= $post['id']; ?>">Edit</a> |
                    <button
                        class="btn-delete-trigger"
                        data-id="<?= $post['id']; ?>"
                        data-title="<?= htmlspecialchars($post['title']); ?>"
                    >
                        Delete
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div id="deleteModal" class="modal hidden">
        <div class="modal-content">
            <h3>Delete post</h3>
            <p id="deleteMessage"></p>

            <form method="post" action="<?=BASE_URL?>admin/delete-post" id="deleteForm">
                <input type="hidden" name="return_url" value="<?= $_SERVER['REQUEST_URI']; ?>">
                <input type="hidden" name="post_id" id="deletePostId">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

                <button type="submit" class="danger">Delete</button>
                <button type="button" id="cancelDelete">Cancel</button>
            </form>
        </div>
    </div>
    <p><a href="<?=BASE_URL?>admin/dashboard">Back to dashboard</a></p>
    </section>

<?php
require_once 'includes/admin-footer.php';
?>