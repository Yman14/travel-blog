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


-- Dumping database structure for lily_db
CREATE DATABASE IF NOT EXISTS `if0_41226406_lily_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `if0_41226406_lily_db`;

-- Dumping structure for table lily_db.admins
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

-- Dumping data for table lily_db.admins: ~0 rows (approximately)
REPLACE INTO `admins` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
	(3, 'lily', 'admin@email.com', '$2y$10$fGNjpQFYeOJBumbXiDgS/.IjmmLlbMkbhZT9KKu5nRIYcY65BoLqq', 'admin', '2026-02-23 15:54:32');

-- Dumping structure for table lily_db.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lily_db.categories: ~3 rows (approximately)
REPLACE INTO `categories` (`id`, `name`, `slug`, `created_at`) VALUES
	(1, 'Japan', 'japan', '2026-01-05 09:44:54'),
	(3, 'Korean', 'korean', '2026-01-15 07:47:56'),
	(30, 'Philippines (sample data)', 'philippines-sample-data-', '2026-02-24 07:25:20');

-- Dumping structure for table lily_db.login_attempts
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) DEFAULT NULL,
  `attempted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lily_db.login_attempts: ~0 rows (approximately)

-- Dumping structure for table lily_db.posts
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
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lily_db.posts: ~2 rows (approximately)
REPLACE INTO `posts` (`id`, `title`, `slug`, `content`, `featured_image`, `category_id`, `status`, `created_at`, `meta_title`, `meta_description`) VALUES
	(90, 'Lamitan', 'lamitan', 'When despair for the world grows in me \r\nand I wake in the night at the least sound \r\nin fear of what my life and my children’s lives may be, \r\nI go and lie down where the wood drake \r\nrests in his beauty on the water, and the great heron feeds. \r\nI come into the peace of wild things \r\nwho do not tax their lives with forethought of grief. \r\nI come into the presence of still water. \r\nAnd I feel above me the day-blind stars waiting with their light. \r\nFor a time I rest in the grace of the world, and am free.\r\n\r\n\r\n-The Peace of Wild Things, Wendell Berry', '2026/02/1771918132_28988199d55e.webp', 30, 'published', '2026-02-24 07:28:53', NULL, NULL),
	(92, 'Random Images', 'random-images', 'The Flying Bird and Fish\r\n\r\nThe furthest distance in the world is not the distance between opposite sides of the world. It is that you don’t know that I love you, when I stand in front of you.\r\nThe furthest distance in the world is not that you don’t know I love you when I stand in front of you. It is when I cannot say I love you, when I love you so madly.\r\n\r\nThe furthest distance in the world is not that I cannot say I love you, when I love you so madly. It is that I have to bury it in my heart, despite the unbearable yearning.\r\n\r\nThe furthest distance in the world is not that I have to bury it in my heart despite the unbearable yearning. It is when we cannot be together, even when we love each other.\r\n\r\nThe furthest distance in the world is not that we cannot be together, when we love each other. It is when we turn a blind eye to it, despite knowing true love conquers all.\r\n\r\nThe furthest distance in the world is not the distance between two distant trees. It is when branches cannot depend on each other in the wind, despite growing from the same root.\r\n\r\nThe furthest distance in the world is not when branches cannot depend on each other in the wind. It is when the trajectories of stars cannot cross, even when the blinking stars look at each other.\r\n\r\nThe furthest distance in the world is not when the trajectories of stars cannot cross. It is when they are unable to find each other after crossing trajectories.\r\n\r\nThe furthest distance in the world is not being unable to find each other. It is when we are doomed not to love, even when we coincidentally meet.\r\n\r\nThe furthest distance in the world is the love between the bird and fish. One is flying in the sky, the other is looking upon the sea.\r\n\r\n\r\n-Zhang Ye\'s Version', '2026/02/1771919601_6baebd814580.webp', 30, 'published', '2026-02-24 07:44:26', NULL, NULL);

-- Dumping structure for table lily_db.post_images
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
) ENGINE=InnoDB AUTO_INCREMENT=229 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lily_db.post_images: ~10 rows (approximately)
REPLACE INTO `post_images` (`id`, `post_id`, `file_path`, `alt_text`, `sort_order`, `created_at`) VALUES
	(219, 92, '2026/02/1771919603_2ba4c506.webp', NULL, 0, '2026-02-24 07:53:25'),
	(220, 92, '2026/02/1771919605_17b186ac.webp', NULL, 0, '2026-02-24 07:53:26'),
	(221, 92, '2026/02/1771919606_8199ec58.webp', NULL, 0, '2026-02-24 07:53:27'),
	(222, 92, '2026/02/1771919607_5b76cc84.webp', NULL, 0, '2026-02-24 07:53:29'),
	(223, 92, '2026/02/1771919609_9ac67c12.webp', NULL, 0, '2026-02-24 07:53:31'),
	(224, 92, '2026/02/1771919611_1bb1a76d.webp', NULL, 0, '2026-02-24 07:53:31'),
	(225, 92, '2026/02/1771919611_7711f451.webp', NULL, 0, '2026-02-24 07:53:32'),
	(226, 92, '2026/02/1771919612_ae7407f6.webp', NULL, 0, '2026-02-24 07:53:34'),
	(227, 92, '2026/02/1771919614_6ceb8e37.webp', NULL, 0, '2026-02-24 07:53:35'),
	(228, 92, '2026/02/1771919615_08b31918.webp', NULL, 0, '2026-02-24 07:53:37');

-- Dumping structure for table lily_db.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `key` varchar(50) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table lily_db.settings: ~9 rows (approximately)
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
