<?php
session_start();

//access protection
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../includes/db.php';

//fetch datas
$sql = "SELECT posts.id, posts.title, posts.status, posts.created_at, categories.name AS category
        FROM posts
        JOIN categories ON posts.category_id = categories.id
        ORDER BY posts.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>