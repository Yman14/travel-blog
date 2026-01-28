<?php
session_start();
require_once '../includes/config.php';

$error = '';

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
        $error = 'Invalid login credentials';
    }
}

$page_title = 'Admin Login';

require_once 'includes/admin-header.php';
?>

<!-- html -->
<h1>Admin Login</h1>

<?php if ($error): ?>
    <p class="alert-error"><?php echo $error; ?></p>
<?php endif; ?>

<form method="post">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>

<?php
require_once 'includes/admin-footer.php';
?>
