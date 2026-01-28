<?php
// Eror Display
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Define Paths
// URL paths (for HTML)
define('BASE_URL', '/');
define('UPLOAD_URL', BASE_URL . 'assets/images/uploads/');

// Filesystem paths (for PHP)
define('ROOT_PATH', dirname(__DIR__));
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/assets/images/uploads');

// Database Credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'travel_blog');
define('DB_USER', 'root');
define('DB_PASS', '');

//PDO Connection
$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    error_log($e->getMessage()); 
    die("Database connection failed. Please try again later.");
}