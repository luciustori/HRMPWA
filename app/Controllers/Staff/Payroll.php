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

        // 1. DATA KARYAWAN
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

        // 2. HISTORY PAYROLL
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

        // 3. ESTIMASI (DRAFT)
        $currentMonth = date('n');
        $hasCurrentPayroll = false;
        if($history) {
            foreach ($history as $h) {
                if ($h['period_month'] == $currentMonth) { $hasCurrentPayroll = true; break; }
            }
        }

        $estimate = null;
        if (!$hasCurrentPayroll && $year == date('Y')) {
            $base = $emp['base_salary'] ?? 0;
            $estimate = [
                'period_month' => $currentMonth,
                'period_year' => $year,
                'basic_salary' => $base,
                'total_allowance' => 0, 
                'total_deduction' => 0, 
                'net_salary' => $base,
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

    // --- DETAIL SLIP (YANG PERLU DIPERBAIKI LOGONYA) ---
    public function detail($id) {
        $db = new Database;
        $employeeId = $_SESSION['employee_id'];

        // 1. Ambil Data Slip & Karyawan
        $db->query("SELECT 
                        pt.*,
                        pp.period_month,
                        pp.period_year,
                        pp.period_name,
                        e.employee_number,
                        e.first_name, 
                        e.last_name, 
                        e.company_id, -- Penting untuk ambil logo
                        e.position as position_name, 
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

        // 2. Ambil Rincian Komponen
        $db->query("SELECT * FROM payroll_transaction_details WHERE transaction_id = :tid ORDER BY component_type, id");
        $db->bind(':tid', $id);
        $details = $db->resultSet();

        // 3. AMBIL DATA PERUSAHAAN (LOGO & NAMA) DARI DATABASE
        // Default Kosong (Bukan MeZone Corp)
        $company = [
            'company_name' => 'PERUSAHAAN',
            'company_address' => '-',
            'company_phone' => '',
            'company_logo' => ''
        ];

        try {
            // Ambil berdasarkan company_id karyawan
            if (!empty($payroll['company_id'])) {
                $db->query("SELECT * FROM companies WHERE id = :cid LIMIT 1");
                $db->bind(':cid', $payroll['company_id']);
                $res = $db->single();
            } else {
                // Fallback ambil perusahaan pertama
                $db->query("SELECT * FROM companies LIMIT 1");
                $res = $db->single();
            }

            if($res) {
                // Mapping kolom DB ke variable view (handle beda nama kolom)
                $company['company_name']    = $res['name'] ?? $res['company_name'] ?? 'PERUSAHAAN';
                $company['company_address'] = $res['address'] ?? $res['company_address'] ?? '-';
                $company['company_phone']   = $res['phone'] ?? $res['company_phone'] ?? '';
                $company['company_logo']    = $res['logo'] ?? $res['company_logo'] ?? '';
            }
        } catch(Exception $e) {
            // Jika tabel companies tidak ada, biarkan default
        }

        $data = [
            'title' => 'Detail Slip Gaji',
            'payroll' => $payroll,
            'details' => $details,
            'company' => $company, // Data Company Dinamis
            'content_view' => 'staff/payroll/detail'
        ];
        $this->view('staff/layouts/staff-layout', $data);
    }
}