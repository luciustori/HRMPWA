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

-- Dumping structure for table mobile_db.task_history
CREATE TABLE IF NOT EXISTS `task_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `task_id` int NOT NULL,
  `user_id` int NOT NULL,
  `action_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'created, updated, status_changed, assigned, commented, completed',
  `field_changed` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kolom yang berubah',
  `old_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `new_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `user_id` (`user_id`),
  KEY `action_type` (`action_type`),
  KEY `idx_history_task_action` (`task_id`,`action_type`),
  KEY `idx_history_user_date` (`user_id`,`created_at`),
  CONSTRAINT `task_history_task_fk` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `task_history_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.task_history: ~93 rows (approximately)
DELETE FROM `task_history`;
INSERT INTO `task_history` (`id`, `task_id`, `user_id`, `action_type`, `field_changed`, `old_value`, `new_value`, `description`, `created_at`) VALUES
	(1, 1, 1, 'created', NULL, NULL, NULL, 'Task created by Admin', '2026-01-10 05:00:38'),
	(2, 1, 1, 'status_changed', NULL, NULL, NULL, 'Status changed from pending to in_progress', '2026-01-10 05:00:38'),
	(3, 1, 1, 'created', NULL, NULL, NULL, 'Task dibuat oleh Admin', '2026-01-10 05:10:31'),
	(4, 1, 1, 'status_changed', NULL, NULL, NULL, 'Status berubah dari pending ke in_progress', '2026-01-10 05:10:31'),
	(6, 3, 1, 'status_changed', 'status', 'pending', 'in_progress', 'Status: pending Ã”Ã¥Ã† in_progress', '2026-01-11 04:28:41'),
	(7, 3, 1, 'status_changed', 'status', 'in_progress', 'completed', 'Status: in_progress Ã”Ã¥Ã† completed', '2026-01-11 04:32:21'),
	(8, 1, 1, 'status_changed', 'status', 'pending', 'in_progress', 'Status: pending Ã”Ã¥Ã† in_progress', '2026-01-11 16:13:24'),
	(9, 2, 1, 'status_changed', 'status', 'pending', 'in_progress', 'Status: pending Ã”Ã¥Ã† in_progress', '2026-01-11 16:13:26'),
	(10, 1, 1, 'status_changed', 'status', 'in_progress', 'pending', 'Status: in_progress Ã”Ã¥Ã† pending', '2026-01-11 16:13:54'),
	(11, 2, 1, 'status_changed', 'status', 'in_progress', 'pending', 'Status: in_progress Ã”Ã¥Ã† pending', '2026-01-11 16:34:39'),
	(12, 3, 1, 'status_changed', 'status', 'completed', 'review', 'Status: completed Ã”Ã¥Ã† review', '2026-01-11 16:34:42'),
	(13, 5, 1, 'created', NULL, NULL, NULL, 'Task "coba notif" dibuat', '2026-01-12 02:09:05'),
	(14, 6, 1, 'created', NULL, NULL, NULL, 'Task "coba notif" dibuat', '2026-01-12 02:14:37'),
	(15, 7, 1, 'created', NULL, NULL, NULL, 'Task "coba notif" dibuat', '2026-01-12 02:16:18'),
	(16, 5, 1, 'status_changed', 'status', 'pending', 'completed', 'Status: pending Ã”Ã¥Ã† completed', '2026-01-12 02:50:36'),
	(17, 6, 1, 'status_changed', 'status', 'pending', 'completed', 'Status: pending Ã”Ã¥Ã† completed', '2026-01-12 02:50:38'),
	(18, 1, 1, 'status_changed', 'status', 'pending', 'in_progress', 'Status: pending Ã”Ã¥Ã† in_progress', '2026-01-13 12:42:00'),
	(19, 5, 1, 'status_changed', 'status', 'completed', 'review', 'Status: completed Ã”Ã¥Ã† review', '2026-01-13 12:42:26'),
	(20, 1, 1, 'reassigned', 'assigned_to', '1', '5', 'Task reassigned: 1 Ã”Ã¥Ã† 5', '2026-01-13 13:06:15'),
	(21, 7, 1, 'status_changed', 'status', 'pending', 'review', 'Status: pending Ã”Ã¥Ã† review', '2026-01-13 15:07:06'),
	(22, 7, 1, 'status_changed', 'status', 'review', 'completed', 'Status: review Ã”Ã¥Ã† completed', '2026-01-13 15:07:20'),
	(23, 7, 1, 'status_changed', 'status', 'completed', 'review', 'Status: completed Ã”Ã¥Ã† review', '2026-01-13 15:11:10'),
	(24, 7, 2, 'time_logged', NULL, NULL, NULL, '2.00 jam dicatat pada 2026-01-15', '2026-01-14 11:00:08'),
	(25, 7, 2, 'commented', NULL, NULL, NULL, 'tambahin comment dlu aahh', '2026-01-14 11:00:35'),
	(26, 5, 2, 'time_logged', NULL, NULL, NULL, '3.00 jam dicatat pada 2026-01-15', '2026-01-14 14:42:07'),
	(27, 5, 2, 'commented', NULL, NULL, NULL, 'ini coba comment', '2026-01-14 14:43:12'),
	(28, 5, 2, 'updated', NULL, NULL, NULL, 'Logged 2 hours on 2026-01-15', '2026-01-14 15:04:36'),
	(29, 5, 2, 'commented', NULL, NULL, NULL, 'ini kok time logsnya numpuk! ra sip!', '2026-01-14 15:05:51'),
	(30, 5, 2, 'commented', NULL, NULL, NULL, 'Added a comment', '2026-01-14 15:05:51'),
	(31, 5, 2, 'time_logged', NULL, NULL, NULL, '1.00 jam dicatat pada 2026-01-15', '2026-01-14 15:11:12'),
	(32, 5, 2, 'updated', NULL, NULL, NULL, 'Logged 1 hours on 2026-01-15', '2026-01-14 15:11:12'),
	(33, 1, 1, 'time_logged', NULL, NULL, NULL, '1.00 jam dicatat pada 2026-01-15', '2026-01-14 23:46:19'),
	(34, 1, 1, 'updated', NULL, NULL, NULL, 'Logged 1 hours on 2026-01-15', '2026-01-14 23:46:19'),
	(35, 7, 1, 'status_changed', 'status', 'review', 'completed', 'Status: review Ã”Ã¥Ã† completed', '2026-01-26 19:01:56'),
	(36, 8, 5, 'created', NULL, NULL, NULL, 'Task "task 1" dibuat', '2026-01-26 21:30:14'),
	(37, 1, 1, 'status_changed', 'status', 'in_progress', 'review', 'Status: in_progress Ã”Ã¥Ã† review', '2026-01-27 05:31:43'),
	(38, 1, 1, 'status_changed', 'status', 'review', 'in_progress', 'Status: review Ã”Ã¥Ã† in_progress', '2026-01-27 05:31:45'),
	(39, 9, 5, 'created', NULL, NULL, NULL, 'Task "Pencatatan Biaya Service Tenant" dibuat', '2026-01-27 22:52:56'),
	(40, 9, 5, 'commented', NULL, NULL, NULL, '2 tenan tutup hari ini', '2026-01-27 23:25:45'),
	(41, 9, 5, 'time_logged', NULL, NULL, NULL, '1.00 jam dicatat pada 2026-01-28', '2026-01-27 23:32:49'),
	(42, 9, 5, 'status_changed', 'status', 'pending', 'in_progress', 'Status: pending Ã”Ã¥Ã† in_progress', '2026-01-27 23:33:00'),
	(43, 7, 1, 'reassigned', 'assigned_to', '2', '9', 'Task reassigned: 2 Ã”Ã¥Ã† 9', '2026-01-27 23:43:05'),
	(44, 9, 5, 'deadline_changed', 'due_date', '2026-01-05', '2026-01-10', 'Deadline: 2026-01-05 Ã”Ã¥Ã† 2026-01-10', '2026-01-30 19:47:02'),
	(45, 9, 5, 'reassigned', 'assigned_to', '7', '18', 'Task reassigned: 7 Ã”Ã¥Ã† 18', '2026-01-30 19:47:02'),
	(46, 5, 1, 'reassigned', 'assigned_to', '2', '10', 'Task reassigned: 2 Ã”Ã¥Ã† 10', '2026-01-30 19:53:50'),
	(47, 9, 5, 'status_changed', 'status', 'in_progress', 'review', 'Status: in_progress Ã”Ã¥Ã† review', '2026-01-31 21:26:57'),
	(48, 9, 5, 'status_changed', 'status', 'review', 'in_progress', 'Status: review Ã”Ã¥Ã† in_progress', '2026-01-31 21:26:58'),
	(49, 9, 5, 'status_changed', 'status', 'in_progress', 'review', 'Status: in_progress Ã”Ã¥Ã† review', '2026-01-31 21:26:59'),
	(50, 9, 5, 'status_changed', 'status', 'review', 'in_progress', 'Status: review Ã”Ã¥Ã† in_progress', '2026-01-31 21:27:00'),
	(51, 8, 18, 'commented', NULL, NULL, NULL, 'coba update comment', '2026-01-31 21:34:18'),
	(52, 8, 18, 'commented', NULL, NULL, NULL, 'Menambahkan komentar: coba update comment...', '2026-01-31 21:34:18'),
	(53, 8, 18, 'commented', NULL, NULL, NULL, 'dah rampung kan ini? cek cek cek', '2026-01-31 21:34:41'),
	(54, 8, 18, 'commented', NULL, NULL, NULL, 'Menambahkan komentar: dah rampung kan ini? cek cek c...', '2026-01-31 21:34:41'),
	(55, 8, 5, 'status_changed', 'status', 'pending', 'completed', 'Status: pending Ã”Ã¥Ã† completed', '2026-01-31 21:34:43'),
	(56, 9, 5, 'status_changed', 'status', 'in_progress', 'review', 'Status: in_progress Ã”Ã¥Ã† review', '2026-01-31 21:34:54'),
	(57, 9, 5, 'status_changed', 'status', 'review', 'completed', 'Status: review Ã”Ã¥Ã† completed', '2026-01-31 21:34:58'),
	(58, 9, 5, 'status_changed', 'status', 'completed', 'review', 'Status: completed Ã”Ã¥Ã† review', '2026-01-31 21:34:59'),
	(59, 9, 5, 'status_changed', 'status', 'review', 'completed', 'Status: review Ã”Ã¥Ã† completed', '2026-01-31 21:35:00'),
	(60, 9, 5, 'status_changed', 'status', 'completed', 'review', 'Status: completed Ã”Ã¥Ã† review', '2026-01-31 21:35:01'),
	(61, 9, 5, 'status_changed', 'status', 'review', 'completed', 'Status: review Ã”Ã¥Ã† completed', '2026-01-31 21:35:03'),
	(62, 9, 16, 'time_logged', NULL, NULL, NULL, '3.00 jam dicatat pada 2026-02-01', '2026-01-31 21:39:11'),
	(63, 9, 18, 'time_logged', NULL, NULL, NULL, '2.00 jam dicatat pada 2026-02-01', '2026-01-31 22:42:09'),
	(64, 9, 18, 'time_logged', NULL, NULL, NULL, '7.00 jam dicatat pada 2026-02-01', '2026-01-31 22:42:17'),
	(65, 9, 16, 'time_logged', NULL, NULL, NULL, '1.00 jam dicatat pada 2026-02-01', '2026-01-31 23:24:27'),
	(66, 9, 18, 'time_logged', NULL, NULL, NULL, '3.00 jam dicatat pada 2026-02-01', '2026-01-31 23:25:01'),
	(67, 9, 18, 'time_logged', NULL, NULL, NULL, '0.00 jam dicatat pada 2026-02-01', '2026-01-31 23:25:10'),
	(68, 9, 5, 'status_changed', 'status', 'completed', 'review', 'Status: completed Ã”Ã¥Ã† review', '2026-02-02 06:06:16'),
	(69, 9, 18, 'status_changed', NULL, NULL, NULL, 'Mengubah status dari completed ke review', '2026-02-02 06:06:16'),
	(70, 9, 5, 'status_changed', 'status', 'review', 'completed', 'Status: review Ã”Ã¥Ã† completed', '2026-02-02 11:57:41'),
	(71, 9, 18, 'status_changed', NULL, NULL, NULL, 'Mengubah status dari review ke completed', '2026-02-02 11:57:41'),
	(72, 6, 1, 'reassigned', 'assigned_to', '2', '8', 'Task reassigned: 2 Ã”Ã¥Ã† 8', '2026-02-06 08:28:14'),
	(73, 10, 1, 'created', NULL, NULL, NULL, 'Task "Persiapan Panitia Seleksi Direktur PT. Jogjatama Vishesha" dibuat', '2026-02-09 12:22:49'),
	(74, 3, 1, 'status_changed', 'status', 'review', 'completed', 'Status: review â†’ completed', '2026-02-09 14:33:50'),
	(75, 10, 1, 'status_changed', 'status', 'pending', 'in_progress', 'Status: pending â†’ in_progress', '2026-02-09 14:57:45'),
	(76, 10, 1, 'status_changed', 'status', 'in_progress', 'review', 'Status: in_progress â†’ review', '2026-02-09 15:09:47'),
	(77, 10, 16, 'commented', NULL, NULL, NULL, 'Sudah buka lowongan di KR', '2026-02-09 15:10:25'),
	(78, 10, 16, 'time_logged', NULL, NULL, NULL, '3.00 jam dicatat pada 2026-02-09', '2026-02-09 15:23:54'),
	(79, 10, 1, 'commented', NULL, NULL, NULL, 'jangan lupa media sosial atau online platform', '2026-02-09 15:31:57'),
	(80, 10, 1, 'status_changed', 'status', 'review', 'in_progress', 'Status: review â†’ in_progress', '2026-02-09 16:56:46'),
	(81, 10, 16, 'time_logged', NULL, NULL, NULL, '2.00 jam dicatat pada 2026-02-10', '2026-02-10 04:50:15'),
	(82, 3, 1, 'status_changed', NULL, NULL, NULL, 'Mengubah status dari completed ke completed', '2026-02-10 04:52:56'),
	(83, 2, 1, 'status_changed', 'status', 'pending', 'in_progress', 'Status: pending â†’ in_progress', '2026-02-10 04:53:17'),
	(84, 2, 1, 'status_changed', NULL, NULL, NULL, 'Mengubah status dari pending ke in_progress', '2026-02-10 04:53:17'),
	(85, 10, 1, 'status_changed', 'status', 'in_progress', 'completed', 'Status: in_progress â†’ completed', '2026-02-10 15:36:37'),
	(86, 10, 16, 'status_changed', NULL, NULL, NULL, 'Mengubah status dari in_progress ke completed', '2026-02-10 15:36:37'),
	(87, 10, 1, 'status_changed', 'status', 'completed', 'review', 'Status: completed â†’ review', '2026-02-10 15:37:12'),
	(88, 10, 16, 'status_changed', NULL, NULL, NULL, 'Mengubah status dari completed ke review', '2026-02-10 15:37:12'),
	(89, 10, 1, 'status_changed', 'status', 'review', 'completed', 'Status: review â†’ completed', '2026-02-10 15:37:18'),
	(90, 10, 16, 'status_changed', NULL, NULL, NULL, 'Mengubah status dari review ke completed', '2026-02-10 15:37:18'),
	(91, 10, 1, 'status_changed', 'status', 'completed', 'in_progress', 'Status: completed â†’ in_progress', '2026-02-10 16:02:46'),
	(92, 10, 1, 'status_changed', 'status', 'in_progress', 'submitted', 'Status: in_progress â†’ submitted', '2026-02-10 16:03:35'),
	(93, 10, 16, 'status_changed', NULL, NULL, NULL, 'Mengubah status menjadi Mengajukan Approval (Selesai)', '2026-02-10 16:03:35'),
	(94, 10, 1, 'status_changed', 'status', 'submitted', 'completed', 'Status: submitted â†’ completed', '2026-02-10 16:03:55'),
	(95, 2, 1, 'time_logged', NULL, NULL, NULL, '0.00 jam dicatat pada 2026-02-15', '2026-02-15 14:57:33'),
	(96, 2, 1, 'commented', NULL, NULL, NULL, 'diskusi progress disini via mobile', '2026-02-15 14:57:47'),
	(97, 11, 1, 'created', NULL, NULL, NULL, 'Task "Trial Test SIXTY " dibuat', '2026-02-16 03:20:17');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
