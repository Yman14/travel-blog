<?php
$page_title = 'Create Category';
require_once '../scripts/config.php';
require_once __DIR__ . '/includes/auth.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        empty($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        http_response_code(403);
        exit('Invalid CSRF token');
    }
    
    $name = trim($_POST['name'] ?? '');
    if ($name !== '') {
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
            $sql = "INSERT INTO categories (name, slug) VALUES (:name, :slug)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':slug', $slug);
            $stmt->execute();

            $_SESSION['flash_success'] = "Category created successfully.";
            header('Location:' . BASE_URL . 'admin/categories');
            exit;
        }
    }
}
header('Location:' . BASE_URL . 'admin/categories');
exit;