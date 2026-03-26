<?php
$page_title = "Site Settings";
require_once '../scripts/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/admin-header.php';

$settings = getSettings($pdo);
?>

<section class="admin-section">
    <h1>Site Settings</h1><br><br>
    <div class="page-btns">
        <button onclick="loadContent('profile')">Profile</button>
        <button onclick="loadContent('contacts')">Contacts</button>
    </div>
    <br><br>
    <div id="loader" style="display: none;">
        <div class="spinner"></div>
        <p>Loading...</p>
    </div>
    <!-- check for notifcation -->
    <?php if (isset($_SESSION['flash_success'])): ?>
    <div class="notify-success">
        <?= htmlspecialchars($_SESSION['flash_success']); ?>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash_error'])): ?>
    <div class="alert-error">
        <?= htmlspecialchars($_SESSION['flash_error']); ?>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>
    <div id="display-area"></div>
</section>

<?php require_once 'includes/admin-footer.php'; ?>
