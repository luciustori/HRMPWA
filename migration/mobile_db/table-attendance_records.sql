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

-- Dumping structure for table mobile_db.attendance_records
CREATE TABLE IF NOT EXISTS `attendance_records` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `attendance_date` date NOT NULL,
  `shift_id` int DEFAULT NULL,
  `check_in_time` datetime DEFAULT NULL,
  `check_in_location_id` int DEFAULT NULL,
  `check_in_latitude` decimal(10,8) DEFAULT NULL,
  `check_in_longitude` decimal(11,8) DEFAULT NULL,
  `check_in_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `check_in_photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Face recognition photo',
  `check_in_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `check_out_time` datetime DEFAULT NULL,
  `check_out_location_id` int DEFAULT NULL,
  `check_out_latitude` decimal(10,8) DEFAULT NULL,
  `check_out_longitude` decimal(11,8) DEFAULT NULL,
  `check_out_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `check_out_photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_out_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_late` tinyint(1) DEFAULT '0' COMMENT '1 if late arrival',
  `late_duration_minutes` int DEFAULT '0',
  `is_early_out` tinyint(1) DEFAULT '0' COMMENT '1 if early departure',
  `early_out_minutes` int DEFAULT '0',
  `work_duration_minutes` int DEFAULT '0' COMMENT 'Actual work minutes',
  `overtime_minutes` int DEFAULT '0' COMMENT 'Minutes beyond shift',
  `status` enum('present','late','early_out','absent','leave','holiday') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'present',
  `requires_approval` tinyint(1) DEFAULT '0' COMMENT 'Late or early out needs approval',
  `approval_status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_by` int DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `approval_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `attendance_date` (`attendance_date`),
  KEY `shift_id` (`shift_id`),
  KEY `status` (`status`),
  KEY `approval_status` (`approval_status`),
  KEY `check_in_location_id` (`check_in_location_id`),
  KEY `check_out_location_id` (`check_out_location_id`),
  KEY `attendance_records_ibfk_5` (`approved_by`),
  KEY `idx_emp_date_range` (`employee_id`,`attendance_date`),
  KEY `idx_approval_workflow` (`requires_approval`,`approval_status`,`attendance_date`),
  KEY `idx_location_validation` (`check_in_location_id`,`attendance_date`),
  CONSTRAINT `attendance_records_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendance_records_ibfk_2` FOREIGN KEY (`shift_id`) REFERENCES `work_shifts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `attendance_records_ibfk_3` FOREIGN KEY (`check_in_location_id`) REFERENCES `office_locations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `attendance_records_ibfk_4` FOREIGN KEY (`check_out_location_id`) REFERENCES `office_locations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `attendance_records_ibfk_5` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.attendance_records: ~38 rows (approximately)
