<?php
session_start();
require_once '../includes/config.php';

//verify if the user is already loggoed in
if (isset($_SESSION['admin_id']) || ($_SESSION['user_role'] ?? '') === 'admin') {
    header('Location:' . BASE_URL . 'admin/dashboard');
    exit;
}

//verify if admin exists
$stmt = $pdo->query("SELECT COUNT(*) FROM admins WHERE role = 'admin'");
$adminExists = $stmt->fetchColumn() > 0;
if (!$adminExists) {
    header('Location:' . BASE_URL . 'admin/setup');
    exit;
}

//delete old records from login attempt
$pdo->query("DELETE FROM login_attempts WHERE attempted_at < (NOW() - INTERVAL 1 HOUR)");

//fetch the data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //check login attemps
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM login_attempts
        WHERE ip_address = ?
        AND attempted_at > (NOW() - INTERVAL 5 MINUTE)
    ");
    $stmt->execute([$_SERVER['REMOTE_ADDR']]);

    if ($stmt->fetchColumn() > 5) {
         $_SESSION['flash_error'] = 'Too many failed attempts. Try again in 5 minutes.';
        header('Location: ' . BASE_URL . 'admin/login');
        exit;
    }


    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username = :username LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':username', $username);
    $stmt->execute();

    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        //clear record for this ip
        $clearStmt = $pdo->prepare("DELETE FROM login_attempts WHERE ip_address = ?");
        $clearStmt->execute([$_SERVER['REMOTE_ADDR']]);

        // replace the current session id with a new 
        session_regenerate_id(true);

        $_SESSION['admin_id']  = $admin['id'];
        $_SESSION['user_role'] = 'admin';
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        header('Location: ' . BASE_URL . 'admin/dashboard');
        exit;
    }else {
        $stmt = $pdo->prepare("
            INSERT INTO login_attempts (ip_address, attempted_at)
            VALUES (?, NOW())
        ");
        $stmt->execute([$_SERVER['REMOTE_ADDR']]);

        $_SESSION['flash_error'] = 'Invalid login credentials';
        header('Location: ' . BASE_URL . 'admin/login');
        exit;
    }
}

$page_title = 'Admin Login';
require_once __DIR__ . '/includes/admin-header.php';
?>

<!-- html -->
<section class="admin-login">
    <img src="<?=BASE_URL?>assets/images/login-bg.webp" class="bg">
    <div class="panel">
        <header class="panel-header">
            <h1 class="site-name"><?=$settings['website_name'] ?? 'WELCOME'; ?></h1>
            <h1>LOGIN</h1>
        </header>
        <div class="panel-body">
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
    </div>
</section>

<?php
require_once 'includes/admin-footer.php';
?>
