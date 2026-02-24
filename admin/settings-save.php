<?php
require_once '../scripts/config.php';
require_once __DIR__ . '/includes/auth.php';

if (
    $_SERVER['REQUEST_METHOD'] !== 'POST' ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')
) {
    http_response_code(403);
    exit;
}

$stmt = $pdo->prepare("
    UPDATE settings SET value = :value WHERE `key` = :key
");

foreach ($_POST as $key => $value) {
    if ($key === 'csrf_token') continue;

    $stmt->execute([
        ':key' => $key,
        ':value' => trim($value)
    ]);
}

$_SESSION['flash_success'] = 'Settings saved.';
header('Location: settings');
exit;
