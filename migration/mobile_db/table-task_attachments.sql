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

-- Dumping structure for table mobile_db.task_attachments
CREATE TABLE IF NOT EXISTS `task_attachments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `task_id` int NOT NULL,
  `user_id` int NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` int DEFAULT NULL COMMENT 'Size in bytes',
  `file_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'pdf, doc, jpg, etc',
  `mime_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `user_id` (`user_id`),
  KEY `uploaded_at` (`uploaded_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Task file attachments (separate from comments)';

-- Dumping data for table mobile_db.task_attachments: ~4 rows (approximately)
DELETE FROM `task_attachments`;
INSERT INTO `task_attachments` (`id`, `task_id`, `user_id`, `file_name`, `file_path`, `file_size`, `file_type`, `mime_type`, `description`, `uploaded_at`) VALUES
	(1, 7, 2, 'Cuplikan layar 2025-11-01 071102.png', '/uploads/tasks/7/1768366242_696720a2892e9.png', 24943, 'png', NULL, NULL, '2026-01-13 21:50:42'),
	(2, 7, 2, 'Cuplikan layar 2025-11-01 071102.png', '/uploads/tasks/7/1768366293_696720d5f3f35.png', 24943, 'png', NULL, NULL, '2026-01-13 21:51:34'),
	(3, 7, 2, 'Cuplikan layar 2025-11-01 071102.png', '/uploads/tasks/7/1768366333_696720fd14248.png', 24943, 'png', NULL, NULL, '2026-01-13 21:52:13'),
	(4, 6, 1, 'Cuplikan layar 2025-11-14 144521.png', '/uploads/tasks/task_6_1768367558_696725c622691.png', 373832, 'png', NULL, NULL, '2026-01-13 22:12:38');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
