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

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
