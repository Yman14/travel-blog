<?php
//disable error display(added here incase htaccess fail)
ini_set('display_errors', 0);
error_reporting(E_ALL);

$host = 'localhost';
$db   = 'travel_blog';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed");
}
