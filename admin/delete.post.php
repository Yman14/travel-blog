<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../includes/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid ID');
}

$postId = (int) $_GET['id'];

$sql = "DELETE FROM posts WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $postId, PDO::PARAM_INT);
$stmt->execute();

header('Location: posts.php');
exit;
