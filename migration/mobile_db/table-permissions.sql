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

-- Dumping structure for table mobile_db.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `module_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `permission_slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `permission_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permission_slug` (`permission_slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.permissions: ~69 rows (approximately)
DELETE FROM `permissions`;
INSERT INTO `permissions` (`id`, `module_name`, `permission_slug`, `permission_name`, `description`, `created_at`) VALUES
	(1, 'company', 'company.view', 'View Company Settings', 'Can view company information', '2025-12-31 14:14:57'),
	(2, 'company', 'company.edit', 'Edit Company Settings', 'Can edit company information', '2025-12-31 14:14:57'),
	(3, 'employees', 'employees.view', 'View Employees', 'Can view employee list', '2025-12-31 14:14:57'),
	(4, 'employees', 'employees.create', 'Create Employee', 'Can add new employees', '2025-12-31 14:14:57'),
	(5, 'employees', 'employees.edit', 'Edit Employee', 'Can edit employee information', '2025-12-31 14:14:57'),
	(6, 'employees', 'employees.delete', 'Delete Employee', 'Can delete employees', '2025-12-31 14:14:57'),
	(7, 'departments', 'departments.view', 'View Departments', 'Can view department list', '2025-12-31 14:14:57'),
	(8, 'departments', 'departments.create', 'Create Department', 'Can add new departments', '2025-12-31 14:14:57'),
	(9, 'departments', 'departments.edit', 'Edit Department', 'Can edit department information', '2025-12-31 14:14:57'),
	(10, 'departments', 'departments.delete', 'Delete Department', 'Can delete departments', '2025-12-31 14:14:57'),
	(11, 'locations', 'locations.view', 'View Office Locations', 'Can view office locations', '2025-12-31 14:14:57'),
	(12, 'locations', 'locations.create', 'Create Office Location', 'Can add new locations', '2025-12-31 14:14:57'),
	(13, 'locations', 'locations.edit', 'Edit Office Location', 'Can edit location information', '2025-12-31 14:14:57'),
	(14, 'locations', 'locations.delete', 'Delete Office Location', 'Can delete locations', '2025-12-31 14:14:57'),
	(15, 'schedules', 'schedules.view', 'View Schedules', 'Can view work schedules', '2025-12-31 14:14:57'),
	(16, 'schedules', 'schedules.create', 'Create Schedule', 'Can create shift assignments', '2025-12-31 14:14:57'),
	(17, 'schedules', 'schedules.edit', 'Edit Schedule', 'Can edit shift assignments', '2025-12-31 14:14:57'),
	(18, 'schedules', 'schedules.delete', 'Delete Schedule', 'Can delete shift assignments', '2025-12-31 14:14:57'),
	(19, 'schedules', 'shifts.manage', 'Manage Shifts', 'Can manage shift types', '2025-12-31 14:14:57'),
	(20, 'schedules', 'holidays.manage', 'Manage Holidays', 'Can manage holidays', '2025-12-31 14:14:57'),
	(21, 'attendance', 'attendance.view', 'View Attendance', 'Can view attendance records', '2025-12-31 14:14:57'),
	(22, 'attendance', 'attendance.edit', 'Edit Attendance', 'Can edit attendance records', '2025-12-31 14:14:57'),
	(23, 'attendance', 'attendance.approve', 'Approve Attendance', 'Can approve late/early out', '2025-12-31 14:14:57'),
	(24, 'requests', 'requests.view', 'View Requests', 'Can view leave/overtime requests', '2025-12-31 14:14:57'),
	(25, 'requests', 'requests.approve', 'Approve Requests', 'Can approve/reject requests', '2025-12-31 14:14:57'),
	(26, 'requests', 'requests.create', 'Create Request', 'Can submit requests', '2025-12-31 14:14:57'),
	(27, 'payroll', 'payroll.view', 'View Payroll', 'Can view salary information', '2025-12-31 14:14:57'),
	(28, 'payroll', 'payroll.edit', 'Edit Payroll', 'Can edit salary components', '2025-12-31 14:14:57'),
	(29, 'payroll', 'payroll.process', 'Process Payroll', 'Can generate payslips', '2025-12-31 14:14:57'),
	(30, 'users', 'users.view', 'View Users', 'Can view user list', '2025-12-31 14:14:57'),
	(31, 'users', 'users.create', 'Create User', 'Can create new users', '2025-12-31 14:14:57'),
	(32, 'users', 'users.edit', 'Edit User', 'Can edit user information', '2025-12-31 14:14:57'),
	(33, 'users', 'users.delete', 'Delete User', 'Can delete users', '2025-12-31 14:14:57'),
	(34, 'users', 'users.permissions', 'Manage Permissions', 'Can assign permissions to users', '2025-12-31 14:14:57'),
	(35, 'reports', 'reports.view', 'View Reports', 'Can view all reports', '2025-12-31 14:14:57'),
	(36, 'reports', 'reports.export', 'Export Reports', 'Can export reports to PDF/Excel', '2025-12-31 14:14:57'),
	(37, 'payslips', 'payslips.view_all', 'Lihat Semua Slip Gaji', 'Melihat slip gaji semua karyawan', '2026-01-05 20:34:30'),
	(38, 'payslips', 'payslips.view_own', 'Lihat Slip Gaji Sendiri', 'Melihat slip gaji sendiri', '2026-01-05 20:34:30'),
	(39, 'payslips', 'payslips.generate', 'Generate Slip Gaji', 'Generate slip gaji karyawan', '2026-01-05 20:34:30'),
	(40, 'payslips', 'payslips.print', 'Cetak Slip Gaji', 'Cetak slip gaji PDF', '2026-01-05 20:34:30'),
	(41, 'payslips', 'payslips.export', 'Export Slip Gaji', 'Export slip gaji ke Excel', '2026-01-05 20:34:30'),
	(42, 'payslips', 'payslips.send', 'Kirim Slip Gaji', 'Kirim slip gaji via email', '2026-01-05 20:34:30'),
	(43, 'announcements', 'announcements.view', 'Lihat Pengumuman', 'Melihat daftar pengumuman', '2026-01-05 20:34:30'),
	(44, 'announcements', 'announcements.create', 'Buat Pengumuman', 'Membuat pengumuman baru', '2026-01-05 20:34:30'),
	(45, 'announcements', 'announcements.edit', 'Edit Pengumuman', 'Mengubah pengumuman', '2026-01-05 20:34:30'),
	(46, 'announcements', 'announcements.delete', 'Hapus Pengumuman', 'Menghapus pengumuman', '2026-01-05 20:34:30'),
	(47, 'announcements', 'announcements.publish', 'Publish Pengumuman', 'Mempublikasikan pengumuman', '2026-01-05 20:34:30'),
	(75, 'kpi', 'kpi.view', 'View KPI', 'Dapat melihat KPI dashboard', '2026-01-14 17:03:00'),
	(76, 'kpi', 'kpi.view_all', 'View All KPI', 'Dapat melihat KPI semua karyawan', '2026-01-14 17:03:00'),
	(77, 'kpi', 'kpi.view_department', 'View Department KPI', 'Dapat melihat KPI karyawan di department sendiri', '2026-01-14 17:03:00'),
	(78, 'kpi', 'kpi.view_own', 'View Own KPI', 'Dapat melihat KPI sendiri', '2026-01-14 17:03:00'),
	(79, 'kpi', 'kpi.calculate', 'Calculate KPI', 'Dapat menghitung/recalculate KPI', '2026-01-14 17:03:00'),
	(80, 'kpi', 'kpi.export', 'Export KPI Report', 'Dapat export laporan KPI', '2026-01-14 17:03:00'),
	(81, 'tasks', 'tasks.view', 'View Tasks', 'Dapat melihat tasks', '2026-01-14 17:03:00'),
	(82, 'tasks', 'tasks.view_all', 'View All Tasks', 'Dapat melihat semua tasks', '2026-01-14 17:03:00'),
	(83, 'tasks', 'tasks.view_department', 'View Department Tasks', 'Dapat melihat tasks di department sendiri', '2026-01-14 17:03:00'),
	(84, 'tasks', 'tasks.view_own', 'View Own Tasks', 'Dapat melihat tasks sendiri', '2026-01-14 17:03:00'),
	(85, 'tasks', 'tasks.create', 'Create Task', 'Dapat membuat task baru', '2026-01-14 17:03:00'),
	(86, 'tasks', 'tasks.assign', 'Assign Task', 'Dapat assign task ke orang lain', '2026-01-14 17:03:00'),
	(87, 'tasks', 'tasks.edit', 'Edit Task', 'Dapat edit task', '2026-01-14 17:03:00'),
	(88, 'tasks', 'tasks.delete', 'Delete Task', 'Dapat hapus task', '2026-01-14 17:03:00'),
	(89, 'tasks', 'tasks.approve', 'Approve Task', 'Dapat approve/reject task submission', '2026-01-14 17:03:00'),
	(90, 'tasks', 'tasks.comment', 'Comment on Task', 'Dapat comment di task', '2026-01-14 17:03:00'),
	(91, 'tasks', 'tasks.log_time', 'Log Work Time', 'Dapat log working hours', '2026-01-14 17:03:00'),
	(98, 'Organization', 'organization.view', 'View Organization', 'Lihat Struktur Org', '2026-02-05 04:01:06'),
	(99, 'Employees', 'contracts.view', 'View Contracts', 'Lihat Kontrak', '2026-02-05 04:01:06'),
	(100, 'Shift', 'shifts.view', 'View Shifts', 'Lihat Shift', '2026-02-05 04:01:06'),
	(101, 'System', 'settings.view', 'View Settings', 'Lihat Pengaturan', '2026-02-05 04:01:06'),
	(102, 'System', 'roles.manage', 'Manage Roles', 'Atur Role', '2026-02-05 04:01:06');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
