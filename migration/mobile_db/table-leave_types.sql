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

-- Dumping structure for table mobile_db.leave_types
CREATE TABLE IF NOT EXISTS `leave_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leave_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `leave_type_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `days_count` int DEFAULT '0' COMMENT '0 = Sesuai Surat Dokter/Fleksibel',
  `is_paid` tinyint(1) DEFAULT '1' COMMENT '1 = Dibayar Penuh (Paid Leave)',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.leave_types: ~13 rows (approximately)
DELETE FROM `leave_types`;
INSERT INTO `leave_types` (`id`, `leave_code`, `leave_type_name`, `days_count`, `is_paid`, `description`, `is_active`) VALUES
	(1, 'ANNUAL', 'Cuti Tahunan', 12, 1, 'Hak cuti tahunan minimal 12 hari kerja (Pasal 79)', 1),
	(2, 'SICK', 'Sakit (Surat Dokter)', 0, 1, 'Sakit dengan keterangan dokter, upah dibayar penuh', 1),
	(3, 'MARRIAGE_SELF', 'Pekerja Menikah', 3, 1, 'Pekerja menikah (3 hari)', 1),
	(4, 'MARRIAGE_CHILD', 'Menikahkan Anak', 2, 1, 'Menikahkan anak kandung (2 hari)', 1),
	(5, 'CIRCUM_BAPTISM', 'Khitanan / Baptisan Anak', 2, 1, 'Mengkhitankan atau membaptiskan anak (2 hari)', 1),
	(6, 'PATERNITY', 'Istri Melahirkan / Keguguran', 2, 1, 'Suami yang istrinya melahirkan atau keguguran (2 hari)', 1),
	(7, 'DEATH_CORE', 'Keluarga Inti Meninggal', 2, 1, 'Suami/Istri, Orang Tua/Mertua, Anak/Menantu meninggal (2 hari)', 1),
	(8, 'DEATH_HOME', 'Anggota Serumah Meninggal', 1, 1, 'Anggota keluarga lain dalam satu rumah meninggal (1 hari)', 1),
	(9, 'MATERNITY', 'Cuti Melahirkan', 90, 1, 'Istirahat melahirkan (1.5 bulan sebelum & sesudah)', 1),
	(10, 'MISCARRIAGE', 'Cuti Keguguran', 45, 1, 'Istirahat gugur kandungan (1.5 bulan atau sesuai surat dokter)', 1),
	(11, 'MENSTRUAL', 'Cuti Haid', 2, 1, 'Hari pertama & kedua haid jika merasakan sakit (Pasal 81)', 1),
	(12, 'UNPAID', 'Unpaid Leave (Potong Gaji)', 0, 0, 'Ijin di luar tanggungan (Potong Gaji)', 1),
	(13, 'DL', 'Dinas Luar / Lapangan', 0, 1, 'Tugas kerja di luar kantor', 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
