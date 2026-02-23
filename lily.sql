-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for travel_blog
CREATE DATABASE IF NOT EXISTS `travel_blog` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `travel_blog`;

-- Dumping structure for table travel_blog.admins
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','editor') DEFAULT 'admin',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table travel_blog.admins: ~1 rows (approximately)
REPLACE INTO `admins` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
	(3, 'lily', 'admin@email.com', '$2y$10$fGNjpQFYeOJBumbXiDgS/.IjmmLlbMkbhZT9KKu5nRIYcY65BoLqq', 'admin', '2026-02-23 15:54:32');

-- Dumping structure for table travel_blog.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table travel_blog.categories: ~5 rows (approximately)
REPLACE INTO `categories` (`id`, `name`, `slug`, `created_at`) VALUES
	(1, 'Japan', 'japan', '2026-01-05 09:44:54'),
	(3, 'Korean', 'korean', '2026-01-15 07:47:56'),
	(4, 'Sample Cat', 'sample-cat', '2026-01-16 08:34:15'),
	(15, 'Nami', 'nami', '2026-01-17 06:57:44'),
	(16, 'Delete', 'delete', '2026-01-17 08:02:22');

-- Dumping structure for table travel_blog.login_attempts
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) DEFAULT NULL,
  `attempted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table travel_blog.login_attempts: ~0 rows (approximately)

-- Dumping structure for table travel_blog.posts
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `category_id` int NOT NULL,
  `status` enum('draft','published') DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `fk_category` (`category_id`),
  CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table travel_blog.posts: ~7 rows (approximately)
REPLACE INTO `posts` (`id`, `title`, `slug`, `content`, `featured_image`, `category_id`, `status`, `created_at`, `meta_title`, `meta_description`) VALUES
	(12, 'SAMPLE FOR IMAGE', 'sample-for-image', 'sample for image iploads and testing', '2026/01/1768986382_42e658eca28b.png', 16, 'published', '2026-01-21 09:06:22', NULL, NULL),
	(20, 'multiple images upload 2', 'multiple-images-upload-2', 'testing multiple images upload', '2026/01/1769666637_76283b19e18b.jpg', 16, 'published', '2026-01-21 14:07:17', NULL, NULL),
	(21, 'multiple images upload 33', 'multiple-images-upload-33', 'testing multiple images upload 3', '2026/01/1769005419_31c9b9b1b128.jpg', 16, 'published', '2026-01-21 14:23:39', NULL, NULL),
	(28, 'Lamitan', 'lamitan', 'When despair for the world grows in me and I wake in the night at the least sound in fear of what my life and my children’s lives may be, I go and lie down where the wood drake rests in his beauty on the water, and the great heron feeds. I come into the peace of wild things who do not tax their lives with forethought of grief. I come into the presence of still water. And I feel above me the day-blind stars waiting with their light. For a time I rest in the grace of the world, and am free.\r\n\r\nThe Peace of Wild Things, Wendell Berry', '2026/01/1769760688_944dc965355a.jpg', 16, 'published', '2026-01-29 10:06:24', NULL, NULL),
	(62, 'The Flying Bird and Fish', 'the-flying-bird-and-fish', 'The furthest distance in the world is not the distance between opposite sides of the world. It is that you don’t know that I love you, when I stand in front of you.\r\nThe furthest distance in the world is not that you don’t know I love you when I stand in front of you. It is when I cannot say I love you, when I love you so madly.\r\n\r\nThe furthest distance in the world is not that I cannot say I love you, when I love you so madly. It is that I have to bury it in my heart, despite the unbearable yearning.\r\n\r\nThe furthest distance in the world is not that I have to bury it in my heart despite the unbearable yearning. It is when we cannot be together, even when we love each other.\r\n\r\nThe furthest distance in the world is not that we cannot be together, when we love each other. It is when we turn a blind eye to it, despite knowing true love conquers all.\r\n\r\nThe furthest distance in the world is not the distance between two distant trees. It is when branches cannot depend on each other in the wind, despite growing from the same root.\r\n\r\nThe furthest distance in the world is not when branches cannot depend on each other in the wind. It is when the trajectories of stars cannot cross, even when the blinking stars look at each other.\r\n\r\nThe furthest distance in the world is not when the trajectories of stars cannot cross. It is when they are unable to find each other after crossing trajectories.\r\n\r\nThe furthest distance in the world is not being unable to find each other. It is when we are doomed not to love, even when we coincidentally meet.\r\n\r\nThe furthest distance in the world is the love between the bird and fish. One is flying in the sky, the other is looking upon the sea.\r\n\r\n-Zhang Ye\'s Version', '2026/02/1771064587_67274fafc6e5.webp', 16, 'published', '2026-02-04 04:58:13', NULL, NULL),
	(81, 'logo', 'logo', 'site brand images', '2026/02/1771219052_86a12fc511d3.webp', 16, 'draft', '2026-02-16 05:17:32', NULL, NULL),
	(87, 'Login Background Image', 'login-background-image', 'use for login page bg wallpaper', '2026/02/1771495701_c0671faf8d89.webp', 16, 'draft', '2026-02-19 10:08:22', NULL, NULL);

-- Dumping structure for table travel_blog.post_images
CREATE TABLE IF NOT EXISTS `post_images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `post_id` int NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_post_id` (`post_id`),
  CONSTRAINT `fk_post_images_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=219 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table travel_blog.post_images: ~33 rows (approximately)
