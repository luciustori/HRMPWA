<?php
// app/Controllers/PWA/DashboardController.php

namespace App\Controllers\PWA;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Permission;
use App\Models\Shift;

class DashboardController {
    public function index() {
        // Ambil user dari session
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: /pwa/login');
            exit;
        }

        // Ambil data karyawan
        $empModel = new Employee();
        $employee = $empModel->db->prepare(
            "SELECT e.*, d.name as department_name, l.name as level_name, s.start_time, s.end_time
             FROM employees e
             LEFT JOIN departments d ON e.department_id = d.id
             LEFT JOIN employee_levels l ON e.level_id = l.id
             LEFT JOIN employee_shifts es ON e.id = es.employee_id AND es.shift_date = CURDATE()
             LEFT JOIN shifts s ON es.shift_id = s.id
             WHERE e.user_id = :user_id LIMIT 1"
        );
        $empModel->db->prepare(
            "SELECT e.*, d.name as department_name, l.name as level_name, s.start_time, s.end_time
             FROM employees e
             LEFT JOIN departments d ON e.department_id = d.id
             LEFT JOIN employee_levels l ON e.level_id = l.id
             LEFT JOIN employee_shifts es ON e.id = es.employee_id AND es.shift_date = CURDATE()
             LEFT JOIN shifts s ON es.shift_id = s.id
             WHERE e.user_id = :user_id LIMIT 1"
        )->execute([':user_id' => $userId]);

        $stmt = $empModel->db->prepare(
            "SELECT e.*, d.name as department_name, l.name as level_name, s.start_time, s.end_time
             FROM employees e
             LEFT JOIN departments d ON e.department_id = d.id
             LEFT JOIN employee_levels l ON e.level_id = l.id
             LEFT JOIN employee_shifts es ON e.id = es.employee_id AND es.shift_date = CURDATE()
             LEFT JOIN shifts s ON es.shift_id = s.id
             WHERE e.user_id = :user_id LIMIT 1"
        );
        $stmt->execute([':user_id' => $userId]);
        $employee = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$employee) {
            header('Location: /pwa/login');
            exit;
        }

        // Data today
        $attendanceModel = new Attendance();
        $today_attendance = $attendanceModel->getTodayStatus($employee['id']);
        
        $permissionModel = new Permission();
        $pending_requests = $permissionModel->countPending($employee['id']);

        // Stats bulan ini
        $now = new \DateTime();
        $month_stats = $attendanceModel->getStats($employee['id'], $now->format('Y'), $now->format('m'));

        $page_title = 'Dashboard';
        $page_content = '../app/Views/PWA/pages/dashboard.php';
        require '../app/Views/PWA/layouts/main.php';
    }
}
?>
