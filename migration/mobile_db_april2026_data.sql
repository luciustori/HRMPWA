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

-- Dumping structure for table mobile_db.activity_logs
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `action` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `action` (`action`),
  KEY `module` (`module`),
  KEY `created_at` (`created_at`),
  KEY `idx_user_date` (`user_id`,`created_at`),
  CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.activity_logs: ~0 rows (approximately)
DELETE FROM `activity_logs`;

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

-- Dumping structure for table mobile_db.announcements
CREATE TABLE IF NOT EXISTS `announcements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_id` int NOT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `attachment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_type` enum('all','department','employee') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'all',
  `target_id` int DEFAULT NULL,
  `type` enum('info','warning','danger','success') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'info',
  `is_event` tinyint(1) NOT NULL DEFAULT '0',
  `target_audience` enum('all','department','specific') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'all',
  `department_id` int DEFAULT NULL,
  `target_employee_ids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'JSON/CSV IDs of specific employees',
  `created_by` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `event_date` date DEFAULT NULL,
  `event_time` time DEFAULT NULL,
  `event_location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  KEY `department_id` (`department_id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  CONSTRAINT `announcements_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  CONSTRAINT `announcements_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.announcements: ~0 rows (approximately)
DELETE FROM `announcements`;

-- Dumping structure for table mobile_db.app_settings
CREATE TABLE IF NOT EXISTS `app_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `setting_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `setting_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'text',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table mobile_db.app_settings: ~13 rows (approximately)
DELETE FROM `app_settings`;
INSERT INTO `app_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `updated_at`) VALUES
	(1, 'app_name', 'HRIS XT', 'text', '2026-02-11 22:38:11'),
	(2, 'app_logo', 'uploads/settings/app_logo_1770782158.png', 'image', '2026-02-11 03:55:58'),
	(3, 'app_tagline', 'Management', 'text', '2026-02-11 22:38:11'),
	(4, 'company_name', 'PT. Maju Jaya Abadi', 'text', '2026-02-11 22:38:11'),
	(5, 'primary_color', '#3f46a6', 'color', '2026-02-11 22:38:11'),
	(6, 'support_email', 'support@company.com', 'email', '2026-02-11 19:54:13'),
	(7, 'company_address', 'Jl. Jendral Sudirman No. 1, Jakarta', 'text', '2026-02-11 22:38:11'),
	(8, 'company_phone', '0812-3456-7890', 'text', '2026-02-11 22:38:11'),
	(9, 'company_logo', 'uploads/settings/logo_company_1770849491.png', 'image', '2026-02-11 22:38:11'),
	(10, 'payslip_format', 'modern', 'text', '2026-02-11 22:38:11'),
	(11, 'late_tolerance', '5', 'number', '2026-02-11 22:38:11'),
	(12, 'company_email', '', 'text', '2026-02-11 22:38:11'),
	(13, 'company_website', '', 'text', '2026-02-11 22:38:11');

-- Dumping structure for table mobile_db.attendance
CREATE TABLE IF NOT EXISTS `attendance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `attendance_date` date NOT NULL,
  `check_in_time` time DEFAULT NULL,
  `check_out_time` time DEFAULT NULL,
  `status` enum('on_time','late','absent','leave') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'on_time',
  `check_in_location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_out_location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_in_latitude` decimal(10,8) DEFAULT NULL,
  `check_in_longitude` decimal(11,8) DEFAULT NULL,
  `check_out_latitude` decimal(10,8) DEFAULT NULL,
  `check_out_longitude` decimal(11,8) DEFAULT NULL,
  `attendance_status` enum('on_time','terlambat','tidak_hadir') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'tidak_hadir',
  `late_minutes` int DEFAULT '0',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_attendance` (`employee_id`,`attendance_date`),
  CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.attendance: ~0 rows (approximately)
DELETE FROM `attendance`;

-- Dumping structure for table mobile_db.attendance_issues
CREATE TABLE IF NOT EXISTS `attendance_issues` (
  `id` int NOT NULL AUTO_INCREMENT,
  `attendance_id` int NOT NULL,
  `employee_id` int NOT NULL,
  `issue_type` enum('late','early_out','no_check_in','no_check_out','invalid_location') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `issue_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `submitted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `employee_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Employee explanation',
  `supporting_document` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Photo/document proof',
  `status` enum('pending','approved','rejected','resolved') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `reviewed_by` int DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `reviewer_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `attendance_id` (`attendance_id`),
  KEY `employee_id` (`employee_id`),
  KEY `issue_type` (`issue_type`),
  KEY `status` (`status`),
  KEY `attendance_issues_ibfk_3` (`reviewed_by`),
  CONSTRAINT `attendance_issues_ibfk_1` FOREIGN KEY (`attendance_id`) REFERENCES `attendance_records` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendance_issues_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendance_issues_ibfk_3` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.attendance_issues: ~0 rows (approximately)
DELETE FROM `attendance_issues`;

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

-- Dumping structure for table mobile_db.bpjs_rates
CREATE TABLE IF NOT EXISTS `bpjs_rates` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bpjs_type` enum('kesehatan','ketenagakerjaan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `component_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'JHT, JKK, JKM, JP, Kesehatan',
  `company_rate` decimal(5,2) DEFAULT '0.00' COMMENT 'Company contribution %',
  `employee_rate` decimal(5,2) DEFAULT '0.00' COMMENT 'Employee contribution %',
  `max_salary_base` decimal(15,2) DEFAULT NULL COMMENT 'Maximum salary for calculation',
  `min_salary_base` decimal(15,2) DEFAULT NULL COMMENT 'Minimum salary for calculation',
  `effective_date` date NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `bpjs_type` (`bpjs_type`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.bpjs_rates: ~5 rows (approximately)
DELETE FROM `bpjs_rates`;
INSERT INTO `bpjs_rates` (`id`, `bpjs_type`, `component_name`, `company_rate`, `employee_rate`, `max_salary_base`, `min_salary_base`, `effective_date`, `notes`, `is_active`, `created_at`) VALUES
	(1, 'kesehatan', 'BPJS Kesehatan', 4.00, 1.00, 12000000.00, NULL, '2024-01-01', 'Rate tahun 2024', 1, '2026-01-01 05:30:57'),
	(2, 'ketenagakerjaan', 'JHT (Jaminan Hari Tua)', 3.70, 2.00, NULL, NULL, '2024-01-01', 'Jaminan Hari Tua', 1, '2026-01-01 05:30:57'),
	(3, 'ketenagakerjaan', 'JKK (Jaminan Kecelakaan Kerja)', 0.24, 0.00, NULL, NULL, '2024-01-01', 'Rate minimal, sesuai risiko perusahaan', 1, '2026-01-01 05:30:57'),
	(4, 'ketenagakerjaan', 'JKM (Jaminan Kematian)', 0.30, 0.00, NULL, NULL, '2024-01-01', 'Jaminan Kematian', 1, '2026-01-01 05:30:57'),
	(5, 'ketenagakerjaan', 'JP (Jaminan Pensiun)', 2.00, 1.00, 9559600.00, NULL, '2024-01-01', 'Jaminan Pensiun - max salary 1% UMP', 1, '2026-01-01 05:30:57');

-- Dumping structure for table mobile_db.business_trip_requests
CREATE TABLE IF NOT EXISTS `business_trip_requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `trip_purpose` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `destination` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_days` int NOT NULL,
  `transportation` enum('flight','train','bus','car','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'car',
  `accommodation_needed` tinyint(1) DEFAULT '0',
  `estimated_budget` decimal(12,2) DEFAULT '0.00',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `supporting_document` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `reviewed_by` int DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `reviewer_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `director_approved_by` int DEFAULT NULL,
  `director_approved_at` datetime DEFAULT NULL,
  `director_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `status` (`status`),
  KEY `start_date` (`start_date`),
  KEY `end_date` (`end_date`),
  KEY `reviewed_by` (`reviewed_by`),
  KEY `director_approved_by` (`director_approved_by`),
  KEY `idx_employee_trip` (`employee_id`,`start_date`,`end_date`),
  CONSTRAINT `business_trip_requests_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `business_trip_requests_ibfk_2` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `business_trip_requests_ibfk_3` FOREIGN KEY (`director_approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.business_trip_requests: ~2 rows (approximately)
DELETE FROM `business_trip_requests`;
INSERT INTO `business_trip_requests` (`id`, `employee_id`, `trip_purpose`, `destination`, `start_date`, `end_date`, `total_days`, `transportation`, `accommodation_needed`, `estimated_budget`, `description`, `supporting_document`, `status`, `reviewed_by`, `reviewed_at`, `reviewer_notes`, `director_approved_by`, `director_approved_at`, `director_notes`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Diklat Coretax', 'Semarang', '2026-01-19', '2026-01-22', 4, 'other', 1, 400000.00, 'biaya 50% ditanggung oleh Pelaksana', NULL, 'approved', 3, '2026-01-15 07:12:29', '', NULL, NULL, NULL, '2026-01-05 17:57:16', '2026-01-14 17:12:29'),
	(2, 13, 'studi tour', 'london', '2026-02-22', '2026-02-27', 6, 'car', 0, 1000000.00, NULL, '698c62d9e236c.pdf', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-11 11:07:05', '2026-02-11 11:07:05');

-- Dumping structure for table mobile_db.companies
CREATE TABLE IF NOT EXISTS `companies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `favicon_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company_code` (`company_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.companies: ~1 rows (approximately)
DELETE FROM `companies`;
INSERT INTO `companies` (`id`, `company_name`, `company_code`, `address`, `phone`, `email`, `website`, `logo_path`, `favicon_path`, `created_at`, `updated_at`) VALUES
	(1, 'PT. Maju Jaya', 'MJ', 'Jl. Ahmad Yani No. 123, Yogyakarta', '0274-123456', 'info@majujaya.com', 'https://www.majujaya.com', 'uploads/logos/logo_1770832866.png', NULL, '2025-12-29 04:31:27', '2026-02-11 18:01:06');

-- Dumping structure for table mobile_db.departments
CREATE TABLE IF NOT EXISTS `departments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_id` int NOT NULL,
  `department_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` int DEFAULT NULL,
  `level` enum('department','division','section') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'department',
  `department_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `manager_id` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `departments_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.departments: ~2 rows (approximately)
DELETE FROM `departments`;
INSERT INTO `departments` (`id`, `company_id`, `department_name`, `parent_id`, `level`, `department_code`, `description`, `is_active`, `manager_id`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Departemen Keuangan HR GA', NULL, 'department', 'FIN', 'mengurusi keuangan, SDM dan Umum', 1, 13, '2025-12-29 04:31:43', '2026-02-05 02:39:28'),
	(2, 1, 'Departemen Marketing Operasional', NULL, 'department', 'MKT', 'Mengurusi Operasional dan Marketing', 1, 2, '2025-12-29 04:31:43', '2026-02-05 02:39:39');

-- Dumping structure for table mobile_db.divisions
CREATE TABLE IF NOT EXISTS `divisions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `department_id` int NOT NULL,
  `coordinator_id` int DEFAULT NULL,
  `division_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `division_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `department_id` (`department_id`),
  KEY `fk_division_coordinator` (`coordinator_id`),
  CONSTRAINT `divisions_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_division_coordinator` FOREIGN KEY (`coordinator_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.divisions: ~5 rows (approximately)
DELETE FROM `divisions`;
INSERT INTO `divisions` (`id`, `department_id`, `coordinator_id`, `division_name`, `division_code`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 1, 17, 'Teknologi Informasi Support', 'IT-DEV', 'IT Support XT Square', 1, '2026-02-03 00:32:22', '2026-02-05 02:43:27'),
	(3, 1, 15, 'Cleaning Services', 'CS', 'Pasukan Cleaning Services', 1, '2026-02-03 00:32:22', '2026-02-05 02:40:37'),
	(4, 2, 7, 'Parkir', 'PAR', 'Divisi Parkir', 1, '2026-02-03 00:32:22', '2026-02-05 02:41:38'),
	(5, 2, 4, 'XT Cafe', 'CAFE', 'XT Cafe', 1, '2026-02-03 00:32:22', '2026-02-05 02:42:39'),
	(6, 1, 8, 'Account Payable', 'AP', 'Account Payable', 1, '2026-02-04 19:52:42', '2026-02-05 02:39:52');

-- Dumping structure for table mobile_db.employee_daily_moods
CREATE TABLE IF NOT EXISTS `employee_daily_moods` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `mood` enum('happy','neutral','tired') NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_mood_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table mobile_db.employee_daily_moods: ~0 rows (approximately)
DELETE FROM `employee_daily_moods`;

-- Dumping structure for table mobile_db.employee_leave_balance
CREATE TABLE IF NOT EXISTS `employee_leave_balance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `leave_type_id` int NOT NULL,
  `year` int NOT NULL,
  `total_entitled` int DEFAULT '0' COMMENT 'Total days entitled',
  `total_used` int DEFAULT '0' COMMENT 'Total days used',
  `total_remaining` int DEFAULT '0' COMMENT 'Remaining days',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_balance` (`employee_id`,`leave_type_id`,`year`),
  KEY `employee_id` (`employee_id`),
  KEY `leave_type_id` (`leave_type_id`),
  KEY `year` (`year`),
  CONSTRAINT `employee_leave_balance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `employee_leave_balance_ibfk_2` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.employee_leave_balance: ~2 rows (approximately)
DELETE FROM `employee_leave_balance`;
INSERT INTO `employee_leave_balance` (`id`, `employee_id`, `leave_type_id`, `year`, `total_entitled`, `total_used`, `total_remaining`, `updated_at`) VALUES
	(36, 1, 11, 2026, 0, 3, 0, '2026-02-09 12:47:48'),
	(37, 1, 2, 2026, 0, 2, 0, '2026-02-11 10:21:41');

-- Dumping structure for table mobile_db.employee_salary_components
CREATE TABLE IF NOT EXISTS `employee_salary_components` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `component_id` int NOT NULL,
  `amount` decimal(15,2) DEFAULT '0.00' COMMENT 'Fixed amount for this employee',
  `percentage` decimal(5,2) DEFAULT '0.00' COMMENT 'Percentage for this employee (if different from default)',
  `custom_formula` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Custom formula override',
  `effective_date` date NOT NULL,
  `end_date` date DEFAULT NULL COMMENT 'NULL = permanent',
  `is_active` tinyint(1) DEFAULT '1',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_employee_component` (`employee_id`,`component_id`,`effective_date`),
  KEY `employee_id` (`employee_id`),
  KEY `component_id` (`component_id`),
  KEY `effective_date` (`effective_date`),
  KEY `is_active` (`is_active`),
  CONSTRAINT `employee_salary_components_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `employee_salary_components_ibfk_2` FOREIGN KEY (`component_id`) REFERENCES `payroll_components` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.employee_salary_components: ~0 rows (approximately)
DELETE FROM `employee_salary_components`;

-- Dumping structure for table mobile_db.employees
CREATE TABLE IF NOT EXISTS `employees` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT '1',
  `employee_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('male','female') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Jenis Kelamin',
  `marital_status` enum('single','married','divorced','widowed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'single' COMMENT 'Status Pernikahan',
  `number_of_dependents` int DEFAULT '0' COMMENT 'Jumlah Tanggungan',
  `npwp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nomor Pokok Wajib Pajak',
  `bank_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nama Bank',
  `bank_account_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nomor Rekening',
  `bank_account_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nama Pemilik Rekening',
  `full_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS (concat(`first_name`,_utf8mb4' ',`last_name`)) STORED,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `identity_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `profile_photo_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `face_recognition_photo_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` int DEFAULT NULL,
  `division_id` int DEFAULT NULL,
  `salary_grade_id` int DEFAULT NULL,
  `employee_level` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `salary` decimal(15,2) DEFAULT '0.00',
  `position` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_status` enum('active','inactive','suspended') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `annual_leave_balance` int DEFAULT '12',
  `hire_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_number` (`employee_number`),
  KEY `department_id` (`department_id`),
  KEY `gender` (`gender`),
  KEY `marital_status` (`marital_status`),
  KEY `bank_account_number` (`bank_account_number`),
  KEY `fk_emp_grade` (`salary_grade_id`),
  KEY `division_id` (`division_id`),
  CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_emp_grade` FOREIGN KEY (`salary_grade_id`) REFERENCES `salary_grades` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.employees: ~24 rows (approximately)
DELETE FROM `employees`;
INSERT INTO `employees` (`id`, `company_id`, `employee_number`, `first_name`, `last_name`, `gender`, `marital_status`, `number_of_dependents`, `npwp`, `bank_name`, `bank_account_number`, `bank_account_name`, `email`, `phone`, `date_of_birth`, `identity_number`, `address`, `profile_photo_path`, `face_recognition_photo_path`, `department_id`, `division_id`, `salary_grade_id`, `employee_level`, `salary`, `position`, `employee_status`, `annual_leave_balance`, `hire_date`, `created_at`, `updated_at`, `is_active`) VALUES
	(1, 1, 'A-10.001', 'Super', 'Admin', 'male', 'single', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 'director', 0.00, 'Direktur PDJV', 'active', 12, NULL, '2026-01-23 19:19:55', '2026-01-29 09:44:31', 1),
	(2, 1, 'T-20.001', 'Anggoro', 'Suharjanto', 'male', 'single', 0, '', 'BCA', '', '', '', '+62 822-2659-2557', '1984-07-23', '', 'Bausasran DN 3/634 RT 32 RW 9 Bausasran Danurejan Yogyakarta', NULL, NULL, 2, NULL, 5, 'manager', 0.00, 'Manager Marketing dan Operasional', 'active', 12, '2012-11-01', '2026-01-24 17:49:41', '2026-02-06 10:55:09', 1),
	(3, 1, 'T-21.003', 'Diana', 'Aprilia Safitri', 'male', 'single', 0, '', 'BCA', '', '', '', '+62 856-8670-888', '1988-04-25', '', 'Nogosari Kidul KP III/60 Yk RT 003 RW 001 Kadipeten Kraton Yogyakarta', NULL, NULL, 2, NULL, 4, 'supervisor', 0.00, 'Supervisor Administrasi dan Keuangan Bisnis', 'active', 12, '2013-01-04', '2026-01-24 17:49:41', '2026-02-06 10:55:20', 1),
	(4, 1, 'T-21.004', 'Thomas', 'Anggi Van Hendrawan', 'male', 'single', 0, '', 'BCA', '', '', '', '+62 856-5527-7123', '1990-01-28', '', 'Jl. Nangka 3 No. 51, Karangnongko, Maguwo, Depok, Sleman', NULL, NULL, 2, NULL, NULL, 'supervisor', 0.00, 'Supervisor Building dan Unit Bisnis Perumahan', 'active', 12, '2014-10-08', '2026-01-24 17:49:41', '2026-02-05 06:58:57', 1),
	(5, 1, 'T-22.005', 'Agus', 'Wardoyo', 'male', 'single', 0, '', 'BCA', '', '', '', '', '1982-09-17', '', 'Sambego no. 16A RT 14 RW 38 Maguwoharjo Depok Sleman', 'uploads/profiles/profile_5_1770516605.png', NULL, 1, NULL, 2, 'staff', 0.00, 'Koordinator Teknisi', 'active', 12, '2013-02-04', '2026-01-24 17:49:41', '2026-02-07 19:10:05', 1),
	(6, 1, 'T-22.006', 'Edi', 'Purwanto', 'male', 'single', 0, '', '', '', '', '', '+62 818-0435-6100', '1979-04-19', '', 'Trini RT 007/RW 018, Trihanggo, Gamping, Sleman', NULL, NULL, 2, NULL, NULL, 'staff', 0.00, 'Teknisi', 'active', 12, '2013-12-03', '2026-01-24 17:49:41', '2026-01-27 02:42:59', 1),
	(7, 1, 'T-23.010', 'Muhammad', 'Roichan Juni Saputra', 'male', 'single', 0, '', '', '', '', '', '+62 812-1693-9885', '1984-06-16', '', 'Jl. Patehan Kidul No. 10 RT 020/005, Patehan, Kraton, Yogyakarta', NULL, NULL, 2, NULL, NULL, 'staff', 0.00, 'Koordinator Unit Bisnis Parkir', 'active', 12, '2018-07-03', '2026-01-24 17:49:41', '2026-01-27 02:43:21', 1),
	(8, 1, 'T-23.011', 'Girian', 'Subekti', 'male', 'single', 0, '', '', '', '', '', '+62 858-6829-7846', '1992-08-24', '', 'Karanganom, RT 004/ 000, Wonokromo, Pleret, Bantul', NULL, NULL, 2, NULL, NULL, 'staff', 0.00, 'Teknisi', 'active', 12, '2017-04-13', '2026-01-24 17:49:41', '2026-01-27 02:43:13', 1),
	(9, 1, 'T-25.012', 'Muhammad', 'Nafi\' Maula', 'male', 'single', 0, '', '', '', '', '', '+62 852-6836-0024', '2000-01-07', '', 'Dusun Sendang RT.001 RW.003 Jetis, Kaliwungu, Kab. Semarang, Jawa Tengah', NULL, NULL, 2, NULL, NULL, 'staff', 0.00, 'Koordinator Existing XT Square dan Unit Bisnis Retail', 'active', 12, '2021-02-09', '2026-01-24 17:49:41', '2026-01-27 02:45:28', 1),
	(10, 1, 'T-25.013', 'Haryanto', '', 'male', 'single', 0, '', '', '', '', '', '+62 852-9218-7198', '1981-02-09', '', 'Gunungcilik RT 004, Muntuk, Dlingo, Bantul', NULL, NULL, 2, NULL, NULL, 'staff', 0.00, 'Staff Retail', 'active', 12, '2017-01-02', '2026-01-24 17:49:41', '2026-01-27 02:42:28', 1),
	(11, 1, 'K-25.060', 'Anggraini', 'Retno Wulandari', 'male', 'single', 0, '', 'BCA', '', '', '', '+62 877-3898-9389', '1987-08-30', '', 'Kemetiran Kidul GT.II/733 RT 056 RW 016 Pringgokusuman Gedongtengan Yogyakarta', NULL, NULL, 2, 5, 2, 'staff', 0.00, 'Koordinator Cafe dan Pelatihan', 'active', 12, '2025-07-05', '2026-01-24 17:49:41', '2026-02-11 15:37:56', 1),
	(12, 1, 'T-25.015', 'Jumiyanto', '', 'male', 'single', 0, '', '', '', '', '', '+62 882-1654-8204', '1983-02-28', '', 'Pelemgede RT 003/RW 003, Sodo, Paliyan, Gunungkidul', NULL, NULL, 2, NULL, NULL, 'staff', 0.00, 'Teknisi', 'active', 12, '2017-03-07', '2026-01-24 17:49:41', '2026-01-27 02:43:37', 1),
	(13, 1, 'T-20.002', 'Windy', 'Kusuma Jayanti', 'male', 'single', 0, '', '', '', '', 'yohannawindy@gmail.com', '+62 851-3336-0405', '1988-05-31', '', 'Jl. R.E. Martadinata 35 Wirobrajan Yogyakarta 55252', 'uploads/profiles/profile_13_1771217844.jpg', NULL, 1, NULL, 5, 'manager', 0.00, 'Manager SDM, Keuangan, dan Umum', 'active', 12, '2012-11-02', '2026-01-24 17:49:41', '2026-02-16 04:57:24', 1),
	(14, 1, 'T-22.007', 'Wahyu', 'Dianto', 'male', 'single', 0, '', '', '', '', '', '+62 857-8344-9953', '1992-11-21', '', 'Kenalan Dk. VIII Kenalan RT 03 Bangunjiwo Kasihan Bantul', NULL, NULL, 1, NULL, NULL, 'staff', 0.00, 'Office Boy', 'active', 12, '2012-12-24', '2026-01-24 17:49:41', '2026-01-27 02:44:41', 1),
	(15, 1, 'T-22.008', 'Patrick', 'Anang Priyambada', 'male', 'single', 0, '', '', '', '', '', '+62 856-2949-456', '1989-03-28', '', 'Klumutan RT013/007, Srikayangan, Sentolo, Kulonprogo', NULL, NULL, 1, NULL, NULL, 'staff', 0.00, 'General Affair', 'active', 12, '2018-04-16', '2026-01-24 17:49:41', '2026-01-27 02:43:28', 1),
	(16, 1, 'T-22.009', 'Thomas', 'Yudhistira', 'male', 'single', 0, '', '', '', '', '', '+62 856-4043-8643', '1989-10-03', '', 'Pilahan Asri II no. 34 RT 045/011, Rejowinangun, Kotagede, Yogyakarta', NULL, NULL, 1, NULL, NULL, 'supervisor', 0.00, 'Satuan Pengawas Internal', 'active', 12, '2018-04-16', '2026-01-24 17:49:41', '2026-01-27 02:44:18', 1),
	(17, 1, 'T-25.014', 'Swandika', 'Addi Wicaksono', NULL, 'single', 0, NULL, NULL, NULL, NULL, NULL, '+62 817-0606-398', '1990-04-12', NULL, 'Perumahan Ndalem Guwosari No 141 Gang Rukun RT 005 RW 002 Guwosari Pajangan Bantul', NULL, NULL, 2, NULL, NULL, 'staff', 0.00, 'Staff IT dan Desain Grafis', 'active', 12, '2019-03-14', '2026-01-24 17:49:41', '2026-01-24 17:49:41', 1),
	(18, 1, 'K-19.058', 'Rian', 'Mei Hermawan', NULL, 'single', 0, NULL, NULL, NULL, NULL, NULL, '+62 852-3210-7447', '1987-05-29', NULL, 'Kemloko RT 06 RW 20 Kel. Margorejo Kec. Tempel Sleman Yogyakarta 55552', NULL, NULL, 2, NULL, NULL, 'staff', 0.00, 'Staff Keuangan dan Pajak', 'active', 12, '2022-12-26', '2026-01-24 17:49:41', '2026-01-24 17:49:41', 1),
	(19, 1, 'K-25.059', 'Andri', 'Yokas Permadi', NULL, 'single', 0, NULL, NULL, NULL, NULL, NULL, '+62 895-0535-2720', '1998-07-13', NULL, 'Wonoroto RT 02 RW - Gadingsari Sanden Bantul', NULL, NULL, 2, NULL, 2, 'staff', 0.00, 'Staff Account Receivable Billing', 'active', 12, '2022-01-01', '2026-01-24 17:49:41', '2026-02-06 12:09:33', 1),
	(20, 1, 'H-22.008', 'Henry', 'Cahyono', 'male', 'single', 0, '', 'BCA', '', '', '', '+62 857-1201-0752', '1976-04-16', '', 'Sidomulyo TR IV/228 RT 019 RW 005 Bener Tegalrejo Yogyakarta', NULL, NULL, 1, 3, 1, 'harian', 0.00, 'Staff Cleaning', 'active', 12, '2022-01-01', '2026-01-24 17:49:41', '2026-02-06 12:31:56', 1),
	(21, 1, 'H-22.009', 'Rismanto', '', 'male', 'single', 0, '', 'BCA', '', '', '', '+62 818-0262-5227', '1969-08-11', '', 'Serangan NG II/73 RT 008 RW 002 Notorajan Ngampilan Yogyakarta', NULL, NULL, 1, 3, NULL, 'harian', 0.00, 'Staff Cleaning', 'active', 12, '2022-01-01', '2026-01-24 17:49:41', '2026-02-06 12:31:40', 1),
	(22, 1, 'H-22.010', 'Sankan', 'Iswanto', NULL, 'single', 0, NULL, NULL, NULL, NULL, NULL, '+62 856-0094-3385', '1980-10-12', NULL, 'Tegal RT 002 RW 014 Sidoarum Godean Sleman', NULL, NULL, 2, NULL, NULL, 'harian', 0.00, 'Staff Cleaning', 'active', 12, '2022-01-01', '2026-01-24 17:49:41', '2026-01-24 17:49:41', 1),
	(23, 1, 'H-22.011', 'Triyono', '', 'male', 'single', 0, '', 'BCA', '', '', '', '+62 897-6072-727', '1981-09-23', '', 'Ngaran RT 001 Gilangharjo Pandak Bantul', NULL, NULL, 1, 3, 1, 'harian', 0.00, 'Staff Cleaning', 'active', 12, '2022-01-01', '2026-01-24 17:49:41', '2026-02-06 12:31:21', 1),
	(25, 1, 'D-26.004', 'Hariyono', '', 'male', 'married', 0, '', '', '', '', 'hariyonost06@gmail.com', '123456789', '1970-01-01', '123456789', '', NULL, NULL, NULL, NULL, 6, 'direktur', 0.00, 'Direktur Utama', 'active', 12, '2026-04-02', '2026-04-02 10:02:37', '2026-04-02 10:02:37', 1);

-- Dumping structure for table mobile_db.employment_contracts
CREATE TABLE IF NOT EXISTS `employment_contracts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `contract_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contract_type` enum('PKWT','PKWTT','Internship','Probation') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PKWT',
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL COMMENT 'NULL jika PKWTT (Permanent)',
  `document_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','expired','terminated') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `status` (`status`),
  KEY `end_date` (`end_date`),
  CONSTRAINT `fk_contract_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.employment_contracts: ~0 rows (approximately)
DELETE FROM `employment_contracts`;

-- Dumping structure for table mobile_db.holidays
CREATE TABLE IF NOT EXISTS `holidays` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_id` int NOT NULL DEFAULT '1',
  `holiday_date` date NOT NULL,
  `holiday_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `holiday_type` enum('national','company','religious') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'national',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `holiday_year` int GENERATED ALWAYS AS (year(`holiday_date`)) STORED,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_holiday` (`company_id`,`holiday_date`),
  KEY `company_id` (`company_id`),
  KEY `holiday_date` (`holiday_date`),
  KEY `holiday_type` (`holiday_type`),
  KEY `idx_holiday_date` (`holiday_date`),
  KEY `idx_holiday_year` (`holiday_year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.holidays: ~27 rows (approximately)
DELETE FROM `holidays`;
INSERT INTO `holidays` (`id`, `company_id`, `holiday_date`, `holiday_name`, `holiday_type`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 1, '2026-01-01', 'Tahun Baru 2026', 'national', NULL, 1, '2025-12-31 13:27:26', '2025-12-31 13:27:26'),
	(2, 1, '2026-03-22', 'Isra Miraj Nabi Muhammad SAW', 'religious', NULL, 1, '2025-12-31 13:27:26', '2025-12-31 13:27:26'),
	(4, 1, '2026-04-03', 'Wafat Isa Al-Masih', 'religious', NULL, 1, '2025-12-31 13:27:26', '2025-12-31 13:27:26'),
	(7, 1, '2026-05-01', 'Hari Buruh Internasional', 'national', NULL, 1, '2025-12-31 13:27:26', '2025-12-31 13:27:26'),
	(8, 1, '2026-05-14', 'Kenaikan Isa Al-Masih', 'religious', NULL, 1, '2025-12-31 13:27:26', '2025-12-31 13:27:26'),
	(9, 1, '2026-05-21', 'Hari Raya Waisak', 'religious', NULL, 1, '2025-12-31 13:27:26', '2025-12-31 13:27:26'),
	(10, 1, '2026-06-01', 'Hari Lahir Pancasila', 'national', NULL, 1, '2025-12-31 13:27:26', '2025-12-31 13:27:26'),
	(11, 1, '2026-06-11', 'Idul Adha 1447 H', 'religious', NULL, 1, '2025-12-31 13:27:26', '2025-12-31 13:27:26'),
	(12, 1, '2026-07-01', 'Tahun Baru Islam 1448 H', 'religious', NULL, 1, '2025-12-31 13:27:26', '2025-12-31 13:27:26'),
	(13, 1, '2026-08-17', 'Hari Kemerdekaan RI', 'national', NULL, 1, '2025-12-31 13:27:26', '2025-12-31 13:27:26'),
	(15, 1, '2026-12-25', 'Hari Raya Natal', 'religious', NULL, 1, '2025-12-31 13:27:26', '2025-12-31 13:27:26'),
	(16, 1, '2026-02-17', 'Tahun Baru Imlek 2577 Kongzili', 'religious', '', 1, '2025-12-31 14:01:09', '2025-12-31 14:01:09'),
	(17, 1, '2026-01-16', 'Isra Mi\'raj Nabi Muhammad SAW', 'religious', '', 0, '2025-12-31 14:01:45', '2026-02-11 06:01:55'),
	(18, 1, '2026-12-22', 'HUT PERUSAHAAN', 'company', 'HUT PERUSAHAAN', 1, '2026-02-11 06:04:20', '2026-02-11 06:04:20'),
	(19, 1, '2026-02-16', 'Tahun Baru Imlek 2577 Kongzili', 'national', 'Libur Nasional (Auto Sync API)', 1, '2026-03-25 05:49:13', '2026-03-25 05:49:13'),
	(20, 1, '2026-03-18', 'Hari Suci Nyepi (Tahun Baru Saka 1948)', 'national', 'Libur Nasional (Auto Sync API)', 1, '2026-03-25 05:49:13', '2026-03-25 05:49:13'),
	(21, 1, '2026-03-19', 'Hari Suci Nyepi (Tahun Baru Saka 1948)', 'national', 'Libur Nasional (Auto Sync API)', 1, '2026-03-25 05:49:13', '2026-03-25 05:49:13'),
	(22, 1, '2026-03-20', 'Idul Fitri 1447 Hijriah', 'national', 'Libur Nasional (Auto Sync API)', 1, '2026-03-25 05:49:13', '2026-03-25 05:49:13'),
	(23, 1, '2026-03-21', 'Idul Fitri 1447 Hijriah', 'national', 'Libur Nasional (Auto Sync API)', 1, '2026-03-25 05:49:13', '2026-03-25 05:49:13'),
	(24, 1, '2026-03-23', 'Idul Fitri 1447 Hijriah', 'national', 'Libur Nasional (Auto Sync API)', 1, '2026-03-25 05:49:13', '2026-03-25 05:49:13'),
	(25, 1, '2026-03-24', 'Idul Fitri 1447 Hijriah', 'national', 'Libur Nasional (Auto Sync API)', 1, '2026-03-25 05:49:13', '2026-03-25 05:49:13'),
	(26, 1, '2026-05-15', 'Kenaikan Yesus Kristus', 'national', 'Libur Nasional (Auto Sync API)', 1, '2026-03-25 05:49:13', '2026-03-25 05:49:13'),
	(27, 1, '2026-05-27', 'Idul Adha 1447 Hijriah', 'national', 'Libur Nasional (Auto Sync API)', 1, '2026-03-25 05:49:13', '2026-03-25 05:49:13'),
	(28, 1, '2026-05-28', 'Idul Adha 1447 Hijriah', 'national', 'Libur Nasional (Auto Sync API)', 1, '2026-03-25 05:49:13', '2026-03-25 05:49:13'),
	(29, 1, '2026-05-31', 'Hari Raya Waisak 2570 BE', 'national', 'Libur Nasional (Auto Sync API)', 1, '2026-03-25 05:49:13', '2026-03-25 05:49:13'),
	(30, 1, '2026-06-16', '1 Muharam Tahun Baru Islam 1448 Hijriah', 'national', 'Libur Nasional (Auto Sync API)', 1, '2026-03-25 05:49:13', '2026-03-25 05:49:13'),
	(31, 1, '2026-08-25', 'Maulid Nabi Muhammad S.A.W.', 'national', 'Libur Nasional (Auto Sync API)', 1, '2026-03-25 05:49:13', '2026-03-25 05:49:13');

-- Dumping structure for table mobile_db.kpi_summary
CREATE TABLE IF NOT EXISTS `kpi_summary` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `year` int NOT NULL,
  `month` int NOT NULL,
  `total_tasks_assigned` int DEFAULT '0',
  `total_tasks_completed` int DEFAULT '0',
  `total_tasks_on_time` int DEFAULT '0',
  `total_tasks_late` int DEFAULT '0',
  `average_completion_time` decimal(5,2) DEFAULT '0.00' COMMENT 'Average days to complete',
  `average_quality_score` decimal(5,2) DEFAULT '0.00',
  `total_estimated_hours` decimal(8,2) DEFAULT '0.00',
  `total_actual_hours` decimal(8,2) DEFAULT '0.00',
  `productivity_score` decimal(5,2) DEFAULT '0.00' COMMENT 'Calculated score 0-100',
  `kpi_score` decimal(5,2) DEFAULT '0.00' COMMENT 'Final KPI score 0-100',
  `calculated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_employee_period` (`employee_id`,`year`,`month`),
  KEY `employee_id` (`employee_id`),
  KEY `year_month` (`year`,`month`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.kpi_summary: ~70 rows (approximately)
DELETE FROM `kpi_summary`;
INSERT INTO `kpi_summary` (`id`, `employee_id`, `year`, `month`, `total_tasks_assigned`, `total_tasks_completed`, `total_tasks_on_time`, `total_tasks_late`, `average_completion_time`, `average_quality_score`, `total_estimated_hours`, `total_actual_hours`, `productivity_score`, `kpi_score`, `calculated_at`, `updated_at`) VALUES
	(1, 1, 2026, 1, 2, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:48:49', '2026-02-07 19:01:00'),
	(2, 2, 2026, 1, 1, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 1.00, '2026-02-07 18:48:49', '2026-02-07 19:01:00'),
	(3, 3, 2026, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:48:49', '2026-02-07 19:01:00'),
	(4, 4, 2026, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:48:49', '2026-02-07 19:01:00'),
	(5, 5, 2026, 1, 1, 1, 1, 0, 0.00, 0.00, 8.00, 0.00, 100.00, 50.00, '2026-02-07 18:48:49', '2026-02-07 19:01:00'),
	(6, 6, 2026, 1, 1, 1, 0, 1, 0.00, 0.00, 8.00, 2.00, 100.00, 31.90, '2026-02-07 18:48:49', '2026-02-07 19:01:00'),
	(7, 7, 2026, 1, 1, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:48:49', '2026-02-07 19:01:00'),
	(8, 8, 2026, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:48:49', '2026-02-07 19:01:00'),
	(9, 9, 2026, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:48:49', '2026-02-07 19:01:00'),
	(10, 10, 2026, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:48:49', '2026-02-07 19:01:00'),
	(11, 11, 2026, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:48:49', '2026-02-07 19:01:00'),
	(12, 12, 2026, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:48:49', '2026-02-07 19:01:00'),
	(13, 13, 2026, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:48:49', '2026-02-07 19:01:00'),
	(14, 14, 2026, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 2.90, '2026-02-07 18:48:49', '2026-02-07 19:01:00'),
	(15, 15, 2026, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:48:49', '2026-02-07 19:01:00'),
	(16, 16, 2026, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:48:49', '2026-02-07 19:01:00'),
	(17, 17, 2026, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:48:49', '2026-02-07 19:01:00'),
	(18, 18, 2026, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:48:49', '2026-02-07 19:01:00'),
	(19, 19, 2026, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:48:49', '2026-02-07 19:01:00'),
	(20, 20, 2026, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:48:49', '2026-02-07 19:01:00'),
	(21, 21, 2026, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:48:49', '2026-02-07 19:01:00'),
	(22, 22, 2026, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:48:49', '2026-02-07 19:01:00'),
	(23, 23, 2026, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:48:49', '2026-02-07 19:01:00'),
	(24, 1, 2025, 12, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:54:57', '2026-02-07 18:54:57'),
	(25, 2, 2025, 12, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:54:57', '2026-02-07 18:54:57'),
	(26, 3, 2025, 12, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:54:57', '2026-02-07 18:54:57'),
	(27, 4, 2025, 12, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:54:57', '2026-02-07 18:54:57'),
	(28, 5, 2025, 12, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:54:58', '2026-02-07 18:54:58'),
	(29, 6, 2025, 12, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:54:58', '2026-02-07 18:54:58'),
	(30, 7, 2025, 12, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:54:58', '2026-02-07 18:54:58'),
	(31, 8, 2025, 12, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:54:58', '2026-02-07 18:54:58'),
	(32, 9, 2025, 12, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:54:58', '2026-02-07 18:54:58'),
	(33, 10, 2025, 12, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:54:58', '2026-02-07 18:54:58'),
	(34, 11, 2025, 12, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:54:58', '2026-02-07 18:54:58'),
	(35, 12, 2025, 12, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:54:58', '2026-02-07 18:54:58'),
	(36, 13, 2025, 12, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:54:58', '2026-02-07 18:54:58'),
	(37, 14, 2025, 12, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:54:58', '2026-02-07 18:54:58'),
	(38, 15, 2025, 12, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:54:58', '2026-02-07 18:54:58'),
	(39, 16, 2025, 12, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:54:58', '2026-02-07 18:54:58'),
	(40, 17, 2025, 12, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:54:58', '2026-02-07 18:54:58'),
	(41, 18, 2025, 12, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:54:58', '2026-02-07 18:54:58'),
	(42, 19, 2025, 12, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:54:58', '2026-02-07 18:54:58'),
	(43, 20, 2025, 12, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:54:58', '2026-02-07 18:54:58'),
	(44, 21, 2025, 12, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:54:58', '2026-02-07 18:54:58'),
	(45, 22, 2025, 12, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:54:58', '2026-02-07 18:54:58'),
	(46, 23, 2025, 12, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 18:54:58', '2026-02-07 18:54:58'),
	(70, 1, 2025, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 19:01:49', '2026-02-07 19:01:49'),
	(71, 2, 2025, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 19:01:49', '2026-02-07 19:01:49'),
	(72, 3, 2025, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 19:01:49', '2026-02-07 19:01:49'),
	(73, 4, 2025, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 19:01:49', '2026-02-07 19:01:49'),
	(74, 5, 2025, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 19:01:49', '2026-02-07 19:01:49'),
	(75, 6, 2025, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 19:01:49', '2026-02-07 19:01:49'),
	(76, 7, 2025, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 19:01:49', '2026-02-07 19:01:49'),
	(77, 8, 2025, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 19:01:49', '2026-02-07 19:01:49'),
	(78, 9, 2025, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 19:01:49', '2026-02-07 19:01:49'),
	(79, 10, 2025, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 19:01:49', '2026-02-07 19:01:49'),
	(80, 11, 2025, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 19:01:49', '2026-02-07 19:01:49'),
	(81, 12, 2025, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 19:01:49', '2026-02-07 19:01:49'),
	(82, 13, 2025, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 19:01:49', '2026-02-07 19:01:49'),
	(83, 14, 2025, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 19:01:49', '2026-02-07 19:01:49'),
	(84, 15, 2025, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 19:01:49', '2026-02-07 19:01:49'),
	(85, 16, 2025, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 19:01:49', '2026-02-07 19:01:49'),
	(86, 17, 2025, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 19:01:49', '2026-02-07 19:01:49'),
	(87, 18, 2025, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 19:01:49', '2026-02-07 19:01:49'),
	(88, 19, 2025, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 19:01:49', '2026-02-07 19:01:49'),
	(89, 20, 2025, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 19:01:49', '2026-02-07 19:01:49'),
	(90, 21, 2025, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 19:01:49', '2026-02-07 19:01:49'),
	(91, 22, 2025, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 19:01:49', '2026-02-07 19:01:49'),
	(92, 23, 2025, 1, 0, NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-02-07 19:01:49', '2026-02-07 19:01:49'),
	(93, 13, 2026, 2, 1, 1, 1, 0, 0.00, 0.00, 15.00, 5.00, 100.00, 54.80, '2026-02-09 16:56:46', '2026-02-10 16:03:55');

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

-- Dumping data for table mobile_db.leave_requests: ~10 rows (approximately)
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

-- Dumping structure for table mobile_db.leave_types
CREATE TABLE IF NOT EXISTS `leave_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leave_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `leave_type_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `days_count` int DEFAULT '0' COMMENT '0 = Sesuai Surat Dokter/Fleksibel',
  `is_paid` tinyint(1) DEFAULT '1' COMMENT '1 = Dibayar Penuh (Paid Leave)',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.leave_types: ~13 rows (approximately)
DELETE FROM `leave_types`;
INSERT INTO `leave_types` (`id`, `leave_code`, `leave_type_name`, `days_count`, `is_paid`, `description`, `is_active`) VALUES
	(1, 'ANNUAL', 'Cuti Tahunan', 12, 1, 'Hak cuti tahunan minimal 12 hari kerja (Pasal 79)', 1),
	(2, 'SICK', 'Sakit (Surat Dokter)', 0, 1, 'Sakit dengan keterangan dokter, upah dibayar penuh', 1),
	(3, 'MARRIAGE_SELF', 'Pekerja Menikah', 3, 1, 'Pekerja menikah (3 hari)', 1),
	(4, 'MARRIAGE_CHILD', 'Menikahkan Anak', 2, 1, 'Menikahkan anak kandung (2 hari)', 1),
	(5, 'CIRCUM_BAPTISM', 'Khitanan / Baptisan Anak', 2, 1, 'Mengkhitankan atau membaptiskan anak (2 hari)', 1),
	(6, 'PATERNITY', 'Istri Melahirkan / Keguguran', 2, 1, 'Suami yang istrinya melahirkan atau keguguran (2 hari)', 1),
	(7, 'DEATH_CORE', 'Keluarga Inti Meninggal', 2, 1, 'Suami/Istri, Orang Tua/Mertua, Anak/Menantu meninggal (2 hari)', 1),
	(8, 'DEATH_HOME', 'Anggota Serumah Meninggal', 1, 1, 'Anggota keluarga lain dalam satu rumah meninggal (1 hari)', 1),
	(9, 'MATERNITY', 'Cuti Melahirkan', 90, 1, 'Istirahat melahirkan (1.5 bulan sebelum & sesudah)', 1),
	(10, 'MISCARRIAGE', 'Cuti Keguguran', 45, 1, 'Istirahat gugur kandungan (1.5 bulan atau sesuai surat dokter)', 1),
	(11, 'MENSTRUAL', 'Cuti Haid', 2, 1, 'Hari pertama & kedua haid jika merasakan sakit (Pasal 81)', 1),
	(12, 'UNPAID', 'Unpaid Leave (Potong Gaji)', 0, 0, 'Ijin di luar tanggungan (Potong Gaji)', 1),
	(13, 'DL', 'Dinas Luar / Lapangan', 0, 1, 'Tugas kerja di luar kantor', 1);

-- Dumping structure for table mobile_db.messages
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int DEFAULT NULL COMMENT 'Jika NULL=Pesan Baru, Jika Terisi=Reply ID Pesan Utama',
  `sender_id` int NOT NULL,
  `recipient_id` int NOT NULL,
  `subject` varchar(255) DEFAULT NULL COMMENT 'Subjek pesan (biasanya cuma diisi di pesan awal)',
  `body` longtext NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0' COMMENT '0=Belum dibaca, 1=Sudah',
  `is_archived` tinyint(1) DEFAULT '0' COMMENT 'Buat nyembunyiin pesan di inbox',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `sender_id` (`sender_id`),
  KEY `recipient_id` (`recipient_id`),
  CONSTRAINT `messages_fk_parent` FOREIGN KEY (`parent_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_fk_recipient` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_fk_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table mobile_db.messages: ~4 rows (approximately)
DELETE FROM `messages`;
INSERT INTO `messages` (`id`, `parent_id`, `sender_id`, `recipient_id`, `subject`, `body`, `attachment`, `is_read`, `is_archived`, `created_at`, `updated_at`) VALUES
	(1, NULL, 1, 16, 'COba Pesan', 'ini cuman pesan saja', NULL, 1, 0, '2026-02-10 16:20:51', '2026-02-15 13:06:55'),
	(2, NULL, 1, 16, 'ini terkirim dari pesan Admin', 'admin kirim pesan', NULL, 0, 0, '2026-02-11 02:36:44', NULL),
	(3, NULL, 1, 16, 'Coba Pesan MObile', 'COba via mobile', 'uploads/messages/7f17804a169668ffecb6b72389e6aaaa.png', 1, 0, '2026-02-14 15:21:03', '2026-02-14 15:21:21'),
	(4, 3, 16, 1, 'Re: Coba Pesan MObile', 'Reply balasan', NULL, 0, 0, '2026-02-15 15:17:09', NULL);

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

-- Dumping data for table mobile_db.notifications: ~10 rows (approximately)
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

-- Dumping structure for table mobile_db.office_locations
CREATE TABLE IF NOT EXISTS `office_locations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_id` int NOT NULL,
  `office_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `radius_meters` int DEFAULT '100',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `office_locations_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.office_locations: ~2 rows (approximately)
DELETE FROM `office_locations`;
INSERT INTO `office_locations` (`id`, `company_id`, `office_name`, `address`, `latitude`, `longitude`, `radius_meters`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Kantor Pusat Yogyakarta', 'Jl. Ahmad Yani No. 123, Yogyakarta', -7.82463300, 110.35828200, 100, 1, '2025-12-29 04:32:00', '2026-02-09 17:58:09'),
	(2, 1, 'Kantor Cabang Jakarta', 'Jl. Sudirman No. 456, Jakarta', -6.22548889, 106.79865556, 100, 1, '2025-12-29 04:32:00', '2025-12-29 04:32:00');

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

-- Dumping structure for table mobile_db.payroll_adjustments
CREATE TABLE IF NOT EXISTS `payroll_adjustments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `transaction_id` int NOT NULL,
  `adjustment_type` enum('earning','deduction') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `adjusted_by` int DEFAULT NULL,
  `adjusted_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `transaction_id` (`transaction_id`),
  KEY `adjusted_by` (`adjusted_by`),
  CONSTRAINT `payroll_adjustments_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `payroll_transactions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payroll_adjustments_ibfk_2` FOREIGN KEY (`adjusted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.payroll_adjustments: ~0 rows (approximately)
DELETE FROM `payroll_adjustments`;

-- Dumping structure for table mobile_db.payroll_component_categories
CREATE TABLE IF NOT EXISTS `payroll_component_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_type` enum('earning','deduction','tax','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `display_order` int DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_type` (`category_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.payroll_component_categories: ~10 rows (approximately)
DELETE FROM `payroll_component_categories`;
INSERT INTO `payroll_component_categories` (`id`, `category_name`, `category_type`, `description`, `display_order`, `created_at`) VALUES
	(1, 'Gaji & Tunjangan', 'earning', 'Komponen penghasilan tetap dan tidak tetap', 1, '2026-01-01 05:30:56'),
	(2, 'Lembur & Bonus', 'earning', 'Penghasilan tambahan dari lembur dan bonus', 2, '2026-01-01 05:30:56'),
	(3, 'BPJS & Asuransi', 'deduction', 'Potongan untuk BPJS dan asuransi lainnya', 3, '2026-01-01 05:30:56'),
	(4, 'Pajak', 'tax', 'Potongan pajak penghasilan', 4, '2026-01-01 05:30:56'),
	(5, 'Potongan Lain', 'deduction', 'Potongan pinjaman, denda, dll', 5, '2026-01-01 05:30:56'),
	(6, 'Gaji Pokok & Tunjangan Tetap', 'earning', NULL, 1, '2026-01-26 01:56:29'),
	(7, 'Tunjangan Tidak Tetap', 'earning', NULL, 2, '2026-01-26 01:56:29'),
	(8, 'Lembur & Bonus', 'earning', NULL, 3, '2026-01-26 01:56:29'),
	(9, 'Potongan Wajib', 'deduction', NULL, 4, '2026-01-26 01:56:29'),
	(10, 'Potongan Lain', 'deduction', NULL, 5, '2026-01-26 01:56:29');

-- Dumping structure for table mobile_db.payroll_components
CREATE TABLE IF NOT EXISTS `payroll_components` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int DEFAULT NULL,
  `component_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Unique code: BASIC_SALARY, TRANSPORT_ALLOW, etc',
  `component_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Display name in Indonesian',
  `component_type` enum('earning','deduction','tax') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `calculation_method` enum('fixed','percentage','formula','auto','manual') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'fixed' COMMENT 'How to calculate this component',
  `calculation_base` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Base for percentage calc: basic_salary, gross_salary, etc',
  `formula` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Custom formula for complex calculations',
  `default_amount` decimal(15,2) DEFAULT '0.00' COMMENT 'Default amount if fixed',
  `default_percentage` decimal(5,2) DEFAULT '0.00' COMMENT 'Default percentage if percentage-based',
  `is_taxable` tinyint(1) DEFAULT '1' COMMENT '1 if included in taxable income',
  `is_bpjs_base` tinyint(1) DEFAULT '0' COMMENT '1 if included in BPJS calculation base',
  `is_mandatory` tinyint(1) DEFAULT '0' COMMENT '1 if must exist for all employees',
  `is_system_component` tinyint(1) DEFAULT '0' COMMENT '1 if system-generated (overtime, attendance bonus)',
  `display_order` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `is_visible_on_slip` tinyint(1) DEFAULT '1' COMMENT '1 if shown on payslip',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `component_code` (`component_code`),
  KEY `category_id` (`category_id`),
  KEY `component_type` (`component_type`),
  KEY `is_active` (`is_active`),
  CONSTRAINT `payroll_components_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `payroll_component_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.payroll_components: ~15 rows (approximately)
DELETE FROM `payroll_components`;
INSERT INTO `payroll_components` (`id`, `category_id`, `component_code`, `component_name`, `component_type`, `calculation_method`, `calculation_base`, `formula`, `default_amount`, `default_percentage`, `is_taxable`, `is_bpjs_base`, `is_mandatory`, `is_system_component`, `display_order`, `is_active`, `is_visible_on_slip`, `notes`, `created_at`, `updated_at`) VALUES
	(1, NULL, 'BASIC_SALARY', 'Gaji Pokok', 'earning', 'fixed', NULL, NULL, 0.00, 0.00, 1, 0, 0, 0, 0, 1, 1, NULL, '2026-01-26 03:18:07', '2026-01-26 03:18:07'),
	(2, NULL, 'POSITION_ALLOW', 'Tunjangan Jabatan', 'earning', 'fixed', NULL, NULL, 0.00, 0.00, 1, 0, 0, 0, 0, 1, 1, NULL, '2026-01-26 03:18:07', '2026-01-26 03:18:07'),
	(3, NULL, 'FAMILY_ALLOW', 'Tunjangan Keluarga', 'earning', 'fixed', NULL, NULL, 0.00, 0.00, 1, 0, 0, 0, 0, 1, 1, NULL, '2026-01-26 03:18:07', '2026-01-26 03:18:07'),
	(4, NULL, 'COMMUNICATION_ALLOW', 'Tunjangan Komunikasi', 'earning', 'fixed', NULL, NULL, 0.00, 0.00, 1, 0, 0, 0, 0, 1, 1, NULL, '2026-01-26 03:18:07', '2026-01-26 03:18:07'),
	(5, NULL, 'TRANSPORT_ALLOW', 'Tunjangan Transport', 'earning', 'fixed', NULL, NULL, 0.00, 0.00, 1, 0, 0, 0, 0, 1, 1, NULL, '2026-01-26 03:18:07', '2026-01-26 03:18:07'),
	(6, NULL, 'ATTENDANCE_ALLOW', 'Tunjangan Kehadiran', 'earning', 'auto', NULL, NULL, 0.00, 0.00, 1, 0, 0, 0, 0, 1, 1, NULL, '2026-01-26 03:18:07', '2026-01-26 03:18:07'),
	(7, NULL, 'OVERTIME', 'Lembur', 'earning', 'auto', NULL, NULL, 0.00, 0.00, 1, 0, 0, 0, 0, 1, 1, NULL, '2026-01-26 03:18:07', '2026-01-26 03:18:07'),
	(8, NULL, 'OTHER_MOD', 'Lain-lain (MOD)', 'earning', 'manual', NULL, NULL, 0.00, 0.00, 1, 0, 0, 0, 0, 1, 1, NULL, '2026-01-26 03:18:07', '2026-01-26 03:18:07'),
	(9, NULL, 'INCENTIVE', 'Lain-lain Insentif', 'earning', 'manual', NULL, NULL, 0.00, 0.00, 1, 0, 0, 0, 0, 1, 1, NULL, '2026-01-26 03:18:07', '2026-01-26 03:18:07'),
	(10, NULL, 'BPJS_TK', 'Potongan BPJS Ketenagakerjaan', 'deduction', 'percentage', NULL, NULL, 0.00, 0.00, 1, 0, 0, 0, 0, 1, 1, NULL, '2026-01-26 03:18:07', '2026-01-26 03:18:07'),
	(11, NULL, 'BPJS_KES', 'Potongan BPJS Kesehatan', 'deduction', 'percentage', NULL, NULL, 0.00, 0.00, 1, 0, 0, 0, 0, 1, 1, NULL, '2026-01-26 03:18:07', '2026-01-26 03:18:07'),
	(12, NULL, 'LOAN_DEDUCTION', 'Potongan Hutang', 'deduction', 'manual', NULL, NULL, 0.00, 0.00, 1, 0, 0, 0, 0, 1, 1, NULL, '2026-01-26 03:18:07', '2026-01-26 03:18:07'),
	(13, NULL, 'OTHER_DEDUCTION', 'Lain-lain', 'deduction', 'manual', NULL, NULL, 0.00, 0.00, 1, 0, 0, 0, 0, 1, 1, NULL, '2026-01-26 03:18:07', '2026-01-26 03:18:07'),
	(14, NULL, 'PPH21', 'PPh 21', 'tax', 'formula', NULL, NULL, 0.00, 0.00, 1, 0, 0, 0, 0, 1, 1, NULL, '2026-01-26 03:18:07', '2026-01-26 03:18:07'),
	(24, NULL, 'BASIC', 'Gaji Pokok', 'earning', 'fixed', NULL, NULL, 0.00, 0.00, 1, 0, 0, 0, 0, 1, 1, NULL, '2026-01-26 03:18:07', '2026-01-26 03:18:07');

-- Dumping structure for table mobile_db.payroll_config
CREATE TABLE IF NOT EXISTS `payroll_config` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_id` int NOT NULL DEFAULT '1',
  `config_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin COMMENT 'JSON data for configuration',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company_id` (`company_id`),
  CONSTRAINT `payroll_config_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.payroll_config: ~1 rows (approximately)
DELETE FROM `payroll_config`;
INSERT INTO `payroll_config` (`id`, `company_id`, `config_data`, `created_at`, `updated_at`) VALUES
	(1, 1, '{\r\n  "bpjs_kesehatan_rate": 4.0,\r\n  "bpjs_ketenagakerjaan_rate": 2.0,\r\n  "ptkp_rate": 54000000,\r\n  "overtime_multiplier": 1.5\r\n}', '2026-01-01 08:01:26', '2026-01-01 08:01:26');

-- Dumping structure for table mobile_db.payroll_periods
CREATE TABLE IF NOT EXISTS `payroll_periods` (
  `id` int NOT NULL AUTO_INCREMENT,
  `period_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'January 2026',
  `period_month` int NOT NULL COMMENT '1-12',
  `period_year` int NOT NULL,
  `start_date` date NOT NULL COMMENT 'Period start (attendance counting)',
  `end_date` date NOT NULL COMMENT 'Period end',
  `payment_date` date DEFAULT NULL COMMENT 'Planned payment date',
  `cutoff_date` date DEFAULT NULL COMMENT 'Payroll processing cutoff',
  `status` enum('draft','processing','review','approved','paid','closed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `processed_by` int DEFAULT NULL,
  `processed_at` datetime DEFAULT NULL,
  `approved_by` int DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `total_employees` int DEFAULT '0',
  `total_gross_salary` decimal(15,2) DEFAULT '0.00',
  `total_deductions` decimal(15,2) DEFAULT '0.00',
  `total_net_salary` decimal(15,2) DEFAULT '0.00',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_period` (`period_year`,`period_month`),
  KEY `status` (`status`),
  KEY `period_year` (`period_year`),
  KEY `processed_by` (`processed_by`),
  KEY `approved_by` (`approved_by`),
  KEY `payroll_periods_ibfk_created_by` (`created_by`),
  CONSTRAINT `payroll_periods_ibfk_1` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payroll_periods_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payroll_periods_ibfk_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.payroll_periods: ~2 rows (approximately)
DELETE FROM `payroll_periods`;
INSERT INTO `payroll_periods` (`id`, `period_name`, `period_month`, `period_year`, `start_date`, `end_date`, `payment_date`, `cutoff_date`, `status`, `processed_by`, `processed_at`, `approved_by`, `approved_at`, `total_employees`, `total_gross_salary`, `total_deductions`, `total_net_salary`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
	(1, 'November 2025', 11, 2025, '2025-11-01', '2025-11-30', '2025-12-05', '2025-11-28', 'paid', 1, '2025-11-29 10:00:00', 1, '2025-11-30 14:00:00', 5, 15000000.00, 2250000.00, 12750000.00, 'Payroll November 2025 - Normal', 1, '2025-10-31 11:00:00', '2025-12-04 19:00:00'),
	(2, 'Desember 2025', 12, 2025, '2025-12-01', '2025-12-31', '2026-01-05', '2025-12-28', 'approved', 1, '2025-12-29 10:00:00', 1, '2025-12-30 14:00:00', 5, 16500000.00, 2475000.00, 14025000.00, 'Payroll Desember 2025 - Include Bonus Akhir Tahun', 1, '2025-11-30 11:00:00', '2025-12-29 19:00:00');

-- Dumping structure for table mobile_db.payroll_transaction_details
CREATE TABLE IF NOT EXISTS `payroll_transaction_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `transaction_id` int NOT NULL,
  `component_id` int NOT NULL,
  `component_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `component_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `component_type` enum('earning','deduction','tax') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `calculation_method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `calculation_base` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate_percentage` decimal(5,2) DEFAULT NULL COMMENT 'Rate used if percentage-based',
  `quantity` decimal(10,2) DEFAULT '1.00' COMMENT 'For overtime hours, days, etc',
  `amount` decimal(15,2) NOT NULL COMMENT 'Final calculated amount',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `transaction_id` (`transaction_id`),
  KEY `component_id` (`component_id`),
  KEY `component_type` (`component_type`),
  KEY `idx_transaction_type` (`transaction_id`,`component_type`),
  CONSTRAINT `payroll_transaction_details_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `payroll_transactions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payroll_transaction_details_ibfk_2` FOREIGN KEY (`component_id`) REFERENCES `payroll_components` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.payroll_transaction_details: ~15 rows (approximately)
DELETE FROM `payroll_transaction_details`;
INSERT INTO `payroll_transaction_details` (`id`, `transaction_id`, `component_id`, `component_code`, `component_name`, `component_type`, `calculation_method`, `calculation_base`, `rate_percentage`, `quantity`, `amount`, `notes`, `created_at`) VALUES
	(1, 1, 1, 'BASIC_SALARY', 'Gaji Pokok', 'earning', 'fixed', NULL, NULL, 1.00, 3500000.00, 'Gaji pokok Direktur', '2025-11-28 13:30:00'),
	(2, 1, 2, 'POSITION_ALLOW', 'Tunjangan Jabatan', 'earning', 'fixed', NULL, NULL, 1.00, 500000.00, 'Tunjangan jabatan Direktur', '2025-11-28 13:30:00'),
	(3, 1, 4, 'COMMUNICATION_ALLOW', 'Tunjangan Komunikasi', 'earning', 'fixed', NULL, NULL, 1.00, 200000.00, 'Tunjangan komunikasi', '2025-11-28 13:30:00'),
	(4, 1, 7, 'OVERTIME', 'Lembur', 'earning', 'auto', NULL, NULL, 1.83, 150000.00, '110 menit overtime (1.83 jam)', '2025-11-28 13:30:00'),
	(5, 1, 10, 'BPJS_TK', 'Potongan BPJS Ketenagakerjaan', 'deduction', 'percentage', 'basic_salary', 3.00, 1.00, 105000.00, '3% dari gaji pokok', '2025-11-28 13:30:00'),
	(6, 1, 11, 'BPJS_KES', 'Potongan BPJS Kesehatan', 'deduction', 'percentage', 'basic_salary', 2.00, 1.00, 70000.00, '2% dari gaji pokok', '2025-11-28 13:30:00'),
	(7, 1, 14, 'PPH21', 'PPh 21', 'tax', 'formula', 'taxable_income', NULL, 1.00, 75000.00, 'Pajak penghasilan', '2025-11-28 13:30:00'),
	(8, 2, 1, 'BASIC_SALARY', 'Gaji Pokok', 'earning', 'fixed', NULL, NULL, 1.00, 3500000.00, 'Gaji pokok Direktur', '2025-12-28 13:30:00'),
	(9, 2, 2, 'POSITION_ALLOW', 'Tunjangan Jabatan', 'earning', 'fixed', NULL, NULL, 1.00, 500000.00, 'Tunjangan jabatan Direktur', '2025-12-28 13:30:00'),
	(10, 2, 4, 'COMMUNICATION_ALLOW', 'Tunjangan Komunikasi', 'earning', 'fixed', NULL, NULL, 1.00, 200000.00, 'Tunjangan komunikasi', '2025-12-28 13:30:00'),
	(11, 2, 7, 'OVERTIME', 'Lembur', 'earning', 'auto', NULL, NULL, 1.17, 100000.00, '70 menit overtime (1.17 jam)', '2025-12-28 13:30:00'),
	(12, 2, 9, 'INCENTIVE', 'Lain-lain Insentif', 'earning', 'manual', NULL, NULL, 1.00, 1000000.00, 'Bonus Akhir Tahun 2025', '2025-12-28 13:30:00'),
	(13, 2, 10, 'BPJS_TK', 'Potongan BPJS Ketenagakerjaan', 'deduction', 'percentage', 'basic_salary', 3.00, 1.00, 105000.00, '3% dari gaji pokok', '2025-12-28 13:30:00'),
	(14, 2, 11, 'BPJS_KES', 'Potongan BPJS Kesehatan', 'deduction', 'percentage', 'basic_salary', 2.00, 1.00, 70000.00, '2% dari gaji pokok', '2025-12-28 13:30:00'),
	(15, 2, 14, 'PPH21', 'PPh 21', 'tax', 'formula', 'taxable_income', NULL, 1.00, 100000.00, 'Pajak penghasilan (lebih tinggi karena bonus)', '2025-12-28 13:30:00');

-- Dumping structure for table mobile_db.payroll_transactions
CREATE TABLE IF NOT EXISTS `payroll_transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `period_id` int NOT NULL,
  `employee_id` int NOT NULL,
  `gross_salary` decimal(15,2) DEFAULT '0.00' COMMENT 'Total earnings before deductions',
  `total_allowances` decimal(15,2) DEFAULT '0.00' COMMENT 'Sum of all allowances',
  `total_overtime` decimal(15,2) DEFAULT '0.00' COMMENT 'Total overtime pay',
  `total_bonuses` decimal(15,2) DEFAULT '0.00' COMMENT 'Total bonuses & incentives',
  `total_bpjs` decimal(15,2) DEFAULT '0.00' COMMENT 'Total BPJS deductions',
  `total_tax` decimal(15,2) DEFAULT '0.00' COMMENT 'PPh 21',
  `total_other_deductions` decimal(15,2) DEFAULT '0.00' COMMENT 'Loans, fines, etc',
  `total_deductions` decimal(15,2) DEFAULT '0.00' COMMENT 'Sum of all deductions',
  `net_salary` decimal(15,2) DEFAULT '0.00' COMMENT 'Take home pay',
  `working_days` int DEFAULT '0',
  `present_days` int DEFAULT '0',
  `absent_days` int DEFAULT '0',
  `late_days` int DEFAULT '0',
  `overtime_hours` decimal(5,2) DEFAULT '0.00',
  `payment_method` enum('transfer','cash','cheque') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'transfer',
  `bank_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','calculated','reviewed','approved','paid') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `is_locked` tinyint(1) DEFAULT '0' COMMENT '1 if locked, cannot edit',
  `calculated_at` datetime DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_period_employee` (`period_id`,`employee_id`),
  KEY `employee_id` (`employee_id`),
  KEY `status` (`status`),
  KEY `is_locked` (`is_locked`),
  KEY `idx_period_status` (`period_id`,`status`),
  CONSTRAINT `payroll_transactions_ibfk_1` FOREIGN KEY (`period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payroll_transactions_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.payroll_transactions: ~2 rows (approximately)
DELETE FROM `payroll_transactions`;
INSERT INTO `payroll_transactions` (`id`, `period_id`, `employee_id`, `gross_salary`, `total_allowances`, `total_overtime`, `total_bonuses`, `total_bpjs`, `total_tax`, `total_other_deductions`, `total_deductions`, `net_salary`, `working_days`, `present_days`, `absent_days`, `late_days`, `overtime_hours`, `payment_method`, `bank_name`, `bank_account_number`, `bank_account_name`, `status`, `is_locked`, `calculated_at`, `paid_at`, `notes`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 4700000.00, 700000.00, 150000.00, 0.00, 175000.00, 75000.00, 0.00, 250000.00, 4450000.00, 22, 22, 0, 3, 1.83, 'transfer', 'BCA', '1234567890', 'Budi Santoso', 'paid', 1, '2025-11-29 10:30:00', '2025-12-05 14:00:00', 'Normal payroll - 3 kali terlambat approved', '2025-11-28 13:00:00', '2025-12-04 17:00:00'),
	(2, 2, 1, 5750000.00, 700000.00, 100000.00, 1000000.00, 175000.00, 100000.00, 0.00, 275000.00, 5475000.00, 23, 23, 0, 3, 1.17, 'transfer', 'BCA', '1234567890', 'Budi Santoso', 'approved', 1, '2025-12-29 10:30:00', NULL, 'Include bonus akhir tahun Rp 1.000.000', '2025-12-28 13:00:00', '2025-12-29 19:00:00');

-- Dumping structure for table mobile_db.payslip_templates
CREATE TABLE IF NOT EXISTS `payslip_templates` (
  `id` int NOT NULL AUTO_INCREMENT,
  `template_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Menyimpan Script HTML & PHP',
  `is_active` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.payslip_templates: ~2 rows (approximately)
DELETE FROM `payslip_templates`;
INSERT INTO `payslip_templates` (`id`, `template_name`, `content`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Modern A4', '<!DOCTYPE html>\r\n<html lang="id">\r\n<head>\r\n    <meta charset="UTF-8">\r\n    <title>Slip Gaji - <?= $header[\'period_name\'] ?></title>\r\n    <script src="https://cdn.tailwindcss.com"></script>\r\n    <style>\r\n        @media print {\r\n            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; background: white !important; }\r\n            .no-print { display: none !important; }\r\n            .sheet { box-shadow: none !important; border: none !important; margin: 0 !important; width: 100% !important; }\r\n        }\r\n        body { font-family: \'Courier New\', Courier, monospace; background: #525659; padding: 20px; }\r\n        .sheet { background: white; width: 210mm; min-height: 148mm; margin: 0 auto; padding: 15mm; box-shadow: 0 0 10px rgba(0,0,0,0.5); position: relative; }\r\n    </style>\r\n</head>\r\n<body>\r\n    <div class="no-print fixed top-4 right-4 flex gap-2 z-50">\r\n        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded shadow font-bold hover:bg-blue-700 flex items-center gap-2">Print Slip</button>\r\n        <button onclick="window.close()" class="bg-gray-600 text-white px-4 py-2 rounded shadow font-bold hover:bg-gray-700">Tutup</button>\r\n    </div>\r\n    <div class="sheet">\r\n        <div class="flex justify-between items-end border-b-2 border-gray-800 pb-4 mb-6">\r\n            <div>\r\n                <h1 class="text-2xl font-bold text-gray-900">PT. NAMA PERUSAHAAN</h1>\r\n                <p class="text-sm text-gray-600">Jl. Contoh Alamat No. 123, Indonesia</p>\r\n            </div>\r\n            <div class="text-right">\r\n                <h2 class="text-xl font-bold text-gray-800 uppercase tracking-widest">SLIP GAJI</h2>\r\n                <p class="text-sm font-bold text-gray-500 uppercase"><?= $header[\'period_name\'] ?></p>\r\n            </div>\r\n        </div>\r\n        <div class="grid grid-cols-2 gap-8 mb-6 text-sm">\r\n            <div>\r\n                <table class="w-full">\r\n                    <tr><td class="w-24 text-gray-500 py-0.5">NIK</td><td class="font-bold">: <?= $header[\'employee_number\'] ?></td></tr>\r\n                    <tr><td class="text-gray-500 py-0.5">Nama</td><td class="font-bold uppercase">: <?= $header[\'first_name\'] . \' \' . $header[\'last_name\'] ?></td></tr>\r\n                    <tr><td class="text-gray-500 py-0.5">Departemen</td><td>: <?= $header[\'department_name\'] ?></td></tr>\r\n                </table>\r\n            </div>\r\n            <div>\r\n                <table class="w-full">\r\n                    <tr><td class="w-24 text-gray-500 py-0.5">Metode</td><td>: <?= ucfirst($header[\'payment_method\'] ?? \'Transfer\') ?></td></tr>\r\n                    <tr><td class="text-gray-500 py-0.5">Kehadiran</td><td>: <?= $header[\'present_days\'] ?> Hari</td></tr>\r\n                    <tr><td class="text-gray-500 py-0.5">Lembur</td><td>: <?= $header[\'overtime_hours\'] ?> Jam</td></tr>\r\n                </table>\r\n            </div>\r\n        </div>\r\n        <div class="grid grid-cols-2 gap-8 mb-6">\r\n            <div class="border border-gray-200 rounded p-4 bg-green-50/30">\r\n                <h3 class="font-bold border-b border-gray-300 mb-3 pb-1 text-sm uppercase text-green-800">Penerimaan</h3>\r\n                <table class="w-full text-sm">\r\n                    <?php $total_earn = 0; foreach($details as $d): if($d[\'component_type\'] == \'earning\'): $total_earn += $d[\'amount\']; ?>\r\n                    <tr><td class="py-1 text-gray-700"><?= $d[\'component_name\'] ?></td><td class="text-right font-mono font-medium">Rp <?= number_format($d[\'amount\'], 0, \',\', \'.\') ?></td></tr>\r\n                    <?php endif; endforeach; ?>\r\n                </table>\r\n                <div class="border-t border-gray-300 mt-3 pt-2 flex justify-between font-bold text-gray-900 text-sm"><span>Total</span><span>Rp <?= number_format($total_earn, 0, \',\', \'.\') ?></span></div>\r\n            </div>\r\n            <div class="border border-gray-200 rounded p-4 bg-red-50/30">\r\n                <h3 class="font-bold border-b border-gray-300 mb-3 pb-1 text-sm uppercase text-red-800">Potongan</h3>\r\n                <table class="w-full text-sm">\r\n                    <?php $total_deduct = 0; foreach($details as $d): if($d[\'component_type\'] != \'earning\'): $total_deduct += $d[\'amount\']; ?>\r\n                    <tr><td class="py-1 text-gray-700"><?= $d[\'component_name\'] ?></td><td class="text-right font-mono text-red-600">Rp <?= number_format($d[\'amount\'], 0, \',\', \'.\') ?></td></tr>\r\n                    <?php endif; endforeach; ?>\r\n                </table>\r\n                <div class="border-t border-gray-300 mt-3 pt-2 flex justify-between font-bold text-red-800 text-sm"><span>Total</span><span>(Rp <?= number_format($total_deduct, 0, \',\', \'.\') ?>)</span></div>\r\n            </div>\r\n        </div>\r\n        <div class="border-2 border-gray-800 p-4 mb-8 bg-gray-50 flex justify-between items-center">\r\n            <div><div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Take Home Pay</div></div>\r\n            <div class="text-2xl font-bold font-mono text-gray-900">Rp <?= number_format($header[\'net_salary\'], 0, \',\', \'.\') ?></div>\r\n        </div>\r\n        <div class="grid grid-cols-2 gap-8 text-center text-sm mt-12">\r\n            <div><p class="mb-16 text-gray-500">Penerima,</p><p class="font-bold underline uppercase"><?= $header[\'first_name\'] . \' \' . $header[\'last_name\'] ?></p></div>\r\n            <div><p class="mb-16 text-gray-500">Manager HRD,</p><p class="font-bold underline">ADMINISTRATOR</p></div>\r\n        </div>\r\n    </div>\r\n</body>\r\n</html>', 1, '2026-02-06 10:08:26', '2026-02-06 10:08:26'),
	(2, 'A5', '<!DOCTYPE html>\r\n<html lang="id">\r\n<head>\r\n    <meta charset="UTF-8">\r\n    <title>Slip Gaji - <?= $header[\'period_name\'] ?></title>\r\n    <script src="https://cdn.tailwindcss.com"></script>\r\n    <style>\r\n        /* Styling khusus cetak */\r\n        @media print {\r\n            body { \r\n                -webkit-print-color-adjust: exact; \r\n                print-color-adjust: exact; \r\n                background: white !important;\r\n            }\r\n            .no-print { display: none !important; }\r\n            .sheet { \r\n                box-shadow: none !important; \r\n                border: none !important; \r\n                margin: 0 !important; \r\n                width: 100% !important; \r\n            }\r\n        }\r\n        \r\n        body { \r\n            font-family: \'Courier New\', Courier, monospace; \r\n            background: #525659; \r\n            padding: 20px;\r\n        }\r\n        \r\n        .sheet { \r\n            background: white; \r\n            width: 210mm; /* A4 Width */\r\n            min-height: 148mm; /* A5 Height */\r\n            margin: 0 auto; \r\n            padding: 15mm; \r\n            box-shadow: 0 0 10px rgba(0,0,0,0.5); \r\n            position: relative; \r\n        }\r\n    </style>\r\n</head>\r\n<body>\r\n\r\n    <div class="no-print fixed top-4 right-4 flex gap-2 z-50">\r\n        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded shadow font-bold hover:bg-blue-700 flex items-center gap-2">\r\n            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>\r\n            Print Slip\r\n        </button>\r\n        <button onclick="window.close()" class="bg-gray-600 text-white px-4 py-2 rounded shadow font-bold hover:bg-gray-700">\r\n            Tutup\r\n        </button>\r\n    </div>\r\n\r\n    <div class="sheet">\r\n        \r\n        <div class="flex justify-between items-end border-b-2 border-gray-800 pb-4 mb-6">\r\n            <div>\r\n                <h1 class="text-2xl font-bold text-gray-900">PT. NAMA PERUSAHAAN</h1>\r\n                <p class="text-sm text-gray-600">Jl. Contoh Alamat No. 123, Indonesia</p>\r\n            </div>\r\n            <div class="text-right">\r\n                <h2 class="text-xl font-bold text-gray-800 uppercase tracking-widest">SLIP GAJI</h2>\r\n                <p class="text-sm font-bold text-gray-500 uppercase"><?= $header[\'period_name\'] ?></p>\r\n            </div>\r\n        </div>\r\n\r\n        <div class="grid grid-cols-2 gap-8 mb-6 text-sm">\r\n            <div>\r\n                <table class="w-full">\r\n                    <tr><td class="w-24 text-gray-500 py-0.5">NIK</td><td class="font-bold">: <?= $header[\'employee_number\'] ?></td></tr>\r\n                    <tr><td class="text-gray-500 py-0.5">Nama</td><td class="font-bold uppercase">: <?= $header[\'first_name\'] . \' \' . $header[\'last_name\'] ?></td></tr>\r\n                    <tr><td class="text-gray-500 py-0.5">Departemen</td><td>: <?= $header[\'department_name\'] ?></td></tr>\r\n                </table>\r\n            </div>\r\n            <div>\r\n                <table class="w-full">\r\n                    <tr><td class="w-24 text-gray-500 py-0.5">Metode</td><td>: <?= ucfirst($header[\'payment_method\']) ?></td></tr>\r\n                    <tr><td class="text-gray-500 py-0.5">Kehadiran</td><td>: <?= $header[\'present_days\'] ?> Hari</td></tr>\r\n                    <tr><td class="text-gray-500 py-0.5">Lembur</td><td>: <?= $header[\'overtime_hours\'] ?> Jam</td></tr>\r\n                </table>\r\n            </div>\r\n        </div>\r\n\r\n        <div class="grid grid-cols-2 gap-8 mb-6">\r\n            \r\n            <div class="border border-gray-200 rounded p-4 bg-green-50/30">\r\n                <h3 class="font-bold border-b border-gray-300 mb-3 pb-1 text-sm uppercase text-green-800">Penerimaan (Earnings)</h3>\r\n                <table class="w-full text-sm">\r\n                    <?php \r\n                    $total_earn = 0;\r\n                    foreach($details as $d): \r\n                        if($d[\'component_type\'] == \'earning\'):\r\n                            $total_earn += $d[\'amount\'];\r\n                    ?>\r\n                    <tr>\r\n                        <td class="py-1 text-gray-700"><?= $d[\'component_name\'] ?></td>\r\n                        <td class="text-right font-mono font-medium">Rp <?= number_format($d[\'amount\'], 0, \',\', \'.\') ?></td>\r\n                    </tr>\r\n                    <?php endif; endforeach; ?>\r\n                </table>\r\n                <div class="border-t border-gray-300 mt-3 pt-2 flex justify-between font-bold text-gray-900 text-sm">\r\n                    <span>Total Penerimaan</span>\r\n                    <span>Rp <?= number_format($total_earn, 0, \',\', \'.\') ?></span>\r\n                </div>\r\n            </div>\r\n\r\n            <div class="border border-gray-200 rounded p-4 bg-red-50/30">\r\n                <h3 class="font-bold border-b border-gray-300 mb-3 pb-1 text-sm uppercase text-red-800">Potongan (Deductions)</h3>\r\n                <table class="w-full text-sm">\r\n                    <?php \r\n                    $total_deduct = 0;\r\n                    foreach($details as $d): \r\n                        if($d[\'component_type\'] != \'earning\'):\r\n                            $total_deduct += $d[\'amount\'];\r\n                    ?>\r\n                    <tr>\r\n                        <td class="py-1 text-gray-700"><?= $d[\'component_name\'] ?></td>\r\n                        <td class="text-right font-mono text-red-600">Rp <?= number_format($d[\'amount\'], 0, \',\', \'.\') ?></td>\r\n                    </tr>\r\n                    <?php endif; endforeach; ?>\r\n                </table>\r\n                <div class="border-t border-gray-300 mt-3 pt-2 flex justify-between font-bold text-red-800 text-sm">\r\n                    <span>Total Potongan</span>\r\n                    <span>(Rp <?= number_format($total_deduct, 0, \',\', \'.\') ?>)</span>\r\n                </div>\r\n            </div>\r\n\r\n        </div>\r\n\r\n        <div class="border-2 border-gray-800 p-4 mb-8 bg-gray-50 flex justify-between items-center">\r\n            <div>\r\n                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Gaji Bersih (Take Home Pay)</div>\r\n                <div class="text-xs text-gray-400 italic">Terbilang: # Nominal Gaji Bersih Rupiah #</div>\r\n            </div>\r\n            <div class="text-2xl font-bold font-mono text-gray-900">\r\n                Rp <?= number_format($header[\'net_salary\'], 0, \',\', \'.\') ?>\r\n            </div>\r\n        </div>\r\n\r\n        <div class="grid grid-cols-2 gap-8 text-center text-sm mt-12">\r\n            <div>\r\n                <p class="mb-16 text-gray-500">Penerima,</p>\r\n                <p class="font-bold underline uppercase"><?= $header[\'first_name\'] . \' \' . $header[\'last_name\'] ?></p>\r\n            </div>\r\n            <div>\r\n                <p class="mb-16 text-gray-500">Manager HRD,</p>\r\n                <p class="font-bold underline">ADMINISTRATOR</p>\r\n            </div>\r\n        </div>\r\n        \r\n        <div class="absolute bottom-4 left-4 text-[10px] text-gray-400 italic">\r\n            * Dokumen ini digenerate otomatis oleh sistem HRMPWA pada <?= date(\'d M Y H:i\') ?>\r\n        </div>\r\n\r\n    </div>\r\n\r\n</body>\r\n</html>', 0, '2026-02-06 10:16:36', '2026-02-06 10:16:36');

-- Dumping structure for table mobile_db.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `module_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `permission_slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `permission_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permission_slug` (`permission_slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.permissions: ~69 rows (approximately)
DELETE FROM `permissions`;
INSERT INTO `permissions` (`id`, `module_name`, `permission_slug`, `permission_name`, `description`, `created_at`) VALUES
	(1, 'company', 'company.view', 'View Company Settings', 'Can view company information', '2025-12-31 14:14:57'),
	(2, 'company', 'company.edit', 'Edit Company Settings', 'Can edit company information', '2025-12-31 14:14:57'),
	(3, 'employees', 'employees.view', 'View Employees', 'Can view employee list', '2025-12-31 14:14:57'),
	(4, 'employees', 'employees.create', 'Create Employee', 'Can add new employees', '2025-12-31 14:14:57'),
	(5, 'employees', 'employees.edit', 'Edit Employee', 'Can edit employee information', '2025-12-31 14:14:57'),
	(6, 'employees', 'employees.delete', 'Delete Employee', 'Can delete employees', '2025-12-31 14:14:57'),
	(7, 'departments', 'departments.view', 'View Departments', 'Can view department list', '2025-12-31 14:14:57'),
	(8, 'departments', 'departments.create', 'Create Department', 'Can add new departments', '2025-12-31 14:14:57'),
	(9, 'departments', 'departments.edit', 'Edit Department', 'Can edit department information', '2025-12-31 14:14:57'),
	(10, 'departments', 'departments.delete', 'Delete Department', 'Can delete departments', '2025-12-31 14:14:57'),
	(11, 'locations', 'locations.view', 'View Office Locations', 'Can view office locations', '2025-12-31 14:14:57'),
	(12, 'locations', 'locations.create', 'Create Office Location', 'Can add new locations', '2025-12-31 14:14:57'),
	(13, 'locations', 'locations.edit', 'Edit Office Location', 'Can edit location information', '2025-12-31 14:14:57'),
	(14, 'locations', 'locations.delete', 'Delete Office Location', 'Can delete locations', '2025-12-31 14:14:57'),
	(15, 'schedules', 'schedules.view', 'View Schedules', 'Can view work schedules', '2025-12-31 14:14:57'),
	(16, 'schedules', 'schedules.create', 'Create Schedule', 'Can create shift assignments', '2025-12-31 14:14:57'),
	(17, 'schedules', 'schedules.edit', 'Edit Schedule', 'Can edit shift assignments', '2025-12-31 14:14:57'),
	(18, 'schedules', 'schedules.delete', 'Delete Schedule', 'Can delete shift assignments', '2025-12-31 14:14:57'),
	(19, 'schedules', 'shifts.manage', 'Manage Shifts', 'Can manage shift types', '2025-12-31 14:14:57'),
	(20, 'schedules', 'holidays.manage', 'Manage Holidays', 'Can manage holidays', '2025-12-31 14:14:57'),
	(21, 'attendance', 'attendance.view', 'View Attendance', 'Can view attendance records', '2025-12-31 14:14:57'),
	(22, 'attendance', 'attendance.edit', 'Edit Attendance', 'Can edit attendance records', '2025-12-31 14:14:57'),
	(23, 'attendance', 'attendance.approve', 'Approve Attendance', 'Can approve late/early out', '2025-12-31 14:14:57'),
	(24, 'requests', 'requests.view', 'View Requests', 'Can view leave/overtime requests', '2025-12-31 14:14:57'),
	(25, 'requests', 'requests.approve', 'Approve Requests', 'Can approve/reject requests', '2025-12-31 14:14:57'),
	(26, 'requests', 'requests.create', 'Create Request', 'Can submit requests', '2025-12-31 14:14:57'),
	(27, 'payroll', 'payroll.view', 'View Payroll', 'Can view salary information', '2025-12-31 14:14:57'),
	(28, 'payroll', 'payroll.edit', 'Edit Payroll', 'Can edit salary components', '2025-12-31 14:14:57'),
	(29, 'payroll', 'payroll.process', 'Process Payroll', 'Can generate payslips', '2025-12-31 14:14:57'),
	(30, 'users', 'users.view', 'View Users', 'Can view user list', '2025-12-31 14:14:57'),
	(31, 'users', 'users.create', 'Create User', 'Can create new users', '2025-12-31 14:14:57'),
	(32, 'users', 'users.edit', 'Edit User', 'Can edit user information', '2025-12-31 14:14:57'),
	(33, 'users', 'users.delete', 'Delete User', 'Can delete users', '2025-12-31 14:14:57'),
	(34, 'users', 'users.permissions', 'Manage Permissions', 'Can assign permissions to users', '2025-12-31 14:14:57'),
	(35, 'reports', 'reports.view', 'View Reports', 'Can view all reports', '2025-12-31 14:14:57'),
	(36, 'reports', 'reports.export', 'Export Reports', 'Can export reports to PDF/Excel', '2025-12-31 14:14:57'),
	(37, 'payslips', 'payslips.view_all', 'Lihat Semua Slip Gaji', 'Melihat slip gaji semua karyawan', '2026-01-05 20:34:30'),
	(38, 'payslips', 'payslips.view_own', 'Lihat Slip Gaji Sendiri', 'Melihat slip gaji sendiri', '2026-01-05 20:34:30'),
	(39, 'payslips', 'payslips.generate', 'Generate Slip Gaji', 'Generate slip gaji karyawan', '2026-01-05 20:34:30'),
	(40, 'payslips', 'payslips.print', 'Cetak Slip Gaji', 'Cetak slip gaji PDF', '2026-01-05 20:34:30'),
	(41, 'payslips', 'payslips.export', 'Export Slip Gaji', 'Export slip gaji ke Excel', '2026-01-05 20:34:30'),
	(42, 'payslips', 'payslips.send', 'Kirim Slip Gaji', 'Kirim slip gaji via email', '2026-01-05 20:34:30'),
	(43, 'announcements', 'announcements.view', 'Lihat Pengumuman', 'Melihat daftar pengumuman', '2026-01-05 20:34:30'),
	(44, 'announcements', 'announcements.create', 'Buat Pengumuman', 'Membuat pengumuman baru', '2026-01-05 20:34:30'),
	(45, 'announcements', 'announcements.edit', 'Edit Pengumuman', 'Mengubah pengumuman', '2026-01-05 20:34:30'),
	(46, 'announcements', 'announcements.delete', 'Hapus Pengumuman', 'Menghapus pengumuman', '2026-01-05 20:34:30'),
	(47, 'announcements', 'announcements.publish', 'Publish Pengumuman', 'Mempublikasikan pengumuman', '2026-01-05 20:34:30'),
	(75, 'kpi', 'kpi.view', 'View KPI', 'Dapat melihat KPI dashboard', '2026-01-14 17:03:00'),
	(76, 'kpi', 'kpi.view_all', 'View All KPI', 'Dapat melihat KPI semua karyawan', '2026-01-14 17:03:00'),
	(77, 'kpi', 'kpi.view_department', 'View Department KPI', 'Dapat melihat KPI karyawan di department sendiri', '2026-01-14 17:03:00'),
	(78, 'kpi', 'kpi.view_own', 'View Own KPI', 'Dapat melihat KPI sendiri', '2026-01-14 17:03:00'),
	(79, 'kpi', 'kpi.calculate', 'Calculate KPI', 'Dapat menghitung/recalculate KPI', '2026-01-14 17:03:00'),
	(80, 'kpi', 'kpi.export', 'Export KPI Report', 'Dapat export laporan KPI', '2026-01-14 17:03:00'),
	(81, 'tasks', 'tasks.view', 'View Tasks', 'Dapat melihat tasks', '2026-01-14 17:03:00'),
	(82, 'tasks', 'tasks.view_all', 'View All Tasks', 'Dapat melihat semua tasks', '2026-01-14 17:03:00'),
	(83, 'tasks', 'tasks.view_department', 'View Department Tasks', 'Dapat melihat tasks di department sendiri', '2026-01-14 17:03:00'),
	(84, 'tasks', 'tasks.view_own', 'View Own Tasks', 'Dapat melihat tasks sendiri', '2026-01-14 17:03:00'),
	(85, 'tasks', 'tasks.create', 'Create Task', 'Dapat membuat task baru', '2026-01-14 17:03:00'),
	(86, 'tasks', 'tasks.assign', 'Assign Task', 'Dapat assign task ke orang lain', '2026-01-14 17:03:00'),
	(87, 'tasks', 'tasks.edit', 'Edit Task', 'Dapat edit task', '2026-01-14 17:03:00'),
	(88, 'tasks', 'tasks.delete', 'Delete Task', 'Dapat hapus task', '2026-01-14 17:03:00'),
	(89, 'tasks', 'tasks.approve', 'Approve Task', 'Dapat approve/reject task submission', '2026-01-14 17:03:00'),
	(90, 'tasks', 'tasks.comment', 'Comment on Task', 'Dapat comment di task', '2026-01-14 17:03:00'),
	(91, 'tasks', 'tasks.log_time', 'Log Work Time', 'Dapat log working hours', '2026-01-14 17:03:00'),
	(98, 'Organization', 'organization.view', 'View Organization', 'Lihat Struktur Org', '2026-02-05 04:01:06'),
	(99, 'Employees', 'contracts.view', 'View Contracts', 'Lihat Kontrak', '2026-02-05 04:01:06'),
	(100, 'Shift', 'shifts.view', 'View Shifts', 'Lihat Shift', '2026-02-05 04:01:06'),
	(101, 'System', 'settings.view', 'View Settings', 'Lihat Pengaturan', '2026-02-05 04:01:06'),
	(102, 'System', 'roles.manage', 'Manage Roles', 'Atur Role', '2026-02-05 04:01:06');

-- Dumping structure for table mobile_db.ptkp_rates
CREATE TABLE IF NOT EXISTS `ptkp_rates` (
  `id` int NOT NULL AUTO_INCREMENT,
  `status_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'TK/0, TK/1, K/0, K/1, K/2, K/3',
  `status_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Description',
  `annual_amount` decimal(15,2) NOT NULL COMMENT 'Annual PTKP amount',
  `effective_year` int NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_status_year` (`status_code`,`effective_year`),
  KEY `effective_year` (`effective_year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.ptkp_rates: ~8 rows (approximately)
DELETE FROM `ptkp_rates`;
INSERT INTO `ptkp_rates` (`id`, `status_code`, `status_name`, `annual_amount`, `effective_year`, `is_active`, `created_at`) VALUES
	(1, 'TK/0', 'Tidak Kawin, 0 tanggungan', 54000000.00, 2024, 1, '2026-01-01 05:30:57'),
	(2, 'TK/1', 'Tidak Kawin, 1 tanggungan', 58500000.00, 2024, 1, '2026-01-01 05:30:57'),
	(3, 'TK/2', 'Tidak Kawin, 2 tanggungan', 63000000.00, 2024, 1, '2026-01-01 05:30:57'),
	(4, 'TK/3', 'Tidak Kawin, 3 tanggungan', 67500000.00, 2024, 1, '2026-01-01 05:30:57'),
	(5, 'K/0', 'Kawin, 0 tanggungan', 58500000.00, 2024, 1, '2026-01-01 05:30:57'),
	(6, 'K/1', 'Kawin, 1 tanggungan', 63000000.00, 2024, 1, '2026-01-01 05:30:57'),
	(7, 'K/2', 'Kawin, 2 tanggungan', 67500000.00, 2024, 1, '2026-01-01 05:30:57'),
	(8, 'K/3', 'Kawin, 3 tanggungan', 72000000.00, 2024, 1, '2026-01-01 05:30:57');

-- Dumping structure for table mobile_db.pwa_auth
CREATE TABLE IF NOT EXISTS `pwa_auth` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Username untuk login (NIP)',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Hashed password',
  `last_login` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1' COMMENT '1 = Aktif, 0 = Suspended',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_employee_number` (`employee_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='PWA Login Credentials';

-- Dumping data for table mobile_db.pwa_auth: ~7 rows (approximately)
DELETE FROM `pwa_auth`;
INSERT INTO `pwa_auth` (`id`, `employee_number`, `password`, `last_login`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'EMP001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-01-14 18:36:58', 1, '2026-01-07 12:53:15', '2026-01-14 04:36:58'),
	(2, 'EMP002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-01-08 23:48:47', 1, '2026-01-07 12:53:15', '2026-01-08 09:48:47'),
	(3, 'EMP003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 1, '2026-01-07 12:53:15', '2026-01-07 12:53:15'),
	(9, 'EMP004', '$2y$10$wETOGfUPiquJE6r7mGqKCeubbS6s79j7.X9ZCCC1.WVoc8W5Tftxe', NULL, 1, '2026-01-10 00:44:22', '2026-01-10 00:44:22'),
	(10, 'EMP005', '$2y$10$wETOGfUPiquJE6r7mGqKCeubbS6s79j7.X9ZCCC1.WVoc8W5Tftxe', '2026-01-10 18:51:51', 1, '2026-01-10 00:44:22', '2026-01-10 04:51:51'),
	(11, 'T202020', '$2y$10$wETOGfUPiquJE6r7mGqKCeubbS6s79j7.X9ZCCC1.WVoc8W5Tftxe', NULL, 1, '2026-01-10 00:44:22', '2026-01-10 00:44:22'),
	(12, 'T20002', '$2y$10$wETOGfUPiquJE6r7mGqKCeubbS6s79j7.X9ZCCC1.WVoc8W5Tftxe', '2026-01-10 14:45:05', 1, '2026-01-10 00:44:22', '2026-01-10 00:45:05');

-- Dumping structure for table mobile_db.requests
CREATE TABLE IF NOT EXISTS `requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `request_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `document_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approved_by` int DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `rejection_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `approved_by` (`approved_by`),
  CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`),
  CONSTRAINT `requests_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.requests: ~1 rows (approximately)
DELETE FROM `requests`;
INSERT INTO `requests` (`id`, `employee_id`, `request_type`, `start_date`, `end_date`, `reason`, `document_path`, `status`, `created_at`, `approved_by`, `approved_at`, `rejection_reason`, `updated_at`) VALUES
	(1, 1, 'sppd', '2026-01-14', '2026-01-16', 'latihan drama di semarang\n\nAdditional Notes:\nundangan dari sanggar', NULL, 'pending', '2026-01-08 00:34:19', NULL, NULL, NULL, '2026-01-08 00:34:19');

-- Dumping structure for table mobile_db.role_permissions
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role_id` int NOT NULL,
  `permission_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_role_permission` (`role_id`,`permission_id`),
  KEY `role_id` (`role_id`),
  KEY `permission_id` (`permission_id`),
  KEY `idx_role_lookup` (`role_id`,`permission_id`),
  CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.role_permissions: ~166 rows (approximately)
DELETE FROM `role_permissions`;
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`, `created_at`) VALUES
	(127, 3, 23, '2025-12-31 14:14:57'),
	(128, 3, 21, '2025-12-31 14:14:57'),
	(129, 3, 7, '2025-12-31 14:14:57'),
	(130, 3, 3, '2025-12-31 14:14:57'),
	(131, 3, 35, '2025-12-31 14:14:57'),
	(132, 3, 25, '2025-12-31 14:14:57'),
	(133, 3, 24, '2025-12-31 14:14:57'),
	(134, 3, 15, '2025-12-31 14:14:57'),
	(142, 4, 21, '2025-12-31 14:14:57'),
	(143, 4, 26, '2025-12-31 14:14:57'),
	(144, 4, 24, '2025-12-31 14:14:57'),
	(207, 3, 44, '2026-01-05 20:34:30'),
	(208, 3, 45, '2026-01-05 20:34:30'),
	(209, 3, 43, '2026-01-05 20:34:30'),
	(216, 3, 40, '2026-01-05 20:34:30'),
	(217, 3, 37, '2026-01-05 20:34:30'),
	(238, 4, 43, '2026-01-05 20:34:30'),
	(240, 4, 40, '2026-01-05 20:34:30'),
	(241, 4, 38, '2026-01-05 20:34:30'),
	(467, 3, 46, '2026-02-05 04:01:06'),
	(468, 3, 47, '2026-02-05 04:01:06'),
	(469, 3, 22, '2026-02-05 04:01:06'),
	(470, 3, 2, '2026-02-05 04:01:06'),
	(471, 3, 1, '2026-02-05 04:01:06'),
	(472, 3, 99, '2026-02-05 04:01:06'),
	(473, 3, 8, '2026-02-05 04:01:06'),
	(474, 3, 10, '2026-02-05 04:01:06'),
	(475, 3, 9, '2026-02-05 04:01:06'),
	(476, 3, 4, '2026-02-05 04:01:06'),
	(477, 3, 6, '2026-02-05 04:01:06'),
	(478, 3, 5, '2026-02-05 04:01:06'),
	(479, 3, 20, '2026-02-05 04:01:06'),
	(480, 3, 79, '2026-02-05 04:01:06'),
	(481, 3, 80, '2026-02-05 04:01:06'),
	(482, 3, 75, '2026-02-05 04:01:06'),
	(483, 3, 76, '2026-02-05 04:01:06'),
	(484, 3, 77, '2026-02-05 04:01:06'),
	(485, 3, 78, '2026-02-05 04:01:06'),
	(486, 3, 12, '2026-02-05 04:01:06'),
	(487, 3, 14, '2026-02-05 04:01:06'),
	(488, 3, 13, '2026-02-05 04:01:06'),
	(489, 3, 11, '2026-02-05 04:01:06'),
	(490, 3, 98, '2026-02-05 04:01:06'),
	(491, 3, 28, '2026-02-05 04:01:06'),
	(492, 3, 29, '2026-02-05 04:01:06'),
	(493, 3, 27, '2026-02-05 04:01:06'),
	(494, 3, 41, '2026-02-05 04:01:06'),
	(495, 3, 39, '2026-02-05 04:01:06'),
	(496, 3, 42, '2026-02-05 04:01:06'),
	(497, 3, 38, '2026-02-05 04:01:06'),
	(498, 3, 36, '2026-02-05 04:01:06'),
	(499, 3, 26, '2026-02-05 04:01:06'),
	(500, 3, 102, '2026-02-05 04:01:06'),
	(501, 3, 16, '2026-02-05 04:01:06'),
	(502, 3, 18, '2026-02-05 04:01:06'),
	(503, 3, 17, '2026-02-05 04:01:06'),
	(504, 3, 101, '2026-02-05 04:01:06'),
	(505, 3, 19, '2026-02-05 04:01:06'),
	(506, 3, 100, '2026-02-05 04:01:06'),
	(507, 3, 89, '2026-02-05 04:01:06'),
	(508, 3, 86, '2026-02-05 04:01:06'),
	(509, 3, 90, '2026-02-05 04:01:06'),
	(510, 3, 85, '2026-02-05 04:01:06'),
	(511, 3, 88, '2026-02-05 04:01:06'),
	(512, 3, 87, '2026-02-05 04:01:06'),
	(513, 3, 91, '2026-02-05 04:01:06'),
	(514, 3, 81, '2026-02-05 04:01:06'),
	(515, 3, 82, '2026-02-05 04:01:06'),
	(516, 3, 83, '2026-02-05 04:01:06'),
	(517, 3, 84, '2026-02-05 04:01:06'),
	(518, 3, 31, '2026-02-05 04:01:06'),
	(519, 3, 33, '2026-02-05 04:01:06'),
	(520, 3, 32, '2026-02-05 04:01:06'),
	(521, 3, 34, '2026-02-05 04:01:06'),
	(522, 3, 30, '2026-02-05 04:01:06'),
	(599, 1, 43, '2026-02-05 04:07:15'),
	(600, 1, 44, '2026-02-05 04:07:15'),
	(601, 1, 45, '2026-02-05 04:07:15'),
	(602, 1, 46, '2026-02-05 04:07:15'),
	(603, 1, 47, '2026-02-05 04:07:15'),
	(604, 1, 21, '2026-02-05 04:07:15'),
	(605, 1, 22, '2026-02-05 04:07:15'),
	(606, 1, 23, '2026-02-05 04:07:15'),
	(607, 1, 1, '2026-02-05 04:07:15'),
	(608, 1, 2, '2026-02-05 04:07:15'),
	(609, 1, 7, '2026-02-05 04:07:15'),
	(610, 1, 8, '2026-02-05 04:07:15'),
	(611, 1, 9, '2026-02-05 04:07:15'),
	(612, 1, 10, '2026-02-05 04:07:15'),
	(613, 1, 3, '2026-02-05 04:07:15'),
	(614, 1, 4, '2026-02-05 04:07:15'),
	(615, 1, 5, '2026-02-05 04:07:15'),
	(616, 1, 6, '2026-02-05 04:07:15'),
	(617, 1, 99, '2026-02-05 04:07:15'),
	(618, 1, 75, '2026-02-05 04:07:15'),
	(619, 1, 76, '2026-02-05 04:07:15'),
	(620, 1, 77, '2026-02-05 04:07:15'),
	(621, 1, 78, '2026-02-05 04:07:15'),
	(622, 1, 79, '2026-02-05 04:07:15'),
	(623, 1, 80, '2026-02-05 04:07:15'),
	(624, 1, 11, '2026-02-05 04:07:15'),
	(625, 1, 12, '2026-02-05 04:07:15'),
	(626, 1, 13, '2026-02-05 04:07:15'),
	(627, 1, 14, '2026-02-05 04:07:15'),
	(628, 1, 98, '2026-02-05 04:07:15'),
	(629, 1, 27, '2026-02-05 04:07:15'),
	(630, 1, 28, '2026-02-05 04:07:15'),
	(631, 1, 29, '2026-02-05 04:07:15'),
	(632, 1, 37, '2026-02-05 04:07:15'),
	(633, 1, 38, '2026-02-05 04:07:15'),
	(634, 1, 39, '2026-02-05 04:07:15'),
	(635, 1, 40, '2026-02-05 04:07:15'),
	(636, 1, 41, '2026-02-05 04:07:15'),
	(637, 1, 42, '2026-02-05 04:07:15'),
	(638, 1, 35, '2026-02-05 04:07:15'),
	(639, 1, 36, '2026-02-05 04:07:15'),
	(640, 1, 24, '2026-02-05 04:07:15'),
	(641, 1, 25, '2026-02-05 04:07:15'),
	(642, 1, 26, '2026-02-05 04:07:15'),
	(643, 1, 15, '2026-02-05 04:07:15'),
	(644, 1, 16, '2026-02-05 04:07:15'),
	(645, 1, 17, '2026-02-05 04:07:15'),
	(646, 1, 18, '2026-02-05 04:07:15'),
	(647, 1, 19, '2026-02-05 04:07:15'),
	(648, 1, 20, '2026-02-05 04:07:15'),
	(649, 1, 100, '2026-02-05 04:07:15'),
	(650, 1, 101, '2026-02-05 04:07:15'),
	(651, 1, 102, '2026-02-05 04:07:15'),
	(652, 1, 81, '2026-02-05 04:07:15'),
	(653, 1, 82, '2026-02-05 04:07:15'),
	(654, 1, 83, '2026-02-05 04:07:15'),
	(655, 1, 84, '2026-02-05 04:07:15'),
	(656, 1, 85, '2026-02-05 04:07:15'),
	(657, 1, 86, '2026-02-05 04:07:15'),
	(658, 1, 87, '2026-02-05 04:07:15'),
	(659, 1, 88, '2026-02-05 04:07:15'),
	(660, 1, 89, '2026-02-05 04:07:15'),
	(661, 1, 90, '2026-02-05 04:07:15'),
	(662, 1, 91, '2026-02-05 04:07:15'),
	(663, 1, 30, '2026-02-05 04:07:15'),
	(664, 1, 31, '2026-02-05 04:07:15'),
	(665, 1, 32, '2026-02-05 04:07:15'),
	(666, 1, 33, '2026-02-05 04:07:15'),
	(667, 1, 34, '2026-02-05 04:07:15'),
	(668, 2, 43, '2026-02-07 17:12:39'),
	(669, 2, 21, '2026-02-07 17:12:39'),
	(670, 2, 22, '2026-02-07 17:12:39'),
	(671, 2, 7, '2026-02-07 17:12:39'),
	(672, 2, 3, '2026-02-07 17:12:39'),
	(673, 2, 99, '2026-02-07 17:12:39'),
	(674, 2, 77, '2026-02-07 17:12:39'),
	(675, 2, 78, '2026-02-07 17:12:39'),
	(676, 2, 11, '2026-02-07 17:12:39'),
	(677, 2, 27, '2026-02-07 17:12:39'),
	(678, 2, 38, '2026-02-07 17:12:39'),
	(679, 2, 40, '2026-02-07 17:12:39'),
	(680, 2, 41, '2026-02-07 17:12:39'),
	(681, 2, 42, '2026-02-07 17:12:39'),
	(682, 2, 24, '2026-02-07 17:12:39'),
	(683, 2, 26, '2026-02-07 17:12:39'),
	(684, 2, 15, '2026-02-07 17:12:39'),
	(685, 2, 83, '2026-02-07 17:12:39'),
	(686, 2, 84, '2026-02-07 17:12:39'),
	(687, 2, 90, '2026-02-07 17:12:39'),
	(688, 2, 91, '2026-02-07 17:12:39'),
	(689, 2, 30, '2026-02-07 17:12:39');

-- Dumping structure for table mobile_db.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_slug` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_system` tinyint(1) DEFAULT '0' COMMENT '1 = cannot be deleted',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_slug` (`role_slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.roles: ~3 rows (approximately)
DELETE FROM `roles`;
INSERT INTO `roles` (`id`, `role_name`, `role_slug`, `description`, `is_system`, `created_at`, `updated_at`) VALUES
	(1, 'Super Admin', 'super_admin', NULL, 1, '2026-01-23 19:19:55', '2026-01-23 19:19:55'),
	(2, 'Employee', 'employee', NULL, 1, '2026-01-23 19:19:55', '2026-01-23 19:19:55'),
	(3, 'Manager / Admin', 'admin', 'Kepala Departemen', 1, '2026-02-05 03:35:11', '2026-02-05 03:35:11');

-- Dumping structure for table mobile_db.salary_grade_components
CREATE TABLE IF NOT EXISTS `salary_grade_components` (
  `id` int NOT NULL AUTO_INCREMENT,
  `grade_id` int NOT NULL,
  `component_id` int NOT NULL,
  `amount` decimal(15,2) DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_grade_comp` (`grade_id`,`component_id`),
  KEY `component_id` (`component_id`),
  CONSTRAINT `salary_grade_components_ibfk_1` FOREIGN KEY (`grade_id`) REFERENCES `salary_grades` (`id`) ON DELETE CASCADE,
  CONSTRAINT `salary_grade_components_ibfk_2` FOREIGN KEY (`component_id`) REFERENCES `payroll_components` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.salary_grade_components: ~6 rows (approximately)
DELETE FROM `salary_grade_components`;
INSERT INTO `salary_grade_components` (`id`, `grade_id`, `component_id`, `amount`, `created_at`) VALUES
	(2, 1, 2, 200000.00, '2026-01-26 11:03:34'),
	(3, 1, 5, 200000.00, '2026-01-26 11:03:39'),
	(4, 1, 6, 200000.00, '2026-01-26 11:04:10'),
	(5, 1, 4, 50000.00, '2026-01-26 11:04:23'),
	(6, 1, 3, 400000.00, '2026-01-26 11:04:31'),
	(7, 2, 1, 2500000.00, '2026-02-02 06:19:36');

-- Dumping structure for table mobile_db.salary_grades
CREATE TABLE IF NOT EXISTS `salary_grades` (
  `id` int NOT NULL AUTO_INCREMENT,
  `grade_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `grade_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `base_salary` decimal(15,2) DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grade_code` (`grade_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.salary_grades: ~6 rows (approximately)
DELETE FROM `salary_grades`;
INSERT INTO `salary_grades` (`id`, `grade_code`, `grade_name`, `description`, `base_salary`, `created_at`) VALUES
	(1, 'I', 'NON STAFF', 'Harian / Lepas', 700000.00, '2026-01-26 09:19:13'),
	(2, 'II', 'STAFF', 'Karyawan Tetap Staff', 0.00, '2026-01-26 09:19:13'),
	(3, 'III', 'SENIOR STAFF', 'Karyawan Senior / Team Lead', 0.00, '2026-01-26 09:19:13'),
	(4, 'IV', 'SUPERVISOR', 'Penyelia', 0.00, '2026-01-26 09:19:13'),
	(5, 'V', 'DEPT MANAGER', 'Manajer Departemen', 0.00, '2026-01-26 09:19:13'),
	(6, 'VI', 'DIRECTOR', 'Direktur Perusahaan', 0.00, '2026-01-26 09:19:13');

-- Dumping structure for table mobile_db.shift_assignments
CREATE TABLE IF NOT EXISTS `shift_assignments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `shift_id` int NOT NULL,
  `assignment_date` date NOT NULL,
  `is_mod` tinyint(1) DEFAULT '0',
  `mod_bonus` decimal(10,2) DEFAULT '0.00',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_assignment` (`employee_id`,`assignment_date`),
  KEY `employee_id` (`employee_id`),
  KEY `shift_id` (`shift_id`),
  KEY `assignment_date` (`assignment_date`),
  KEY `is_mod` (`is_mod`),
  KEY `idx_date_range` (`assignment_date`,`employee_id`),
  CONSTRAINT `shift_assignments_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `shift_assignments_ibfk_2` FOREIGN KEY (`shift_id`) REFERENCES `work_shifts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.shift_assignments: ~970 rows (approximately)
DELETE FROM `shift_assignments`;
INSERT INTO `shift_assignments` (`id`, `employee_id`, `shift_id`, `assignment_date`, `is_mod`, `mod_bonus`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
	(1, 2, 1, '2026-01-03', 1, 150000.00, 'MOD Sabtu (Anggoro)', NULL, '2026-01-26 03:18:07', '2026-01-26 03:18:07'),
	(2, 3, 1, '2026-01-04', 1, 200000.00, 'MOD Minggu (Diana)', NULL, '2026-01-26 03:18:07', '2026-01-26 03:18:07'),
	(3, 2, 4, '2026-01-04', 1, 100000.00, NULL, 16, '2026-01-26 08:37:49', '2026-01-26 08:37:49'),
	(668, 11, 4, '2026-01-01', 0, 0.00, NULL, 16, '2026-01-26 08:39:16', '2026-01-26 08:39:16'),
	(671, 8, 4, '2026-01-01', 0, 0.00, NULL, 16, '2026-01-26 08:39:16', '2026-01-26 08:39:16'),
	(672, 10, 4, '2026-01-01', 0, 0.00, NULL, 16, '2026-01-26 08:39:16', '2026-01-26 08:39:16'),
	(673, 12, 4, '2026-01-01', 0, 0.00, NULL, 16, '2026-01-26 08:39:16', '2026-01-26 08:39:16'),
	(675, 9, 4, '2026-01-01', 0, 0.00, NULL, 16, '2026-01-26 08:39:16', '2026-01-26 08:39:16'),
	(678, 19, 4, '2026-01-01', 0, 0.00, NULL, 16, '2026-01-26 08:39:16', '2026-01-26 08:39:16'),
	(679, 20, 4, '2026-01-01', 0, 0.00, NULL, 16, '2026-01-26 08:39:16', '2026-01-26 08:39:16'),
	(680, 15, 4, '2026-01-01', 0, 0.00, NULL, 16, '2026-01-26 08:39:16', '2026-01-26 08:39:16'),
	(681, 18, 4, '2026-01-01', 0, 0.00, NULL, 16, '2026-01-26 08:39:16', '2026-01-26 08:39:16'),
	(682, 21, 4, '2026-01-01', 0, 0.00, NULL, 16, '2026-01-26 08:39:16', '2026-01-26 08:39:16'),
	(683, 22, 4, '2026-01-01', 0, 0.00, NULL, 16, '2026-01-26 08:39:16', '2026-01-26 08:39:16'),
	(684, 17, 4, '2026-01-01', 0, 0.00, NULL, 16, '2026-01-26 08:39:16', '2026-01-26 08:39:16'),
	(685, 16, 4, '2026-01-01', 0, 0.00, NULL, 16, '2026-01-26 08:39:16', '2026-01-26 08:39:16'),
	(686, 23, 4, '2026-01-01', 0, 0.00, NULL, 16, '2026-01-26 08:39:16', '2026-01-26 08:39:16'),
	(687, 14, 4, '2026-01-01', 0, 0.00, NULL, 16, '2026-01-26 08:39:16', '2026-01-26 08:39:16'),
	(688, 13, 4, '2026-01-01', 0, 0.00, NULL, 16, '2026-01-26 08:39:16', '2026-01-26 08:39:16'),
	(689, 1, 4, '2026-01-01', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(690, 2, 4, '2026-01-01', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(691, 3, 4, '2026-01-01', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(692, 4, 4, '2026-01-01', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(693, 5, 4, '2026-01-01', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(694, 6, 4, '2026-01-01', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(695, 7, 4, '2026-01-01', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(698, 11, 4, '2026-01-02', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(701, 8, 4, '2026-01-02', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(702, 10, 4, '2026-01-02', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(705, 9, 4, '2026-01-02', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(708, 19, 4, '2026-01-02', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(709, 20, 4, '2026-01-02', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(710, 15, 4, '2026-01-02', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(711, 18, 4, '2026-01-02', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(712, 21, 4, '2026-01-02', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(713, 22, 4, '2026-01-02', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(714, 17, 4, '2026-01-02', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(715, 16, 4, '2026-01-02', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(716, 23, 4, '2026-01-02', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(717, 14, 4, '2026-01-02', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(718, 13, 4, '2026-01-02', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(719, 1, 4, '2026-01-02', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(720, 2, 4, '2026-01-02', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(721, 3, 4, '2026-01-02', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(722, 4, 4, '2026-01-02', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(723, 5, 4, '2026-01-02', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(724, 6, 4, '2026-01-02', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(725, 7, 4, '2026-01-02', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(728, 11, 4, '2026-01-05', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(731, 8, 4, '2026-01-05', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(732, 10, 4, '2026-01-05', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(733, 12, 4, '2026-01-05', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(735, 9, 4, '2026-01-05', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(738, 19, 4, '2026-01-05', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(739, 20, 4, '2026-01-05', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(740, 15, 4, '2026-01-05', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(741, 18, 4, '2026-01-05', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(742, 21, 4, '2026-01-05', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(743, 22, 4, '2026-01-05', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(744, 17, 4, '2026-01-05', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(745, 16, 4, '2026-01-05', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(746, 23, 4, '2026-01-05', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(747, 14, 4, '2026-01-05', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(748, 13, 4, '2026-01-05', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(749, 1, 4, '2026-01-05', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(750, 2, 4, '2026-01-05', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(751, 3, 4, '2026-01-05', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(752, 4, 4, '2026-01-05', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(753, 5, 4, '2026-01-05', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(754, 6, 4, '2026-01-05', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(755, 7, 4, '2026-01-05', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(758, 11, 4, '2026-01-06', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(761, 8, 4, '2026-01-06', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(762, 10, 4, '2026-01-06', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(763, 12, 4, '2026-01-06', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(765, 9, 4, '2026-01-06', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(768, 19, 4, '2026-01-06', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(769, 20, 4, '2026-01-06', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(770, 15, 4, '2026-01-06', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(771, 18, 4, '2026-01-06', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(772, 21, 4, '2026-01-06', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(773, 22, 4, '2026-01-06', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(774, 17, 4, '2026-01-06', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(775, 16, 4, '2026-01-06', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(776, 23, 4, '2026-01-06', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(777, 14, 4, '2026-01-06', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(778, 13, 4, '2026-01-06', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(779, 1, 4, '2026-01-06', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(780, 2, 4, '2026-01-06', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(781, 3, 4, '2026-01-06', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(782, 4, 4, '2026-01-06', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(783, 5, 4, '2026-01-06', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(784, 6, 4, '2026-01-06', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(785, 7, 4, '2026-01-06', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(788, 11, 4, '2026-01-07', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(791, 8, 4, '2026-01-07', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(792, 10, 4, '2026-01-07', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(793, 12, 4, '2026-01-07', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(795, 9, 4, '2026-01-07', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(798, 19, 4, '2026-01-07', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(799, 20, 4, '2026-01-07', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(800, 15, 4, '2026-01-07', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(801, 18, 4, '2026-01-07', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(802, 21, 4, '2026-01-07', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(803, 22, 4, '2026-01-07', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(804, 17, 4, '2026-01-07', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(805, 16, 4, '2026-01-07', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(806, 23, 4, '2026-01-07', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(807, 14, 4, '2026-01-07', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(808, 13, 4, '2026-01-07', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(809, 1, 4, '2026-01-07', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(810, 2, 4, '2026-01-07', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(811, 3, 4, '2026-01-07', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(812, 4, 4, '2026-01-07', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(813, 5, 4, '2026-01-07', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(814, 6, 4, '2026-01-07', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(815, 7, 4, '2026-01-07', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(818, 11, 4, '2026-01-08', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(821, 8, 4, '2026-01-08', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(822, 10, 4, '2026-01-08', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(823, 12, 4, '2026-01-08', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(825, 9, 4, '2026-01-08', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(828, 19, 4, '2026-01-08', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(829, 20, 4, '2026-01-08', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(830, 15, 4, '2026-01-08', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(831, 18, 4, '2026-01-08', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(832, 21, 4, '2026-01-08', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(833, 22, 4, '2026-01-08', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(834, 17, 4, '2026-01-08', 0, 0.00, NULL, 16, '2026-01-26 08:39:17', '2026-01-26 08:39:17'),
	(835, 16, 4, '2026-01-08', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(836, 23, 4, '2026-01-08', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(837, 14, 4, '2026-01-08', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(838, 13, 4, '2026-01-08', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(839, 1, 4, '2026-01-08', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(840, 2, 4, '2026-01-08', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(841, 3, 4, '2026-01-08', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(842, 4, 4, '2026-01-08', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(843, 5, 4, '2026-01-08', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(844, 6, 4, '2026-01-08', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(845, 7, 4, '2026-01-08', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(848, 11, 4, '2026-01-09', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(851, 8, 4, '2026-01-09', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(852, 10, 4, '2026-01-09', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(853, 12, 4, '2026-01-09', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(855, 9, 4, '2026-01-09', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(858, 19, 4, '2026-01-09', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(859, 20, 4, '2026-01-09', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(860, 15, 4, '2026-01-09', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(861, 18, 4, '2026-01-09', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(862, 21, 4, '2026-01-09', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(863, 22, 4, '2026-01-09', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(864, 17, 4, '2026-01-09', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(865, 16, 4, '2026-01-09', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(866, 23, 4, '2026-01-09', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(867, 14, 4, '2026-01-09', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(868, 13, 4, '2026-01-09', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(869, 1, 4, '2026-01-09', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(870, 2, 4, '2026-01-09', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(871, 3, 4, '2026-01-09', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(872, 4, 4, '2026-01-09', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(873, 5, 4, '2026-01-09', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(874, 6, 4, '2026-01-09', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(875, 7, 4, '2026-01-09', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(878, 11, 4, '2026-01-12', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(881, 8, 4, '2026-01-12', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(882, 10, 4, '2026-01-12', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(883, 12, 4, '2026-01-12', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(885, 9, 4, '2026-01-12', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(888, 19, 4, '2026-01-12', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(889, 20, 4, '2026-01-12', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(890, 15, 4, '2026-01-12', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(891, 18, 4, '2026-01-12', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(892, 21, 4, '2026-01-12', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(893, 22, 4, '2026-01-12', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(894, 17, 4, '2026-01-12', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(895, 16, 4, '2026-01-12', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(896, 23, 4, '2026-01-12', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(897, 14, 4, '2026-01-12', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(898, 13, 4, '2026-01-12', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(899, 1, 4, '2026-01-12', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(900, 2, 4, '2026-01-12', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(901, 3, 4, '2026-01-12', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(902, 4, 4, '2026-01-12', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(903, 5, 4, '2026-01-12', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(904, 6, 4, '2026-01-12', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(905, 7, 4, '2026-01-12', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(908, 11, 4, '2026-01-13', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(911, 8, 4, '2026-01-13', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(912, 10, 4, '2026-01-13', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(913, 12, 4, '2026-01-13', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(915, 9, 4, '2026-01-13', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(918, 19, 4, '2026-01-13', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(919, 20, 4, '2026-01-13', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(920, 15, 4, '2026-01-13', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(921, 18, 4, '2026-01-13', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(922, 21, 4, '2026-01-13', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(923, 22, 4, '2026-01-13', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(924, 17, 4, '2026-01-13', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(925, 16, 4, '2026-01-13', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(926, 23, 4, '2026-01-13', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(927, 14, 4, '2026-01-13', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(928, 13, 4, '2026-01-13', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(929, 1, 4, '2026-01-13', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(930, 2, 4, '2026-01-13', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(931, 3, 4, '2026-01-13', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(932, 4, 4, '2026-01-13', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(933, 5, 4, '2026-01-13', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(934, 6, 4, '2026-01-13', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(935, 7, 4, '2026-01-13', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(938, 11, 4, '2026-01-14', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(941, 8, 4, '2026-01-14', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(942, 10, 4, '2026-01-14', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(943, 12, 4, '2026-01-14', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(945, 9, 4, '2026-01-14', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(948, 19, 4, '2026-01-14', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(949, 20, 4, '2026-01-14', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(950, 15, 4, '2026-01-14', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(951, 18, 4, '2026-01-14', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(952, 21, 4, '2026-01-14', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(953, 22, 4, '2026-01-14', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(954, 17, 4, '2026-01-14', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(955, 16, 4, '2026-01-14', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(956, 23, 4, '2026-01-14', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(957, 14, 4, '2026-01-14', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(958, 13, 4, '2026-01-14', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(959, 1, 4, '2026-01-14', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(960, 2, 4, '2026-01-14', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(961, 3, 4, '2026-01-14', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(962, 4, 4, '2026-01-14', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(963, 5, 4, '2026-01-14', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(964, 6, 4, '2026-01-14', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(965, 7, 4, '2026-01-14', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(968, 11, 4, '2026-01-15', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(971, 8, 4, '2026-01-15', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(972, 10, 4, '2026-01-15', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(973, 12, 4, '2026-01-15', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(975, 9, 4, '2026-01-15', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(978, 19, 4, '2026-01-15', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(979, 20, 4, '2026-01-15', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(980, 15, 4, '2026-01-15', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(981, 18, 4, '2026-01-15', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(982, 21, 4, '2026-01-15', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(983, 22, 4, '2026-01-15', 0, 0.00, NULL, 16, '2026-01-26 08:39:18', '2026-01-26 08:39:18'),
	(984, 17, 4, '2026-01-15', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(985, 16, 4, '2026-01-15', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(986, 23, 4, '2026-01-15', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(987, 14, 4, '2026-01-15', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(988, 13, 4, '2026-01-15', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(989, 1, 4, '2026-01-15', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(990, 2, 4, '2026-01-15', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(991, 3, 4, '2026-01-15', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(992, 4, 4, '2026-01-15', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(993, 5, 4, '2026-01-15', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(994, 6, 4, '2026-01-15', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(995, 7, 4, '2026-01-15', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(998, 11, 4, '2026-01-16', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1001, 8, 4, '2026-01-16', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1002, 10, 4, '2026-01-16', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1003, 12, 4, '2026-01-16', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1005, 9, 4, '2026-01-16', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1008, 19, 4, '2026-01-16', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1009, 20, 4, '2026-01-16', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1010, 15, 4, '2026-01-16', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1011, 18, 4, '2026-01-16', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1012, 21, 4, '2026-01-16', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1013, 22, 4, '2026-01-16', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1014, 17, 4, '2026-01-16', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1015, 16, 4, '2026-01-16', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1016, 23, 4, '2026-01-16', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1017, 14, 4, '2026-01-16', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1018, 13, 4, '2026-01-16', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1019, 1, 4, '2026-01-16', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1020, 2, 4, '2026-01-16', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1021, 3, 4, '2026-01-16', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1022, 4, 4, '2026-01-16', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1023, 5, 4, '2026-01-16', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1024, 6, 4, '2026-01-16', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1025, 7, 4, '2026-01-16', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1028, 11, 4, '2026-01-19', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1031, 8, 4, '2026-01-19', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1032, 10, 4, '2026-01-19', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1033, 12, 4, '2026-01-19', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1035, 9, 4, '2026-01-19', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1038, 19, 4, '2026-01-19', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1039, 20, 4, '2026-01-19', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1040, 15, 4, '2026-01-19', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1041, 18, 4, '2026-01-19', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1042, 21, 4, '2026-01-19', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1043, 22, 4, '2026-01-19', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1044, 17, 4, '2026-01-19', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1045, 16, 4, '2026-01-19', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1046, 23, 4, '2026-01-19', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1047, 14, 4, '2026-01-19', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1048, 13, 4, '2026-01-19', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1049, 1, 4, '2026-01-19', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1050, 2, 4, '2026-01-19', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1051, 3, 4, '2026-01-19', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1052, 4, 4, '2026-01-19', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1053, 5, 4, '2026-01-19', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1054, 6, 4, '2026-01-19', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1055, 7, 4, '2026-01-19', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1058, 11, 4, '2026-01-20', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1061, 8, 4, '2026-01-20', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1062, 10, 4, '2026-01-20', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1063, 12, 4, '2026-01-20', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1065, 9, 4, '2026-01-20', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1068, 19, 4, '2026-01-20', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1069, 20, 4, '2026-01-20', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1070, 15, 4, '2026-01-20', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1071, 18, 4, '2026-01-20', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1072, 21, 4, '2026-01-20', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1073, 22, 4, '2026-01-20', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1074, 17, 4, '2026-01-20', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1075, 16, 4, '2026-01-20', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1076, 23, 4, '2026-01-20', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1077, 14, 4, '2026-01-20', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1078, 13, 4, '2026-01-20', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1079, 1, 4, '2026-01-20', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1080, 2, 4, '2026-01-20', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1081, 3, 4, '2026-01-20', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1082, 4, 4, '2026-01-20', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1083, 5, 4, '2026-01-20', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1084, 6, 4, '2026-01-20', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1085, 7, 4, '2026-01-20', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1088, 11, 4, '2026-01-21', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1091, 8, 4, '2026-01-21', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1092, 10, 4, '2026-01-21', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1093, 12, 4, '2026-01-21', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1095, 9, 4, '2026-01-21', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1098, 19, 4, '2026-01-21', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1099, 20, 4, '2026-01-21', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1100, 15, 4, '2026-01-21', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1101, 18, 4, '2026-01-21', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1102, 21, 4, '2026-01-21', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1103, 22, 4, '2026-01-21', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1104, 17, 4, '2026-01-21', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1105, 16, 4, '2026-01-21', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1106, 23, 4, '2026-01-21', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1107, 14, 4, '2026-01-21', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1108, 13, 4, '2026-01-21', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1109, 1, 4, '2026-01-21', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1110, 2, 4, '2026-01-21', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1111, 3, 4, '2026-01-21', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1112, 4, 4, '2026-01-21', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1113, 5, 4, '2026-01-21', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1114, 6, 4, '2026-01-21', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1115, 7, 4, '2026-01-21', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1118, 11, 4, '2026-01-22', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1121, 8, 4, '2026-01-22', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1122, 10, 4, '2026-01-22', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1123, 12, 4, '2026-01-22', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1125, 9, 4, '2026-01-22', 0, 0.00, NULL, 16, '2026-01-26 08:39:19', '2026-01-26 08:39:19'),
	(1128, 19, 4, '2026-01-22', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1129, 20, 4, '2026-01-22', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1130, 15, 4, '2026-01-22', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1131, 18, 4, '2026-01-22', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1132, 21, 4, '2026-01-22', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1133, 22, 4, '2026-01-22', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1134, 17, 4, '2026-01-22', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1135, 16, 4, '2026-01-22', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1136, 23, 4, '2026-01-22', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1137, 14, 4, '2026-01-22', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1138, 13, 4, '2026-01-22', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1139, 1, 4, '2026-01-22', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1140, 2, 4, '2026-01-22', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1141, 3, 4, '2026-01-22', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1142, 4, 4, '2026-01-22', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1143, 5, 4, '2026-01-22', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1144, 6, 4, '2026-01-22', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1145, 7, 4, '2026-01-22', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1148, 11, 4, '2026-01-23', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1151, 8, 4, '2026-01-23', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1152, 10, 4, '2026-01-23', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1153, 12, 4, '2026-01-23', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1155, 9, 4, '2026-01-23', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1158, 19, 4, '2026-01-23', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1159, 20, 4, '2026-01-23', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1160, 15, 4, '2026-01-23', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1161, 18, 4, '2026-01-23', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1162, 21, 4, '2026-01-23', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1163, 22, 4, '2026-01-23', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1164, 17, 4, '2026-01-23', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1165, 16, 4, '2026-01-23', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1166, 23, 4, '2026-01-23', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1167, 14, 4, '2026-01-23', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1168, 13, 4, '2026-01-23', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1169, 1, 4, '2026-01-23', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1170, 2, 4, '2026-01-23', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1171, 3, 4, '2026-01-23', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1172, 4, 4, '2026-01-23', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1173, 5, 4, '2026-01-23', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1174, 6, 4, '2026-01-23', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1175, 7, 4, '2026-01-23', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1178, 11, 4, '2026-01-26', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1181, 8, 4, '2026-01-26', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1182, 10, 4, '2026-01-26', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1183, 12, 4, '2026-01-26', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1185, 9, 4, '2026-01-26', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1188, 19, 4, '2026-01-26', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1189, 20, 4, '2026-01-26', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1190, 15, 4, '2026-01-26', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1191, 18, 4, '2026-01-26', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1192, 21, 4, '2026-01-26', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1193, 22, 4, '2026-01-26', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1194, 17, 4, '2026-01-26', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1195, 16, 4, '2026-01-26', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1196, 23, 4, '2026-01-26', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1197, 14, 4, '2026-01-26', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1198, 13, 4, '2026-01-26', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1199, 1, 4, '2026-01-26', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1200, 2, 4, '2026-01-26', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1201, 3, 4, '2026-01-26', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1202, 4, 4, '2026-01-26', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1203, 5, 4, '2026-01-26', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1204, 6, 4, '2026-01-26', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1205, 7, 4, '2026-01-26', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1208, 11, 4, '2026-01-27', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1211, 8, 4, '2026-01-27', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1212, 10, 4, '2026-01-27', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1213, 12, 4, '2026-01-27', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1215, 9, 4, '2026-01-27', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1218, 19, 4, '2026-01-27', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1219, 20, 4, '2026-01-27', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1220, 15, 4, '2026-01-27', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1221, 18, 4, '2026-01-27', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1222, 21, 4, '2026-01-27', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1223, 22, 4, '2026-01-27', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1224, 17, 4, '2026-01-27', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1225, 16, 4, '2026-01-27', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1226, 23, 4, '2026-01-27', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1227, 14, 4, '2026-01-27', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1228, 13, 4, '2026-01-27', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1229, 1, 4, '2026-01-27', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1230, 2, 4, '2026-01-27', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1231, 3, 4, '2026-01-27', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1232, 4, 4, '2026-01-27', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1233, 5, 4, '2026-01-27', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1234, 6, 4, '2026-01-27', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1235, 7, 4, '2026-01-27', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1238, 11, 4, '2026-01-28', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1241, 8, 4, '2026-01-28', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1242, 10, 4, '2026-01-28', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1243, 12, 4, '2026-01-28', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1245, 9, 4, '2026-01-28', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1248, 19, 4, '2026-01-28', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1249, 20, 4, '2026-01-28', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1250, 15, 4, '2026-01-28', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1251, 18, 4, '2026-01-28', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1252, 21, 4, '2026-01-28', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1253, 22, 4, '2026-01-28', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1254, 17, 4, '2026-01-28', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1255, 16, 4, '2026-01-28', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1256, 23, 4, '2026-01-28', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1257, 14, 4, '2026-01-28', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1258, 13, 4, '2026-01-28', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1259, 1, 4, '2026-01-28', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1260, 2, 4, '2026-01-28', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1261, 3, 4, '2026-01-28', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1262, 4, 4, '2026-01-28', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1263, 5, 4, '2026-01-28', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1264, 6, 4, '2026-01-28', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1265, 7, 4, '2026-01-28', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1268, 11, 4, '2026-01-29', 0, 0.00, NULL, 16, '2026-01-26 08:39:20', '2026-01-26 08:39:20'),
	(1271, 8, 4, '2026-01-29', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1272, 10, 4, '2026-01-29', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1273, 12, 4, '2026-01-29', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1275, 9, 4, '2026-01-29', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1278, 19, 4, '2026-01-29', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1279, 20, 4, '2026-01-29', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1280, 15, 4, '2026-01-29', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1281, 18, 4, '2026-01-29', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1282, 21, 4, '2026-01-29', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1283, 22, 4, '2026-01-29', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1284, 17, 4, '2026-01-29', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1285, 16, 4, '2026-01-29', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1286, 23, 4, '2026-01-29', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1287, 14, 4, '2026-01-29', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1288, 13, 4, '2026-01-29', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1289, 1, 4, '2026-01-29', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1290, 2, 4, '2026-01-29', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1291, 3, 4, '2026-01-29', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1292, 4, 4, '2026-01-29', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1293, 5, 4, '2026-01-29', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1294, 6, 4, '2026-01-29', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1295, 7, 4, '2026-01-29', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1298, 11, 4, '2026-01-30', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1301, 8, 4, '2026-01-30', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1302, 10, 4, '2026-01-30', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1303, 12, 4, '2026-01-30', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1305, 9, 4, '2026-01-30', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1308, 19, 4, '2026-01-30', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1309, 20, 4, '2026-01-30', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1310, 15, 4, '2026-01-30', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1311, 18, 4, '2026-01-30', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1312, 21, 4, '2026-01-30', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1313, 22, 4, '2026-01-30', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1314, 17, 4, '2026-01-30', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1315, 16, 4, '2026-01-30', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1316, 23, 4, '2026-01-30', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1317, 14, 4, '2026-01-30', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1318, 13, 4, '2026-01-30', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1319, 1, 4, '2026-01-30', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1320, 2, 4, '2026-01-30', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1321, 3, 4, '2026-01-30', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1322, 4, 4, '2026-01-30', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1323, 5, 4, '2026-01-30', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1324, 6, 4, '2026-01-30', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1325, 7, 4, '2026-01-30', 0, 0.00, NULL, 16, '2026-01-26 08:39:21', '2026-01-26 08:39:21'),
	(1326, 4, 4, '2026-01-04', 1, 100000.00, NULL, 1, '2026-01-30 19:44:59', '2026-01-30 19:44:59'),
	(1327, 1, 1, '2025-01-01', 1, 100000.00, NULL, NULL, '2026-01-30 19:52:51', '2026-01-30 19:52:51'),
	(1930, 15, 4, '2026-02-02', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1932, 16, 4, '2026-02-02', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1933, 14, 4, '2026-02-02', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1934, 13, 4, '2026-02-02', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1935, 19, 4, '2026-02-02', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1937, 11, 4, '2026-02-02', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1940, 8, 4, '2026-02-02', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1941, 10, 4, '2026-02-02', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1942, 20, 4, '2026-02-02', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1943, 12, 4, '2026-02-02', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1945, 9, 4, '2026-02-02', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1946, 18, 4, '2026-02-02', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1947, 21, 4, '2026-02-02', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1948, 22, 4, '2026-02-02', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1949, 17, 4, '2026-02-02', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1951, 23, 4, '2026-02-02', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1952, 1, 4, '2026-02-02', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1953, 2, 4, '2026-02-02', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1954, 3, 4, '2026-02-02', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1955, 4, 4, '2026-02-02', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1956, 5, 4, '2026-02-02', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1957, 6, 4, '2026-02-02', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1958, 7, 4, '2026-02-02', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1960, 15, 4, '2026-02-03', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1962, 16, 4, '2026-02-03', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1963, 14, 4, '2026-02-03', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1964, 13, 4, '2026-02-03', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1965, 19, 4, '2026-02-03', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1967, 11, 4, '2026-02-03', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1970, 8, 4, '2026-02-03', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1971, 10, 4, '2026-02-03', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1972, 20, 4, '2026-02-03', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1973, 12, 4, '2026-02-03', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1975, 9, 4, '2026-02-03', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1976, 18, 4, '2026-02-03', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1977, 21, 4, '2026-02-03', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1978, 22, 4, '2026-02-03', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1979, 17, 4, '2026-02-03', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1981, 23, 4, '2026-02-03', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1982, 1, 4, '2026-02-03', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1983, 2, 4, '2026-02-03', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1984, 3, 4, '2026-02-03', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1985, 4, 4, '2026-02-03', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1986, 5, 4, '2026-02-03', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1987, 6, 4, '2026-02-03', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1988, 7, 4, '2026-02-03', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1990, 15, 4, '2026-02-04', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1992, 16, 4, '2026-02-04', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1993, 14, 4, '2026-02-04', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1994, 13, 4, '2026-02-04', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1995, 19, 4, '2026-02-04', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(1997, 11, 4, '2026-02-04', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2000, 8, 4, '2026-02-04', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2001, 10, 4, '2026-02-04', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2002, 20, 4, '2026-02-04', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2003, 12, 4, '2026-02-04', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2005, 9, 4, '2026-02-04', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2006, 18, 4, '2026-02-04', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2007, 21, 4, '2026-02-04', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2008, 22, 4, '2026-02-04', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2009, 17, 4, '2026-02-04', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2011, 23, 4, '2026-02-04', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2012, 1, 4, '2026-02-04', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2013, 2, 4, '2026-02-04', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2014, 3, 4, '2026-02-04', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2015, 4, 4, '2026-02-04', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2016, 5, 4, '2026-02-04', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2017, 6, 4, '2026-02-04', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2018, 7, 4, '2026-02-04', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2020, 15, 4, '2026-02-05', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2022, 16, 4, '2026-02-05', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2023, 14, 4, '2026-02-05', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2024, 13, 4, '2026-02-05', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2025, 19, 4, '2026-02-05', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2027, 11, 4, '2026-02-05', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2030, 8, 4, '2026-02-05', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2031, 10, 4, '2026-02-05', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2032, 20, 4, '2026-02-05', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2033, 12, 4, '2026-02-05', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2035, 9, 4, '2026-02-05', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2036, 18, 4, '2026-02-05', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2037, 21, 4, '2026-02-05', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2038, 22, 4, '2026-02-05', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2039, 17, 4, '2026-02-05', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2041, 23, 4, '2026-02-05', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2042, 1, 4, '2026-02-05', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2043, 2, 4, '2026-02-05', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2044, 3, 4, '2026-02-05', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2045, 4, 4, '2026-02-05', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2046, 5, 4, '2026-02-05', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2047, 6, 4, '2026-02-05', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2048, 7, 4, '2026-02-05', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2050, 15, 4, '2026-02-06', 0, 0.00, NULL, 16, '2026-02-02 08:38:51', '2026-02-02 08:38:51'),
	(2052, 16, 4, '2026-02-06', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2053, 14, 4, '2026-02-06', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2054, 13, 4, '2026-02-06', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2055, 19, 4, '2026-02-06', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2057, 11, 4, '2026-02-06', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2060, 8, 4, '2026-02-06', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2061, 10, 4, '2026-02-06', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2062, 20, 4, '2026-02-06', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2063, 12, 4, '2026-02-06', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2065, 9, 4, '2026-02-06', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2066, 18, 4, '2026-02-06', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2067, 21, 4, '2026-02-06', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2068, 22, 4, '2026-02-06', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2069, 17, 4, '2026-02-06', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2071, 23, 4, '2026-02-06', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2072, 1, 4, '2026-02-06', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2073, 2, 4, '2026-02-06', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2074, 3, 4, '2026-02-06', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2075, 4, 4, '2026-02-06', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2076, 5, 4, '2026-02-06', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2077, 6, 4, '2026-02-06', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2078, 7, 4, '2026-02-06', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2080, 15, 4, '2026-02-09', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2082, 16, 4, '2026-02-09', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2083, 14, 4, '2026-02-09', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2084, 13, 4, '2026-02-09', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2085, 19, 4, '2026-02-09', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2087, 11, 4, '2026-02-09', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2090, 8, 4, '2026-02-09', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2091, 10, 4, '2026-02-09', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2092, 20, 4, '2026-02-09', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2093, 12, 4, '2026-02-09', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2095, 9, 4, '2026-02-09', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2096, 18, 4, '2026-02-09', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2097, 21, 4, '2026-02-09', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2098, 22, 4, '2026-02-09', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2099, 17, 4, '2026-02-09', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2101, 23, 4, '2026-02-09', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2102, 1, 4, '2026-02-09', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2103, 2, 4, '2026-02-09', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2104, 3, 4, '2026-02-09', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2105, 4, 4, '2026-02-09', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2106, 5, 4, '2026-02-09', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2107, 6, 4, '2026-02-09', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2108, 7, 4, '2026-02-09', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2110, 15, 4, '2026-02-10', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2112, 16, 4, '2026-02-10', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2113, 14, 4, '2026-02-10', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2114, 13, 4, '2026-02-10', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2115, 19, 4, '2026-02-10', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2117, 11, 4, '2026-02-10', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2120, 8, 4, '2026-02-10', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2121, 10, 4, '2026-02-10', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2122, 20, 4, '2026-02-10', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2123, 12, 4, '2026-02-10', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2125, 9, 4, '2026-02-10', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2126, 18, 4, '2026-02-10', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2127, 21, 4, '2026-02-10', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2128, 22, 4, '2026-02-10', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2129, 17, 4, '2026-02-10', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2131, 23, 4, '2026-02-10', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2132, 1, 4, '2026-02-10', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2133, 2, 4, '2026-02-10', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2134, 3, 4, '2026-02-10', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2135, 4, 4, '2026-02-10', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2136, 5, 4, '2026-02-10', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2137, 6, 4, '2026-02-10', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2138, 7, 4, '2026-02-10', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2140, 15, 4, '2026-02-11', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2142, 16, 4, '2026-02-11', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2143, 14, 4, '2026-02-11', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2144, 13, 4, '2026-02-11', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2145, 19, 4, '2026-02-11', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2147, 11, 4, '2026-02-11', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2150, 8, 4, '2026-02-11', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2151, 10, 4, '2026-02-11', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2152, 20, 4, '2026-02-11', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2153, 12, 4, '2026-02-11', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2155, 9, 4, '2026-02-11', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2156, 18, 4, '2026-02-11', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2157, 21, 4, '2026-02-11', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2158, 22, 4, '2026-02-11', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2159, 17, 4, '2026-02-11', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2161, 23, 4, '2026-02-11', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2162, 1, 4, '2026-02-11', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2163, 2, 4, '2026-02-11', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2164, 3, 4, '2026-02-11', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2165, 4, 4, '2026-02-11', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2166, 5, 4, '2026-02-11', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2167, 6, 4, '2026-02-11', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2168, 7, 4, '2026-02-11', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2170, 15, 4, '2026-02-12', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2172, 16, 4, '2026-02-12', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2173, 14, 4, '2026-02-12', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2174, 13, 4, '2026-02-12', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2175, 19, 4, '2026-02-12', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2177, 11, 4, '2026-02-12', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2180, 8, 4, '2026-02-12', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2181, 10, 4, '2026-02-12', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2182, 20, 4, '2026-02-12', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2183, 12, 4, '2026-02-12', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2185, 9, 4, '2026-02-12', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2186, 18, 4, '2026-02-12', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2187, 21, 4, '2026-02-12', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2188, 22, 4, '2026-02-12', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2189, 17, 4, '2026-02-12', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2191, 23, 4, '2026-02-12', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2192, 1, 4, '2026-02-12', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2193, 2, 4, '2026-02-12', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2194, 3, 4, '2026-02-12', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2195, 4, 4, '2026-02-12', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2196, 5, 4, '2026-02-12', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2197, 6, 4, '2026-02-12', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2198, 7, 4, '2026-02-12', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2200, 15, 4, '2026-02-13', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2202, 16, 4, '2026-02-13', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2203, 14, 4, '2026-02-13', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2204, 13, 4, '2026-02-13', 0, 0.00, NULL, 16, '2026-02-02 08:38:52', '2026-02-02 08:38:52'),
	(2205, 19, 4, '2026-02-13', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2207, 11, 4, '2026-02-13', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2210, 8, 4, '2026-02-13', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2211, 10, 4, '2026-02-13', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2212, 20, 4, '2026-02-13', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2213, 12, 4, '2026-02-13', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2215, 9, 4, '2026-02-13', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2216, 18, 4, '2026-02-13', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2217, 21, 4, '2026-02-13', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2218, 22, 4, '2026-02-13', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2219, 17, 4, '2026-02-13', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2221, 23, 4, '2026-02-13', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2222, 1, 4, '2026-02-13', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2223, 2, 4, '2026-02-13', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2224, 3, 4, '2026-02-13', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2225, 4, 4, '2026-02-13', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2226, 5, 4, '2026-02-13', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2227, 6, 4, '2026-02-13', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2228, 7, 4, '2026-02-13', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2230, 15, 4, '2026-02-16', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2232, 16, 4, '2026-02-16', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2233, 14, 4, '2026-02-16', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2234, 13, 4, '2026-02-16', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2235, 19, 4, '2026-02-16', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2237, 11, 4, '2026-02-16', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2240, 8, 4, '2026-02-16', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2241, 10, 4, '2026-02-16', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2242, 20, 4, '2026-02-16', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2243, 12, 4, '2026-02-16', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2245, 9, 4, '2026-02-16', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2246, 18, 4, '2026-02-16', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2247, 21, 4, '2026-02-16', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2248, 22, 4, '2026-02-16', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2249, 17, 4, '2026-02-16', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2251, 23, 4, '2026-02-16', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2252, 1, 4, '2026-02-16', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2253, 2, 4, '2026-02-16', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2254, 3, 4, '2026-02-16', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2255, 4, 4, '2026-02-16', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2256, 5, 4, '2026-02-16', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2257, 6, 4, '2026-02-16', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2258, 7, 4, '2026-02-16', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2260, 15, 4, '2026-02-17', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2262, 16, 4, '2026-02-17', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2263, 14, 4, '2026-02-17', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2264, 13, 4, '2026-02-17', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2265, 19, 4, '2026-02-17', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2267, 11, 4, '2026-02-17', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2270, 8, 4, '2026-02-17', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2271, 10, 4, '2026-02-17', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2272, 20, 4, '2026-02-17', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2273, 12, 4, '2026-02-17', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2275, 9, 4, '2026-02-17', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2276, 18, 4, '2026-02-17', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2277, 21, 4, '2026-02-17', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2278, 22, 4, '2026-02-17', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2279, 17, 4, '2026-02-17', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2281, 23, 4, '2026-02-17', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2282, 1, 4, '2026-02-17', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2283, 2, 4, '2026-02-17', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2284, 3, 4, '2026-02-17', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2285, 4, 4, '2026-02-17', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2286, 5, 4, '2026-02-17', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2287, 6, 4, '2026-02-17', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2288, 7, 4, '2026-02-17', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2290, 15, 4, '2026-02-18', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2292, 16, 4, '2026-02-18', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2293, 14, 4, '2026-02-18', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2294, 13, 4, '2026-02-18', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2295, 19, 4, '2026-02-18', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2297, 11, 4, '2026-02-18', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2300, 8, 4, '2026-02-18', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2301, 10, 4, '2026-02-18', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2302, 20, 4, '2026-02-18', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2303, 12, 4, '2026-02-18', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2305, 9, 4, '2026-02-18', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2306, 18, 4, '2026-02-18', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2307, 21, 4, '2026-02-18', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2308, 22, 4, '2026-02-18', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2309, 17, 4, '2026-02-18', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2311, 23, 4, '2026-02-18', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2312, 1, 4, '2026-02-18', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2313, 2, 4, '2026-02-18', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2314, 3, 4, '2026-02-18', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2315, 4, 4, '2026-02-18', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2316, 5, 4, '2026-02-18', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2317, 6, 4, '2026-02-18', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2318, 7, 4, '2026-02-18', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2320, 15, 4, '2026-02-19', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2322, 16, 4, '2026-02-19', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2323, 14, 4, '2026-02-19', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2324, 13, 4, '2026-02-19', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2325, 19, 4, '2026-02-19', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2327, 11, 4, '2026-02-19', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2330, 8, 4, '2026-02-19', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2331, 10, 4, '2026-02-19', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2332, 20, 4, '2026-02-19', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2333, 12, 4, '2026-02-19', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2335, 9, 4, '2026-02-19', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2336, 18, 4, '2026-02-19', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2337, 21, 4, '2026-02-19', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2338, 22, 4, '2026-02-19', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2339, 17, 4, '2026-02-19', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2341, 23, 4, '2026-02-19', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2342, 1, 4, '2026-02-19', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2343, 2, 4, '2026-02-19', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2344, 3, 4, '2026-02-19', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2345, 4, 4, '2026-02-19', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2346, 5, 4, '2026-02-19', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2347, 6, 4, '2026-02-19', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2348, 7, 4, '2026-02-19', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2350, 15, 4, '2026-02-20', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2352, 16, 4, '2026-02-20', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2353, 14, 4, '2026-02-20', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2354, 13, 4, '2026-02-20', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2355, 19, 4, '2026-02-20', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2357, 11, 4, '2026-02-20', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2360, 8, 4, '2026-02-20', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2361, 10, 4, '2026-02-20', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2362, 20, 4, '2026-02-20', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2363, 12, 4, '2026-02-20', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2365, 9, 4, '2026-02-20', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2366, 18, 4, '2026-02-20', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2367, 21, 4, '2026-02-20', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2368, 22, 4, '2026-02-20', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2369, 17, 4, '2026-02-20', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2371, 23, 4, '2026-02-20', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2372, 1, 4, '2026-02-20', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2373, 2, 4, '2026-02-20', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2374, 3, 4, '2026-02-20', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2375, 4, 4, '2026-02-20', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2376, 5, 4, '2026-02-20', 0, 0.00, NULL, 16, '2026-02-02 08:38:53', '2026-02-02 08:38:53'),
	(2377, 6, 4, '2026-02-20', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2378, 7, 4, '2026-02-20', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2380, 15, 4, '2026-02-23', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2382, 16, 4, '2026-02-23', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2383, 14, 4, '2026-02-23', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2384, 13, 4, '2026-02-23', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2385, 19, 4, '2026-02-23', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2387, 11, 4, '2026-02-23', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2390, 8, 4, '2026-02-23', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2391, 10, 4, '2026-02-23', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2392, 20, 4, '2026-02-23', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2393, 12, 4, '2026-02-23', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2395, 9, 4, '2026-02-23', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2396, 18, 4, '2026-02-23', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2397, 21, 4, '2026-02-23', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2398, 22, 4, '2026-02-23', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2399, 17, 4, '2026-02-23', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2401, 23, 4, '2026-02-23', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2402, 1, 4, '2026-02-23', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2403, 2, 4, '2026-02-23', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2404, 3, 4, '2026-02-23', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2405, 4, 4, '2026-02-23', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2406, 5, 4, '2026-02-23', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2407, 6, 4, '2026-02-23', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2408, 7, 4, '2026-02-23', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2410, 15, 4, '2026-02-24', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2412, 16, 4, '2026-02-24', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2413, 14, 4, '2026-02-24', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2414, 13, 4, '2026-02-24', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2415, 19, 4, '2026-02-24', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2417, 11, 4, '2026-02-24', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2420, 8, 4, '2026-02-24', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2421, 10, 4, '2026-02-24', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2422, 20, 4, '2026-02-24', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2423, 12, 4, '2026-02-24', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2425, 9, 4, '2026-02-24', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2426, 18, 4, '2026-02-24', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2427, 21, 4, '2026-02-24', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2428, 22, 4, '2026-02-24', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2429, 17, 4, '2026-02-24', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2431, 23, 4, '2026-02-24', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2432, 1, 4, '2026-02-24', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2433, 2, 4, '2026-02-24', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2434, 3, 4, '2026-02-24', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2435, 4, 4, '2026-02-24', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2436, 5, 4, '2026-02-24', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2437, 6, 4, '2026-02-24', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2438, 7, 4, '2026-02-24', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2440, 15, 4, '2026-02-25', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2442, 16, 4, '2026-02-25', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2443, 14, 4, '2026-02-25', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2444, 13, 4, '2026-02-25', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2445, 19, 4, '2026-02-25', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2447, 11, 4, '2026-02-25', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2450, 8, 4, '2026-02-25', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2451, 10, 4, '2026-02-25', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2452, 20, 4, '2026-02-25', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2453, 12, 4, '2026-02-25', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2455, 9, 4, '2026-02-25', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2456, 18, 4, '2026-02-25', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2457, 21, 4, '2026-02-25', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2458, 22, 4, '2026-02-25', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2459, 17, 4, '2026-02-25', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2461, 23, 4, '2026-02-25', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2462, 1, 4, '2026-02-25', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2463, 2, 4, '2026-02-25', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2464, 3, 4, '2026-02-25', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2465, 4, 4, '2026-02-25', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2466, 5, 4, '2026-02-25', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2467, 6, 4, '2026-02-25', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2468, 7, 4, '2026-02-25', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2470, 15, 4, '2026-02-26', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2472, 16, 4, '2026-02-26', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2473, 14, 4, '2026-02-26', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2474, 13, 4, '2026-02-26', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2475, 19, 4, '2026-02-26', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2477, 11, 4, '2026-02-26', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2480, 8, 4, '2026-02-26', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2481, 10, 4, '2026-02-26', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2482, 20, 4, '2026-02-26', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2483, 12, 4, '2026-02-26', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2485, 9, 4, '2026-02-26', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2486, 18, 4, '2026-02-26', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2487, 21, 4, '2026-02-26', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2488, 22, 4, '2026-02-26', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2489, 17, 4, '2026-02-26', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2491, 23, 4, '2026-02-26', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2492, 1, 4, '2026-02-26', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2493, 2, 4, '2026-02-26', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2494, 3, 4, '2026-02-26', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2495, 4, 4, '2026-02-26', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2496, 5, 4, '2026-02-26', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2497, 6, 4, '2026-02-26', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2498, 7, 4, '2026-02-26', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2500, 15, 4, '2026-02-27', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2502, 16, 4, '2026-02-27', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2503, 14, 4, '2026-02-27', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2504, 13, 4, '2026-02-27', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2505, 19, 4, '2026-02-27', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2507, 11, 4, '2026-02-27', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2510, 8, 4, '2026-02-27', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2511, 10, 4, '2026-02-27', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2512, 20, 4, '2026-02-27', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2513, 12, 4, '2026-02-27', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2515, 9, 4, '2026-02-27', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2516, 18, 4, '2026-02-27', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2517, 21, 4, '2026-02-27', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2518, 22, 4, '2026-02-27', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2519, 17, 4, '2026-02-27', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2521, 23, 4, '2026-02-27', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2522, 1, 4, '2026-02-27', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2523, 2, 4, '2026-02-27', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2524, 3, 4, '2026-02-27', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2525, 4, 4, '2026-02-27', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2526, 5, 4, '2026-02-27', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2527, 6, 4, '2026-02-27', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54'),
	(2528, 7, 4, '2026-02-27', 0, 0.00, NULL, 16, '2026-02-02 08:38:54', '2026-02-02 08:38:54');

-- Dumping structure for table mobile_db.task_approvals
CREATE TABLE IF NOT EXISTS `task_approvals` (
  `id` int NOT NULL AUTO_INCREMENT,
  `task_id` int NOT NULL,
  `submitted_by` int NOT NULL COMMENT 'Employee submitting',
  `submitted_at` datetime NOT NULL,
  `reviewer_id` int DEFAULT NULL COMMENT 'Manager reviewing',
  `reviewed_at` datetime DEFAULT NULL,
  `action` enum('submitted','approved','rejected','revision_requested') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kpi_score_proposed` decimal(5,2) DEFAULT NULL COMMENT 'Self-assessment score',
  `kpi_score_approved` decimal(5,2) DEFAULT NULL COMMENT 'Manager final score',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Reviewer feedback',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `submitted_by` (`submitted_by`),
  KEY `reviewer_id` (`reviewer_id`),
  KEY `action` (`action`),
  CONSTRAINT `fk_approval_task` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Task approval workflow & KPI scoring';

-- Dumping data for table mobile_db.task_approvals: ~0 rows (approximately)
DELETE FROM `task_approvals`;

-- Dumping structure for table mobile_db.task_attachments
CREATE TABLE IF NOT EXISTS `task_attachments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `task_id` int NOT NULL,
  `user_id` int NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` int DEFAULT NULL COMMENT 'Size in bytes',
  `file_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'pdf, doc, jpg, etc',
  `mime_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `user_id` (`user_id`),
  KEY `uploaded_at` (`uploaded_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Task file attachments (separate from comments)';

-- Dumping data for table mobile_db.task_attachments: ~4 rows (approximately)
DELETE FROM `task_attachments`;
INSERT INTO `task_attachments` (`id`, `task_id`, `user_id`, `file_name`, `file_path`, `file_size`, `file_type`, `mime_type`, `description`, `uploaded_at`) VALUES
	(1, 7, 2, 'Cuplikan layar 2025-11-01 071102.png', '/uploads/tasks/7/1768366242_696720a2892e9.png', 24943, 'png', NULL, NULL, '2026-01-13 21:50:42'),
	(2, 7, 2, 'Cuplikan layar 2025-11-01 071102.png', '/uploads/tasks/7/1768366293_696720d5f3f35.png', 24943, 'png', NULL, NULL, '2026-01-13 21:51:34'),
	(3, 7, 2, 'Cuplikan layar 2025-11-01 071102.png', '/uploads/tasks/7/1768366333_696720fd14248.png', 24943, 'png', NULL, NULL, '2026-01-13 21:52:13'),
	(4, 6, 1, 'Cuplikan layar 2025-11-14 144521.png', '/uploads/tasks/task_6_1768367558_696725c622691.png', 373832, 'png', NULL, NULL, '2026-01-13 22:12:38');

-- Dumping structure for table mobile_db.task_categories
CREATE TABLE IF NOT EXISTS `task_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#3B82F6' COMMENT 'Hex color for UI',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'briefcase' COMMENT 'Icon identifier',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_code` (`category_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.task_categories: ~8 rows (approximately)
DELETE FROM `task_categories`;
INSERT INTO `task_categories` (`id`, `category_name`, `category_code`, `color`, `icon`, `description`, `is_active`, `created_at`) VALUES
	(1, 'Development', 'DEV', '#10B981', 'code', 'Software development tasks', 1, '2026-01-02 14:32:43'),
	(2, 'Design', 'DES', '#8B5CF6', 'palette', 'UI/UX design tasks', 1, '2026-01-02 14:32:43'),
	(3, 'Marketing', 'MKT', '#F59E0B', 'megaphone', 'Marketing and promotion', 1, '2026-01-02 14:32:43'),
	(4, 'Sales', 'SAL', '#EF4444', 'trending-up', 'Sales activities', 1, '2026-01-02 14:32:43'),
	(5, 'Support', 'SUP', '#3B82F6', 'headphones', 'Customer support', 1, '2026-01-02 14:32:43'),
	(6, 'Admin', 'ADM', '#6B7280', 'clipboard', 'Administrative tasks', 1, '2026-01-02 14:32:43'),
	(7, 'Meeting', 'MTG', '#EC4899', 'users', 'Meetings and discussions', 1, '2026-01-02 14:32:43'),
	(8, 'Research', 'RES', '#14B8A6', 'search', 'Research and analysis', 1, '2026-01-02 14:32:43');

-- Dumping structure for table mobile_db.task_checklist
CREATE TABLE IF NOT EXISTS `task_checklist` (
  `id` int NOT NULL AUTO_INCREMENT,
  `task_id` int NOT NULL,
  `item_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_completed` tinyint(1) DEFAULT '0',
  `completed_by` int DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `is_completed` (`is_completed`),
  KEY `sort_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Task checklist items (sub-tasks)';

-- Dumping data for table mobile_db.task_checklist: ~24 rows (approximately)
DELETE FROM `task_checklist`;
INSERT INTO `task_checklist` (`id`, `task_id`, `item_text`, `is_completed`, `completed_by`, `completed_at`, `sort_order`, `created_at`) VALUES
	(1, 1, 'Review requirements dan scope', 1, 1, '2026-01-11 15:09:58', 1, '2026-01-10 05:10:31'),
	(2, 1, 'Create database schema', 1, 1, '2026-01-11 15:08:44', 2, '2026-01-10 05:10:31'),
	(3, 1, 'Develop backend API endpoints', 1, 1, '2026-01-13 13:04:35', 3, '2026-01-10 05:10:31'),
	(4, 1, 'Frontend implementation', 0, NULL, NULL, 4, '2026-01-10 05:10:31'),
	(5, 1, 'Testing dan QA', 1, 3, '2026-01-11 15:45:18', 5, '2026-01-10 05:10:31'),
	(6, 1, 'Deploy to staging', 0, NULL, NULL, 6, '2026-01-10 05:10:31'),
	(7, 3, 'pilihan lokasi', 0, NULL, NULL, 1, '2026-01-13 15:12:53'),
	(8, 7, 'mencoba submit approval 1', 1, NULL, NULL, 1, '2026-01-13 21:31:11'),
	(9, 7, 'coba add list 1 lagi', 1, NULL, NULL, 0, '2026-01-13 21:37:55'),
	(10, 7, 'checklist masuk tapi notifnya bilang error!', 1, 2, NULL, 0, '2026-01-13 21:38:47'),
	(11, 7, 'coba item baru ke 3', 1, NULL, NULL, 0, '2026-01-13 21:50:00'),
	(12, 5, 'add checlist 1', 0, NULL, NULL, 0, '2026-01-14 15:05:08'),
	(13, 9, 'Pencatatan Meteran Listrik', 1, NULL, NULL, 0, '2026-01-27 22:52:56'),
	(14, 9, 'Pencatatan Meteran Air', 1, NULL, NULL, 0, '2026-01-27 22:52:56'),
	(15, 9, 'Input di SIXTY', 1, NULL, NULL, 0, '2026-01-27 22:52:56'),
	(16, 10, 'Buka lowongan kerja posisi Direktur PT. JV', 1, NULL, NULL, 0, '2026-02-09 12:22:49'),
	(17, 10, 'Koordinasi dengan BKPSDM untuk pengumpulan kandidat', 1, NULL, NULL, 0, '2026-02-09 12:22:49'),
	(18, 10, 'Bekerja sama dengan BKPSDM untuk Test Kompetensi', 1, NULL, NULL, 0, '2026-02-09 12:22:49'),
	(19, 11, 'Test Online ( sudahkah di server XT )', 0, NULL, NULL, 0, '2026-02-16 03:20:17'),
	(20, 11, 'Login Test', 0, NULL, NULL, 0, '2026-02-16 03:20:17'),
	(21, 11, 'Input Test', 0, NULL, NULL, 0, '2026-02-16 03:20:17'),
	(22, 11, 'Input Tagihan', 0, NULL, NULL, 0, '2026-02-16 03:21:22'),
	(23, 11, 'Print Invoice dan  Nota', 0, NULL, NULL, 0, '2026-02-16 03:21:35'),
	(24, 11, 'Laporan Jurnal Jurnal', 0, NULL, NULL, 0, '2026-02-16 03:22:06');

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

-- Dumping data for table mobile_db.task_comments: ~9 rows (approximately)
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

-- Dumping structure for table mobile_db.task_dependencies
CREATE TABLE IF NOT EXISTS `task_dependencies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `task_id` int NOT NULL COMMENT 'The dependent task',
  `depends_on_task_id` int NOT NULL COMMENT 'The task it depends on',
  `dependency_type` enum('blocks','relates_to','duplicates') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'blocks',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `depends_on_task_id` (`depends_on_task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Task dependencies and relationships';

-- Dumping data for table mobile_db.task_dependencies: ~0 rows (approximately)
DELETE FROM `task_dependencies`;

-- Dumping structure for table mobile_db.task_history
CREATE TABLE IF NOT EXISTS `task_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `task_id` int NOT NULL,
  `user_id` int NOT NULL,
  `action_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'created, updated, status_changed, assigned, commented, completed',
  `field_changed` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kolom yang berubah',
  `old_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `new_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `user_id` (`user_id`),
  KEY `action_type` (`action_type`),
  KEY `idx_history_task_action` (`task_id`,`action_type`),
  KEY `idx_history_user_date` (`user_id`,`created_at`),
  CONSTRAINT `task_history_task_fk` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `task_history_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.task_history: ~96 rows (approximately)
DELETE FROM `task_history`;
INSERT INTO `task_history` (`id`, `task_id`, `user_id`, `action_type`, `field_changed`, `old_value`, `new_value`, `description`, `created_at`) VALUES
	(1, 1, 1, 'created', NULL, NULL, NULL, 'Task created by Admin', '2026-01-10 05:00:38'),
	(2, 1, 1, 'status_changed', NULL, NULL, NULL, 'Status changed from pending to in_progress', '2026-01-10 05:00:38'),
	(3, 1, 1, 'created', NULL, NULL, NULL, 'Task dibuat oleh Admin', '2026-01-10 05:10:31'),
	(4, 1, 1, 'status_changed', NULL, NULL, NULL, 'Status berubah dari pending ke in_progress', '2026-01-10 05:10:31'),
	(6, 3, 1, 'status_changed', 'status', 'pending', 'in_progress', 'Status: pending Ã”Ã¥Ã† in_progress', '2026-01-11 04:28:41'),
	(7, 3, 1, 'status_changed', 'status', 'in_progress', 'completed', 'Status: in_progress Ã”Ã¥Ã† completed', '2026-01-11 04:32:21'),
	(8, 1, 1, 'status_changed', 'status', 'pending', 'in_progress', 'Status: pending Ã”Ã¥Ã† in_progress', '2026-01-11 16:13:24'),
	(9, 2, 1, 'status_changed', 'status', 'pending', 'in_progress', 'Status: pending Ã”Ã¥Ã† in_progress', '2026-01-11 16:13:26'),
	(10, 1, 1, 'status_changed', 'status', 'in_progress', 'pending', 'Status: in_progress Ã”Ã¥Ã† pending', '2026-01-11 16:13:54'),
	(11, 2, 1, 'status_changed', 'status', 'in_progress', 'pending', 'Status: in_progress Ã”Ã¥Ã† pending', '2026-01-11 16:34:39'),
	(12, 3, 1, 'status_changed', 'status', 'completed', 'review', 'Status: completed Ã”Ã¥Ã† review', '2026-01-11 16:34:42'),
	(13, 5, 1, 'created', NULL, NULL, NULL, 'Task "coba notif" dibuat', '2026-01-12 02:09:05'),
	(14, 6, 1, 'created', NULL, NULL, NULL, 'Task "coba notif" dibuat', '2026-01-12 02:14:37'),
	(15, 7, 1, 'created', NULL, NULL, NULL, 'Task "coba notif" dibuat', '2026-01-12 02:16:18'),
	(16, 5, 1, 'status_changed', 'status', 'pending', 'completed', 'Status: pending Ã”Ã¥Ã† completed', '2026-01-12 02:50:36'),
	(17, 6, 1, 'status_changed', 'status', 'pending', 'completed', 'Status: pending Ã”Ã¥Ã† completed', '2026-01-12 02:50:38'),
	(18, 1, 1, 'status_changed', 'status', 'pending', 'in_progress', 'Status: pending Ã”Ã¥Ã† in_progress', '2026-01-13 12:42:00'),
	(19, 5, 1, 'status_changed', 'status', 'completed', 'review', 'Status: completed Ã”Ã¥Ã† review', '2026-01-13 12:42:26'),
	(20, 1, 1, 'reassigned', 'assigned_to', '1', '5', 'Task reassigned: 1 Ã”Ã¥Ã† 5', '2026-01-13 13:06:15'),
	(21, 7, 1, 'status_changed', 'status', 'pending', 'review', 'Status: pending Ã”Ã¥Ã† review', '2026-01-13 15:07:06'),
	(22, 7, 1, 'status_changed', 'status', 'review', 'completed', 'Status: review Ã”Ã¥Ã† completed', '2026-01-13 15:07:20'),
	(23, 7, 1, 'status_changed', 'status', 'completed', 'review', 'Status: completed Ã”Ã¥Ã† review', '2026-01-13 15:11:10'),
	(24, 7, 2, 'time_logged', NULL, NULL, NULL, '2.00 jam dicatat pada 2026-01-15', '2026-01-14 11:00:08'),
	(25, 7, 2, 'commented', NULL, NULL, NULL, 'tambahin comment dlu aahh', '2026-01-14 11:00:35'),
	(26, 5, 2, 'time_logged', NULL, NULL, NULL, '3.00 jam dicatat pada 2026-01-15', '2026-01-14 14:42:07'),
	(27, 5, 2, 'commented', NULL, NULL, NULL, 'ini coba comment', '2026-01-14 14:43:12'),
	(28, 5, 2, 'updated', NULL, NULL, NULL, 'Logged 2 hours on 2026-01-15', '2026-01-14 15:04:36'),
	(29, 5, 2, 'commented', NULL, NULL, NULL, 'ini kok time logsnya numpuk! ra sip!', '2026-01-14 15:05:51'),
	(30, 5, 2, 'commented', NULL, NULL, NULL, 'Added a comment', '2026-01-14 15:05:51'),
	(31, 5, 2, 'time_logged', NULL, NULL, NULL, '1.00 jam dicatat pada 2026-01-15', '2026-01-14 15:11:12'),
	(32, 5, 2, 'updated', NULL, NULL, NULL, 'Logged 1 hours on 2026-01-15', '2026-01-14 15:11:12'),
	(33, 1, 1, 'time_logged', NULL, NULL, NULL, '1.00 jam dicatat pada 2026-01-15', '2026-01-14 23:46:19'),
	(34, 1, 1, 'updated', NULL, NULL, NULL, 'Logged 1 hours on 2026-01-15', '2026-01-14 23:46:19'),
	(35, 7, 1, 'status_changed', 'status', 'review', 'completed', 'Status: review Ã”Ã¥Ã† completed', '2026-01-26 19:01:56'),
	(36, 8, 5, 'created', NULL, NULL, NULL, 'Task "task 1" dibuat', '2026-01-26 21:30:14'),
	(37, 1, 1, 'status_changed', 'status', 'in_progress', 'review', 'Status: in_progress Ã”Ã¥Ã† review', '2026-01-27 05:31:43'),
	(38, 1, 1, 'status_changed', 'status', 'review', 'in_progress', 'Status: review Ã”Ã¥Ã† in_progress', '2026-01-27 05:31:45'),
	(39, 9, 5, 'created', NULL, NULL, NULL, 'Task "Pencatatan Biaya Service Tenant" dibuat', '2026-01-27 22:52:56'),
	(40, 9, 5, 'commented', NULL, NULL, NULL, '2 tenan tutup hari ini', '2026-01-27 23:25:45'),
	(41, 9, 5, 'time_logged', NULL, NULL, NULL, '1.00 jam dicatat pada 2026-01-28', '2026-01-27 23:32:49'),
	(42, 9, 5, 'status_changed', 'status', 'pending', 'in_progress', 'Status: pending Ã”Ã¥Ã† in_progress', '2026-01-27 23:33:00'),
	(43, 7, 1, 'reassigned', 'assigned_to', '2', '9', 'Task reassigned: 2 Ã”Ã¥Ã† 9', '2026-01-27 23:43:05'),
	(44, 9, 5, 'deadline_changed', 'due_date', '2026-01-05', '2026-01-10', 'Deadline: 2026-01-05 Ã”Ã¥Ã† 2026-01-10', '2026-01-30 19:47:02'),
	(45, 9, 5, 'reassigned', 'assigned_to', '7', '18', 'Task reassigned: 7 Ã”Ã¥Ã† 18', '2026-01-30 19:47:02'),
	(46, 5, 1, 'reassigned', 'assigned_to', '2', '10', 'Task reassigned: 2 Ã”Ã¥Ã† 10', '2026-01-30 19:53:50'),
	(47, 9, 5, 'status_changed', 'status', 'in_progress', 'review', 'Status: in_progress Ã”Ã¥Ã† review', '2026-01-31 21:26:57'),
	(48, 9, 5, 'status_changed', 'status', 'review', 'in_progress', 'Status: review Ã”Ã¥Ã† in_progress', '2026-01-31 21:26:58'),
	(49, 9, 5, 'status_changed', 'status', 'in_progress', 'review', 'Status: in_progress Ã”Ã¥Ã† review', '2026-01-31 21:26:59'),
	(50, 9, 5, 'status_changed', 'status', 'review', 'in_progress', 'Status: review Ã”Ã¥Ã† in_progress', '2026-01-31 21:27:00'),
	(51, 8, 18, 'commented', NULL, NULL, NULL, 'coba update comment', '2026-01-31 21:34:18'),
	(52, 8, 18, 'commented', NULL, NULL, NULL, 'Menambahkan komentar: coba update comment...', '2026-01-31 21:34:18'),
	(53, 8, 18, 'commented', NULL, NULL, NULL, 'dah rampung kan ini? cek cek cek', '2026-01-31 21:34:41'),
	(54, 8, 18, 'commented', NULL, NULL, NULL, 'Menambahkan komentar: dah rampung kan ini? cek cek c...', '2026-01-31 21:34:41'),
	(55, 8, 5, 'status_changed', 'status', 'pending', 'completed', 'Status: pending Ã”Ã¥Ã† completed', '2026-01-31 21:34:43'),
	(56, 9, 5, 'status_changed', 'status', 'in_progress', 'review', 'Status: in_progress Ã”Ã¥Ã† review', '2026-01-31 21:34:54'),
	(57, 9, 5, 'status_changed', 'status', 'review', 'completed', 'Status: review Ã”Ã¥Ã† completed', '2026-01-31 21:34:58'),
	(58, 9, 5, 'status_changed', 'status', 'completed', 'review', 'Status: completed Ã”Ã¥Ã† review', '2026-01-31 21:34:59'),
	(59, 9, 5, 'status_changed', 'status', 'review', 'completed', 'Status: review Ã”Ã¥Ã† completed', '2026-01-31 21:35:00'),
	(60, 9, 5, 'status_changed', 'status', 'completed', 'review', 'Status: completed Ã”Ã¥Ã† review', '2026-01-31 21:35:01'),
	(61, 9, 5, 'status_changed', 'status', 'review', 'completed', 'Status: review Ã”Ã¥Ã† completed', '2026-01-31 21:35:03'),
	(62, 9, 16, 'time_logged', NULL, NULL, NULL, '3.00 jam dicatat pada 2026-02-01', '2026-01-31 21:39:11'),
	(63, 9, 18, 'time_logged', NULL, NULL, NULL, '2.00 jam dicatat pada 2026-02-01', '2026-01-31 22:42:09'),
	(64, 9, 18, 'time_logged', NULL, NULL, NULL, '7.00 jam dicatat pada 2026-02-01', '2026-01-31 22:42:17'),
	(65, 9, 16, 'time_logged', NULL, NULL, NULL, '1.00 jam dicatat pada 2026-02-01', '2026-01-31 23:24:27'),
	(66, 9, 18, 'time_logged', NULL, NULL, NULL, '3.00 jam dicatat pada 2026-02-01', '2026-01-31 23:25:01'),
	(67, 9, 18, 'time_logged', NULL, NULL, NULL, '0.00 jam dicatat pada 2026-02-01', '2026-01-31 23:25:10'),
	(68, 9, 5, 'status_changed', 'status', 'completed', 'review', 'Status: completed Ã”Ã¥Ã† review', '2026-02-02 06:06:16'),
	(69, 9, 18, 'status_changed', NULL, NULL, NULL, 'Mengubah status dari completed ke review', '2026-02-02 06:06:16'),
	(70, 9, 5, 'status_changed', 'status', 'review', 'completed', 'Status: review Ã”Ã¥Ã† completed', '2026-02-02 11:57:41'),
	(71, 9, 18, 'status_changed', NULL, NULL, NULL, 'Mengubah status dari review ke completed', '2026-02-02 11:57:41'),
	(72, 6, 1, 'reassigned', 'assigned_to', '2', '8', 'Task reassigned: 2 Ã”Ã¥Ã† 8', '2026-02-06 08:28:14'),
	(73, 10, 1, 'created', NULL, NULL, NULL, 'Task "Persiapan Panitia Seleksi Direktur PT. Jogjatama Vishesha" dibuat', '2026-02-09 12:22:49'),
	(74, 3, 1, 'status_changed', 'status', 'review', 'completed', 'Status: review â†’ completed', '2026-02-09 14:33:50'),
	(75, 10, 1, 'status_changed', 'status', 'pending', 'in_progress', 'Status: pending â†’ in_progress', '2026-02-09 14:57:45'),
	(76, 10, 1, 'status_changed', 'status', 'in_progress', 'review', 'Status: in_progress â†’ review', '2026-02-09 15:09:47'),
	(77, 10, 16, 'commented', NULL, NULL, NULL, 'Sudah buka lowongan di KR', '2026-02-09 15:10:25'),
	(78, 10, 16, 'time_logged', NULL, NULL, NULL, '3.00 jam dicatat pada 2026-02-09', '2026-02-09 15:23:54'),
	(79, 10, 1, 'commented', NULL, NULL, NULL, 'jangan lupa media sosial atau online platform', '2026-02-09 15:31:57'),
	(80, 10, 1, 'status_changed', 'status', 'review', 'in_progress', 'Status: review â†’ in_progress', '2026-02-09 16:56:46'),
	(81, 10, 16, 'time_logged', NULL, NULL, NULL, '2.00 jam dicatat pada 2026-02-10', '2026-02-10 04:50:15'),
	(82, 3, 1, 'status_changed', NULL, NULL, NULL, 'Mengubah status dari completed ke completed', '2026-02-10 04:52:56'),
	(83, 2, 1, 'status_changed', 'status', 'pending', 'in_progress', 'Status: pending â†’ in_progress', '2026-02-10 04:53:17'),
	(84, 2, 1, 'status_changed', NULL, NULL, NULL, 'Mengubah status dari pending ke in_progress', '2026-02-10 04:53:17'),
	(85, 10, 1, 'status_changed', 'status', 'in_progress', 'completed', 'Status: in_progress â†’ completed', '2026-02-10 15:36:37'),
	(86, 10, 16, 'status_changed', NULL, NULL, NULL, 'Mengubah status dari in_progress ke completed', '2026-02-10 15:36:37'),
	(87, 10, 1, 'status_changed', 'status', 'completed', 'review', 'Status: completed â†’ review', '2026-02-10 15:37:12'),
	(88, 10, 16, 'status_changed', NULL, NULL, NULL, 'Mengubah status dari completed ke review', '2026-02-10 15:37:12'),
	(89, 10, 1, 'status_changed', 'status', 'review', 'completed', 'Status: review â†’ completed', '2026-02-10 15:37:18'),
	(90, 10, 16, 'status_changed', NULL, NULL, NULL, 'Mengubah status dari review ke completed', '2026-02-10 15:37:18'),
	(91, 10, 1, 'status_changed', 'status', 'completed', 'in_progress', 'Status: completed â†’ in_progress', '2026-02-10 16:02:46'),
	(92, 10, 1, 'status_changed', 'status', 'in_progress', 'submitted', 'Status: in_progress â†’ submitted', '2026-02-10 16:03:35'),
	(93, 10, 16, 'status_changed', NULL, NULL, NULL, 'Mengubah status menjadi Mengajukan Approval (Selesai)', '2026-02-10 16:03:35'),
	(94, 10, 1, 'status_changed', 'status', 'submitted', 'completed', 'Status: submitted â†’ completed', '2026-02-10 16:03:55'),
	(95, 2, 1, 'time_logged', NULL, NULL, NULL, '0.00 jam dicatat pada 2026-02-15', '2026-02-15 14:57:33'),
	(96, 2, 1, 'commented', NULL, NULL, NULL, 'diskusi progress disini via mobile', '2026-02-15 14:57:47'),
	(97, 11, 1, 'created', NULL, NULL, NULL, 'Task "Trial Test SIXTY " dibuat', '2026-02-16 03:20:17');

-- Dumping structure for table mobile_db.task_reminders
CREATE TABLE IF NOT EXISTS `task_reminders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `task_id` int NOT NULL,
  `user_id` int NOT NULL COMMENT 'Who will be reminded',
  `remind_at` datetime NOT NULL,
  `reminder_type` enum('deadline','followup','custom','before_deadline') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'deadline',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_sent` tinyint(1) DEFAULT '0',
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `user_id` (`user_id`),
  KEY `remind_at` (`remind_at`),
  KEY `is_sent` (`is_sent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Task reminders and notifications';

-- Dumping data for table mobile_db.task_reminders: ~0 rows (approximately)
DELETE FROM `task_reminders`;

-- Dumping structure for table mobile_db.task_tag_relations
CREATE TABLE IF NOT EXISTS `task_tag_relations` (
  `task_id` int NOT NULL,
  `tag_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`task_id`,`tag_id`),
  KEY `tag_id` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Many-to-many relationship between tasks and tags';

-- Dumping data for table mobile_db.task_tag_relations: ~0 rows (approximately)
DELETE FROM `task_tag_relations`;

-- Dumping structure for table mobile_db.task_tags
CREATE TABLE IF NOT EXISTS `task_tags` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag_slug` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag_color` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#64748B',
  `tag_icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'fa-tag',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag_slug` (`tag_slug`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tags for flexible task categorization';

-- Dumping data for table mobile_db.task_tags: ~10 rows (approximately)
DELETE FROM `task_tags`;
INSERT INTO `task_tags` (`id`, `tag_name`, `tag_slug`, `tag_color`, `tag_icon`, `description`, `is_active`, `created_at`) VALUES
	(1, 'Urgent', 'urgent', '#EF4444', 'fa-fire', 'Tugas yang sangat mendesak', 1, '2026-01-10 05:10:31'),
	(2, 'Bug Fix', 'bug-fix', '#F59E0B', 'fa-bug', 'Perbaikan bug atau error', 1, '2026-01-10 05:10:31'),
	(3, 'Feature', 'feature', '#10B981', 'fa-sparkles', 'Fitur baru', 1, '2026-01-10 05:10:31'),
	(4, 'Documentation', 'documentation', '#3B82F6', 'fa-book', 'Dokumentasi dan tutorial', 1, '2026-01-10 05:10:31'),
	(5, 'Testing', 'testing', '#8B5CF6', 'fa-vial', 'Testing dan QA', 1, '2026-01-10 05:10:31'),
	(6, 'Client Request', 'client-request', '#EC4899', 'fa-user-tie', 'Permintaan dari client', 1, '2026-01-10 05:10:31'),
	(7, 'Backend', 'backend', '#6366F1', 'fa-server', 'Backend development', 1, '2026-01-10 05:10:31'),
	(8, 'Frontend', 'frontend', '#14B8A6', 'fa-desktop', 'Frontend development', 1, '2026-01-10 05:10:31'),
	(9, 'Database', 'database', '#F97316', 'fa-database', 'Database work', 1, '2026-01-10 05:10:31'),
	(10, 'Optimization', 'optimization', '#84CC16', 'fa-tachometer-alt', 'Performance optimization', 1, '2026-01-10 05:10:31');

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

-- Dumping data for table mobile_db.task_time_logs: ~14 rows (approximately)
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

-- Dumping structure for table mobile_db.task_watchers
CREATE TABLE IF NOT EXISTS `task_watchers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `task_id` int NOT NULL,
  `user_id` int NOT NULL,
  `notify_comments` tinyint(1) DEFAULT '1',
  `notify_status_change` tinyint(1) DEFAULT '1',
  `notify_deadline` tinyint(1) DEFAULT '1',
  `added_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_task_watcher` (`task_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Users who watch/follow tasks for notifications';

-- Dumping data for table mobile_db.task_watchers: ~0 rows (approximately)
DELETE FROM `task_watchers`;

-- Dumping structure for table mobile_db.tasks
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `task_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Auto-generated: TSK-2026-001',
  `category_id` int DEFAULT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `assigned_by` int NOT NULL COMMENT 'Manager/Supervisor user_id',
  `assigned_to` int NOT NULL COMMENT 'Employee user_id',
  `department_id` int DEFAULT NULL,
  `priority` enum('low','medium','high','urgent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'medium',
  `status` enum('pending','in_progress','submitted','review','completed','overdue','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `start_date` date NOT NULL,
  `due_date` date NOT NULL,
  `completed_at` datetime DEFAULT NULL,
  `estimated_hours` decimal(5,2) DEFAULT '0.00',
  `actual_hours` decimal(5,2) DEFAULT '0.00',
  `completion_percentage` int DEFAULT '0' COMMENT '0-100',
  `kpi_weight` decimal(5,2) DEFAULT '0.00' COMMENT 'Weight for KPI calculation (0-100)',
  `quality_score` int DEFAULT NULL COMMENT 'Quality rating 0-100',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `attachment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `requires_approval` tinyint(1) DEFAULT '1' COMMENT 'Butuh approval manager',
  `approval_status` enum('pending','submitted','approved','rejected','revision') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `approved_by` int DEFAULT NULL COMMENT 'Manager user_id',
  `approved_at` datetime DEFAULT NULL,
  `approval_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Feedback dari manager',
  `submitted_for_approval_at` datetime DEFAULT NULL,
  `kpi_score_final` decimal(5,2) DEFAULT NULL COMMENT 'Final score setelah di-approve',
  `is_billable` tinyint(1) DEFAULT '0' COMMENT 'Task billable ke client',
  `tags` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Comma-separated tag IDs',
  `is_recurring` tinyint(1) DEFAULT '0' COMMENT '1=Ya, 0=Tidak',
  `recurrence_type` enum('none','daily','weekly','monthly','yearly') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'none',
  `recurrence_day` int DEFAULT NULL COMMENT 'Tanggal pengulangan (misal: 25)',
  `last_generated_at` date DEFAULT NULL COMMENT 'Kapan terakhir digenerate otomatis',
  PRIMARY KEY (`id`),
  UNIQUE KEY `task_code` (`task_code`),
  KEY `assigned_by` (`assigned_by`),
  KEY `assigned_to` (`assigned_to`),
  KEY `category_id` (`category_id`),
  KEY `department_id` (`department_id`),
  KEY `status` (`status`),
  KEY `due_date` (`due_date`),
  KEY `idx_employee_status` (`assigned_to`,`status`),
  KEY `idx_task_status_due` (`status`,`due_date`),
  KEY `idx_task_assigned_status` (`assigned_to`,`status`),
  KEY `idx_task_priority_status` (`priority`,`status`),
  KEY `idx_approval_status` (`approval_status`),
  KEY `idx_approved_by` (`approved_by`),
  KEY `idx_kpi_final` (`kpi_score_final`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.tasks: ~10 rows (approximately)
DELETE FROM `tasks`;
INSERT INTO `tasks` (`id`, `task_code`, `category_id`, `title`, `description`, `assigned_by`, `assigned_to`, `department_id`, `priority`, `status`, `start_date`, `due_date`, `completed_at`, `estimated_hours`, `actual_hours`, `completion_percentage`, `kpi_weight`, `quality_score`, `notes`, `attachment`, `created_at`, `updated_at`, `requires_approval`, `approval_status`, `approved_by`, `approved_at`, `approval_notes`, `submitted_for_approval_at`, `kpi_score_final`, `is_billable`, `tags`, `is_recurring`, `recurrence_type`, `recurrence_day`, `last_generated_at`) VALUES
	(1, 'TSK-2026-001', 4, 'Penagihan Tenan', 'Penagihan Invoice Listrik, Air dan Service', 1, 5, 1, 'high', 'in_progress', '2026-01-05', '2026-01-09', NULL, 0.00, 1.00, 0, 10.00, NULL, '', NULL, '2026-01-02 14:59:05', '2026-01-27 05:31:45', 1, 'submitted', NULL, NULL, NULL, '2026-01-14 11:27:49', NULL, 0, NULL, 0, 'none', NULL, NULL),
	(2, 'TSK-2026-002', 5, 'Laporan Progress Mingguan', 'buat Grand Report untuk meeting mingguan', 1, 1, 1, 'medium', 'in_progress', '2026-01-05', '2026-01-09', NULL, 16.00, 0.00, 0, 10.00, NULL, NULL, NULL, '2026-01-08 18:35:20', '2026-02-15 14:57:33', 1, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'none', NULL, NULL),
	(3, 'TSK-2026-003', 7, 'Internal Meeting', 'Membahas tentang piknik akhir bulan ini', 1, 1, 1, 'medium', 'completed', '2026-01-09', '2026-01-09', '2026-02-09 21:33:50', 0.00, 0.00, 100, 5.00, NULL, NULL, NULL, '2026-01-08 18:49:13', '2026-02-09 14:33:50', 1, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'none', NULL, NULL),
	(5, 'TSK-2026-004', 5, 'coba notif', 'coba notif aja', 1, 10, NULL, 'medium', 'review', '2026-01-19', '2026-01-22', '2026-01-12 16:50:36', 8.00, 7.00, 0, 10.00, NULL, NULL, NULL, '2026-01-12 02:09:05', '2026-01-30 19:53:50', 1, 'submitted', NULL, NULL, NULL, '2026-01-15 05:20:28', NULL, 0, NULL, 0, 'none', NULL, NULL),
	(6, 'TSK-2026-005', 5, 'coba notif', 'coba notif aja', 1, 8, NULL, 'medium', 'completed', '2026-01-19', '2026-01-22', '2026-01-14 02:43:28', 8.00, 0.00, 0, 10.00, NULL, NULL, NULL, '2026-01-12 02:14:37', '2026-02-06 08:28:14', 1, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'none', NULL, NULL),
	(7, 'TSK-2026-006', 5, 'coba notif', 'coba notif aja', 1, 9, NULL, 'medium', 'completed', '2026-01-19', '2026-01-22', '2026-01-27 02:01:56', 8.00, 2.00, 0, 10.00, NULL, NULL, NULL, '2026-01-12 02:16:18', '2026-01-27 23:43:05', 1, 'submitted', NULL, NULL, NULL, '2026-01-15 05:22:03', NULL, 0, NULL, 0, 'none', NULL, NULL),
	(8, 'TSK-2026-007', 8, 'task 1', 'coba input task pertama yang lengkap', 5, 18, NULL, 'medium', 'completed', '2026-01-27', '2026-01-27', NULL, 1.00, 0.00, 0, 1.00, NULL, NULL, NULL, '2026-01-26 14:30:14', '2026-01-31 21:34:43', 1, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'none', NULL, NULL),
	(9, 'TSK-2026-008', 6, 'Invoicing Service Tenant', 'Catat semua meter listrik dan air yang digunakan oleh tenant', 5, 18, NULL, 'medium', 'completed', '2026-01-01', '2026-01-10', NULL, 5.00, 17.00, 0, 10.00, NULL, NULL, NULL, '2026-01-27 22:52:56', '2026-02-02 11:57:41', 1, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, 'monthly', 10, NULL),
	(10, 'TSK-2026-009', 7, 'Persiapan Panitia Seleksi Direktur PT. Jogjatama Vishesha', 'Persiapan acara cari ', 1, 16, NULL, 'high', 'completed', '2026-02-01', '2026-02-13', '2026-02-10 16:03:55', 15.00, 5.00, 100, 10.00, NULL, NULL, NULL, '2026-02-09 12:22:49', '2026-02-15 13:29:09', 1, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'none', NULL, NULL),
	(11, 'TSK-2026-010', 1, 'Trial Test SIXTY ', 'Trial dan Test Sistem SIXTY hingga tuntas. \r\nCatat jika ada error dan ketidaksesuaian dengan yang dibutuhkan !', 1, 16, NULL, 'high', 'pending', '2026-02-18', '2026-02-20', NULL, 190.00, 0.00, 0, 10.00, NULL, NULL, NULL, '2026-02-16 03:20:17', '2026-02-16 03:20:17', 1, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'none', NULL, NULL);

-- Dumping structure for table mobile_db.tax_brackets
CREATE TABLE IF NOT EXISTS `tax_brackets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bracket_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_income` decimal(15,2) NOT NULL COMMENT 'Minimum annual taxable income',
  `max_income` decimal(15,2) DEFAULT NULL COMMENT 'Maximum annual taxable income, NULL = no limit',
  `tax_rate` decimal(5,2) NOT NULL COMMENT 'Tax rate percentage',
  `effective_year` int NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `effective_year` (`effective_year`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.tax_brackets: ~5 rows (approximately)
DELETE FROM `tax_brackets`;
INSERT INTO `tax_brackets` (`id`, `bracket_name`, `min_income`, `max_income`, `tax_rate`, `effective_year`, `is_active`, `created_at`) VALUES
	(1, 'Layer 1', 0.00, 60000000.00, 5.00, 2024, 1, '2026-01-01 05:30:57'),
	(2, 'Layer 2', 60000001.00, 250000000.00, 15.00, 2024, 1, '2026-01-01 05:30:57'),
	(3, 'Layer 3', 250000001.00, 500000000.00, 25.00, 2024, 1, '2026-01-01 05:30:57'),
	(4, 'Layer 4', 500000001.00, 5000000000.00, 30.00, 2024, 1, '2026-01-01 05:30:57'),
	(5, 'Layer 5', 5000000001.00, NULL, 35.00, 2024, 1, '2026-01-01 05:30:57');

-- Dumping structure for table mobile_db.user_specific_permissions
CREATE TABLE IF NOT EXISTS `user_specific_permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `permission_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `permission_id` (`permission_id`),
  CONSTRAINT `fk_usp_perm` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_usp_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.user_specific_permissions: ~13 rows (approximately)
DELETE FROM `user_specific_permissions`;
INSERT INTO `user_specific_permissions` (`id`, `user_id`, `permission_id`) VALUES
	(1, 7, 16),
	(2, 7, 17),
	(3, 7, 19),
	(4, 7, 83),
	(5, 7, 84),
	(6, 7, 90),
	(13, 18, 15),
	(14, 18, 16),
	(15, 18, 17),
	(16, 18, 18),
	(17, 18, 19),
	(18, 18, 20),
	(19, 18, 100);

-- Dumping structure for table mobile_db.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'staff',
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin COMMENT 'Granular permissions per module',
  `is_active` tinyint(1) DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `can_view_department_only` tinyint(1) DEFAULT '0' COMMENT 'Manager/Supervisor hanya bisa lihat department sendiri',
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_id` (`employee_id`),
  UNIQUE KEY `username` (`username`),
  KEY `idx_role` (`role`),
  KEY `idx_active` (`is_active`),
  CONSTRAINT `users_chk_1` CHECK (json_valid(`permissions`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.users: ~24 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `employee_id`, `username`, `password`, `email`, `role`, `permissions`, `is_active`, `last_login`, `created_at`, `updated_at`, `can_view_department_only`) VALUES
	(1, 1, 'A-10.001', '$2y$10$DacSz/888it4D.VVJ.DJAu.DBvoyUdROfoe6lIHzDrpW7P61z8X3a', NULL, 'super_admin', NULL, 1, '2026-01-24 09:23:34', '2026-01-23 19:19:56', '2026-02-10 01:29:22', 0),
	(5, 2, 'T-20.001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'admin', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(6, 3, 'T-21.003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-27 05:01:11', 0),
	(7, 4, 'T-21.004', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'admin', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(8, 5, 'T-22.005', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(9, 6, 'T-22.006', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(10, 7, 'T-23.010', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(11, 8, 'T-23.011', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(12, 9, 'T-25.012', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(13, 10, 'T-25.013', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(14, 11, 'K-25.060', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(15, 12, 'T-25.015', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(16, 13, 'T-20.002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'admin', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(17, 14, 'T-22.007', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(18, 15, 'T-22.008', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(19, 16, 'T-22.009', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'admin', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(20, 17, 'T-25.014', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(21, 18, 'K-19.058', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(22, 19, 'K-25.059', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(23, 20, 'H-22.008', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(24, 21, 'H-22.009', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(25, 22, 'H-22.010', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(26, 23, 'H-22.011', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(27, 25, 'D-26.004', '$2y$10$MaGEe7R4i6PFImo3D24JL.QlKwYSczQ5VGQt20uc/0kBqAaCPCUky', NULL, 'super_admin', NULL, 1, NULL, '2026-04-02 10:05:47', '2026-04-02 10:05:47', 0);

-- Dumping structure for view mobile_db.v_employee_leave_summary
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_employee_leave_summary` (
	`employee_id` INT NOT NULL,
	`employee_number` INT NOT NULL,
	`first_name` INT NOT NULL,
	`last_name` INT NOT NULL,
	`full_name` INT NOT NULL,
	`department_name` INT NOT NULL,
	`leave_type_name` INT NOT NULL,
	`leave_code` INT NOT NULL,
	`entitled` INT NOT NULL,
	`used` INT NOT NULL,
	`remaining` INT NOT NULL,
	`year` INT NOT NULL
);

-- Dumping structure for view mobile_db.v_employee_salary_summary
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_employee_salary_summary` (
	`employee_id` INT NOT NULL,
	`employee_number` INT NOT NULL,
	`first_name` INT NOT NULL,
	`last_name` INT NOT NULL,
	`full_name` INT NOT NULL,
	`gender` INT NOT NULL,
	`marital_status` INT NOT NULL,
	`number_of_dependents` INT NOT NULL,
	`npwp` INT NOT NULL,
	`bank_name` INT NOT NULL,
	`bank_account_number` INT NOT NULL,
	`bank_account_name` INT NOT NULL,
	`department_name` INT NOT NULL,
	`basic_salary` INT NOT NULL,
	`total_fixed_allowances` INT NOT NULL,
	`estimated_gross` INT NOT NULL,
	`bpjs_base` INT NOT NULL,
	`ptkp_status` INT NOT NULL
);

-- Dumping structure for view mobile_db.v_monthly_attendance_report
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_monthly_attendance_report` (
	`employee_id` INT NOT NULL,
	`employee_number` INT NOT NULL,
	`first_name` INT NOT NULL,
	`last_name` INT NOT NULL,
	`full_name` INT NOT NULL,
	`department_name` INT NOT NULL,
	`year` INT NOT NULL,
	`month` INT NOT NULL,
	`total_days` INT NOT NULL,
	`present_days` INT NOT NULL,
	`late_days` INT NOT NULL,
	`early_out_days` INT NOT NULL,
	`total_work_minutes` INT NOT NULL,
	`total_overtime_minutes` INT NOT NULL,
	`total_late_minutes` INT NOT NULL,
	`attendance_rate` INT NOT NULL
);

-- Dumping structure for view mobile_db.v_pending_attendance_approvals
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_pending_attendance_approvals` (
	`id` INT NOT NULL,
	`attendance_date` INT NOT NULL,
	`employee_id` INT NOT NULL,
	`employee_number` INT NOT NULL,
	`first_name` INT NOT NULL,
	`last_name` INT NOT NULL,
	`full_name` INT NOT NULL,
	`department_name` INT NOT NULL,
	`manager_id` INT NOT NULL,
	`check_in_time` INT NOT NULL,
	`check_out_time` INT NOT NULL,
	`is_late` INT NOT NULL,
	`late_duration_minutes` INT NOT NULL,
	`is_early_out` INT NOT NULL,
	`early_out_minutes` INT NOT NULL,
	`status` INT NOT NULL,
	`created_at` INT NOT NULL
);

-- Dumping structure for view mobile_db.v_pending_leave_requests
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_pending_leave_requests` (
	`id` INT NOT NULL,
	`request_date` INT NOT NULL,
	`start_date` INT NOT NULL,
	`end_date` INT NOT NULL,
	`total_days` INT NOT NULL,
	`reason` INT NOT NULL,
	`status` INT NOT NULL,
	`employee_id` INT NOT NULL,
	`employee_number` INT NOT NULL,
	`first_name` INT NOT NULL,
	`last_name` INT NOT NULL,
	`full_name` INT NOT NULL,
	`department_name` INT NOT NULL,
	`manager_id` INT NOT NULL,
	`leave_type_name` INT NOT NULL,
	`requires_document` INT NOT NULL,
	`supporting_document` INT NOT NULL,
	`created_at` INT NOT NULL
);

-- Dumping structure for view mobile_db.v_pending_overtime_requests
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_pending_overtime_requests` (
	`id` INT NOT NULL,
	`overtime_date` INT NOT NULL,
	`start_time` INT NOT NULL,
	`end_time` INT NOT NULL,
	`total_hours` INT NOT NULL,
	`reason` INT NOT NULL,
	`overtime_rate` INT NOT NULL,
	`is_weekend` INT NOT NULL,
	`is_holiday` INT NOT NULL,
	`status` INT NOT NULL,
	`employee_id` INT NOT NULL,
	`employee_number` INT NOT NULL,
	`first_name` INT NOT NULL,
	`last_name` INT NOT NULL,
	`full_name` INT NOT NULL,
	`department_name` INT NOT NULL,
	`manager_id` INT NOT NULL,
	`created_at` INT NOT NULL
);

-- Dumping structure for view mobile_db.v_pending_trip_requests
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_pending_trip_requests` (
	`id` INT NOT NULL,
	`trip_purpose` INT NOT NULL,
	`destination` INT NOT NULL,
	`start_date` INT NOT NULL,
	`end_date` INT NOT NULL,
	`total_days` INT NOT NULL,
	`transportation` INT NOT NULL,
	`estimated_budget` INT NOT NULL,
	`status` INT NOT NULL,
	`employee_id` INT NOT NULL,
	`employee_number` INT NOT NULL,
	`first_name` INT NOT NULL,
	`last_name` INT NOT NULL,
	`full_name` INT NOT NULL,
	`department_name` INT NOT NULL,
	`manager_id` INT NOT NULL,
	`created_at` INT NOT NULL
);

-- Dumping structure for view mobile_db.v_task_stats_by_category
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_task_stats_by_category` (
	`category_id` INT NOT NULL,
	`category_name` INT NOT NULL,
	`color` INT NOT NULL,
	`total_tasks` INT NOT NULL,
	`completed_tasks` INT NOT NULL,
	`in_progress_tasks` INT NOT NULL,
	`pending_tasks` INT NOT NULL,
	`overdue_tasks` INT NOT NULL,
	`avg_completion` INT NOT NULL,
	`total_estimated_hours` INT NOT NULL,
	`total_actual_hours` INT NOT NULL
);

-- Dumping structure for view mobile_db.v_tasks_full_overview
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_tasks_full_overview` (
	`id` INT NOT NULL,
	`task_code` INT NOT NULL,
	`title` INT NOT NULL,
	`description` INT NOT NULL,
	`priority` INT NOT NULL,
	`status` INT NOT NULL,
	`start_date` INT NOT NULL,
	`due_date` INT NOT NULL,
	`completion_percentage` INT NOT NULL,
	`estimated_hours` INT NOT NULL,
	`actual_hours` INT NOT NULL,
	`kpi_weight` INT NOT NULL,
	`category_name` INT NOT NULL,
	`category_color` INT NOT NULL,
	`category_icon` INT NOT NULL,
	`employee_number` INT NOT NULL,
	`department_name` INT NOT NULL,
	`days_remaining` INT NOT NULL,
	`urgency_status` INT NOT NULL,
	`comment_count` INT NOT NULL,
	`attachment_count` INT NOT NULL,
	`total_checklist` INT NOT NULL,
	`completed_checklist` INT NOT NULL,
	`tags` INT NOT NULL,
	`created_at` INT NOT NULL,
	`updated_at` INT NOT NULL
);

-- Dumping structure for view mobile_db.v_tasks_overdue_alert
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_tasks_overdue_alert` (
	`id` INT NOT NULL,
	`task_code` INT NOT NULL,
	`title` INT NOT NULL,
	`priority` INT NOT NULL,
	`status` INT NOT NULL,
	`due_date` INT NOT NULL,
	`days_overdue` INT NOT NULL,
	`employee_number` INT NOT NULL,
	`category_name` INT NOT NULL,
	`alert_level` INT NOT NULL
);

-- Dumping structure for view mobile_db.v_today_attendance
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_today_attendance` (
	`id` INT NOT NULL,
	`attendance_date` INT NOT NULL,
	`employee_id` INT NOT NULL,
	`employee_number` INT NOT NULL,
	`first_name` INT NOT NULL,
	`last_name` INT NOT NULL,
	`full_name` INT NOT NULL,
	`department_name` INT NOT NULL,
	`shift_name` INT NOT NULL,
	`shift_start` INT NOT NULL,
	`shift_end` INT NOT NULL,
	`check_in_time` INT NOT NULL,
	`check_out_time` INT NOT NULL,
	`is_late` INT NOT NULL,
	`late_duration_minutes` INT NOT NULL,
	`is_early_out` INT NOT NULL,
	`early_out_minutes` INT NOT NULL,
	`work_duration_minutes` INT NOT NULL,
	`overtime_minutes` INT NOT NULL,
	`status` INT NOT NULL,
	`approval_status` INT NOT NULL,
	`check_in_location` INT NOT NULL
);

-- Dumping structure for view mobile_db.v_user_permissions
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_user_permissions` (
	`user_id` INT NOT NULL,
	`username` INT NOT NULL,
	`role` INT NOT NULL,
	`role_name` INT NOT NULL,
	`module_name` INT NOT NULL,
	`permission_slug` INT NOT NULL,
	`permission_name` INT NOT NULL
);

-- Dumping structure for table mobile_db.work_shifts
CREATE TABLE IF NOT EXISTS `work_shifts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_id` int NOT NULL DEFAULT '1',
  `shift_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shift_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `late_tolerance_minutes` int DEFAULT '10',
  `total_hours` decimal(4,2) GENERATED ALWAYS AS ((timestampdiff(MINUTE,`start_time`,`end_time`) / 60)) STORED,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_shift_code` (`company_id`,`shift_code`),
  KEY `company_id` (`company_id`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.work_shifts: ~5 rows (approximately)
DELETE FROM `work_shifts`;
INSERT INTO `work_shifts` (`id`, `company_id`, `shift_name`, `shift_code`, `start_time`, `end_time`, `late_tolerance_minutes`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Shift Pagi', 'PAGI', '06:00:00', '14:00:00', 10, 1, '2025-12-31 13:27:26', '2025-12-31 14:02:35'),
	(2, 1, 'Shift Siang', 'SIANG', '12:00:00', '20:00:00', 10, 1, '2025-12-31 13:27:26', '2025-12-31 13:27:26'),
	(3, 1, 'Shift Malam', 'MALAM', '20:00:00', '04:00:00', 10, 1, '2025-12-31 13:27:26', '2025-12-31 13:27:26'),
	(4, 1, 'Shift Full Day', 'FULL', '08:00:00', '17:00:00', 15, 1, '2025-12-31 13:27:26', '2026-02-13 11:05:03'),
	(5, 1, 'COBA', 'TRY', '10:00:00', '19:00:00', 15, 1, '2026-03-25 05:19:20', '2026-03-25 05:19:20');

-- Dumping structure for trigger mobile_db.calculate_attendance_stats
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `calculate_attendance_stats` BEFORE UPDATE ON `attendance_records` FOR EACH ROW BEGIN

    DECLARE shift_start TIME;

    DECLARE shift_end TIME;

    DECLARE shift_tolerance INT;



    

    IF NEW.check_out_time IS NOT NULL AND OLD.check_out_time IS NULL THEN



        

        SELECT start_time, end_time, late_tolerance_minutes 

        INTO shift_start, shift_end, shift_tolerance

        FROM work_shifts 

        WHERE id = NEW.shift_id;



        IF shift_start IS NOT NULL THEN

            

            IF TIME(NEW.check_in_time) > ADDTIME(shift_start, SEC_TO_TIME(shift_tolerance * 60)) THEN

                SET NEW.is_late = 1;

                SET NEW.late_duration_minutes = TIMESTAMPDIFF(MINUTE, 

                    CONCAT(NEW.attendance_date, ' ', shift_start), 

                    NEW.check_in_time

                );

            END IF;



            

            IF TIME(NEW.check_out_time) < shift_end THEN

                SET NEW.is_early_out = 1;

                SET NEW.early_out_minutes = TIMESTAMPDIFF(MINUTE, 

                    NEW.check_out_time,

                    CONCAT(NEW.attendance_date, ' ', shift_end)

                );

            END IF;



            

            SET NEW.work_duration_minutes = TIMESTAMPDIFF(MINUTE, NEW.check_in_time, NEW.check_out_time);



            

            IF TIME(NEW.check_out_time) > shift_end THEN

                SET NEW.overtime_minutes = TIMESTAMPDIFF(MINUTE, 

                    CONCAT(NEW.attendance_date, ' ', shift_end),

                    NEW.check_out_time

                );

            END IF;



            

            IF NEW.is_late = 1 OR NEW.is_early_out = 1 THEN

                SET NEW.requires_approval = 1;

                SET NEW.approval_status = 'pending';

            END IF;

        END IF;

    END IF;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger mobile_db.generate_task_code
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `generate_task_code` BEFORE INSERT ON `tasks` FOR EACH ROW BEGIN

  DECLARE next_num INT;

  DECLARE new_code VARCHAR(20);

  

  

  SELECT COALESCE(MAX(CAST(SUBSTRING(task_code, 10) AS UNSIGNED)), 0) + 1 

  INTO next_num

  FROM tasks

  WHERE YEAR(created_at) = YEAR(NOW());

  

  

  SET new_code = CONCAT('TSK-', YEAR(NOW()), '-', LPAD(next_num, 3, '0'));

  SET NEW.task_code = new_code;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger mobile_db.notify_new_task_assignment
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `notify_new_task_assignment` AFTER INSERT ON `tasks` FOR EACH ROW BEGIN

    

    INSERT INTO notifications (

        employee_id, 

        type, 

        title, 

        message, 

        reference_id, 

        reference_table, 

        icon, 

        priority

    )

    VALUES (

        NEW.assigned_to,

        'task',

        'Tugas Baru ?',

        CONCAT('Anda mendapat tugas baru: ', NEW.title, ' (Deadline: ', DATE_FORMAT(NEW.due_date, '%d %b %Y'), ')'),

        NEW.id,

        'tasks',

        'clipboard-check',

        CASE 

            WHEN NEW.priority = 'urgent' THEN 'high'

            WHEN NEW.priority = 'high' THEN 'high'

            ELSE 'normal'

        END

    );

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger mobile_db.notify_task_update
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `notify_task_update` AFTER UPDATE ON `tasks` FOR EACH ROW BEGIN

    

    IF OLD.due_date != NEW.due_date THEN

        INSERT INTO notifications (

            employee_id, 

            type, 

            title, 

            message, 

            reference_id, 

            reference_table, 

            icon, 

            priority

        )

        VALUES (

            NEW.assigned_to,

            'task',

            'Deadline Berubah â°',

            CONCAT('Deadline tugas "', NEW.title, '" diubah menjadi ', DATE_FORMAT(NEW.due_date, '%d %b %Y')),

            NEW.id,

            'tasks',

            'calendar-alt',

            'high'

        );

    END IF;

    

    

    IF OLD.priority != NEW.priority THEN

        INSERT INTO notifications (

            employee_id, 

            type, 

            title, 

            message, 

            reference_id, 

            reference_table, 

            icon, 

            priority

        )

        VALUES (

            NEW.assigned_to,

            'task',

            'Prioritas Berubah ?',

            CONCAT('Prioritas tugas "', NEW.title, '" diubah menjadi ', UPPER(NEW.priority)),

            NEW.id,

            'tasks',

            'exclamation-triangle',

            'high'

        );

    END IF;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger mobile_db.set_overtime_rate
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `set_overtime_rate` BEFORE INSERT ON `overtime_requests` FOR EACH ROW BEGIN

    

    IF EXISTS (SELECT 1 FROM holidays WHERE holiday_date = NEW.overtime_date) THEN

        SET NEW.is_holiday = 1;

        SET NEW.overtime_rate = 3.0; 

    

    ELSEIF DAYOFWEEK(NEW.overtime_date) IN (1, 7) THEN

        SET NEW.is_weekend = 1;

        SET NEW.overtime_rate = 2.0; 

    ELSE

        SET NEW.overtime_rate = 1.5; 

    END IF;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger mobile_db.trg_task_comment_history
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `trg_task_comment_history` AFTER INSERT ON `task_comments` FOR EACH ROW BEGIN

    INSERT INTO task_history (task_id, user_id, action_type, description)

    VALUES (

        NEW.task_id, 

        NEW.user_id, 

        'commented', 

        SUBSTRING(NEW.comment, 1, 100)

    );

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger mobile_db.trg_task_created_history
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `trg_task_created_history` AFTER INSERT ON `tasks` FOR EACH ROW BEGIN

    INSERT INTO task_history (task_id, user_id, action_type, description)

    VALUES (

        NEW.id, 

        NEW.assigned_by, 

        'created', 

        CONCAT('Task "', NEW.title, '" dibuat')

    );

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger mobile_db.trg_task_status_changed_history
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `trg_task_status_changed_history` AFTER UPDATE ON `tasks` FOR EACH ROW BEGIN

    

    IF OLD.status != NEW.status THEN

        INSERT INTO task_history (

            task_id, 

            user_id, 

            action_type, 

            field_changed, 

            old_value, 

            new_value, 

            description

        ) VALUES (

            NEW.id,

            NEW.assigned_by,

            'status_changed',

            'status',

            OLD.status,

            NEW.status,

            CONCAT('Status: ', OLD.status, ' â†’ ', NEW.status)

        );

    END IF;

    

    

    IF OLD.priority != NEW.priority THEN

        INSERT INTO task_history (

            task_id, 

            user_id, 

            action_type, 

            field_changed, 

            old_value, 

            new_value, 

            description

        ) VALUES (

            NEW.id,

            NEW.assigned_by,

            'priority_changed',

            'priority',

            OLD.priority,

            NEW.priority,

            CONCAT('Priority: ', OLD.priority, ' â†’ ', NEW.priority)

        );

    END IF;

    

    

    IF OLD.due_date != NEW.due_date THEN

        INSERT INTO task_history (

            task_id, 

            user_id, 

            action_type, 

            field_changed, 

            old_value, 

            new_value, 

            description

        ) VALUES (

            NEW.id,

            NEW.assigned_by,

            'deadline_changed',

            'due_date',

            OLD.due_date,

            NEW.due_date,

            CONCAT('Deadline: ', OLD.due_date, ' â†’ ', NEW.due_date)

        );

    END IF;

    

    

    IF OLD.assigned_to != NEW.assigned_to THEN

        INSERT INTO task_history (

            task_id, 

            user_id, 

            action_type, 

            field_changed, 

            old_value, 

            new_value, 

            description

        ) VALUES (

            NEW.id,

            NEW.assigned_by,

            'reassigned',

            'assigned_to',

            OLD.assigned_to,

            NEW.assigned_to,

            CONCAT('Task reassigned: ', OLD.assigned_to, ' â†’ ', NEW.assigned_to)

        );

    END IF;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger mobile_db.trg_task_timelog_history
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `trg_task_timelog_history` AFTER INSERT ON `task_time_logs` FOR EACH ROW BEGIN

    INSERT INTO task_history (task_id, user_id, action_type, description)

    VALUES (

        NEW.task_id, 

        NEW.user_id, 

        'time_logged', 

        CONCAT(NEW.hours_spent, ' jam dicatat pada ', NEW.log_date)

    );

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger mobile_db.trg_update_task_hours_on_delete
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `trg_update_task_hours_on_delete` AFTER DELETE ON `task_time_logs` FOR EACH ROW BEGIN

    UPDATE tasks 

    SET actual_hours = (

        SELECT COALESCE(SUM(hours_spent), 0) 

        FROM task_time_logs 

        WHERE task_id = OLD.task_id

    )

    WHERE id = OLD.task_id;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger mobile_db.trg_update_task_hours_on_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `trg_update_task_hours_on_insert` AFTER INSERT ON `task_time_logs` FOR EACH ROW BEGIN

    UPDATE tasks 

    SET actual_hours = (

        SELECT COALESCE(SUM(hours_spent), 0) 

        FROM task_time_logs 

        WHERE task_id = NEW.task_id

    )

    WHERE id = NEW.task_id;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger mobile_db.trg_update_task_hours_on_update
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `trg_update_task_hours_on_update` AFTER UPDATE ON `task_time_logs` FOR EACH ROW BEGIN

    UPDATE tasks 

    SET actual_hours = (

        SELECT COALESCE(SUM(hours_spent), 0) 

        FROM task_time_logs 

        WHERE task_id = NEW.task_id

    )

    WHERE id = NEW.task_id;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger mobile_db.update_leave_balance_on_approval
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `update_leave_balance_on_approval` AFTER UPDATE ON `leave_requests` FOR EACH ROW BEGIN

    

    IF OLD.status = 'pending' AND NEW.status = 'approved' THEN

        

        INSERT INTO employee_leave_balance 

        (employee_id, leave_type_id, year, total_used, total_remaining)

        VALUES 

        (NEW.employee_id, NEW.leave_type_id, YEAR(NEW.start_date), NEW.total_days, 0)

        ON DUPLICATE KEY UPDATE

            total_used = total_used + NEW.total_days,

            total_remaining = total_entitled - (total_used + NEW.total_days);

    END IF;



    

    IF OLD.status = 'approved' AND (NEW.status = 'cancelled' OR NEW.status = 'rejected') THEN

        UPDATE employee_leave_balance

        SET total_used = total_used - NEW.total_days,

            total_remaining = total_remaining + NEW.total_days

        WHERE employee_id = NEW.employee_id 

          AND leave_type_id = NEW.leave_type_id 

          AND year = YEAR(NEW.start_date);

    END IF;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger mobile_db.update_task_hours
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `update_task_hours` AFTER INSERT ON `task_time_logs` FOR EACH ROW BEGIN

  UPDATE tasks 

  SET actual_hours = (

    SELECT SUM(hours_spent) 

    FROM task_time_logs 

    WHERE task_id = NEW.task_id

  )

  WHERE id = NEW.task_id;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_employee_leave_summary`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_employee_leave_summary` AS select 1 AS `employee_id`,1 AS `employee_number`,1 AS `first_name`,1 AS `last_name`,1 AS `full_name`,1 AS `department_name`,1 AS `leave_type_name`,1 AS `leave_code`,1 AS `entitled`,1 AS `used`,1 AS `remaining`,1 AS `year`
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_employee_salary_summary`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_employee_salary_summary` AS select 1 AS `employee_id`,1 AS `employee_number`,1 AS `first_name`,1 AS `last_name`,1 AS `full_name`,1 AS `gender`,1 AS `marital_status`,1 AS `number_of_dependents`,1 AS `npwp`,1 AS `bank_name`,1 AS `bank_account_number`,1 AS `bank_account_name`,1 AS `department_name`,1 AS `basic_salary`,1 AS `total_fixed_allowances`,1 AS `estimated_gross`,1 AS `bpjs_base`,1 AS `ptkp_status`
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_monthly_attendance_report`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_monthly_attendance_report` AS select 1 AS `employee_id`,1 AS `employee_number`,1 AS `first_name`,1 AS `last_name`,1 AS `full_name`,1 AS `department_name`,1 AS `year`,1 AS `month`,1 AS `total_days`,1 AS `present_days`,1 AS `late_days`,1 AS `early_out_days`,1 AS `total_work_minutes`,1 AS `total_overtime_minutes`,1 AS `total_late_minutes`,1 AS `attendance_rate`
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_pending_attendance_approvals`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_pending_attendance_approvals` AS select 1 AS `id`,1 AS `attendance_date`,1 AS `employee_id`,1 AS `employee_number`,1 AS `first_name`,1 AS `last_name`,1 AS `full_name`,1 AS `department_name`,1 AS `manager_id`,1 AS `check_in_time`,1 AS `check_out_time`,1 AS `is_late`,1 AS `late_duration_minutes`,1 AS `is_early_out`,1 AS `early_out_minutes`,1 AS `status`,1 AS `created_at`
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_pending_leave_requests`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_pending_leave_requests` AS select 1 AS `id`,1 AS `request_date`,1 AS `start_date`,1 AS `end_date`,1 AS `total_days`,1 AS `reason`,1 AS `status`,1 AS `employee_id`,1 AS `employee_number`,1 AS `first_name`,1 AS `last_name`,1 AS `full_name`,1 AS `department_name`,1 AS `manager_id`,1 AS `leave_type_name`,1 AS `requires_document`,1 AS `supporting_document`,1 AS `created_at`
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_pending_overtime_requests`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_pending_overtime_requests` AS select 1 AS `id`,1 AS `overtime_date`,1 AS `start_time`,1 AS `end_time`,1 AS `total_hours`,1 AS `reason`,1 AS `overtime_rate`,1 AS `is_weekend`,1 AS `is_holiday`,1 AS `status`,1 AS `employee_id`,1 AS `employee_number`,1 AS `first_name`,1 AS `last_name`,1 AS `full_name`,1 AS `department_name`,1 AS `manager_id`,1 AS `created_at`
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_pending_trip_requests`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_pending_trip_requests` AS select 1 AS `id`,1 AS `trip_purpose`,1 AS `destination`,1 AS `start_date`,1 AS `end_date`,1 AS `total_days`,1 AS `transportation`,1 AS `estimated_budget`,1 AS `status`,1 AS `employee_id`,1 AS `employee_number`,1 AS `first_name`,1 AS `last_name`,1 AS `full_name`,1 AS `department_name`,1 AS `manager_id`,1 AS `created_at`
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_task_stats_by_category`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_task_stats_by_category` AS select 1 AS `category_id`,1 AS `category_name`,1 AS `color`,1 AS `total_tasks`,1 AS `completed_tasks`,1 AS `in_progress_tasks`,1 AS `pending_tasks`,1 AS `overdue_tasks`,1 AS `avg_completion`,1 AS `total_estimated_hours`,1 AS `total_actual_hours`
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_tasks_full_overview`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_tasks_full_overview` AS select 1 AS `id`,1 AS `task_code`,1 AS `title`,1 AS `description`,1 AS `priority`,1 AS `status`,1 AS `start_date`,1 AS `due_date`,1 AS `completion_percentage`,1 AS `estimated_hours`,1 AS `actual_hours`,1 AS `kpi_weight`,1 AS `category_name`,1 AS `category_color`,1 AS `category_icon`,1 AS `employee_number`,1 AS `department_name`,1 AS `days_remaining`,1 AS `urgency_status`,1 AS `comment_count`,1 AS `attachment_count`,1 AS `total_checklist`,1 AS `completed_checklist`,1 AS `tags`,1 AS `created_at`,1 AS `updated_at`
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_tasks_overdue_alert`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_tasks_overdue_alert` AS select 1 AS `id`,1 AS `task_code`,1 AS `title`,1 AS `priority`,1 AS `status`,1 AS `due_date`,1 AS `days_overdue`,1 AS `employee_number`,1 AS `category_name`,1 AS `alert_level`
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_today_attendance`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_today_attendance` AS select 1 AS `id`,1 AS `attendance_date`,1 AS `employee_id`,1 AS `employee_number`,1 AS `first_name`,1 AS `last_name`,1 AS `full_name`,1 AS `department_name`,1 AS `shift_name`,1 AS `shift_start`,1 AS `shift_end`,1 AS `check_in_time`,1 AS `check_out_time`,1 AS `is_late`,1 AS `late_duration_minutes`,1 AS `is_early_out`,1 AS `early_out_minutes`,1 AS `work_duration_minutes`,1 AS `overtime_minutes`,1 AS `status`,1 AS `approval_status`,1 AS `check_in_location`
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_user_permissions`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_user_permissions` AS select 1 AS `user_id`,1 AS `username`,1 AS `role`,1 AS `role_name`,1 AS `module_name`,1 AS `permission_slug`,1 AS `permission_name`
;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
