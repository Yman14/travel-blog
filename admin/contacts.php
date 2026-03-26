<?php
require_once __DIR__ . '/includes/auth.php';
$settings = getSettings($pdo);
?>
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
