<?php
$page_title = "Create New Post";
require_once 'includes/admin-header.php';
require_once '../includes/db.php';

//fetch categories
$sql = "SELECT id, name FROM categories ORDER BY name ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);


//handle form submission
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
        // slug generation
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

        $sql = "INSERT INTO posts (title, slug, content, category_id, status)
                VALUES (:title, :slug, :content, :category_id, :status)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':slug', $slug);
        $stmt->bindValue(':content', $content);
        $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindValue(':status', $status);

        $stmt->execute();

        $success = 'Post created successfully.';
    }
}
?>

<!-- //admin form html -->
<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
</head>
<body>

<h1>Create New Post</h1>

<?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p style="color:green;"><?php echo $success; ?></p>
<?php endif; ?>


<form method="post">

    <label>Title</label><br>
    <input type="text" name="title" required><br><br>

    <label>Category</label><br>
    <select name="category_id" required>
        <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['id']; ?>">
                <?php echo htmlspecialchars($cat['name']); ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Content</label><br>
    <textarea name="content" rows="8" required></textarea><br><br>

    <label>Status</label><br>
    <select name="status">
        <option value="draft">Draft</option>
        <option value="published">Published</option>
    </select><br><br>

    <button type="submit">Create Post</button>

</form>

<p><a href="dashboard.php">Back to dashboard</a></p>

</body>
</html>

<?php
    require_once 'includes/admin-footer.php';
?>


