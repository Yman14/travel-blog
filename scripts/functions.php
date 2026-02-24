<?php

// Helper to fetch site settings from the database.
// function getSettings(PDO $pdo): array {
//     $stmt = $pdo->query("SELECT `key`, `value` FROM settings");
//     return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
// }

/**
 * converts an uploaded image to webp format.
 * Reduces file size significantly for better seo and speed.
 */
function convertToWebP($source, $quality = 80, $maxWidth = 1200) {
    if (!file_exists($source)) return false;

    $info = pathinfo($source);
    $destination = $info['dirname'] . '/' . $info['filename'] . '.webp';
    $extension = strtolower($info['extension']);

    // Load the original
     if (in_array($extension, ['jpeg', 'jpg', 'jfif'])) {
        $image = imagecreatefromjpeg($source);
    } elseif ($extension === 'png') {
        $image = imagecreatefrompng($source);
        // preserve png transparency
        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);
    } else {
        return $info; // sip unsupported formats
    }

    // reizes logic
    $width = imagesx($image);
    $height = imagesy($image);

    if ($width > $maxWidth) {
        $newWidth = $maxWidth;
        $newHeight = floor($height * ($maxWidth / $width));
        
        // Create a new blank canvas with the smaller dimensions
        $destImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Copy and resize original onto the small canvas
        imagecopyresampled($destImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    } else {
        $destImage = $image;
    }

    // save  n free up memory
    if (imagewebp($destImage, $destination, $quality)) {
        imagedestroy($image);
        if ($destImage !== $image) imagedestroy($destImage);
        unlink($source);
        return $info['filename'] . '.webp';
    }

    return false; 
}