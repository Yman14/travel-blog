<?php
require_once '../includes/config.php';
require_once __DIR__ . '/includes/auth.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    http_response_code(400);
    exit('Invalid post ID');
}

$postId = (int) $_GET['id'];

try {
    $pdo->beginTransaction();

    //Fetch featured image
    $stmt = $pdo->prepare(
        "SELECT featured_image FROM posts WHERE id = :id LIMIT 1"
    );
    $stmt->execute([':id' => $postId]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        throw new Exception('Post not found');
    }

    // Fetch gallery images
    $stmt = $pdo->prepare(
        "SELECT file_path FROM post_images WHERE post_id = :id"
    );
    $stmt->execute([':id' => $postId]);
    $galleryImages = $stmt->fetchAll(PDO::FETCH_COLUMN);

    //Delete gallery DB rows
    $stmt = $pdo->prepare(
        "DELETE FROM post_images WHERE post_id = :id"
    );
    $stmt->execute([':id' => $postId]);

    // Delete post row
    $stmt = $pdo->prepare(
        "DELETE FROM posts WHERE id = :id"
    );
    $stmt->execute([':id' => $postId]);

    $pdo->commit();

    // Filesystem cleanup (AFTER commit)

    // Featured image
    if (!empty($post['featured_image'])) {
        $path = UPLOAD_PATH . '/' . $post['featured_image'];
        if (is_file($path)) {
            @unlink($path);
        }
    }

    // Gallery images
    foreach ($galleryImages as $img) {
        $path = UPLOAD_PATH . '/' . $img;
        if (is_file($path)) {
            @unlink($path);
        }
    }

    $_SESSION['flash_success'] = 'Post deleted successfully.';
    
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $_SESSION['flash_error'] = 'Failed to delete post.';
    // error_log($e->getMessage());
}

header('Location: ' . BASE_URL . 'admin/posts');
exit;
