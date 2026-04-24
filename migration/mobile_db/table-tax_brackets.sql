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

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
