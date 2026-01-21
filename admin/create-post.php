<?php
$page_title = "Create New Post";
require_once '../includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/admin-header.php';

//fetch categories
$sql = "SELECT id, name FROM categories ORDER BY name ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$postId = null;
$galleryImages = [];
if (!empty($postId)) {
    $imgStmt = $pdo->prepare("
        SELECT id, file_path
        FROM post_images
        WHERE post_id = :id
        ORDER BY sort_order
    ");
    $imgStmt->execute([':id' => $postId]);
    $galleryImages = $imgStmt->fetchAll(PDO::FETCH_ASSOC);
}


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
        //image upload
        $uploadDir = '../assets/images/uploads/' . date('Y/m/') ;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $featuredPath = null;

        if (!empty($_FILES['featured_image']['name'])) {

            if ($_FILES['featured_image']['size'] > 5 * 1024 * 1024) {
                $error = 'Featured image too large.';
            }

            $mime = mime_content_type($_FILES['featured_image']['tmp_name']);
            if (!in_array($mime, ['image/jpeg','image/png','image/webp'])) {
                $error = 'Invalid featured image type.';
            }

            if (!$error) {
                $ext = pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION);
                $filename = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
                move_uploaded_file(
                    $_FILES['featured_image']['tmp_name'],
                    $uploadDir . $filename
                );

                $featuredPath = date('Y/m/') . $filename;
            }
        }

        // slug generation
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

        $sql = "INSERT INTO posts (title, slug, content, featured_image, category_id, status)
                VALUES (:title, :slug, :content, :featured_image, :category_id, :status)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':slug', $slug);
        $stmt->bindValue(':content', $content);
        $stmt->bindValue(':featured_image', $featuredPath);
        $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindValue(':status', $status);

        $stmt->execute();
        $success = 'Post created successfully.';

        $postId = $pdo->lastInsertId();
        if (!empty($_FILES['gallery_images']['name'][0])) {
            foreach ($_FILES['gallery_images']['tmp_name'] as $i => $tmp) {
                if ($_FILES['gallery_images']['size'][$i] > 5 * 1024 * 1024) {
                    continue;
                }

                $mime = mime_content_type($tmp);
                if (!in_array($mime, ['image/jpeg','image/png','image/webp'])) {
                    continue;
                }

                $ext = pathinfo($_FILES['gallery_images']['name'][$i], PATHINFO_EXTENSION);
                $name = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                move_uploaded_file($tmp, $uploadDir . $name);

                $stmt = $pdo->prepare("
                    INSERT INTO post_images (post_id, file_path)
                    VALUES (:post_id, :path)
                ");
                $stmt->execute([
                    ':post_id' => $postId,
                    ':path' => date('Y/m/') . $name
                ]);
            }
        }

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

<form method="post" enctype="multipart/form-data">

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

    <label>Featured Image</label><br>
    <input type="file" name="featured_image" accept="image/jpeg,image/png,image/webp"><br><br>

    <label>Gallery Images</label><br>
    <input type="file" name="gallery_images[]" multiple
        accept="image/jpeg,image/png,image/webp"><br><br>

    <label>Status</label><br>
    <select name="status">
        <option value="draft">Draft</option>
        <option value="published">Published</option>
    </select><br><br>

    <button type="submit">Create Post</button>

</form>

<?php if ($galleryImages): ?>
    <h3>Gallery Images</h3>
    <ul>
        <?php foreach ($galleryImages as $img): ?>
            <li>
                <img src="<?= $img['file_path']; ?>" style="max-width:120px;">
                <a href="delete-image.php?id=<?= $img['id']; ?>&post=<?= $postId; ?>"
                   onclick="return confirm('Delete this image?')">
                   Delete
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<p><a href="dashboard.php">Back to dashboard</a></p>

</body>
</html>

<?php
    require_once 'includes/admin-footer.php';
?>


