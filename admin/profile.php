<?php
require_once __DIR__ . '/includes/auth.php';
$settings = getSettings($pdo);
?>
<!-- display -->
<form method="post" action="settings-save">
    <h3>Website Name</h3>
    <input type="text" name="website_name" value="<?=htmlspecialchars($settings['website_name'] ?? '')?>"><br>
    <h3>Owner Name</h3>
    <input type="text" name="site_author" value="<?=htmlspecialchars($settings['site_author'] ?? '')?>"><br><br>


    <h3>Homepage Title</h3>
    <input type="text" name="hero_title"
            value="<?= htmlspecialchars($settings['hero_title'] ?? '') ?>"><br>

    <h3>Homepage Subtitle</h3>
    <textarea name="hero_subtitle"><?= htmlspecialchars($settings['hero_subtitle'] ?? '') ?></textarea><br><br>

    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <button type="submit">Save Settings</button>
</form>
