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

-- Dumping structure for table mobile_db.task_time_logs
CREATE TABLE IF NOT EXISTS `task_time_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `task_id` int NOT NULL,
  `user_id` int NOT NULL,
  `log_date` date NOT NULL,
  `hours_spent` decimal(5,2) NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `user_id` (`user_id`),
  KEY `idx_timelogs_task_date` (`task_id`,`log_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.task_time_logs: ~13 rows (approximately)
DELETE FROM `task_time_logs`;
INSERT INTO `task_time_logs` (`id`, `task_id`, `user_id`, `log_date`, `hours_spent`, `description`, `created_at`) VALUES
	(1, 7, 2, '2026-01-15', 2.00, 'survey', '2026-01-14 11:00:08'),
	(2, 5, 2, '2026-01-15', 6.00, 'ini coba notif 1 | coba notif ke 2 action_type | coba notif ke 2 action_type', '2026-01-14 14:42:07'),
	(3, 5, 2, '2026-01-15', 1.00, 'log dalam baris baru', '2026-01-14 15:11:12'),
	(4, 1, 1, '2026-01-15', 1.00, '10 tenant', '2026-01-14 23:46:19'),
	(5, 9, 5, '2026-01-28', 1.00, 'Umar Kayam Selesai', '2026-01-27 23:32:49'),
	(6, 9, 16, '2026-02-01', 3.00, 'Gedung C2 Selesai Meter Listrik', '2026-01-31 21:39:11'),
	(7, 9, 18, '2026-02-01', 2.00, 'nguber nguber tikus di xtcafe', '2026-01-31 15:42:09'),
	(8, 9, 18, '2026-02-01', 7.00, 'nguber nguber tikus di xtcafe', '2026-01-31 15:42:17'),
	(9, 9, 16, '2026-02-01', 1.00, 'sip', '2026-01-31 23:24:27'),
	(10, 9, 18, '2026-02-01', 3.00, 'coba simpan', '2026-01-31 16:25:01'),
	(11, 9, 18, '2026-02-01', 0.00, 'catat lagi', '2026-01-31 16:25:10'),
	(12, 10, 16, '2026-02-09', 3.00, '5 hotel survey', '2026-02-09 15:23:54'),
	(13, 10, 16, '2026-02-10', 2.00, 'Seluruh CV sudah diserahkan ke BpKSDM', '2026-02-10 04:50:15'),
	(14, 2, 1, '2026-02-15', 0.00, 'coba log dulu mobile', '2026-02-15 14:57:33');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
