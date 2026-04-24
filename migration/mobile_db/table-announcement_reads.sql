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

-- Dumping structure for table mobile_db.announcement_reads
CREATE TABLE IF NOT EXISTS `announcement_reads` (
  `id` int NOT NULL AUTO_INCREMENT,
  `announcement_id` int NOT NULL,
  `user_id` int NOT NULL,
  `read_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_read` (`announcement_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.announcement_reads: ~18 rows (approximately)
DELETE FROM `announcement_reads`;
INSERT INTO `announcement_reads` (`id`, `announcement_id`, `user_id`, `read_at`) VALUES
	(1, 3, 16, '2026-02-01 10:20:52'),
	(2, 2, 16, '2026-02-01 10:21:12'),
	(3, 3, 9, '2026-02-01 10:24:54'),
	(4, 1, 16, '2026-02-01 13:33:41'),
	(5, 3, 8, '2026-02-08 07:09:24'),
	(6, 1, 8, '2026-02-08 07:09:28'),
	(7, 2, 8, '2026-02-08 07:09:37'),
	(8, 0, 0, '2026-02-10 11:59:51'),
	(11, 2, 1, '2026-02-10 12:14:24'),
	(12, 3, 1, '2026-02-10 12:14:40'),
	(17, 7, 16, '2026-02-10 23:46:21'),
	(18, 8, 1, '2026-02-10 23:51:32'),
	(19, 6, 16, '2026-02-10 23:51:50'),
	(20, 9, 1, '2026-02-10 23:52:15'),
	(21, 5, 16, '2026-02-11 09:41:42'),
	(25, 7, 1, '2026-02-14 22:17:38'),
	(26, 5, 1, '2026-02-14 22:17:41'),
	(29, 6, 1, '2026-02-14 22:18:15');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
