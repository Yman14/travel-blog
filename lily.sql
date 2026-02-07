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
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table travel_blog.admins: ~1 rows (approximately)
REPLACE INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
	(3, 'admin', '$2y$10$FVdsAOQbyj/MK49IHpncgeV7zh3HLB7JiTSJ6Ppz8I7b7j5o0Vkk6', '2026-01-05 13:53:55');

-- Dumping structure for table travel_blog.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table travel_blog.categories: ~5 rows (approximately)
REPLACE INTO `categories` (`id`, `name`, `slug`, `created_at`) VALUES
	(1, 'Japan', 'japan', '2026-01-05 09:44:54'),
	(3, 'Korean', 'korean', '2026-01-15 07:47:56'),
	(4, 'Sample Cat', 'sample-cat', '2026-01-16 08:34:15'),
	(15, 'Nami', 'nami', '2026-01-17 06:57:44'),
	(16, 'Delete', 'delete', '2026-01-17 08:02:22');

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
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table travel_blog.posts: ~14 rows (approximately)
REPLACE INTO `posts` (`id`, `title`, `slug`, `content`, `featured_image`, `category_id`, `status`, `created_at`) VALUES
	(1, 'My First Trip to Japan', 'my-first-trip-to-japan', 'This is my first travel post content. this is edited', NULL, 1, 'published', '2026-01-05 09:44:54'),
	(3, 'My First Trip to Cebu', 'my-first-trip-to-cebu', 'This is my second travel post content.', NULL, 1, 'published', '2026-01-05 12:07:14'),
	(4, 'hello', 'hello', 'my first input from website instead of manually inputted in my code.', NULL, 1, 'published', '2026-01-07 04:45:11'),
	(5, 'first draft', 'first-draft', 'draft sample data', NULL, 1, 'draft', '2026-01-07 04:45:39'),
	(8, 'sample', 'sample', 'Lorem ipsum dolor sit amet. Aut fugiat dicta est officia omnis et nihil culpa sit dignissimos quibusdam qui magnam assumenda qui maxime cupiditate. Et iure quaerat aut quae quia aut ullam sint qui fugit soluta et vitae voluptatum ut architecto numquam. Ut quas enim et amet consectetur qui distinctio quidem qui numquam totam aut quia Quis. Aut adipisci fugit sit iusto impedit ut voluptatem unde est deleniti quae ad voluptatem voluptatem aut quae iure. Qui omnis omnis et officia ratione 33 minus excepturi et animi mollitia ut distinctio fuga. Sed laudantium culpa non Quis doloribus eos deserunt consequatur. Qui vero asperiores aut omnis voluptas quo consequatur laudantium vel vero accusantium et voluptas aliquid et voluptas magni. Et voluptas officiis et quod repellendus hic deserunt culpa ex voluptatibus unde. Qui animi molestiae sed repellendus libero vel doloremque amet et fugiat vero. Eum voluptatem tempora non internos sunt aut neque autem qui praesentium quia? Ea Quis molestiae rem incidunt maxime ex recusandae quos sit voluptas nostrum.\r\n\r\nId voluptas suscipit hic dolorem eius id blanditiis omnis aut culpa quaerat? Eos placeat commodi eum dolorum molestias qui impedit minus eum eaque nobis rem corporis quibusdam et ipsum itaque qui dolor voluptatem. Et unde quos ut magnam perspiciatis vel dicta voluptate vel voluptatum commodi. Vel quae tempora et placeat rerum est consequatur nostrum. Ut nemo quibusdam et quisquam expedita est suscipit tempore eos iste reiciendis? A quia Quis ea rerum voluptate vel quas voluptatem. Hic obcaecati quisquam ut voluptate quas vel odit commodi ut atque dolores! Qui velit maxime vel dolorem assumenda ut nisi voluptatem et assumenda sapiente. Et minus asperiores et voluptas iure et fugiat molestiae. Qui modi quia At laboriosam fuga et natus maiores. Ut culpa voluptatem aut soluta quis sed neque ipsa qui earum magni. Et repellendus quia et sunt natus et distinctio facilis est assumenda rerum et voluptas illum non culpa quaerat ut voluptate quia. Est aperiam reiciendis quo consectetur odio et dolorum sint quo voluptatem modi hic ipsa suscipit sed dolores delectus. Sit quas similique et officiis laborum aut sapiente iusto.\r\n\r\nUt veritatis iste eum officia quisquam id dignissimos earum ut reiciendis consequatur qui mollitia illum sed impedit nesciunt. Et esse provident et provident porro id consequatur ducimus. Quo porro quis eos quas unde nam perspiciatis quia et explicabo odio! Aut magnam repellendus ea minima velit 33 perferendis velit ea excepturi illo! Et necessitatibus dolores sit dolore pariatur est omnis dolorem eos nisi quibusdam qui error dolorem qui libero tenetur sed nemo voluptatem! Aut tempora nisi cum consequatur officiis qui ducimus officiis ea natus itaque. Aut dolor harum sed eligendi quaerat 33 dolore veniam sed fuga vitae ut quos magnam sit rerum ullam. Rem consequatur neque aut quos dicta non explicabo dolorem? In officiis excepturi est earum rerum aut sint odio At voluptas temporibus vel aliquid quos est cupiditate laboriosam 33 facere voluptate. At voluptate maiores qui reprehenderit animi aut odit dolorem et consectetur ipsam sit saepe adipisci. Ea voluptatem nesciunt eos veniam molestiae non facere voluptate ut facilis voluptatibus! Non ipsum internos qui repellendus facilis aut necessitatibus nulla aut earum nesciunt non magni accusantium. Ut sint dolorem qui sunt sunt est doloribus obcaecati vel exercitationem nulla. Ut modi beatae ut blanditiis tempore rem nihil molestiae.', NULL, 3, 'published', '2026-01-16 08:12:26'),
	(10, 'sample for delete', 'sample-for-delete', 'delete this content and post', NULL, 16, 'published', '2026-01-17 08:02:42'),
	(12, 'SAMPLE FOR IMAGE', 'sample-for-image', 'sample for image iploads and testing', '2026/01/1768986382_42e658eca28b.png', 16, 'published', '2026-01-21 09:06:22'),
	(13, 'sampe for multiple images', 'sampe-for-multiple-images', 'multiple images upload testing', NULL, 16, 'published', '2026-01-21 12:14:14'),
	(15, 'Slug12', 'slug12', 'slug checking', NULL, 16, 'published', '2026-01-21 13:39:04'),
	(19, 'multiple images upload', 'multiple-images-upload', 'testing multiple images upload', NULL, 16, 'published', '2026-01-21 14:05:12'),
	(20, 'multiple images upload 2', 'multiple-images-upload-2', 'testing multiple images upload', '2026/01/1769666637_76283b19e18b.jpg', 16, 'published', '2026-01-21 14:07:17'),
	(21, 'multiple images upload 33', 'multiple-images-upload-33', 'testing multiple images upload 3', '2026/01/1769005419_31c9b9b1b128.jpg', 16, 'published', '2026-01-21 14:23:39'),
	(28, 'Lamitan', 'lamitan', 'When despair for the world grows in me and I wake in the night at the least sound in fear of what my life and my children’s lives may be, I go and lie down where the wood drake rests in his beauty on the water, and the great heron feeds. I come into the peace of wild things who do not tax their lives with forethought of grief. I come into the presence of still water. And I feel above me the day-blind stars waiting with their light. For a time I rest in the grace of the world, and am free.\r\n\r\nThe Peace of Wild Things, Wendell Berry', '2026/01/1769760688_944dc965355a.jpg', 16, 'published', '2026-01-29 10:06:24'),
	(62, 'The Flying Bird and Fish', 'the-flying-bird-and-fish', 'The furthest distance in the world is not the distance between opposite sides of the world. It is that you don’t know that I love you, when I stand in front of you.\r\nThe furthest distance in the world is not that you don’t know I love you when I stand in front of you. It is when I cannot say I love you, when I love you so madly.\r\n\r\nThe furthest distance in the world is not that I cannot say I love you, when I love you so madly. It is that I have to bury it in my heart, despite the unbearable yearning.\r\n\r\nThe furthest distance in the world is not that I have to bury it in my heart despite the unbearable yearning. It is when we cannot be together, even when we love each other.\r\n\r\nThe furthest distance in the world is not that we cannot be together, when we love each other. It is when we turn a blind eye to it, despite knowing true love conquers all.\r\n\r\nThe furthest distance in the world is not the distance between two distant trees. It is when branches cannot depend on each other in the wind, despite growing from the same root.\r\n\r\nThe furthest distance in the world is not when branches cannot depend on each other in the wind. It is when the trajectories of stars cannot cross, even when the blinking stars look at each other.\r\n\r\nThe furthest distance in the world is not when the trajectories of stars cannot cross. It is when they are unable to find each other after crossing trajectories.\r\n\r\nThe furthest distance in the world is not being unable to find each other. It is when we are doomed not to love, even when we coincidentally meet.\r\n\r\nThe furthest distance in the world is the love between the bird and fish. One is flying in the sky, the other is looking upon the sea.\r\n\r\n-Zhang Ye\'s Version', '2026/02/1770189393_73cee93257c2.jpg', 16, 'published', '2026-02-04 04:58:13');

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
) ENGINE=InnoDB AUTO_INCREMENT=185 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table travel_blog.post_images: ~41 rows (approximately)
REPLACE INTO `post_images` (`id`, `post_id`, `file_path`, `alt_text`, `sort_order`, `created_at`) VALUES
	(3, 12, '2026/01/1768986382_85ce8ba4.jpg', NULL, 0, '2026-01-21 09:06:22'),
	(4, 12, '2026/01/1768986382_ab4fe094.png', NULL, 0, '2026-01-21 09:06:22'),
	(5, 13, '2026/01/1768997654_a7d4a1eb.jpg', NULL, 0, '2026-01-21 12:14:14'),
	(6, 13, '2026/01/1768997654_fa6ef3df.jpg', NULL, 0, '2026-01-21 12:14:14'),
	(7, 13, '2026/01/1768997654_2a39bc57.jpg', NULL, 0, '2026-01-21 12:14:14'),
	(8, 13, '2026/01/1768997654_19ad3e88.jpg', NULL, 0, '2026-01-21 12:14:14'),
	(9, 13, '2026/01/1768997654_772a67de.png', NULL, 0, '2026-01-21 12:14:14'),
	(10, 13, '2026/01/1768997654_f7405824.jpg', NULL, 0, '2026-01-21 12:14:14'),
	(11, 13, '2026/01/1768997654_d7b13a08.jpg', NULL, 0, '2026-01-21 12:14:14'),
	(12, 13, '2026/01/1768997654_2d0889e0.jpg', NULL, 0, '2026-01-21 12:14:14'),
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
	(182, 62, '2026/02/1770181093_2ab29947.jpg', NULL, 0, '2026-02-04 04:58:13'),
	(183, 62, '2026/02/1770181093_cee0a24a.jpg', NULL, 0, '2026-02-04 04:58:13'),
	(184, 62, '2026/02/1770181093_ef135fc4.jpg', NULL, 0, '2026-02-04 04:58:13');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
