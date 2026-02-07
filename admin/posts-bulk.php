<?php
require_once '../includes/config.php';
require_once __DIR__ . '/includes/auth.php';

if (
    $_SERVER['REQUEST_METHOD'] !== 'POST' ||
    empty($_POST['post_ids']) ||
    empty($_POST['action'])
) {
    header('Location: posts');
    exit;
}

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

header('Location: posts');
exit;
