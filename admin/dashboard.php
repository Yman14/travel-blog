<?php
$page_title = "Dashboard";
require_once '../includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/admin-header.php';

// KPI counts
$totalPosts = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
$publishedPosts = $pdo->query("SELECT COUNT(*) FROM posts WHERE status = 'published'")->fetchColumn();
$draftPosts = $pdo->query("SELECT COUNT(*) FROM posts WHERE status = 'draft'")->fetchColumn();
$totalCategories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();

// Recent posts
$stmt = $pdo->prepare("
    SELECT id, title, status, created_at
    FROM posts
    ORDER BY created_at DESC
    LIMIT 5
");
$stmt->execute();
$recentPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- html -->
 <section class="admin-dashboard">

    <!-- Header -->
    <header class="admin-dashboard-header">
        <h1>Dashboard</h1>
        <p>Overview of your site activity and content.</p>
    </header>

    <!-- KPI Cards -->
    <div class="dashboard-cards">
        <div class="card">
            <strong class="value"><?= $totalPosts ?></strong>
            <span class="name">Total Posts</span>
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <title>Total Posts</title>
                <path d="M19 5V19H5V5H19M21 3H3V21H21V3M17 17H7V16H17V17M17 15H7V14H17V15M17 12H7V7H17V12Z" />
            </svg>
        </div>
        <div class="card">
            <strong class="value"><?= $publishedPosts ?></strong>
            <span class="name">Published</span>
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <title>Published</title>
                <path d="M5,4V6H19V4H5M5,14H9V20H15V14H19L12,7L5,14Z" />
            </svg>
        </div>
        <div class="card">
            <strong class="value"><?= $draftPosts ?></strong>
            <span class="name">Drafts</span>
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <title>Drafts</title>
                <path d="M20.8 22.7L15 16.9V20H9V14H5L8.6 10.4L1.1 3L2.4 1.7L22.1 21.4L20.8 22.7M19 6V4H7.2L9.2 6H19M17.2 14H19L12 7L11.1 7.9L17.2 14Z" />
            </svg>
        </div>
        <div class="card">
            <strong class="value"><?= $totalCategories ?></strong>
            <span class="name">Categories</span>
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <title>Categories</title>
                <path d="M12,11.5A2.5,2.5 0 0,1 9.5,9A2.5,2.5 0 0,1 12,6.5A2.5,2.5 0 0,1 14.5,9A2.5,2.5 0 0,1 12,11.5M12,2A7,7 0 0,0 5,9C5,14.25 12,22 12,22C12,22 19,14.25 19,9A7,7 0 0,0 12,2Z" />
            </svg>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="dashboard-actions">
        <a class="btn primary" href="<?= BASE_URL ?>admin/create-post">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 8px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
            New Post
        </a>
        <a class="btn" href="<?= BASE_URL ?>admin/posts">Manage Posts</a>
        <a class="btn" href="<?= BASE_URL ?>admin/categories">Manage Categories</a>
        <a class="btn" href="<?=BASE_URL?>admin/settings">Site Settings</a>
    </div>

    <!-- Recent Posts -->
    <section class="dashboard-panel">
        <h2>Recent Posts</h2>
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

        <?php if ($recentPosts): ?>
            <form method="post" action="posts-bulk">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="check-all"></th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($recentPosts as $post): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="post_ids[]" value="<?= $post['id'] ?>" class="post-checkbox">
                            </td>
                            <td><?= htmlspecialchars($post['title']) ?></td>
                            <td>
                                <span class="status <?= $post['status'] ?>">
                                    <?= ucfirst($post['status']) ?>
                                </span>
                            </td>
                            <td><?= date('M j, Y', strtotime($post['created_at'])) ?></td>
                            <td>
                                <a href="<?= BASE_URL ?>admin/edit-post?id=<?= $post['id'] ?>">Edit</a>
                                <button
                                    type="button"
                                    class="btn-delete-trigger"
                                    data-id="<?= $post['id']; ?>"
                                    data-title="<?= htmlspecialchars($post['title']); ?>"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="bulk-actions">
                    <select name="action" required>
                        <option value="">Bulk actions</option>
                        <option value="publish">Publish</option>
                        <option value="draft">Move to Draft</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button type="submit" onclick="return confirm('Apply bulk action to selected items?')">Apply</button>
                </div>
            </form>
        <?php else: ?>
            <p class="empty-state">No posts yet.</p>
        <?php endif; ?>
    </section>

    <!-- MODAL FOR DELETE BUTTON -->
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

</section>

<?php 
require_once 'includes/admin-footer.php';
?>