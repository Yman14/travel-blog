<?php
$page_title = "Manage Posts";
require_once '../includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/admin-header.php';

//fetch datas
$sql = "SELECT posts.id, posts.title, posts.status, posts.created_at, categories.name AS category
        FROM posts
        JOIN categories ON posts.category_id = categories.id
        ORDER BY posts.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- html -->
<h1>All Posts</h1>

<p><a href="create-post.php">Create New Post</a></p>

<!-- check for success notifcation -->
<?php if (isset($_SESSION['flash_success'])): ?>
    <div class="notify-success">
        <?= htmlspecialchars($_SESSION['flash_success']); ?>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

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
                <a href="edit-post.php?id=<?= $post['id']; ?>">Edit</a> |
                <a href="delete-post.php?id=<?= $post['id']; ?>"
                   onclick="return confirm('Delete this post?');">
                   Delete
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<p><a href="dashboard.php">Back to dashboard</a></p>


<?php
require_once 'includes/admin-footer.php';
?>