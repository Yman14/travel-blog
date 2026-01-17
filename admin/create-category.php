<?php
require_once 'includes/admin-header.php';
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    if ($name !== '') {
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
        $stmt->execute([':name' => $name]);
        header('Location: categories.php');
        exit;
    }
}
?>

<h1>Add Category</h1>

<form method="post">
    <input type="text" name="name" required>
    <button type="submit">Create</button>
</form>

<?php require_once 'includes/admin-footer.php'; ?>
