</main>

<footer class="container site-footer">
    <nav class="footer-nav">
        <a href="<?= BASE_URL ?>about.php">ABOUT</a>
        <a href="<?= BASE_URL ?>contact.php">CONTACT</a>
        <a href="<?= BASE_URL ?>privacy-policy.php">PRIVACY POLICY</a>
    </nav>

    <p>&copy; <?= date('Y'); ?> <?=htmlspecialchars($settings['website_name'] ?? 'Travel Blog'); ?></p>
</footer>
<script src="<?=BASE_URL . 'assets/js/main.js'?>" defer></script>
</body>
</html>
