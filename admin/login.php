<?php
session_start();
require_once '../includes/config.php';

//verify if the user is already loggoed in
if (isset($_SESSION['admin_id']) || ($_SESSION['user_role'] ?? '') === 'admin') {
    header('Location:' . BASE_URL . 'admin/dashboard');
    exit;
}

//fetch the data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username = :username LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':username', $username);
    $stmt->execute();

    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    //verify password and redirect to the dashboard 
    if ($admin && password_verify($password, $admin['password'])) {
        session_regenerate_id(true);

        $_SESSION['admin_id']  = $admin['id'];
        $_SESSION['user_role'] = 'admin';
        $_SESSION['csrf'] = bin2hex(random_bytes(32));

        header('Location: ' . BASE_URL . 'admin/dashboard');
        exit;
    }else {
        $_SESSION['flash_error'] = 'Invalid login credentials';
    }
}

$page_title = 'Admin Login';
?>

<!-- html -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars('$page_title', ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="<?=BASE_URL?>assets/css/admin.css">
</head>
<body>

<main class="admin-content">
<section class="admin-section admin-login">
    <header class="admin-section-header">
        <h1>Admin Login</h1>
    </header>

    <div class="admin-section-body">
        <?php if(isset($_SESSION['flash_error'])):?>
            <div class="alert-error">
                <?= htmlspecialchars($_SESSION['flash_error'])?>
            </div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>

        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</section>

<?php
require_once 'includes/admin-footer.php';
?>
