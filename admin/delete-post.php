<?php
require_once '../includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/admin-header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid ID');
}

$postId = (int) $_GET['id'];

$sql = "DELETE FROM posts WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $postId, PDO::PARAM_INT);
$stmt->execute();

header('Location: posts.php');
exit;
