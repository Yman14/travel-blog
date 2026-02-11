<?php
require_once '../includes/config.php';

header("Content-Type: application/xml; charset=utf-8");

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

$stmt = $pdo->query("
    SELECT slug, created_at 
    FROM posts 
    WHERE status = 'published'
");

while ($row = $stmt->fetch()) {
    echo '<url>';
    echo '<loc>' . BASE_URL . $row['slug'] . '</loc>';
    echo '<lastmod>' . date('Y-m-d', strtotime($row['created_at'])) . '</lastmod>';
    echo '</url>';
}

echo '</urlset>';