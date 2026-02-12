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
    SELECT id, title, status, created_at, featured_image
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
        <div class="panel-header">
            <h2>Recent Posts</h2>
            <label class="custom-checkbox">
                <input type="checkbox" id="check-all">
                <span class="checkmark"></span>
            </label>
            <span class="post-count"><?= count($recentPosts) ?> total</span>
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

        <?php if ($recentPosts): ?>
        <form method="post" action="posts-bulk" id="bulkActionForm">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            
            <div class="post-list-container">
                <?php foreach ($recentPosts as $post): ?>
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
                            <div class="post-meta-sub">
                                <span class="meta-item">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <?= date('M j, Y', strtotime($post['created_at'])) ?>
                                </span>
                                <span class="status-pill <?= $post['status'] ?>">
                                    <?= ucfirst($post['status']) ?>
                                </span>
                            </div>
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

</section>

<?php 
require_once 'includes/admin-footer.php';
?>