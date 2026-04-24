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

-- Dumping structure for table mobile_db.attendance_summary
CREATE TABLE IF NOT EXISTS `attendance_summary` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `year` int NOT NULL,
  `month` int NOT NULL,
  `total_working_days` int DEFAULT '0',
  `total_present` int DEFAULT '0',
  `total_late` int DEFAULT '0',
  `total_early_out` int DEFAULT '0',
  `total_absent` int DEFAULT '0',
  `total_leave` int DEFAULT '0',
  `total_work_minutes` int DEFAULT '0',
  `total_overtime_minutes` int DEFAULT '0',
  `total_late_minutes` int DEFAULT '0',
  `attendance_percentage` decimal(5,2) DEFAULT '0.00',
  `punctuality_percentage` decimal(5,2) DEFAULT '0.00',
  `attendance_deduction` decimal(10,2) DEFAULT '0.00' COMMENT 'Late penalty',
  `calculated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_summary` (`employee_id`,`year`,`month`),
  KEY `employee_id` (`employee_id`),
  KEY `year_month` (`year`,`month`),
  CONSTRAINT `attendance_summary_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.attendance_summary: ~2 rows (approximately)
DELETE FROM `attendance_summary`;
INSERT INTO `attendance_summary` (`id`, `employee_id`, `year`, `month`, `total_working_days`, `total_present`, `total_late`, `total_early_out`, `total_absent`, `total_leave`, `total_work_minutes`, `total_overtime_minutes`, `total_late_minutes`, `attendance_percentage`, `punctuality_percentage`, `attendance_deduction`, `calculated_at`, `updated_at`) VALUES
	(1, 1, 2025, 11, 22, 22, 3, 0, 0, 0, 12140, 110, 75, 100.00, 86.36, 0.00, '2025-11-29 11:00:00', '2025-11-29 11:00:00'),
	(2, 1, 2025, 12, 23, 23, 3, 2, 0, 0, 12740, 70, 45, 100.00, 86.96, 0.00, '2025-12-30 23:00:00', '2025-12-30 23:00:00');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
