<?php
// Eror Display
ini_set('display_errors', 0);
error_reporting(E_ALL);

//session security
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
// Force cookies to only be sent over HTTPS
ini_set('session.cookie_secure', 1);

// Define Paths
// URL paths (for HTML)
define('BASE_URL', 'http://localhost/');
define('UPLOAD_URL', BASE_URL . 'assets/images/uploads/');

// Filesystem paths (for PHP)
define('ROOT_PATH', dirname(__DIR__));
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/assets/images/uploads');
define('INCLUDE_PATH', ROOT_PATH . '/scripts/');

// Database Credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'lily_db');
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

//settings
function getSettings(PDO $pdo): array {
    $stmt = $pdo->query("SELECT `key`, `value` FROM settings");
    return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
}

//fetch settings
$settings = getSettings($pdo);
