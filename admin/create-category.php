<?php
$page_title = 'Create Category';
require_once '../includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/admin-header.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    if ($name !== '') {
        //generate slug
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        // Check if the name ALREADY exists
        $check = $pdo->prepare("SELECT id FROM categories WHERE slug = :slug");
        $check->execute([':slug' => $slug]);
        if ($check->fetch()) {
        $error = "The category '$name' already exists. You cannot create duplicates.";
        }else {
            $sql = "INSERT INTO categories (name, slug) VALUES (:name, :slug)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':slug', $slug);
            $stmt->execute();

            $_SESSION['flash_success'] = "Category created successfully.";
            header('Location:' . BASE_URL . 'admin/categories');
        exit;
        }
    }
}
?>

<h1>Add Category</h1>
<!-- Logic: Only show the div if there is an actual error -->
<?php if (!empty($error)): ?>
    <div class="alert-error">
        <strong>Error:</strong> <?= htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<form method="post">
    <input type="text" name="name" required>
    <button type="submit">Create</button>
</form>

<?php require_once 'includes/admin-footer.php'; ?>
