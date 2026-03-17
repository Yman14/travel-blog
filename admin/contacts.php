<?php
require_once __DIR__ . '/includes/auth.php';
$settings = getSettings($pdo);
?>

<section class="admin-section">
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
    
    <!-- display -->
    <form method="post" action="settings-save">
        <h3>Contact Email</h3>
        <input type="email" name="contact_email"
                value="<?= htmlspecialchars($settings['contact_email'] ?? '') ?>"><br><br>

        <h3>Social Links</h3><br>

        <input type="url" name="facebook" placeholder="Facebook"
                value="<?= htmlspecialchars($settings['facebook'] ?? '') ?>"><br>

        <input type="url" name="instagram" placeholder="Instagram"
                value="<?= htmlspecialchars($settings['instagram'] ?? '') ?>"><br>

        <input type="url" name="twitter" placeholder="Twitter"
                value="<?= htmlspecialchars($settings['twitter'] ?? '') ?>"><br>

        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <button type="submit">Save Settings</button>
    </form>
</section>
