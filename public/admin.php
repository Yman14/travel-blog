<?php
require_once __DIR__ . '/../includes/config.php';
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
    '/posts' => 'posts.php',
    '/create-post' => 'create-post.php',
    '/edit-post' => 'edit-post.php',
    '/delete-post' => 'delete-post.php',
    '/categories' => 'categories.php',
    '/create-category' => 'create-category.php',
    '/delete-category' => 'delete-category.php',
    '/logout' => 'logout.php',
];




if (isset($routes[$request])) {
    require_once __DIR__ . '/../admin/' . $routes[$request];
} else {
    http_response_code(404);
    echo "Admin page not found";
}
