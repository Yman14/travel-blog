<?php
require_once '../includes/config.php';
require_once __DIR__ . '/includes/auth.php';

if (
    $_SERVER['REQUEST_METHOD'] !== 'POST' ||
    empty($_POST['post_ids']) ||
    empty($_POST['action'])
) {
    $_SESSION['flash_error'] = 'Bulk action failed.';
    header('Location: dashboard');
    exit;
}

if (
    empty($_POST['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    // http_response_code(403);
    // exit('Invalid CSRF token');
    $_SESSION['flash_error'] = 'Invalid CSRF token.';
    header('Location: dashboard');
    exit;
}

// notifi
$_SESSION['flash_success'] = '';


$ids = array_map('intval', $_POST['post_ids']);
$action = $_POST['action'];
$in = implode(',', array_fill(0, count($ids), '?'));

switch ($action) {
    case 'publish':
    case 'draft':
        $status = ($action === 'publish') ? 'published' : 'draft';
        $sql = "UPDATE posts SET status = ? WHERE id IN ($in)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_merge([$status], $ids));
        break;

    case 'delete':
        try {
            $pdo->beginTransaction();

            //fretch
            $stmt = $pdo->prepare("SELECT featured_image FROM posts WHERE id IN ($in)");
            $stmt->execute($ids);
            $featuredImages = $stmt->fetchAll(PDO::FETCH_COLUMN);

            $stmt = $pdo->prepare("SELECT file_path FROM post_images WHERE post_id IN ($in)");
            $stmt->execute($ids);
            $galleryImages = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // delete db records
            $pdo->prepare("DELETE FROM post_images WHERE post_id IN ($in)")->execute($ids);
            $pdo->prepare("DELETE FROM posts WHERE id IN ($in)")->execute($ids);

            $pdo->commit();

            //Filesystem Cleanup
            $allFiles = array_merge($featuredImages, $galleryImages);
            foreach ($allFiles as $file) {
                if (!empty($file)) {
                    $path = UPLOAD_PATH . '/' . $file;
                    if (is_file($path)) {
                        @unlink($path);
                    }
                }
            } 
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $_SESSION['flash_error'] = 'Failed to delete post. ' . $e->getMessage();
            error_log($e->getMessage());
            header('Location: ' . BASE_URL . 'admin/dashboard');
            exit;
        }
        break;

    default:
        $_SESSION['flash_error'] = 'Failed action.';
        header('Location: ' . BASE_URL . 'admin/dashboard');
        exit;
}

$_SESSION['flash_success'] = 'Bulk action updated successfully.';
header('Location: dashboard');
exit;
