<?php
require_once '../includes/config.php';
require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['flash_error'] = 'POST METHOD ERROR.';
    header('Location: categories');
    exit;
}

$id = (int) $_POST['id'];
$name = trim($_POST['name']);

if ($id <= 0 || $name === '') {
    $_SESSION['flash_error'] = 'Failed.';
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

$_SESSION['flash_success'] = 'Updated successfully.';
header('Location: categories');
exit;