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

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
