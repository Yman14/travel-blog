<?php
session_start();

//access protection
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../includes/db.php';

//validate id url
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid post ID');
}

$postId = (int) $_GET['id'];

/* Fetch post */
$sql = "SELECT * FROM posts WHERE id = :id LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $postId, PDO::PARAM_INT);
$stmt->execute();
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die('Post not found');
}

/* Fetch categories */
$cats = $pdo->query("SELECT id, name FROM categories")->fetchAll(PDO::FETCH_ASSOC);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = (int) $_POST['category_id'];
    $status = $_POST['status'];

    if ($title === '' || $content === '') {
        $error = 'Title and content are required.';
    } else {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

        $sql = "UPDATE posts
                SET title = :title,
                    slug = :slug,
                    content = :content,
                    category_id = :category_id,
                    status = :status
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':slug' => $slug,
            ':content' => $content,
            ':category_id' => $category_id,
            ':status' => $status,
            ':id' => $postId
        ]);

        $success = 'Post updated successfully.';
    }
}

require_once '../includes/header.php';
?>

<!-- html -->
<h1>Edit Post</h1>

<?php if ($error): ?><p style="color:red;"><?php echo $error; ?></p><?php endif; ?>
<?php if ($success): ?><p style="color:green;"><?php echo $success; ?></p><?php endif; ?>

<form method="post">
    <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required><br><br>

    <select name="category_id">
        <?php foreach ($cats as $cat): ?>
            <option value="<?php echo $cat['id']; ?>"
                <?php if ($cat['id'] == $post['category_id']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($cat['name']); ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <textarea name="content" rows="8" required><?php echo htmlspecialchars($post['content']); ?></textarea><br><br>

    <select name="status">
        <option value="draft" <?php if ($post['status'] === 'draft') echo 'selected'; ?>>Draft</option>
        <option value="published" <?php if ($post['status'] === 'published') echo 'selected'; ?>>Published</option>
    </select><br><br>

    <button type="submit">Update Post</button>
</form>

<p><a href="posts.php">Back to posts</a></p>

<?php
require_once '../includes/footer.php';