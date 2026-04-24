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

-- Data exporting was unselected.

-- Dumping structure for table mobile_db.announcement_reads
CREATE TABLE IF NOT EXISTS `announcement_reads` (
  `id` int NOT NULL AUTO_INCREMENT,
  `announcement_id` int NOT NULL,
  `user_id` int NOT NULL,
  `read_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_read` (`announcement_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

-- Dumping structure for table mobile_db.task_tag_relations
CREATE TABLE IF NOT EXISTS `task_tag_relations` (
  `task_id` int NOT NULL,
  `tag_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`task_id`,`tag_id`),
  KEY `tag_id` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Many-to-many relationship between tasks and tags';

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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
