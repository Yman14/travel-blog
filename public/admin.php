<?php
require_once '../scripts/config.php';
// Get the path relative to /admin
$request = $_SERVER['REQUEST_URI'];

// Remove query string
$request = parse_url($request, PHP_URL_PATH);

// Remove trailing slash
$request = rtrim($request, '/');

// Remove BASE_URL + admin prefix
$request = str_replace(rtrim(BASE_URL, '/') . '/admin', '', $request);

// Ensure it starts with a leading slash
if ($request === '') {
    $request = '/';
}

// Routing
$routes = [
    '/' => 'login.php',
    '/login' => 'login.php',
    '/dashboard' => 'dashboard.php',
    '/posts-bulk' => 'posts-bulk.php',
    '/posts' => 'posts.php',
    '/create-post' => 'create-post.php',
    '/edit-post' => 'edit-post.php',
    '/delete-post' => 'delete-post.php',
    '/categories' => 'categories.php',
    '/create-category' => 'create-category.php',
    '/update-category' => 'update-category.php',
    '/delete-category' => 'delete-category.php',
    '/settings' => 'settings.php',
    '/settings-save' => 'settings-save.php',
    '/logout' => 'logout.php',
    '/404-admin' => 'admin404.php',
];




if (isset($routes[$request])) {
    require_once __DIR__ . '/../admin/' . $routes[$request];
} else {
    http_response_code(404);
    HEADER('Location: '. BASE_URL . 'admin/404-admin');
    exit;
    // echo "Admin page not found";
}
