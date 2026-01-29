<?php
$page_title = "Edit Post";
require_once '../includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/admin-header.php';

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

// fetch gallery image
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

if (!$post) {
    die('Post not found');
}

/* Fetch categories */
$cats = $pdo->query("SELECT id, name FROM categories")->fetchAll(PDO::FETCH_ASSOC);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
            http_response_code(403);
            exit('Invalid CSRF token');
        }

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = (int) $_POST['category_id'];
    $status = $_POST['status'];

    if ($title === '' || $content === '') {
        $error = 'Title and content are required.';
    }

    //create new directory if dont exist
    $relativePath = date('Y/m/');
    $uploadDir = UPLOAD_PATH . '/' . $relativePath;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    //feature image upload process
    //variable for image path
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
    
    if(empty($error)) {
        try{
            $pdo->beginTransaction();
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

            //check slug for duplicate
            $check = $pdo->prepare("SELECT id FROM posts WHERE slug = :slug LIMIT 1");
            $check->execute([':slug' => $slug]);
            if ($check->fetch()) {
                throw new Exception('Duplicate title');
            }

            $sql = "UPDATE posts
                    SET title = :title,
                        slug = :slug,
                        content = :content,
                        featured_image = :featured_image,
                        category_id = :category_id,
                        status = :status
                    WHERE id = :id";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':slug' => $slug,
                ':content' => $content,
                'featured_image' => $featuredPath,
                ':category_id' => $category_id,
                ':status' => $status,
                ':id' => $postId
            ]);
            
            //DELETE GALLERY
            if (!empty($_POST['remove_images'])) {
                $ids = array_map('intval', $_POST['remove_images']);

                $in  = str_repeat('?,', count($ids) - 1) . '?';
                $stmt = $pdo->prepare("
                    SELECT id, file_path
                    FROM post_images
                    WHERE id IN ($in) AND post_id = ?
                ");

                $stmt->execute([...$ids, $postId]);
                $toDelete = $stmt->fetchAll(PDO::FETCH_ASSOC);

                //delete db entries
                $delStmt = $pdo->prepare("DELETE FROM post_images WHERE id IN ($in)");
                $delStmt->execute($ids);
            }

            //insert new gallery images
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

            //only unlink the files from storage after transaction passed
            if (!empty($toDelete)) {
                foreach ($toDelete as $img) {
                    $fullPath = UPLOAD_PATH . '/' . $img['file_path'];
                    if (is_file($fullPath)) {
                        unlink($fullPath);
                    }
                }
            }
            $success = 'Post updated successfully.';
            $_SESSION['flash_success'] = "Post updated successfully.";
            header('Location:' . BASE_URL . 'admin/posts');
            exit;

        }catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            //error_log($e->getMessage());
            $error = $e->getMessage() ?: 'Failed to create post.';
        }

    }
}
?>

<!-- html -->
<h1>Edit Post</h1>

<?php if ($error): ?><p style="color:red;"><?php echo $error; ?></p><?php endif; ?>
<?php if ($success): ?><p style="color:green;"><?php echo $success; ?></p><?php endif; ?>


<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf']; ?>">
    
    <br><br><label for="title"><h3>Title</h3></label><br>
    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required><br><br>

    <h3>Featured Image</h3>
    <div id="featurePreview">
        <?php if ($post['featured_image']): ?>
        <img src="<?= UPLOAD_URL .  $post['featured_image']; ?>" class="post-featured">
        <?php endif; ?>
    </div>
    <input type="file" name="featured_image" id="featureInput" accept="image/jpeg,image/png,image/webp"><br><br>

    <h3>Category</h3><br>
    <select name="category_id">
        <?php foreach ($cats as $cat): ?>
            <option value="<?php echo $cat['id']; ?>"
                <?php if ($cat['id'] == $post['category_id']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($cat['name']); ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <textarea name="content" rows="8" required><?php echo htmlspecialchars($post['content']); ?></textarea><br><br>

    <input type="file" name="gallery_images[]" multiple id="galleryInput">
    <div id="galleryPreview"></div>
    <?php if ($galleryImages): ?>
        <h3>Gallery Images</h3>
        <ul>
            <?php foreach ($galleryImages as $img): ?>
                <li>
                    <img src="<?= htmlspecialchars(UPLOAD_URL . $img['file_path']); ?>" style="max-width:120px;">
                    <label class="image-remove">
                        <input type="checkbox" name="remove_images[]" value="<?= $img['id']; ?>">
                        Remove
                    </label>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <select name="status">
        <option value="draft" <?php if ($post['status'] === 'draft') echo 'selected'; ?>>Draft</option>
        <option value="published" <?php if ($post['status'] === 'published') echo 'selected'; ?>>Published</option>
    </select><br><br>

    <button type="submit">Update Post</button>
</form>

<p><a href="<?=BASE_URL?>admin/posts">Back to posts</a></p>

<?php
require_once 'includes/admin-footer.php';