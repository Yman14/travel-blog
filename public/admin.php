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
switch ($request) {
    case '/':
    case '/login':
        require_once __DIR__ . '/../admin/login.php';
        break;

    case '/dashboard':
        require_once __DIR__ . '/../admin/dashboard.php';
        break;

    default:
        http_response_code(404);
        echo "Admin page not found";
        break;
}
