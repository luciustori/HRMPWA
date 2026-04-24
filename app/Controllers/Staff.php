<?php
// File: app/Controllers/Staff.php

class Staff extends Controller {
    protected $db;

    public function __construct() {
        // Cek Login Sederhana
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
        $this->db = new Database;
    }

    // Method ini dipanggil saat buka /staff/dashboard
    public function dashboard() {
        $userId = $_SESSION['user_id'];
        
        // Ambil Employee ID
        if (!isset($_SESSION['employee_id'])) {
            $user = $this->db->fetchOne("SELECT employee_id FROM users WHERE id = ?", [$userId]);
            $empId = $user['employee_id'] ?? 0;
        } else {
            $empId = $_SESSION['employee_id'];
        }

        // 1. DATA KPI (Skor)
        $taskStats = $this->db->fetchOne("
            SELECT COUNT(*) as total,
            SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed
            FROM tasks WHERE assigned_to = ?
        ", [$userId]);

        // Cek tabel attendance (Try Catch biar aman)
        try {
            $attendStats = $this->db->fetchOne("
                SELECT COUNT(*) as present
                FROM attendance 
                WHERE employee_id = ? AND MONTH(date) = MONTH(CURRENT_DATE())
            ", [$empId]);
        } catch (Exception $e) {
            $attendStats = ['present' => 0];
        }

        $score = ($taskStats['total'] > 0) ? round(($taskStats['completed'] / $taskStats['total']) * 100) : 0;

        // 2. DATA TASKS
        $tasks = $this->db->fetchAll("
            SELECT t.*, tc.category_name, tc.color as cat_color
            FROM tasks t
            LEFT JOIN task_categories tc ON t.category_id = tc.id
            WHERE t.assigned_to = ? AND t.status != 'completed'
            ORDER BY t.due_date ASC LIMIT 4
        ", [$userId]);

        // 3. DATA ANNOUNCEMENTS
        try {
            $announcements = $this->db->fetchAll("
                SELECT * FROM announcements ORDER BY created_at DESC LIMIT 3
            ");
        } catch (Exception $e) {
            $announcements = [];
        }

        $data = [
            'user_name'     => $_SESSION['full_name'] ?? 'Staff',
            'kpi_score'     => $score,
            'attendance'    => $attendStats,
            'tasks'         => $tasks,
            'announcements' => $announcements,
            'title'         => 'Staff Dashboard'
        ];

        // Panggil View yang sudah kita buat sebelumnya
        // Pastikan file view tetap ada di: app/Views/staff/dashboard/index.php
        $this->view('staff/dashboard/index', $data); 
    }
}