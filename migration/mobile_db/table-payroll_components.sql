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

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
