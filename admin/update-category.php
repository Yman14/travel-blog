<?php
require_once '../includes/config.php';
require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['flash_error'] = 'POST METHOD ERROR.';
    header('Location: categories');
    exit;
}

if (
        empty($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        http_response_code(403);
        exit('Invalid CSRF token');
    }

$id = (int) $_POST['id'];
$name = trim($_POST['name']);

if ($id <= 0 || $name === '') {
    $_SESSION['flash_error'] = 'Failed. Name invalid.';
    header('Location: categories');
    exit;
}

//generate slug
$slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));

// Check if the name ALREADY exists
$check = $pdo->prepare("SELECT id FROM categories WHERE slug = :slug");
$check->execute([':slug' => $slug]);
if ($check->fetch()) {
    $_SESSION['flash_error'] = "The category '$name' already exists. You cannot create duplicates.";
    header('Location:' . BASE_URL . 'admin/categories');
    exit;
}else {
    $stmt = $pdo->prepare("
        UPDATE categories
        SET name = :name, slug = :slug
        WHERE id = :id
    ");
    $stmt->execute([
        ':name' => $name,
        ':slug' => $slug,
        ':id' => $id
    ]);

    $_SESSION['flash_success'] = 'Updated successfully.';
    header('Location: categories');
    exit;
}

$_SESSION['flash_error'] = "Update failed to process.";
header('Location:' . BASE_URL . 'admin/categories');
exit;
