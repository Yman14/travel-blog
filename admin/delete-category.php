<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/admin-header.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: categories.php');
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

header('Location: categories.php');
exit;