<?php
require_once '../includes/config.php';

$page_title = 'Temp';
require_once '../includes/header.php';
?>

<!-- displau -->
<div class="main-content">
    <div class="maintenance-container">
        <div class="glass-card">
            <!-- Skeleton Header -->
            <div class="skeleton skeleton-title">Hello World!</div>
            
            <!-- Main Message -->
            <div class="status-content">
                <h1 class="glitch-text" data-text="ADVENTURE PENDING">ADVENTURE PENDING</h1>
                <p>Working in progress. Stay tuned for new stories, guides, and inspiration.</p>
            </div>

            <!-- Skeleton Content Blocks -->
            <div class="skeleton-grid">
                <div class="skeleton skeleton-block"></div>
                <div class="skeleton skeleton-block"></div>
                <div class="skeleton skeleton-block"></div>
            </div>

            <a href="<?= BASE_URL ?>" class="btn-modern">Return to Home</a>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>