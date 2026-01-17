<?php
$page_title = 'Create Category';
require_once 'includes/admin-header.php';
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    if ($name !== '') {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        $sql = "INSERT INTO categories (name, slug) VALUES (:name, :slug)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':slug', $slug);
        $stmt->execute();
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
