<?php
// File: app/Controllers/Admin/KpiGenerator.php

class KpiGenerator extends Controller {

    private $db;

    public function __construct() {
        parent::__construct();
        if (!session_id()) session_start();
        
        // 1. Cek Login
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/Admin/LoginController');
            exit;
        }

        // 2. Cek Role (Admin Only)
        $role = strtolower($_SESSION['role'] ?? '');
        if ($role !== 'admin' && $role !== 'super_admin') {
            header('Location: ' . BASEURL . '/staff/dashboard');
            exit;
        }
        
        $this->db = new Database;
        
        // Load Model Calculator
        require_once '../app/Models/KpiCalculator.php';
    }

    // Tampilkan Halaman Generator
    public function index() {
        $data = [
            'title' => 'Generate Monthly KPI Report',
            'content_view' => 'admin/kpi/generator'
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    // Proses Generate (Dipanggil via POST)
    public function process() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $month = $_POST['month'] ?? date('m');
            $year  = $_POST['year'] ?? date('Y');

            // 1. Ambil Semua Karyawan Aktif
            $this->db->query("SELECT id, first_name FROM employees WHERE is_active = 1");
            $employees = $this->db->resultSet();

            $calculator = new KpiCalculator();
            $count = 0;
            $errors = 0;

            // 2. Loop & Generate Satu per Satu
            foreach ($employees as $emp) {
                // Panggil fungsi syncToDatabase di Calculator
                if($calculator->syncToDatabase($emp['id'], $month, $year)) {
                    $count++;
                } else {
                    $errors++;
                }
            }

            // 3. Selesai
            // Anda bisa tambahkan Flash Message di sini jika ada library-nya
            // Contoh sederhana: Redirect dengan parameter sukses
            header('Location: ' . BASEURL . '/admin/kpi/report?year=' . $year . '&generated=' . $count); 
            exit;
        }
    }
}