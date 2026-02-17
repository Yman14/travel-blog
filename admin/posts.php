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
$sql = "SELECT posts.id, posts.title, posts.status, posts.featured_image, posts.created_at, categories.name AS category
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

    <section class="dashboard-panel">
        <div class="panel-header">
            <h2>Recent Posts</h2>
            <label class="custom-checkbox">
                <input type="checkbox" id="check-all">
                <span class="checkmark"></span>
            </label>
            <span class="post-count"><?= count($posts) ?> total</span>
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

        <?php if ($posts): ?>
        <form method="post" action="posts-bulk" id="bulkActionForm">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            
            <div class="post-list-container">
                <?php foreach ($posts as $post): ?>
                    <div class="post-row-card">
                        <!-- Select & Thumbnail -->
                        <div class="post-row-leading">
                            <label class="custom-checkbox">
                                <input type="checkbox" name="post_ids[]" value="<?= $post['id'] ?>" class="post-checkbox">
                                <span class="checkmark"></span>
                            </label>
                            <div class="post-thumb-preview">
                                <?php if($post['featured_image']): ?>
                                    <img src="<?= UPLOAD_URL . $post['featured_image'] ?>" alt="preview">
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Content Info -->
                        <div class="post-row-main">
                            <h4 class="post-title-link">
                                <a href="<?= BASE_URL ?>admin/edit-post?id=<?= $post['id'] ?>">
                                    <?= htmlspecialchars($post['title']) ?>
                                </a>
                            </h4>
                            <span class="meta-item">
                                <?=htmlspecialchars($post['category']); ?>
                            </span>
                            <span class="meta-item">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                <?= date('M j, Y', strtotime($post['created_at'])) ?>
                            </span>
                            <span class="status-pill <?= $post['status'] ?>">
                                <?= ucfirst($post['status']) ?>
                            </span>
                        </div>

                        <!-- Action Group -->
                        <div class="post-row-actions">
                            <a title="Edit" href="<?= BASE_URL ?>admin/edit-post?id=<?= $post['id'] ?>" class="action-icon-btn edit">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </a>
                            <button 
                                type="button" 
                                class="action-icon-btn delete btn-delete-trigger"
                                data-id="<?= $post['id']; ?>"
                                data-title="<?= htmlspecialchars($post['title']); ?>"
                            >
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Floating Bulk Action Bar -->
            <div class="bulk-action-footer">
                <div class="selection-info">
                    <span id="selected-count">0</span> items selected
                </div>
                <div class="action-controls">
                    <select name="action" required class="modern-select">
                        <option value="">Bulk actions</option>
                        <option value="publish">Publish</option>
                        <option value="draft">Move to Draft</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button type="submit" class="btn-apply-bulk" onclick="return confirm('Apply bulk action to selected items?')">Apply Action</button>
                </div>
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