<?php
require_once '../includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/admin-header.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['flash_error'] = "Delete not successful.";
    header('Location:' . BASE_URL .  'admin/categories');
    exit;
}

$id = (int) $_POST['id'];

/* Check dependency */
$stmt = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE category_id = :id");
$stmt->execute([':id' => $id]);
$count = $stmt->fetchColumn();

if ($count > 0) {
    $_SESSION['flash_error'] = "Cannot delete: This category still has active posts.";
    header('Location: categories.php');
    exit;
}

$pdo->prepare("DELETE FROM categories WHERE id = :id")->execute([':id' => $id]);

header('Location:' . BASE_URL .  'admin/categories');
exit;