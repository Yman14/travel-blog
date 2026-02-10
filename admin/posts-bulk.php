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
        $sql = "UPDATE posts SET status = 'published' WHERE id IN ($in)";
        break;

    case 'draft':
        $sql = "UPDATE posts SET status = 'draft' WHERE id IN ($in)";
        break;

    case 'delete':
        $sql = "DELETE FROM posts WHERE id IN ($in)";
        break;

    default:
        header('Location: posts');
        exit;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($ids);

$_SESSION['flash_success'] = 'Bulk action ' . $_SESSION['flash_success'] . 'successfully.';
header('Location: dashboard');
exit;
