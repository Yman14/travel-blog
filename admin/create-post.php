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



//handle form submission
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = (int) $_POST['category_id'];
    $status = $_POST['status'];

    //validation
    if (!in_array($status, ['draft', 'published'], true)) {
        $error .= 'Invalid post status.<br>';
    }
    if ($title === '' || $content === '') {
        $error .= 'Title and content are required.<br>';
    }

    //path of saved image
    //create new directory if dont exist
    $relativePath = date('Y/m/');
    $uploadDir = UPLOAD_PATH . '/' . $relativePath;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    //feature image upload process
    $featuredPath = null;
    if (!empty($_FILES['featured_image']['name'])) {
        if ($_FILES['featured_image']['error'] !== UPLOAD_ERR_OK) {
            $error .= 'Featured image upload failed.<br>';
        } else {
            if ($_FILES['featured_image']['size'] > 5 * 1024 * 1024) {
                $error .= 'Featured image too large.';
            } else {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime  = finfo_file($finfo, $_FILES['featured_image']['tmp_name']);
                finfo_close($finfo);
                if (!in_array($mime, ['image/jpeg','image/png','image/webp'], true)) {
                    $error .= 'Invalid featured image type.';
                }
            }
        }

        if (empty($error)) {
            $ext = pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION);
            $filename = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            move_uploaded_file($_FILES['featured_image']['tmp_name'], $uploadDir . '/' . $filename);
            $featuredPath = $relativePath . $filename;
        }
    }

    if(empty($error)){
        try{
            $pdo->beginTransaction();
            // slug generation
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

            //check slug for duplicate
            $check = $pdo->prepare("SELECT id FROM posts WHERE slug = :slug LIMIT 1");
            $check->execute([':slug' => $slug]);
            if ($check->fetch()) {
                throw new Exception('Duplicate title');
            }

            //For post
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

            //for insert gallery images
            $postId = $pdo->lastInsertId();
            if (!empty($_FILES['gallery_images']['name'][0])) {
                $galleryErrors = [];

                foreach ($_FILES['gallery_images']['tmp_name'] as $i => $tmp) {
                    //prevents corrupted temp files reads
                    if ($_FILES['gallery_images']['error'][$i] !== UPLOAD_ERR_OK) {
                        $galleryErrors[] = $_FILES['gallery_images']['name'][$i] . ' upload failed.';
                        continue;
                    }
                    //prevent large size image
                    if ($_FILES['gallery_images']['size'][$i] > 5 * 1024 * 1024) {
                        //not sure yet
                        $galleryErrors[] = $_FILES['gallery_images']['name'][$i] . ' exceeds size limit.';
                        continue;
                    }

                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($finfo, $tmp);
                    finfo_close($finfo);
                    if (!in_array($mime, ['image/jpeg','image/png','image/webp'])) {
                        $galleryErrors[] = $_FILES['gallery_images']['name'][$i] . ' has invalid type.';
                        continue;
                    }

                    $ext = pathinfo($_FILES['gallery_images']['name'][$i], PATHINFO_EXTENSION);
                    $name = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                    move_uploaded_file($tmp, $uploadDir . '/' . $name);

                    $stmt = $pdo->prepare("
                        INSERT INTO post_images (post_id, file_path)
                        VALUES (:post_id, :path)
                    ");
                    $stmt->execute([
                        ':post_id' => $postId,
                        ':path' => $relativePath . $name
                    ]);
                }

                if (!empty($galleryErrors)) {
                    throw new Exception(implode('; ', $galleryErrors));
                }      
            }

            //successful process
            $pdo->commit();
            $success = 'Post created successfully.';
            $_SESSION['flash_success'] = "Post created successfully.";
            header('Location:' . BASE_URL . 'admin/posts');

        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
                if (!empty($featuredPath)) {
                    @unlink(UPLOAD_URL . $featuredPath);
                }
            }
            //error_log($e->getMessage());
            $error = $e->getMessage() ?: 'Failed to create post.';
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
    <div id="featurePreview"></div>
    <input type="file" name="featured_image" id="featureInput" accept="image/jpeg,image/png,image/webp"><br><br>

    <label>Gallery Images</label><br>
    <div id="galleryPreview" class="media-grid"></div>
    <input type="file"
        name="gallery_images[]"
        id="galleryInput"
        accept="image/jpeg,image/png,image/webp"
        multiple><br><br>

    <label>Status</label><br>
    <select name="status">
        <option value="draft">Draft</option>
        <option value="published">Published</option>
    </select><br><br>

    <button type="submit">Create Post</button>

</form>

<p><a href="<?=BASE_URL?>admin/dashboard">Back to dashboard</a></p>

</body>
</html>

<?php
    require_once 'includes/admin-footer.php';
?>


