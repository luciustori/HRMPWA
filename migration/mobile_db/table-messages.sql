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

-- Dumping structure for table mobile_db.messages
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int DEFAULT NULL COMMENT 'Jika NULL=Pesan Baru, Jika Terisi=Reply ID Pesan Utama',
  `sender_id` int NOT NULL,
  `recipient_id` int NOT NULL,
  `subject` varchar(255) DEFAULT NULL COMMENT 'Subjek pesan (biasanya cuma diisi di pesan awal)',
  `body` longtext NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0' COMMENT '0=Belum dibaca, 1=Sudah',
  `is_archived` tinyint(1) DEFAULT '0' COMMENT 'Buat nyembunyiin pesan di inbox',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `sender_id` (`sender_id`),
  KEY `recipient_id` (`recipient_id`),
  CONSTRAINT `messages_fk_parent` FOREIGN KEY (`parent_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_fk_recipient` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_fk_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table mobile_db.messages: ~4 rows (approximately)
DELETE FROM `messages`;
INSERT INTO `messages` (`id`, `parent_id`, `sender_id`, `recipient_id`, `subject`, `body`, `attachment`, `is_read`, `is_archived`, `created_at`, `updated_at`) VALUES
	(1, NULL, 1, 16, 'COba Pesan', 'ini cuman pesan saja', NULL, 1, 0, '2026-02-10 16:20:51', '2026-02-15 13:06:55'),
	(2, NULL, 1, 16, 'ini terkirim dari pesan Admin', 'admin kirim pesan', NULL, 0, 0, '2026-02-11 02:36:44', NULL),
	(3, NULL, 1, 16, 'Coba Pesan MObile', 'COba via mobile', 'uploads/messages/7f17804a169668ffecb6b72389e6aaaa.png', 1, 0, '2026-02-14 15:21:03', '2026-02-14 15:21:21'),
	(4, 3, 16, 1, 'Re: Coba Pesan MObile', 'Reply balasan', NULL, 0, 0, '2026-02-15 15:17:09', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
