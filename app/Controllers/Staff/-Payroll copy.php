<?php
// File: app/Controllers/Staff/Payroll.php

class Payroll extends Controller {

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
        $db = new Database;
        $employeeId = $_SESSION['employee_id'];
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');

        // 1. AMBIL DATA KARYAWAN (FIXED: Hapus JOIN positions)
        // Kita ambil kolom 'position' dan 'salary' langsung dari tabel employees
        $db->query("SELECT 
                        e.id,
                        e.first_name, 
                        e.last_name, 
                        e.employee_number, 
                        e.position,  
                        e.salary as base_salary, 
                        d.department_name 
                    FROM employees e
                    LEFT JOIN departments d ON e.department_id = d.id
                    WHERE e.id = :id");
        $db->bind(':id', $employeeId);
        $emp = $db->single();

        // Safety jika data karyawan tidak ditemukan (misal Super Admin belum setup profile lengkap)
        if (!$emp) {
            $emp = [
                'first_name' => 'Super', 'last_name' => 'Admin', 
                'employee_number' => 'A-001', 'position' => 'Administrator',
                'base_salary' => 0, 'department_name' => 'IT Dept'
            ];
        }

        // 2. AMBIL HISTORY PAYROLL (Dari tabel payroll_transactions)
        // Gunakan LEFT JOIN agar tidak error jika tabel periods kosong
        $history = [];
        try {
            $db->query("SELECT 
                            pt.id,
                            pt.net_salary,
                            pt.paid_at as payment_date,
                            pt.status,
                            pp.period_month,
                            pp.period_year,
                            pp.period_name
                        FROM payroll_transactions pt
                        JOIN payroll_periods pp ON pt.period_id = pp.id
                        WHERE pt.employee_id = :eid AND pp.period_year = :y 
                        ORDER BY pp.period_month DESC");
            $db->bind(':eid', $employeeId);
            $db->bind(':y', $year);
            $history = $db->resultSet();
        } catch (Exception $e) {
            // Ignore error jika tabel belum ada
        }

        // 3. HITUNG ESTIMASI GAJI BULAN INI
        $currentMonth = date('n');
        $hasCurrentPayroll = false;
        if (!empty($history)) {
            foreach ($history as $h) {
                if ($h['period_month'] == $currentMonth) {
                    $hasCurrentPayroll = true;
                    break;
                }
            }
        }

        $estimate = null;
        if (!$hasCurrentPayroll && $year == date('Y')) {
            // Cek Potongan Telat dari Attendance
            $totalLate = 0;
            try {
                $db->query("SELECT COUNT(*) as total_late FROM attendance_records 
                            WHERE employee_id = :eid 
                            AND MONTH(attendance_date) = :m 
                            AND YEAR(attendance_date) = :y 
                            AND is_late = 1");
                $db->bind(':eid', $employeeId);
                $db->bind(':m', $currentMonth);
                $db->bind(':y', $year);
                $res = $db->single();
                $totalLate = $res['total_late'] ?? 0;
            } catch (Exception $e) {}
            
            $penalty = $totalLate * 50000; // Denda 50rb per telat
            $base = $emp['base_salary'] ?? 0;
            $allowance = 0; 
            
            $estimate = [
                'period_month' => $currentMonth,
                'period_year' => $year,
                'basic_salary' => $base,
                'total_allowance' => $allowance,
                'total_deduction' => $penalty,
                'net_salary' => $base + $allowance - $penalty,
                'status' => 'estimasi'
            ];
        }

        $data = [
            'title' => 'Slip Gaji',
            'employee' => $emp,
            'history' => $history,
            'estimate' => $estimate,
            'filter_year' => $year
        ];

        $data['content_view'] = 'staff/payroll/index';
        $this->view('staff/layouts/staff-layout', $data);
    }

    // Detail Slip Gaji
    public function detail($id) {
        $db = new Database;
        $employeeId = $_SESSION['employee_id'];

        // FIXED: Hapus JOIN positions
        $db->query("SELECT 
                        pt.*,
                        pp.period_month,
                        pp.period_year,
                        pp.period_name,
                        e.first_name, 
                        e.last_name, 
                        e.employee_number, 
                        e.position, 
                        d.department_name
                    FROM payroll_transactions pt
                    JOIN payroll_periods pp ON pt.period_id = pp.id
                    JOIN employees e ON pt.employee_id = e.id
                    LEFT JOIN departments d ON e.department_id = d.id
                    WHERE pt.id = :id AND pt.employee_id = :eid");
        $db->bind(':id', $id);
        $db->bind(':eid', $employeeId);
        $payroll = $db->single();

        if (!$payroll) {
            header('Location: ' . BASEURL . '/staff/payroll');
            exit;
        }

        // Ambil Rincian
        $details = [];
        try {
            $db->query("SELECT * FROM payroll_transaction_details WHERE transaction_id = :tid");
            $db->bind(':tid', $id);
            $details = $db->resultSet();
        } catch (Exception $e) {}

        $data = [
            'title' => 'Detail Slip Gaji',
            'payroll' => $payroll,
            'details' => $details,
            'content_view' => 'staff/payroll/detail'
        ];
        $this->view('staff/layouts/staff-layout', $data);
    }
}