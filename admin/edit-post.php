<?php
$page_title = "Edit Post";
require_once '../scripts/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once INCLUDE_PATH . 'functions.php';
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
    if (
        empty($_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')
    ) {
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

    if (!in_array($status, ['draft','published'], true)) {
        throw new Exception('Invalid status');
    }

    //get the old featured image before a new one is uploaded for unlinking later
    $oldFeaturedImage = $post['featured_image'] ?? null;

    //delete files holder
    $toDelete = [];

    //create new directory if dont exist
    $relativePath = date('Y/m/');
    $uploadDir = UPLOAD_PATH . '/' . $relativePath;
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
        throw new RuntimeException('Failed to create upload directory');
    }
    // Upload featured and gallery images to a temporary folder
    //temp file
    $tmpFeatured = null;
    $featuredPath = null;
    $tmpUploads = [];
    $galleryPath = null;
    $tmpDir = UPLOAD_PATH . '/tmp/' . session_id() . '/' . $postId;
    if (!is_dir($tmpDir) && !mkdir($tmpDir, 0755, true)) {
        throw new RuntimeException('Failed to create temporary upload directory.');
    }


    //feature image upload process
    //variable for image path
    
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
            if (!is_uploaded_file($_FILES['featured_image']['tmp_name'])) {
                throw new RuntimeException('Invalid upload source');
            }
            $tmpFeatured = $tmpDir . '/' . $filename;
            if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $tmpFeatured)) {
                //optimize image
                $db_filename = convertToWebP($tmpFeatured);
                if(!$db_filename) {
                    $error = 'Invalid featured image type.';
                    if (!empty($tmpFeatured)) {
                        unlink($tmpFeatured);
                    }
                }

                //update the path name
                $tmpFeatured = $tmpDir . '/' . $db_filename;
                
                // Save $db_filename to your database
                $featuredPath = $relativePath . $db_filename;
            }
        }
    }
    
    if(empty($error)) {
        try{
            //temp untewsted
            if (!empty($_POST['remove_featured']) && $featuredPath !== null) {
                throw new Exception('Cannot remove and upload featured image simultaneously.');
            }

            $pdo->beginTransaction();
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

            $sql = "UPDATE posts
                    SET title = :title,
                        slug = :slug,
                        content = :content,
                        category_id = :category_id,
                        status = :status";

            //check if the new image is uploaded and if not dont include in the SET
            if($featuredPath !== null){
                $sql .= ", featured_image = :featured_image";
            }
            $sql .= " WHERE id = :id";
            
            //preparation so that new featured_image can be added later on
            $params = [
                ':title' => $title,
                ':slug' => $slug,
                ':content' => $content,
                ':category_id' => $category_id,
                ':status' => $status,
                ':id' => $postId
            ];
            if ($featuredPath !== null) {
                $params[':featured_image'] = $featuredPath;
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            //delete featured image
            if (!empty($_POST['remove_featured']) && $featuredPath === null) {
                $stmt = $pdo->prepare("
                    UPDATE posts
                    SET featured_image = NULL
                    WHERE id = :id
                ");
                $stmt->execute([':id' => $postId]);
            }
            

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

                //cehck numbers of uploaded imags
                if (count($_FILES['gallery_images']['name']) > 10) {
                    throw new Exception('Maximum 10 images allowed');
                }

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
                    $tmpPath = $tmpDir . '/' . $name;
                    if (move_uploaded_file($tmp, $tmpPath)) {
                        //optimize image
                        $db_filename = convertToWebP($tmpPath);
                        
                        if(!$db_filename) {
                            $galleryErrors[] = $_FILES['gallery_images']['name'][$i] . ' is an invalid image type.';
                            if (!empty($tmpPath)) {
                                unlink($tmpPath);
                            }
                        }

                        //update the path name
                        $tmpPath = $tmpDir . '/' . $db_filename;
                        $tmpUploads[] = $tmpPath;
                        
                        // Save $db_filename to your database
                        $galleryPath = $relativePath . $db_filename;
                    }

                    $stmt = $pdo->prepare("
                        INSERT INTO post_images (post_id, file_path)
                        VALUES (:post_id, :path)
                    ");
                    $stmt->execute([
                        ':post_id' => $postId,
                        ':path' => $galleryPath
                    ]);
                }

                if (!empty($galleryErrors)) {
                    throw new Exception(implode('; ', $galleryErrors));
                }      
            }

            //successful process
            $pdo->commit();

            //save featured images
            if ($tmpFeatured) {
                rename($tmpFeatured, $uploadDir . '/' . basename($tmpFeatured));
            }

            //save uploaded images
            foreach ($tmpUploads as $tmpFile) {
                rename($tmpFile, $uploadDir . '/' . basename($tmpFile));
            } 

            //only unlink the files from storage after transaction passed

            // FEATURED IMAGE CLEANUP (post-commit)
            if ($oldFeaturedImage) {
                $oldPath = UPLOAD_PATH . '/' . $oldFeaturedImage;

                //replaced by new image
                if ($featuredPath !== null) {
                    if (is_file($oldPath)) {
                        unlink($oldPath);
                    }
                }

                //explicitly removed without replacement
                elseif (!empty($_POST['remove_featured'])) {
                    if (is_file($oldPath)) {
                        unlink($oldPath);
                    }
                }
            }
            //for gallery image
            if (!empty($toDelete)) {
                foreach ($toDelete as $img) {
                    $fullPath = UPLOAD_PATH . '/' . $img['file_path'];
                    if (is_file($fullPath)) {
                        unlink($fullPath);
                    }
                }
            }
            if($tmpDir){
                @rmdir($tmpDir);
                @rmdir(dirname($tmpDir));
            }
            
            $success = 'Post updated successfully.';
            $_SESSION['flash_success'] = "Post updated successfully.";
            header('Location:' . BASE_URL . 'admin/dashboard');
            exit;

        }catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            
            //unlinking temp files
            if ($tmpFeatured && is_file($tmpFeatured)) {
                unlink($tmpFeatured);
            }

            if (!empty($tmpUploads)) {
                foreach ($tmpUploads as $file) {
                    unlink($file);
                }
            }

            //error_log($e->getMessage());
            $error = $e->getMessage() ?: 'Failed to create post.';
        }

    }
}
?>

