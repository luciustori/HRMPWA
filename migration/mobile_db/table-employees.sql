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

-- Dumping structure for table mobile_db.employees
CREATE TABLE IF NOT EXISTS `employees` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT '1',
  `employee_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('male','female') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Jenis Kelamin',
  `marital_status` enum('single','married','divorced','widowed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'single' COMMENT 'Status Pernikahan',
  `number_of_dependents` int DEFAULT '0' COMMENT 'Jumlah Tanggungan',
  `npwp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nomor Pokok Wajib Pajak',
  `bank_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nama Bank',
  `bank_account_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nomor Rekening',
  `bank_account_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nama Pemilik Rekening',
  `full_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS (concat(`first_name`,_utf8mb4' ',`last_name`)) STORED,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `identity_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `profile_photo_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `face_recognition_photo_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` int DEFAULT NULL,
  `division_id` int DEFAULT NULL,
  `salary_grade_id` int DEFAULT NULL,
  `employee_level` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `salary` decimal(15,2) DEFAULT '0.00',
  `position` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_status` enum('active','inactive','suspended') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `annual_leave_balance` int DEFAULT '12',
  `hire_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_number` (`employee_number`),
  KEY `department_id` (`department_id`),
  KEY `gender` (`gender`),
  KEY `marital_status` (`marital_status`),
  KEY `bank_account_number` (`bank_account_number`),
  KEY `fk_emp_grade` (`salary_grade_id`),
  KEY `division_id` (`division_id`),
  CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_emp_grade` FOREIGN KEY (`salary_grade_id`) REFERENCES `salary_grades` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.employees: ~24 rows (approximately)
