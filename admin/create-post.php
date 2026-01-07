<?php
session_start();

//only logged-in admins can access this page
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../includes/db.php';
require_once '../includes/header.php';

//fetch categories
$sql = "SELECT id, name FROM categories ORDER BY name ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);