<!-- html -->
<section class="admin-section">
    <h1>Edit Post</h1>

    <?php if ($error): ?><p style="color:red;"><?php echo $error; ?></p><?php endif; ?>
    <?php if ($success): ?><p style="color:green;"><?php echo $success; ?></p><?php endif; ?>


    <form id="form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
        
        <br><br><label for="title"><h3>Title</h3></label><br>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required><br><br>

        <h3>Featured Image</h3>
        <div id="featurePreview">
            <div class="media-item">
                <?php if ($post['featured_image']): ?>
                <img src="<?= htmlspecialchars(UPLOAD_URL .  $post['featured_image']); ?>" loading="lazy">
                <input type="checkbox" name="remove_featured" value="1" class="image-remove">
                <?php endif; ?>
            </div>
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
        
        <h3>Gallery Images</h3>
        <div id="galleryPreview" class="media-grid"></div>
        <?php if ($galleryImages): ?>
            <ul class="media-grid">
                <?php foreach ($galleryImages as $img): ?>
                    <li class="media-item">
                        <img src="<?= htmlspecialchars(UPLOAD_URL . $img['file_path']); ?>" loading="lazy">
                        <input type="checkbox" name="remove_images[]" value="<?= $img['id']; ?>" class="image-remove">
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <input type="file" name="gallery_images[]" multiple id="galleryInput">

        <select name="status">
            <option value="draft" <?php if ($post['status'] === 'draft') echo 'selected'; ?>>Draft</option>
            <option value="published" <?php if ($post['status'] === 'published') echo 'selected'; ?>>Published</option>
        </select><br><br>

        <button type="submit">Update Post</button>
    </form>

    <p><a href="<?=BASE_URL?>admin/posts">Manage All Posts</a></p>
</section>
<?php
require_once 'includes/admin-footer.php';