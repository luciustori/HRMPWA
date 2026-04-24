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

-- Dumping structure for table mobile_db.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `type` enum('request','task','shift','announcement','attendance','system') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `reference_id` int DEFAULT NULL,
  `reference_table` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'bell',
  `is_read` tinyint(1) DEFAULT '0',
  `priority` enum('low','normal','high') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'normal',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `read_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_employee_read` (`employee_id`,`is_read`),
  KEY `idx_created` (`created_at`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table mobile_db.notifications: ~9 rows (approximately)
DELETE FROM `notifications`;
INSERT INTO `notifications` (`id`, `employee_id`, `type`, `title`, `message`, `reference_id`, `reference_table`, `icon`, `is_read`, `priority`, `created_at`, `read_at`) VALUES
	(1, 1, 'task', 'Tugas Baru Â­Æ’Ã´Ã¯', 'Anda mendapat tugas baru: Internal Meeting (Deadline: 09 Jan 2026)', 3, 'tasks', 'clipboard-check', 1, 'normal', '2026-01-08 18:49:13', '2026-01-08 18:49:24'),
	(2, 3, 'task', 'Tugas Baru Â­Æ’Ã´Ã¯', 'Anda mendapat tugas baru: Coba Task form baru (Deadline: 19 Jan 2026)', 4, 'tasks', 'clipboard-check', 0, 'normal', '2026-01-11 04:18:43', NULL),
	(3, 2, 'task', 'Tugas Baru Â­Æ’Ã´Ã¯', 'Anda mendapat tugas baru: coba notif (Deadline: 22 Jan 2026)', 5, 'tasks', 'clipboard-check', 0, 'normal', '2026-01-12 02:09:05', NULL),
	(4, 2, 'task', 'Tugas Baru Â­Æ’Ã´Ã¯', 'Anda mendapat tugas baru: coba notif (Deadline: 22 Jan 2026)', 6, 'tasks', 'clipboard-check', 0, 'normal', '2026-01-12 02:14:37', NULL),
	(5, 2, 'task', 'Tugas Baru Â­Æ’Ã´Ã¯', 'Anda mendapat tugas baru: coba notif (Deadline: 22 Jan 2026)', 7, 'tasks', 'clipboard-check', 0, 'normal', '2026-01-12 02:16:18', NULL),
	(6, 18, 'task', 'Tugas Baru ?', 'Anda mendapat tugas baru: task 1 (Deadline: 27 Jan 2026)', 8, 'tasks', 'clipboard-check', 0, 'normal', '2026-01-26 21:30:14', NULL),
	(7, 7, 'task', 'Tugas Baru ?', 'Anda mendapat tugas baru: Pencatatan Biaya Service Tenant (Deadline: 05 Jan 2026)', 9, 'tasks', 'clipboard-check', 0, 'normal', '2026-01-27 22:52:56', NULL),
	(8, 18, 'task', 'Deadline Berubah Ã”Ã…â–‘', 'Deadline tugas "Invoicing Service Tenant" diubah menjadi 10 Jan 2026', 9, 'tasks', 'calendar-alt', 0, 'high', '2026-01-30 19:47:02', NULL),
	(9, 16, 'task', 'Tugas Baru ?', 'Anda mendapat tugas baru: Persiapan Panitia Seleksi Direktur PT. Jogjatama Vishesha (Deadline: 13 Feb 2026)', 10, 'tasks', 'clipboard-check', 0, 'high', '2026-02-09 12:22:49', NULL),
	(10, 16, 'task', 'Tugas Baru ?', 'Anda mendapat tugas baru: Trial Test SIXTY  (Deadline: 20 Feb 2026)', 11, 'tasks', 'clipboard-check', 0, 'high', '2026-02-16 03:20:17', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
