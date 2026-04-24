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

-- Dumping data for table mobile_db.payroll_periods: ~3 rows (approximately)
DELETE FROM `payroll_periods`;
INSERT INTO `payroll_periods` (`id`, `period_name`, `period_month`, `period_year`, `start_date`, `end_date`, `payment_date`, `cutoff_date`, `status`, `processed_by`, `processed_at`, `approved_by`, `approved_at`, `total_employees`, `total_gross_salary`, `total_deductions`, `total_net_salary`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
	(1, 'November 2025', 11, 2025, '2025-11-01', '2025-11-30', '2025-12-05', '2025-11-28', 'paid', 1, '2025-11-29 10:00:00', 1, '2025-11-30 14:00:00', 5, 15000000.00, 2250000.00, 12750000.00, 'Payroll November 2025 - Normal', 1, '2025-10-31 11:00:00', '2025-12-04 19:00:00'),
	(2, 'Desember 2025', 12, 2025, '2025-12-01', '2025-12-31', '2026-01-05', '2025-12-28', 'approved', 1, '2025-12-29 10:00:00', 1, '2025-12-30 14:00:00', 5, 16500000.00, 2475000.00, 14025000.00, 'Payroll Desember 2025 - Include Bonus Akhir Tahun', 1, '2025-11-30 11:00:00', '2025-12-29 19:00:00');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
