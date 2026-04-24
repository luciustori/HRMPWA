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

-- Dumping data for table mobile_db.task_checklist: ~24 rows (approximately)
DELETE FROM `task_checklist`;
INSERT INTO `task_checklist` (`id`, `task_id`, `item_text`, `is_completed`, `completed_by`, `completed_at`, `sort_order`, `created_at`) VALUES
	(1, 1, 'Review requirements dan scope', 1, 1, '2026-01-11 15:09:58', 1, '2026-01-10 05:10:31'),
	(2, 1, 'Create database schema', 1, 1, '2026-01-11 15:08:44', 2, '2026-01-10 05:10:31'),
	(3, 1, 'Develop backend API endpoints', 1, 1, '2026-01-13 13:04:35', 3, '2026-01-10 05:10:31'),
	(4, 1, 'Frontend implementation', 0, NULL, NULL, 4, '2026-01-10 05:10:31'),
	(5, 1, 'Testing dan QA', 1, 3, '2026-01-11 15:45:18', 5, '2026-01-10 05:10:31'),
	(6, 1, 'Deploy to staging', 0, NULL, NULL, 6, '2026-01-10 05:10:31'),
	(7, 3, 'pilihan lokasi', 0, NULL, NULL, 1, '2026-01-13 15:12:53'),
	(8, 7, 'mencoba submit approval 1', 1, NULL, NULL, 1, '2026-01-13 21:31:11'),
	(9, 7, 'coba add list 1 lagi', 1, NULL, NULL, 0, '2026-01-13 21:37:55'),
	(10, 7, 'checklist masuk tapi notifnya bilang error!', 1, 2, NULL, 0, '2026-01-13 21:38:47'),
	(11, 7, 'coba item baru ke 3', 1, NULL, NULL, 0, '2026-01-13 21:50:00'),
	(12, 5, 'add checlist 1', 0, NULL, NULL, 0, '2026-01-14 15:05:08'),
	(13, 9, 'Pencatatan Meteran Listrik', 1, NULL, NULL, 0, '2026-01-27 22:52:56'),
	(14, 9, 'Pencatatan Meteran Air', 1, NULL, NULL, 0, '2026-01-27 22:52:56'),
	(15, 9, 'Input di SIXTY', 1, NULL, NULL, 0, '2026-01-27 22:52:56'),
	(16, 10, 'Buka lowongan kerja posisi Direktur PT. JV', 1, NULL, NULL, 0, '2026-02-09 12:22:49'),
	(17, 10, 'Koordinasi dengan BKPSDM untuk pengumpulan kandidat', 1, NULL, NULL, 0, '2026-02-09 12:22:49'),
	(18, 10, 'Bekerja sama dengan BKPSDM untuk Test Kompetensi', 1, NULL, NULL, 0, '2026-02-09 12:22:49'),
	(19, 11, 'Test Online ( sudahkah di server XT )', 0, NULL, NULL, 0, '2026-02-16 03:20:17'),
	(20, 11, 'Login Test', 0, NULL, NULL, 0, '2026-02-16 03:20:17'),
	(21, 11, 'Input Test', 0, NULL, NULL, 0, '2026-02-16 03:20:17'),
	(22, 11, 'Input Tagihan', 0, NULL, NULL, 0, '2026-02-16 03:21:22'),
	(23, 11, 'Print Invoice dan  Nota', 0, NULL, NULL, 0, '2026-02-16 03:21:35'),
	(24, 11, 'Laporan Jurnal Jurnal', 0, NULL, NULL, 0, '2026-02-16 03:22:06');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
