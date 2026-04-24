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

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
