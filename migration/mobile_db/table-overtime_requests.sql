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

-- Dumping structure for table mobile_db.overtime_requests
CREATE TABLE IF NOT EXISTS `overtime_requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `overtime_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `total_hours` decimal(4,2) NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `work_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Description of work during overtime',
  `is_weekend` tinyint(1) DEFAULT '0',
  `is_holiday` tinyint(1) DEFAULT '0',
  `overtime_rate` decimal(5,2) DEFAULT '1.50' COMMENT 'Multiplier: 1.5x for weekday, 2x for weekend, 3x for holiday',
  `status` enum('pending','approved','rejected','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `reviewed_by` int DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `reviewer_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `overtime_date` (`overtime_date`),
  KEY `status` (`status`),
  KEY `reviewed_by` (`reviewed_by`),
  KEY `idx_employee_date` (`employee_id`,`overtime_date`),
  CONSTRAINT `overtime_requests_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `overtime_requests_ibfk_2` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.overtime_requests: ~4 rows (approximately)
DELETE FROM `overtime_requests`;
INSERT INTO `overtime_requests` (`id`, `employee_id`, `overtime_date`, `start_time`, `end_time`, `total_hours`, `reason`, `work_description`, `is_weekend`, `is_holiday`, `overtime_rate`, `status`, `reviewed_by`, `reviewed_at`, `reviewer_notes`, `created_at`, `updated_at`) VALUES
	(1, 6, '2026-01-02', '17:00:00', '19:00:00', 2.00, 'Perbaikan kabel LAN', NULL, 0, 0, 1.50, 'approved', NULL, NULL, NULL, '2026-01-26 03:18:07', '2026-01-26 03:18:07'),
	(2, 6, '2026-01-04', '08:00:00', '12:00:00', 4.00, 'Maintenance Server Minggu', NULL, 1, 1, 2.00, 'approved', NULL, NULL, NULL, '2026-01-26 03:18:07', '2026-01-26 03:18:07'),
	(3, 1, '2026-02-09', '17:00:00', '20:30:00', 3.50, 'Finishing absensi', NULL, 0, 0, 1.50, 'approved', 1, '2026-02-09 19:47:55', '', '2026-02-09 11:32:36', '2026-02-09 12:47:55'),
	(4, 13, '2026-02-12', '17:00:00', '20:00:00', 3.00, 'resik2 kantor bro..reget banget', NULL, 0, 0, 1.50, 'pending', NULL, NULL, NULL, '2026-02-11 11:00:33', '2026-02-11 11:00:33');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
