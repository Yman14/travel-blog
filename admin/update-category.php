<?php
require_once '../includes/config.php';
require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: categories');
    exit;
}

$id = (int) $_POST['id'];
$name = trim($_POST['name']);

if ($id <= 0 || $name === '') {
    header('Location: categories');
    exit;
}

$stmt = $pdo->prepare("
    UPDATE categories
    SET name = :name
    WHERE id = :id
");
$stmt->execute([
    ':name' => $name,
    ':id' => $id
]);

header('Location: categories');
exit;