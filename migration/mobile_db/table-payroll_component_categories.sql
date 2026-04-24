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

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
