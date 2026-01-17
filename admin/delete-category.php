<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/admin-header.php';

$id = (int) $_GET['id'];
$pdo->prepare("DELETE FROM categories WHERE id = :id")->execute([':id' => $id]);

header('Location: categories.php');
exit;
