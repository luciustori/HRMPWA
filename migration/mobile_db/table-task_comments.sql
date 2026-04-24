-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.15.0.7171
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table mobile_db.task_comments
CREATE TABLE IF NOT EXISTS `task_comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `task_id` int NOT NULL,
  `user_id` int NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_status_change` tinyint(1) DEFAULT '0' COMMENT '1 if this is a status update',
  `old_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `user_id` (`user_id`),
  KEY `idx_comments_task_created` (`task_id`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.task_comments: ~8 rows (approximately)
DELETE FROM `task_comments`;
INSERT INTO `task_comments` (`id`, `task_id`, `user_id`, `comment`, `attachment`, `is_status_change`, `old_status`, `new_status`, `created_at`) VALUES
	(1, 7, 2, 'tambahin comment dlu aahh', NULL, 0, NULL, NULL, '2026-01-14 11:00:35'),
	(2, 5, 2, 'ini coba comment', NULL, 0, NULL, NULL, '2026-01-14 14:43:12'),
	(3, 5, 2, 'ini kok time logsnya numpuk! ra sip!', NULL, 0, NULL, NULL, '2026-01-14 15:05:51'),
	(4, 9, 5, '2 tenan tutup hari ini', NULL, 0, NULL, NULL, '2026-01-27 23:25:45'),
	(5, 8, 18, 'coba update comment', NULL, 0, NULL, NULL, '2026-01-31 21:34:18'),
	(6, 8, 18, 'dah rampung kan ini? cek cek cek', NULL, 0, NULL, NULL, '2026-01-31 21:34:41'),
	(7, 10, 16, 'Sudah buka lowongan di KR', NULL, 0, NULL, NULL, '2026-02-09 15:10:25'),
	(8, 10, 1, 'jangan lupa media sosial atau online platform', NULL, 0, NULL, NULL, '2026-02-09 15:31:57'),
	(9, 2, 1, 'diskusi progress disini via mobile', NULL, 0, NULL, NULL, '2026-02-15 14:57:47');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
