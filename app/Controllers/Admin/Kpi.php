<?php
// File: app/Controllers/Admin/Kpi.php

class Kpi extends Controller {
    private $db;

    public function __construct() {
        parent::__construct(); 
        if (!session_id()) session_start();
        
        if (!isset($_SESSION['user_id'])) { 
            header('Location: ' . BASEURL . '/Admin/LoginController'); 
            exit; 
        }
        
        $role = strtolower($_SESSION['role'] ?? '');
        if ($role !== 'admin' && $role !== 'super_admin') {
            header('Location: ' . BASEURL . '/staff/dashboard');
            exit;
        }

        $this->db = new Database;
        require_once '../app/Models/KpiCalculator.php';
    }

    /**
     * DASHBOARD UTAMA (EXECUTIVE OVERVIEW)
     * Sekarang mendukung Filter Bulan & Tahun
     */
    public function index() {
        // 1. Tangkap Filter
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
        $month = isset($_GET['month']) ? $_GET['month'] : 'all'; // Default 'all' (Setahun)

        // Buat string filter SQL tambahan jika bulan dipilih
        $month_sql = ($month !== 'all') ? " AND MONTH(t.created_at) = :m " : "";
        $month_sql_simple = ($month !== 'all') ? " AND MONTH(created_at) = :m " : "";

        // A. GLOBAL STATS (Card Atas)
        $sql_global = "SELECT 
            COUNT(id) as total_tasks,
            SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_tasks,
            AVG(CASE WHEN status = 'completed' THEN kpi_score_final ELSE NULL END) as avg_score
        FROM tasks 
        WHERE YEAR(created_at) = :y $month_sql_simple";

        $this->db->query($sql_global);
        $this->db->bind(':y', $year);
        if ($month !== 'all') $this->db->bind(':m', $month);
        $global = $this->db->single();

        $company_score = round($global['avg_score'] ?? 0, 1);
        
        // Grading Perusahaan
        $grade = 'D'; $theme = 'red';
        if($company_score >= 90) { $grade = 'A+'; $theme = 'emerald'; }
        elseif($company_score >= 80) { $grade = 'A'; $theme = 'green'; }
        elseif($company_score >= 70) { $grade = 'B'; $theme = 'blue'; }
        elseif($company_score >= 60) { $grade = 'C'; $theme = 'yellow'; }

        // B. CHART DATA: TREN BULANAN (Tetap tampilkan setahun penuh agar terlihat trennya)
        $monthly_performance = array_fill(1, 12, 0);
        $this->db->query("SELECT MONTH(created_at) as month, AVG(kpi_score_final) as score 
                          FROM tasks 
                          WHERE status = 'completed' AND YEAR(created_at) = :y 
                          GROUP BY MONTH(created_at)");
        $this->db->bind(':y', $year);
        $rows = $this->db->resultSet();
        
        foreach($rows as $r) {
            $monthly_performance[$r['month']] = round($r['score'] ?? 0, 1);
        }

        // C. CHART DATA: PERFORMA DEPARTEMEN (Terpengaruh Filter Bulan)
        $sql_dept = "SELECT d.department_name, AVG(t.kpi_score_final) as avg_score
                          FROM departments d
                          JOIN employees e ON e.department_id = d.id
                          JOIN tasks t ON t.assigned_to = e.id
                          WHERE t.status = 'completed' AND YEAR(t.created_at) = :y $month_sql
                          GROUP BY d.id
                          ORDER BY avg_score DESC LIMIT 5";
        
        $this->db->query($sql_dept);
        $this->db->bind(':y', $year);
        if ($month !== 'all') $this->db->bind(':m', $month);
        $dept_stats = $this->db->resultSet();

        // D. TOP EMPLOYEES (Terpengaruh Filter Bulan)
        $sql_top = "SELECT e.id, e.first_name, e.last_name, e.profile_photo_path, e.employee_number, 
                                 d.department_name,
                                 COUNT(t.id) as total_done,
                                 AVG(t.kpi_score_final) as quality
                          FROM employees e
                          JOIN tasks t ON t.assigned_to = e.id
                          LEFT JOIN departments d ON e.department_id = d.id
                          WHERE t.status = 'completed' AND YEAR(t.created_at) = :y $month_sql
                          GROUP BY e.id
                          ORDER BY quality DESC, total_done DESC
                          LIMIT 5";

        $this->db->query($sql_top);
        $this->db->bind(':y', $year);
        if ($month !== 'all') $this->db->bind(':m', $month);
        $top_employees = $this->db->resultSet();

        $data = [
            'title' => 'Executive KPI Dashboard',
            'filter_year'  => $year,
            'filter_month' => $month,
            'global' => $global,
            'company_score' => $company_score,
            'grade' => $grade,
            'theme' => $theme,
            'chart_monthly' => array_values($monthly_performance),
            'chart_dept_labels' => array_column($dept_stats, 'department_name'),
            'chart_dept_scores' => array_column($dept_stats, 'avg_score'),
            'top_employees' => $top_employees,
            'content_view' => 'admin/kpi/index'
        ];

        $this->view('admin/layouts/admin-layout', $data);
    }

    /**
     * DETAIL KPI STAFF (RAPORT)
     */
    public function detail($employee_id) {
        $month = isset($_GET['month']) ? $_GET['month'] : date('m');
        $year  = isset($_GET['year']) ? $_GET['year'] : date('Y');

        $this->db->query("SELECT e.*, d.department_name FROM employees e LEFT JOIN departments d ON e.department_id = d.id WHERE e.id = :id");
        $this->db->bind(':id', $employee_id);
        $employee = $this->db->single();

        if (!$employee) { 
            header('Location: ' . BASEURL . '/admin/kpi'); 
            exit; 
        }

        $calculator = new KpiCalculator();
        $metrics = $calculator->calculate($employee_id, $month, $year);

        $data = [
            'title'        => 'Staff Report Card',
            'content_view' => 'admin/kpi/detail',
            'employee'     => $employee,
            'period'       => date('F Y', mktime(0, 0, 0, $month, 1, $year)),
            'metrics'      => $metrics
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    /**
     * HALAMAN LAPORAN BULANAN (TABEL LENGKAP)
     */
    public function report() {
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
        $month = isset($_GET['month']) ? $_GET['month'] : date('n');
        $dept_id = isset($_GET['dept_id']) ? $_GET['dept_id'] : 'all';

        // Query ke kpi_summary agar cepat
        $sql = "SELECT 
                    e.id, e.employee_number, e.first_name, e.last_name, e.profile_photo_path,
                    d.department_name,
                    COALESCE(ks.total_tasks_assigned, 0) as total_tasks,
                    COALESCE(ks.total_tasks_completed, 0) as total_done,
                    COALESCE(ks.kpi_score, 0) as kpi_score
                FROM employees e
                LEFT JOIN departments d ON e.department_id = d.id
                LEFT JOIN kpi_summary ks ON e.id = ks.employee_id AND ks.year = :year AND ks.month = :month
                WHERE e.is_active = 1 ";

        if ($dept_id !== 'all') {
            $sql .= " AND e.department_id = :dept_id ";
        }

        $sql .= " ORDER BY kpi_score DESC, total_done DESC";

        $this->db->query($sql);
        $this->db->bind(':year', $year);
        $this->db->bind(':month', $month);
        
        if ($dept_id !== 'all') {
            $this->db->bind(':dept_id', $dept_id);
        }
        
        $employees = $this->db->resultSet();

        $this->db->query("SELECT id, department_name FROM departments ORDER BY department_name ASC");
        $departments = $this->db->resultSet();

        $data = [
            'title' => 'KPI Full Report',
            'employees' => $employees,
            'departments' => $departments,
            'filter_year' => $year,
            'filter_month' => $month,
            'filter_dept' => $dept_id,
            'content_view' => 'admin/kpi/report'
        ];

        $this->view('admin/layouts/admin-layout', $data);
    }
}