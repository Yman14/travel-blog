<?php
session_start();

//access protection
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../includes/db.php';

//validate id url
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid post ID');
}

$postId = (int) $_GET['id'];

/* Fetch post */
$sql = "SELECT * FROM posts WHERE id = :id LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $postId, PDO::PARAM_INT);
$stmt->execute();
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die('Post not found');
}
