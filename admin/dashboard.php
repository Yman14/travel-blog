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
            <strong><?= $totalPosts ?></strong>
            <span>Total Posts</span>
        </div>
        <div class="card">
            <strong><?= $publishedPosts ?></strong>
            <span>Published</span>
        </div>
        <div class="card">
            <strong><?= $draftPosts ?></strong>
            <span>Drafts</span>
        </div>
        <div class="card">
            <strong><?= $totalCategories ?></strong>
            <span>Categories</span>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="dashboard-actions">
        <a class="btn primary" href="<?= BASE_URL ?>admin/create-post">+ New Post</a>
        <a class="btn" href="<?= BASE_URL ?>admin/posts">Manage Posts</a>
        <a class="btn" href="<?= BASE_URL ?>admin/categories">Manage Categories</a>
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