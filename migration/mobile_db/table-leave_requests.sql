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

-- Dumping structure for table mobile_db.leave_requests
CREATE TABLE IF NOT EXISTS `leave_requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `leave_type_id` int NOT NULL,
  `request_date` date NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_days` int NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `supporting_document` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'File path to uploaded document',
  `status` enum('pending','approved','rejected','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `reviewed_by` int DEFAULT NULL COMMENT 'Manager who reviewed',
  `reviewed_at` datetime DEFAULT NULL,
  `reviewer_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `director_approved_by` int DEFAULT NULL,
  `director_approved_at` datetime DEFAULT NULL,
  `director_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `leave_type_id` (`leave_type_id`),
  KEY `status` (`status`),
  KEY `start_date` (`start_date`),
  KEY `end_date` (`end_date`),
  KEY `reviewed_by` (`reviewed_by`),
  KEY `director_approved_by` (`director_approved_by`),
  KEY `idx_employee_status` (`employee_id`,`status`),
  KEY `idx_date_range` (`start_date`,`end_date`),
  CONSTRAINT `leave_requests_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `leave_requests_ibfk_2` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `leave_requests_ibfk_3` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leave_requests_ibfk_4` FOREIGN KEY (`director_approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.leave_requests: ~8 rows (approximately)
DELETE FROM `leave_requests`;
INSERT INTO `leave_requests` (`id`, `employee_id`, `leave_type_id`, `request_date`, `start_date`, `end_date`, `total_days`, `reason`, `supporting_document`, `status`, `reviewed_by`, `reviewed_at`, `reviewer_notes`, `director_approved_by`, `director_approved_at`, `director_notes`, `created_at`, `updated_at`) VALUES
	(2, 15, 2, '2026-02-03', '2026-02-02', '2026-02-06', 5, 'mules', '6980ee019e7f6.png', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-02 11:33:37', '2026-02-02 11:33:37'),
	(3, 1, 11, '2026-02-09', '2026-02-11', '2026-02-13', 3, 'Bulanan', NULL, 'approved', 1, '2026-02-09 19:47:48', '', NULL, NULL, NULL, '2026-02-09 10:46:47', '2026-02-09 12:47:48'),
	(4, 1, 13, '2026-02-11', '2026-02-11', '2026-02-11', 1, 'SURVEY RESTO [Jam Dinas: 13:00 - 15:00]', 'uploads/requests/1770799425_9236.png', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-11 08:43:45', '2026-02-11 08:43:45'),
	(5, 1, 2, '2026-02-11', '2026-02-11', '2026-02-12', 2, 'dokter kulit', 'uploads/requests/1770804159_6705.png', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-11 10:02:39', '2026-02-11 10:02:39'),
	(6, 1, 2, '2026-02-11', '2026-02-11', '2026-02-12', 2, 'test', 'uploads/requests/1770804943_9264.png', 'approved', 1, '2026-02-11 17:21:41', '', NULL, NULL, NULL, '2026-02-11 10:15:43', '2026-02-11 10:21:41'),
	(7, 1, 13, '2026-02-11', '2026-02-18', '2026-02-18', 1, 'halan halan [Jam Dinas: 09:00 - 13:00]', '698c57fdb3c4d.png', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-11 10:20:45', '2026-02-11 10:20:45'),
	(8, 13, 11, '2026-02-11', '2026-02-19', '2026-02-22', 4, 'bulanan ceweekkk', NULL, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-11 10:58:43', '2026-02-11 10:58:43'),
	(9, 13, 1, '2026-02-11', '2026-03-11', '2026-03-13', 3, 'cuti healing', '698c612bf0add.pdf', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-11 10:59:55', '2026-02-11 10:59:55'),
	(10, 16, 1, '2026-01-20', '2026-01-20', '2026-01-20', 1, 'Cuti', NULL, 'approved', 1, '2026-03-30 07:53:13', 'Diinputkan oleh Manager/Admin (Auto Approved)', NULL, NULL, NULL, '2026-03-30 07:53:13', '2026-03-30 07:53:13'),
	(11, 16, 1, '2026-01-22', '2026-01-22', '2026-01-23', 2, 'cuti', NULL, 'approved', 1, '2026-03-30 07:53:59', 'Diinputkan oleh Manager/Admin (Auto Approved)', NULL, NULL, NULL, '2026-03-30 07:53:59', '2026-03-30 07:53:59');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
