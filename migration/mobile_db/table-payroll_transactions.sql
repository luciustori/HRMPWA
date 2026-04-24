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

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
