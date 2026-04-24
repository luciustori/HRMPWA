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

-- Dumping data for table mobile_db.payroll_transaction_details: ~16 rows (approximately)
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

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