DELETE FROM `employees`;
INSERT INTO `employees` (`id`, `company_id`, `employee_number`, `first_name`, `last_name`, `gender`, `marital_status`, `number_of_dependents`, `npwp`, `bank_name`, `bank_account_number`, `bank_account_name`, `email`, `phone`, `date_of_birth`, `identity_number`, `address`, `profile_photo_path`, `face_recognition_photo_path`, `department_id`, `division_id`, `salary_grade_id`, `employee_level`, `salary`, `position`, `employee_status`, `annual_leave_balance`, `hire_date`, `created_at`, `updated_at`, `is_active`) VALUES
	(1, 1, 'A-10.001', 'Super', 'Admin', 'male', 'single', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 'director', 0.00, 'Direktur PDJV', 'active', 12, NULL, '2026-01-23 19:19:55', '2026-01-29 09:44:31', 1),
	(2, 1, 'T-20.001', 'Anggoro', 'Suharjanto', 'male', 'single', 0, '', 'BCA', '', '', '', '+62 822-2659-2557', '1984-07-23', '', 'Bausasran DN 3/634 RT 32 RW 9 Bausasran Danurejan Yogyakarta', NULL, NULL, 2, NULL, 5, 'manager', 0.00, 'Manager Marketing dan Operasional', 'active', 12, '2012-11-01', '2026-01-24 17:49:41', '2026-02-06 10:55:09', 1),
	(3, 1, 'T-21.003', 'Diana', 'Aprilia Safitri', 'male', 'single', 0, '', 'BCA', '', '', '', '+62 856-8670-888', '1988-04-25', '', 'Nogosari Kidul KP III/60 Yk RT 003 RW 001 Kadipeten Kraton Yogyakarta', NULL, NULL, 2, NULL, 4, 'supervisor', 0.00, 'Supervisor Administrasi dan Keuangan Bisnis', 'active', 12, '2013-01-04', '2026-01-24 17:49:41', '2026-02-06 10:55:20', 1),
	(4, 1, 'T-21.004', 'Thomas', 'Anggi Van Hendrawan', 'male', 'single', 0, '', 'BCA', '', '', '', '+62 856-5527-7123', '1990-01-28', '', 'Jl. Nangka 3 No. 51, Karangnongko, Maguwo, Depok, Sleman', NULL, NULL, 2, NULL, NULL, 'supervisor', 0.00, 'Supervisor Building dan Unit Bisnis Perumahan', 'active', 12, '2014-10-08', '2026-01-24 17:49:41', '2026-02-05 06:58:57', 1),
	(5, 1, 'T-22.005', 'Agus', 'Wardoyo', 'male', 'single', 0, '', 'BCA', '', '', '', '', '1982-09-17', '', 'Sambego no. 16A RT 14 RW 38 Maguwoharjo Depok Sleman', 'uploads/profiles/profile_5_1770516605.png', NULL, 1, NULL, 2, 'staff', 0.00, 'Koordinator Teknisi', 'active', 12, '2013-02-04', '2026-01-24 17:49:41', '2026-02-07 19:10:05', 1),
	(6, 1, 'T-22.006', 'Edi', 'Purwanto', 'male', 'single', 0, '', '', '', '', '', '+62 818-0435-6100', '1979-04-19', '', 'Trini RT 007/RW 018, Trihanggo, Gamping, Sleman', NULL, NULL, 2, NULL, NULL, 'staff', 0.00, 'Teknisi', 'active', 12, '2013-12-03', '2026-01-24 17:49:41', '2026-01-27 02:42:59', 1),
	(7, 1, 'T-23.010', 'Muhammad', 'Roichan Juni Saputra', 'male', 'single', 0, '', '', '', '', '', '+62 812-1693-9885', '1984-06-16', '', 'Jl. Patehan Kidul No. 10 RT 020/005, Patehan, Kraton, Yogyakarta', NULL, NULL, 2, NULL, NULL, 'staff', 0.00, 'Koordinator Unit Bisnis Parkir', 'active', 12, '2018-07-03', '2026-01-24 17:49:41', '2026-01-27 02:43:21', 1),
	(8, 1, 'T-23.011', 'Girian', 'Subekti', 'male', 'single', 0, '', '', '', '', '', '+62 858-6829-7846', '1992-08-24', '', 'Karanganom, RT 004/ 000, Wonokromo, Pleret, Bantul', NULL, NULL, 2, NULL, NULL, 'staff', 0.00, 'Teknisi', 'active', 12, '2017-04-13', '2026-01-24 17:49:41', '2026-01-27 02:43:13', 1),
	(9, 1, 'T-25.012', 'Muhammad', 'Nafi\' Maula', 'male', 'single', 0, '', '', '', '', '', '+62 852-6836-0024', '2000-01-07', '', 'Dusun Sendang RT.001 RW.003 Jetis, Kaliwungu, Kab. Semarang, Jawa Tengah', NULL, NULL, 2, NULL, NULL, 'staff', 0.00, 'Koordinator Existing XT Square dan Unit Bisnis Retail', 'active', 12, '2021-02-09', '2026-01-24 17:49:41', '2026-01-27 02:45:28', 1),
	(10, 1, 'T-25.013', 'Haryanto', '', 'male', 'single', 0, '', '', '', '', '', '+62 852-9218-7198', '1981-02-09', '', 'Gunungcilik RT 004, Muntuk, Dlingo, Bantul', NULL, NULL, 2, NULL, NULL, 'staff', 0.00, 'Staff Retail', 'active', 12, '2017-01-02', '2026-01-24 17:49:41', '2026-01-27 02:42:28', 1),
	(11, 1, 'K-25.060', 'Anggraini', 'Retno Wulandari', 'male', 'single', 0, '', 'BCA', '', '', '', '+62 877-3898-9389', '1987-08-30', '', 'Kemetiran Kidul GT.II/733 RT 056 RW 016 Pringgokusuman Gedongtengan Yogyakarta', NULL, NULL, 2, 5, 2, 'staff', 0.00, 'Koordinator Cafe dan Pelatihan', 'active', 12, '2025-07-05', '2026-01-24 17:49:41', '2026-02-11 15:37:56', 1),
	(12, 1, 'T-25.015', 'Jumiyanto', '', 'male', 'single', 0, '', '', '', '', '', '+62 882-1654-8204', '1983-02-28', '', 'Pelemgede RT 003/RW 003, Sodo, Paliyan, Gunungkidul', NULL, NULL, 2, NULL, NULL, 'staff', 0.00, 'Teknisi', 'active', 12, '2017-03-07', '2026-01-24 17:49:41', '2026-01-27 02:43:37', 1),
	(13, 1, 'T-20.002', 'Windy', 'Kusuma Jayanti', 'male', 'single', 0, '', '', '', '', 'yohannawindy@gmail.com', '+62 851-3336-0405', '1988-05-31', '', 'Jl. R.E. Martadinata 35 Wirobrajan Yogyakarta 55252', 'uploads/profiles/profile_13_1771217844.jpg', NULL, 1, NULL, 5, 'manager', 0.00, 'Manager SDM, Keuangan, dan Umum', 'active', 12, '2012-11-02', '2026-01-24 17:49:41', '2026-02-16 04:57:24', 1),
	(14, 1, 'T-22.007', 'Wahyu', 'Dianto', 'male', 'single', 0, '', '', '', '', '', '+62 857-8344-9953', '1992-11-21', '', 'Kenalan Dk. VIII Kenalan RT 03 Bangunjiwo Kasihan Bantul', NULL, NULL, 1, NULL, NULL, 'staff', 0.00, 'Office Boy', 'active', 12, '2012-12-24', '2026-01-24 17:49:41', '2026-01-27 02:44:41', 1),
	(15, 1, 'T-22.008', 'Patrick', 'Anang Priyambada', 'male', 'single', 0, '', '', '', '', '', '+62 856-2949-456', '1989-03-28', '', 'Klumutan RT013/007, Srikayangan, Sentolo, Kulonprogo', NULL, NULL, 1, NULL, NULL, 'staff', 0.00, 'General Affair', 'active', 12, '2018-04-16', '2026-01-24 17:49:41', '2026-01-27 02:43:28', 1),
	(16, 1, 'T-22.009', 'Thomas', 'Yudhistira', 'male', 'single', 0, '', '', '', '', '', '+62 856-4043-8643', '1989-10-03', '', 'Pilahan Asri II no. 34 RT 045/011, Rejowinangun, Kotagede, Yogyakarta', NULL, NULL, 1, NULL, NULL, 'supervisor', 0.00, 'Satuan Pengawas Internal', 'active', 12, '2018-04-16', '2026-01-24 17:49:41', '2026-01-27 02:44:18', 1),
	(17, 1, 'T-25.014', 'Swandika', 'Addi Wicaksono', NULL, 'single', 0, NULL, NULL, NULL, NULL, NULL, '+62 817-0606-398', '1990-04-12', NULL, 'Perumahan Ndalem Guwosari No 141 Gang Rukun RT 005 RW 002 Guwosari Pajangan Bantul', NULL, NULL, 2, NULL, NULL, 'staff', 0.00, 'Staff IT dan Desain Grafis', 'active', 12, '2019-03-14', '2026-01-24 17:49:41', '2026-01-24 17:49:41', 1),
	(18, 1, 'K-19.058', 'Rian', 'Mei Hermawan', NULL, 'single', 0, NULL, NULL, NULL, NULL, NULL, '+62 852-3210-7447', '1987-05-29', NULL, 'Kemloko RT 06 RW 20 Kel. Margorejo Kec. Tempel Sleman Yogyakarta 55552', NULL, NULL, 2, NULL, NULL, 'staff', 0.00, 'Staff Keuangan dan Pajak', 'active', 12, '2022-12-26', '2026-01-24 17:49:41', '2026-01-24 17:49:41', 1),
	(19, 1, 'K-25.059', 'Andri', 'Yokas Permadi', NULL, 'single', 0, NULL, NULL, NULL, NULL, NULL, '+62 895-0535-2720', '1998-07-13', NULL, 'Wonoroto RT 02 RW - Gadingsari Sanden Bantul', NULL, NULL, 2, NULL, 2, 'staff', 0.00, 'Staff Account Receivable Billing', 'active', 12, '2022-01-01', '2026-01-24 17:49:41', '2026-02-06 12:09:33', 1),
	(20, 1, 'H-22.008', 'Henry', 'Cahyono', 'male', 'single', 0, '', 'BCA', '', '', '', '+62 857-1201-0752', '1976-04-16', '', 'Sidomulyo TR IV/228 RT 019 RW 005 Bener Tegalrejo Yogyakarta', NULL, NULL, 1, 3, 1, 'harian', 0.00, 'Staff Cleaning', 'active', 12, '2022-01-01', '2026-01-24 17:49:41', '2026-02-06 12:31:56', 1),
	(21, 1, 'H-22.009', 'Rismanto', '', 'male', 'single', 0, '', 'BCA', '', '', '', '+62 818-0262-5227', '1969-08-11', '', 'Serangan NG II/73 RT 008 RW 002 Notorajan Ngampilan Yogyakarta', NULL, NULL, 1, 3, NULL, 'harian', 0.00, 'Staff Cleaning', 'active', 12, '2022-01-01', '2026-01-24 17:49:41', '2026-02-06 12:31:40', 1),
	(22, 1, 'H-22.010', 'Sankan', 'Iswanto', NULL, 'single', 0, NULL, NULL, NULL, NULL, NULL, '+62 856-0094-3385', '1980-10-12', NULL, 'Tegal RT 002 RW 014 Sidoarum Godean Sleman', NULL, NULL, 2, NULL, NULL, 'harian', 0.00, 'Staff Cleaning', 'active', 12, '2022-01-01', '2026-01-24 17:49:41', '2026-01-24 17:49:41', 1),
	(23, 1, 'H-22.011', 'Triyono', '', 'male', 'single', 0, '', 'BCA', '', '', '', '+62 897-6072-727', '1981-09-23', '', 'Ngaran RT 001 Gilangharjo Pandak Bantul', NULL, NULL, 1, 3, 1, 'harian', 0.00, 'Staff Cleaning', 'active', 12, '2022-01-01', '2026-01-24 17:49:41', '2026-02-06 12:31:21', 1),
	(25, 1, 'D-26.004', 'Hariyono', '', 'male', 'married', 0, '', '', '', '', 'hariyonost06@gmail.com', '123456789', '1970-01-01', '123456789', '', NULL, NULL, NULL, NULL, 6, 'direktur', 0.00, 'Direktur Utama', 'active', 12, '2026-04-02', '2026-04-02 10:02:37', '2026-04-02 10:02:37', 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
