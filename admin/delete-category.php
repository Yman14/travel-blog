<?php
require_once 'includes/admin-header.php';
require_once '../includes/db.php';

$id = (int) $_GET['id'];
$pdo->prepare("DELETE FROM categories WHERE id = :id")->execute([':id' => $id]);

header('Location: categories.php');
exit;