REPLACE INTO `post_images` (`id`, `post_id`, `file_path`, `alt_text`, `sort_order`, `created_at`) VALUES
	(3, 12, '2026/01/1768986382_85ce8ba4.jpg', NULL, 0, '2026-01-21 09:06:22'),
	(4, 12, '2026/01/1768986382_ab4fe094.png', NULL, 0, '2026-01-21 09:06:22'),
	(37, 21, '2026/01/1769005419_785f9072.jpg', NULL, 0, '2026-01-21 14:23:39'),
	(38, 21, '2026/01/1769005419_616ce31e.jpg', NULL, 0, '2026-01-21 14:23:39'),
	(39, 21, '2026/01/1769005419_ba2c467a.jpg', NULL, 0, '2026-01-21 14:23:39'),
	(71, 28, '2026/01/1769681184_f6a9ee6c.jpg', NULL, 0, '2026-01-29 10:06:24'),
	(72, 28, '2026/01/1769681184_ad92ab64.jpg', NULL, 0, '2026-01-29 10:06:24'),
	(73, 28, '2026/01/1769681184_39f044c1.png', NULL, 0, '2026-01-29 10:06:24'),
	(74, 28, '2026/01/1769681184_35de9e54.jpg', NULL, 0, '2026-01-29 10:06:24'),
	(75, 28, '2026/01/1769681184_d460d7d7.jpg', NULL, 0, '2026-01-29 10:06:24'),
	(76, 28, '2026/01/1769681184_277894a6.jpg', NULL, 0, '2026-01-29 10:06:24'),
	(77, 28, '2026/01/1769681184_f5cc1314.jpg', NULL, 0, '2026-01-29 10:06:24'),
	(78, 28, '2026/01/1769681184_b78099aa.jpg', NULL, 0, '2026-01-29 10:06:24'),
	(79, 28, '2026/01/1769681184_213ee64e.jpg', NULL, 0, '2026-01-29 10:06:24'),
	(80, 28, '2026/01/1769681184_bdaf9885.jpg', NULL, 0, '2026-01-29 10:06:24'),
	(81, 28, '2026/01/1769681184_ef47024b.png', NULL, 0, '2026-01-29 10:06:24'),
	(82, 28, '2026/01/1769681184_7e7368a1.jpg', NULL, 0, '2026-01-29 10:06:24'),
	(83, 28, '2026/01/1769681184_41ed3772.jpg', NULL, 0, '2026-01-29 10:06:24'),
	(84, 28, '2026/01/1769681184_90c169e0.jpg', NULL, 0, '2026-01-29 10:06:24'),
	(85, 28, '2026/01/1769681184_3e0be61b.jpg', NULL, 0, '2026-01-29 10:06:24'),
	(86, 28, '2026/01/1769681184_72bb43bd.jpg', NULL, 0, '2026-01-29 10:06:24'),
	(87, 28, '2026/01/1769681184_b9476ffa.jpg', NULL, 0, '2026-01-29 10:06:24'),
	(88, 28, '2026/01/1769681184_2f42a471.jpg', NULL, 0, '2026-01-29 10:06:24'),
	(176, 62, '2026/02/1770181093_6d1586d1.jpg', NULL, 0, '2026-02-04 04:58:13'),
	(177, 62, '2026/02/1770181093_0f77e20e.jpg', NULL, 0, '2026-02-04 04:58:13'),
	(179, 62, '2026/02/1770181093_bd2b14af.jpg', NULL, 0, '2026-02-04 04:58:13'),
	(181, 62, '2026/02/1770181093_d12d7015.jpg', NULL, 0, '2026-02-04 04:58:13'),
	(183, 62, '2026/02/1770181093_cee0a24a.jpg', NULL, 0, '2026-02-04 04:58:13'),
	(184, 62, '2026/02/1770181093_ef135fc4.jpg', NULL, 0, '2026-02-04 04:58:13'),
	(187, 62, '2026/02/1771065641_27866260.webp', NULL, 0, '2026-02-14 10:40:43'),
	(188, 62, '2026/02/1771065643_b26a025a.webp', NULL, 0, '2026-02-14 10:40:45'),
	(217, 81, '2026/02/1771408199_1899f0fa.webp', NULL, 0, '2026-02-18 09:50:00'),
	(218, 81, '2026/02/1771408200_c6d3c4ab.webp', NULL, 0, '2026-02-18 09:50:02');

-- Dumping structure for table travel_blog.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `key` varchar(50) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table travel_blog.settings: ~9 rows (approximately)
REPLACE INTO `settings` (`key`, `value`) VALUES
	('contact_email', 'lilypod_journal@gmail.com'),
	('contact_number', '0912 345 6789'),
	('facebook', 'https://www.facebook.com/'),
	('hero_subtitle', 'A little space where my life moments are written and shared.'),
	('hero_title', 'Lilypod Journal'),
	('instagram', 'https://www.instagram.com/'),
	('site_author', 'Lily'),
	('twitter', 'https://x.com/'),
	('website_name', 'Lilypod Journal');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