DELETE FROM `attendance_records`;
INSERT INTO `attendance_records` (`id`, `employee_id`, `attendance_date`, `shift_id`, `check_in_time`, `check_in_location_id`, `check_in_latitude`, `check_in_longitude`, `check_in_address`, `check_in_photo`, `check_in_notes`, `check_out_time`, `check_out_location_id`, `check_out_latitude`, `check_out_longitude`, `check_out_address`, `check_out_photo`, `check_out_notes`, `is_late`, `late_duration_minutes`, `is_early_out`, `early_out_minutes`, `work_duration_minutes`, `overtime_minutes`, `status`, `requires_approval`, `approval_status`, `approved_by`, `approved_at`, `approval_notes`, `created_at`, `updated_at`) VALUES
	(1, 14, '2026-01-02', NULL, '2026-01-02 07:30:00', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-02 16:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'present', 0, NULL, NULL, NULL, NULL, '2026-01-26 03:18:07', '2026-01-26 03:18:07'),
	(2, 14, '2026-01-03', NULL, '2026-01-03 07:30:00', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-03 16:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'present', 0, NULL, NULL, NULL, NULL, '2026-01-26 03:18:07', '2026-01-26 03:18:07'),
	(3, 14, '2026-01-05', NULL, '2026-01-05 07:30:00', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-05 16:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'present', 0, NULL, NULL, NULL, NULL, '2026-01-26 03:18:07', '2026-01-26 03:18:07'),
	(4, 6, '2026-01-02', NULL, '2026-01-02 08:00:00', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-02 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'present', 0, NULL, NULL, NULL, NULL, '2026-01-26 03:18:07', '2026-01-26 03:18:07'),
	(5, 6, '2026-01-03', NULL, '2026-01-03 08:45:00', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-03 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, 45, 0, 0, 0, 0, 'present', 0, NULL, NULL, NULL, NULL, '2026-01-26 03:18:07', '2026-01-26 03:18:07'),
	(6, 2, '2026-01-02', NULL, '2026-01-02 08:00:00', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-02 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'present', 0, NULL, NULL, NULL, NULL, '2026-01-26 03:18:07', '2026-01-26 03:18:07'),
	(7, 1, '2025-01-02', 1, '2025-01-02 08:15:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 15, 0, 0, 0, 0, 'late', 0, NULL, NULL, NULL, NULL, '2026-01-30 22:43:15', '2026-01-30 22:43:15'),
	(8, 2, '2025-01-02', 1, '2025-01-02 08:15:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 15, 0, 0, 0, 0, 'late', 0, NULL, NULL, NULL, NULL, '2026-01-30 22:43:15', '2026-01-30 22:43:15'),
	(9, 3, '2025-01-02', 1, '2025-01-02 08:15:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 15, 0, 0, 0, 0, 'late', 0, NULL, NULL, NULL, NULL, '2026-01-30 22:43:15', '2026-01-30 22:43:15'),
	(10, 4, '2025-01-02', 1, '2025-01-02 08:15:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 15, 0, 0, 0, 0, 'late', 0, NULL, NULL, NULL, NULL, '2026-01-30 22:43:15', '2026-01-30 22:43:15'),
	(11, 5, '2025-01-02', 1, '2025-01-02 08:15:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 15, 0, 0, 0, 0, 'late', 0, NULL, NULL, NULL, NULL, '2026-01-30 22:43:15', '2026-01-30 22:43:15'),
	(12, 6, '2025-01-02', 1, '2025-01-02 08:15:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 15, 0, 0, 0, 0, 'late', 0, NULL, NULL, NULL, NULL, '2026-01-30 22:43:15', '2026-01-30 22:43:15'),
	(13, 7, '2025-01-02', 1, '2025-01-02 08:15:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 15, 0, 0, 0, 0, 'late', 0, NULL, NULL, NULL, NULL, '2026-01-30 22:43:15', '2026-01-30 22:43:15'),
	(14, 8, '2025-01-02', 1, '2025-01-02 08:15:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 15, 0, 0, 0, 0, 'late', 0, NULL, NULL, NULL, NULL, '2026-01-30 22:43:15', '2026-01-30 22:43:15'),
	(15, 9, '2025-01-02', 1, '2025-01-02 08:15:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 15, 0, 0, 0, 0, 'late', 0, NULL, NULL, NULL, NULL, '2026-01-30 22:43:15', '2026-01-30 22:43:15'),
	(16, 10, '2025-01-02', 1, '2025-01-02 08:15:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 15, 0, 0, 0, 0, 'late', 0, NULL, NULL, NULL, NULL, '2026-01-30 22:43:15', '2026-01-30 22:43:15'),
	(17, 11, '2025-01-02', 1, '2025-01-02 08:15:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 15, 0, 0, 0, 0, 'late', 0, NULL, NULL, NULL, NULL, '2026-01-30 22:43:15', '2026-01-30 22:43:15'),
	(18, 12, '2025-01-02', 1, '2025-01-02 08:15:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 15, 0, 0, 0, 0, 'late', 0, NULL, NULL, NULL, NULL, '2026-01-30 22:43:15', '2026-01-30 22:43:15'),
	(19, 13, '2025-01-02', 1, '2025-01-02 08:15:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 15, 0, 0, 0, 0, 'late', 0, NULL, NULL, NULL, NULL, '2026-01-30 22:43:15', '2026-01-30 22:43:15'),
	(20, 14, '2025-01-02', 1, '2025-01-02 08:15:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 15, 0, 0, 0, 0, 'late', 0, NULL, NULL, NULL, NULL, '2026-01-30 22:43:15', '2026-01-30 22:43:15'),
	(21, 15, '2025-01-02', 1, '2025-01-02 08:15:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 15, 0, 0, 0, 0, 'late', 0, NULL, NULL, NULL, NULL, '2026-01-30 22:43:15', '2026-01-30 22:43:15'),
	(22, 16, '2025-01-02', 1, '2025-01-02 08:15:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 15, 0, 0, 0, 0, 'late', 0, NULL, NULL, NULL, NULL, '2026-01-30 22:43:15', '2026-01-30 22:43:15'),
	(23, 17, '2025-01-02', 1, '2025-01-02 08:15:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 15, 0, 0, 0, 0, 'late', 0, NULL, NULL, NULL, NULL, '2026-01-30 22:43:15', '2026-01-30 22:43:15'),
	(24, 18, '2025-01-02', 1, '2025-01-02 08:15:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 15, 0, 0, 0, 0, 'late', 0, NULL, NULL, NULL, NULL, '2026-01-30 22:43:15', '2026-01-30 22:43:15'),
	(25, 19, '2025-01-02', 1, '2025-01-02 08:15:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 15, 0, 0, 0, 0, 'late', 0, NULL, NULL, NULL, NULL, '2026-01-30 22:43:15', '2026-01-30 22:43:15'),
	(26, 20, '2025-01-02', 1, '2025-01-02 08:15:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 15, 0, 0, 0, 0, 'late', 0, NULL, NULL, NULL, NULL, '2026-01-30 22:43:15', '2026-01-30 22:43:15'),
	(27, 21, '2025-01-02', 1, '2025-01-02 08:15:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 15, 0, 0, 0, 0, 'late', 0, NULL, NULL, NULL, NULL, '2026-01-30 22:43:15', '2026-01-30 22:43:15'),
	(28, 22, '2025-01-02', 1, '2025-01-02 08:15:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 15, 0, 0, 0, 0, 'late', 0, NULL, NULL, NULL, NULL, '2026-01-30 22:43:15', '2026-01-30 22:43:15'),
	(29, 23, '2025-01-02', 1, '2025-01-02 08:15:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 15, 0, 0, 0, 0, 'late', 0, NULL, NULL, NULL, NULL, '2026-01-30 22:43:15', '2026-01-30 22:43:15'),
	(32, 1, '2026-02-08', NULL, '2026-02-08 22:26:47', NULL, -7.82466400, 110.35832900, NULL, 'uploads/attendance/checkin_1_1770564407.jpg', NULL, '2026-02-08 22:27:14', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'present', 0, NULL, NULL, NULL, NULL, '2026-02-08 15:26:47', '2026-02-08 15:27:14'),
	(33, 13, '2026-02-08', NULL, '2026-02-08 22:29:21', NULL, -7.82463600, 110.35824380, NULL, 'uploads/attendance/checkin_13_1770564561.jpg', NULL, '2026-02-09 05:39:16', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'present', 0, NULL, NULL, NULL, NULL, '2026-02-08 15:29:21', '2026-02-08 22:39:16'),
	(36, 1, '2026-02-08', NULL, '2026-02-09 06:20:46', NULL, -7.82466400, 110.35832900, NULL, 'uploads/attendance/checkin_1_1770592846.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'present', 0, NULL, NULL, NULL, NULL, '2026-02-08 23:20:46', '2026-02-08 23:20:46'),
	(37, 13, '2026-02-08', NULL, '2026-02-09 06:34:04', NULL, -7.82458600, 110.35821230, NULL, 'uploads/attendance/checkin_13_1770593644.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'present', 0, NULL, NULL, NULL, NULL, '2026-02-08 23:34:04', '2026-02-08 23:34:04'),
	(41, 13, '2026-02-09', NULL, '2026-02-09 10:23:45', NULL, -7.82463480, 110.35824270, NULL, 'uploads/attendance/checkin_13_1770607425.jpg', NULL, '2026-02-10 00:52:54', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'present', 0, NULL, NULL, NULL, NULL, '2026-02-09 03:23:45', '2026-02-09 17:52:54'),
	(42, 13, '2026-02-09', NULL, '2026-02-10 01:06:52', NULL, -7.82459010, 110.35821330, NULL, 'uploads/attendance/checkin_13_1770660412.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'present', 0, NULL, NULL, NULL, NULL, '2026-02-09 18:06:52', '2026-02-09 18:06:52'),
	(43, 13, '2026-02-10', NULL, '2026-02-10 11:49:20', NULL, -7.82464010, 110.35824230, NULL, 'uploads/attendance/checkin_13_1770698960.jpg', NULL, '2026-02-10 12:46:55', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'present', 0, NULL, NULL, NULL, NULL, '2026-02-10 04:49:20', '2026-02-10 05:46:55'),
	(44, 8, '2026-02-14', 1, '2026-02-14 08:00:00', 1, NULL, NULL, 'Manual by Admin', NULL, 'pemkot', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'present', 0, NULL, NULL, NULL, NULL, '2026-02-14 03:49:48', '2026-02-14 03:49:48'),
	(47, 5, '2026-04-01', 1, '2026-04-01 07:45:00', NULL, NULL, NULL, 'Manual by Admin', NULL, 'GPS error', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 'present', 0, NULL, NULL, NULL, NULL, '2026-04-01 01:42:13', '2026-04-01 01:42:13');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
