<?php
$page_title = "Site Settings";
require_once '../includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/admin-header.php';

$settings = getSettings($pdo);
?>

<section class="admin-section">
       <h1>Site Settings</h1><br><br>
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

       <form method="post" action="settings-save">

       <h3>Homepage Title</h3>
       <input type="text" name="hero_title"
              value="<?= htmlspecialchars($settings['hero_title'] ?? '') ?>"><br>

       <h3>Homepage Subtitle</h3>
       <textarea name="hero_subtitle"><?= htmlspecialchars($settings['hero_subtitle'] ?? '') ?></textarea><br>

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

       <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>"><br>

       <button type="submit">Save Settings</button>
       </form>
</section>

<?php require_once 'includes/admin-footer.php'; ?>
