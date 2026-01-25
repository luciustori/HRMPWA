<?php
// File: app/Controllers/Admin/Dashboard.php

class Dashboard extends Controller {
    
    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
        // --- DUMMY DATA STATIS (Nanti diganti query real) ---
        $dummy_stats = [
            'total_emp' => 22,
            'present' => 18,
            'late' => 3,
            'leave' => 1,
            'requests' => 5
        ];

        $dummy_approvals = [
            ['name' => 'Rian Mei Hermawan', 'type' => 'Cuti Tahunan', 'date' => '26 Jan - 28 Jan', 'status' => 'Pending', 'avatar' => 'RM'],
            ['name' => 'Anggraini Retno', 'type' => 'Izin Sakit', 'date' => '25 Jan', 'status' => 'Pending', 'avatar' => 'AR'],
            ['name' => 'Henry Cahyono', 'type' => 'Lembur', 'date' => 'Kemarin', 'status' => 'Reviewed', 'avatar' => 'HC'],
        ];

        $dummy_updates = [
            ['title' => 'Maintenance Server', 'time' => '2 jam lalu', 'desc' => 'Sistem akan down sebentar pada jam 12 malam nanti.', 'color' => 'red'],
            ['title' => 'Kebijakan Baru', 'time' => '1 hari lalu', 'desc' => 'Update aturan lembur terbaru sudah diterbitkan.', 'color' => 'blue'],
        ];

        $dummy_kpi = [
            ['title' => 'Penyelesaian Project HRM', 'progress' => 75, 'color' => 'bg-purple-500'],
            ['title' => 'Target Rekrutmen Q1', 'progress' => 40, 'color' => 'bg-orange-500'],
            ['title' => 'Efisiensi Anggaran', 'progress' => 90, 'color' => 'bg-emerald-500'],
        ];

        // ----------------------------------------------------

        $data = [
            'title' => 'Dashboard - AbsenPWA',
            'content_view' => 'admin/dashboard/index',
            'stats' => $dummy_stats,
            'approvals' => $dummy_approvals,
            'updates' => $dummy_updates,
            'kpi' => $dummy_kpi
        ];

        $this->view('admin/layouts/admin-layout', $data);
    }
}