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


//handle form submission
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = (int) $_POST['category_id'];
    $status = $_POST['status'];

    if ($title === '' || $content === '') {
        $error = 'Title and content are required.';
    } else {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

        $sql = "INSERT INTO posts (title, slug, content, category_id, status)
                VALUES (:title, :slug, :content, :category_id, :status)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':slug', $slug);
        $stmt->bindValue(':content', $content);
        $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindValue(':status', $status);

        $stmt->execute();

        $success = 'Post created successfully.';
    }
}

