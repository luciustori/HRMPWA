<?php
// File: app/Controllers/Admin/Reports.php

class Reports extends Controller {

    private $db;

    public function __construct() {
        parent::__construct();
        if (!session_id()) session_start();

        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/Auth');
            exit;
        }
        
        $allowed = ['super_admin', 'admin', 'hr_manager', 'manager'];
        if (!in_array($_SESSION['role'], $allowed)) {
            header('Location: ' . BASEURL . '/Staff/Dashboard');
            exit;
        }

        $this->db = new Database;
    }

    public function index() {
        $this->monthly();
    }

    // --- LAPORAN BULANAN (PAYROLL CUT-OFF) ---
    public function monthly() {
        $month_year = $_GET['month_year'] ?? date('Y-m');
        $dept_id = $_GET['dept_id'] ?? 'all';
        list($year, $month) = explode('-', $month_year);
        
        $end_date   = "$year-$month-24"; 
        $start_date = date('Y-m-d', strtotime("$year-$month-25 -1 month")); 
        $period_text = date('d M Y', strtotime($start_date)) . " - " . date('d M Y', strtotime($end_date));

        $sql = "SELECT 
                    e.id as employee_id,
                    e.employee_number,
                    e.full_name,
                    d.department_name,
                    
                    (SELECT COUNT(*) FROM shift_assignments sa WHERE sa.employee_id = e.id AND sa.assignment_date BETWEEN '$start_date' AND '$end_date') as total_days,
                    (SELECT COALESCE(SUM(CASE WHEN sa.is_mod = 1 THEN 1 ELSE 0 END), 0) FROM shift_assignments sa WHERE sa.employee_id = e.id AND sa.assignment_date BETWEEN '$start_date' AND '$end_date') as total_mod,

                    (SELECT COALESCE(SUM(CASE WHEN ar.status IN ('present', 'late', 'early_out') THEN 1 ELSE 0 END), 0) FROM attendance_records ar WHERE ar.employee_id = e.id AND ar.attendance_date BETWEEN '$start_date' AND '$end_date') as present_days,
                    (SELECT COALESCE(SUM(CASE WHEN ar.is_late = 1 THEN 1 ELSE 0 END), 0) FROM attendance_records ar WHERE ar.employee_id = e.id AND ar.attendance_date BETWEEN '$start_date' AND '$end_date') as late_days,
                    (SELECT COALESCE(SUM(ar.late_duration_minutes), 0) FROM attendance_records ar WHERE ar.employee_id = e.id AND ar.attendance_date BETWEEN '$start_date' AND '$end_date') as total_late_minutes,
                    (SELECT COALESCE(SUM(CASE WHEN ar.is_early_out = 1 THEN 1 ELSE 0 END), 0) FROM attendance_records ar WHERE ar.employee_id = e.id AND ar.attendance_date BETWEEN '$start_date' AND '$end_date') as early_out_days,
                    (SELECT COALESCE(SUM(CASE WHEN ar.status = 'absent' THEN 1 ELSE 0 END), 0) FROM attendance_records ar WHERE ar.employee_id = e.id AND ar.attendance_date BETWEEN '$start_date' AND '$end_date') as total_absent,
                    
                    (SELECT COALESCE(SUM(ar.work_duration_minutes), 0) FROM attendance_records ar WHERE ar.employee_id = e.id AND ar.attendance_date BETWEEN '$start_date' AND '$end_date') as total_work_minutes,
                    (SELECT COALESCE(SUM(ar.overtime_minutes), 0) FROM attendance_records ar WHERE ar.employee_id = e.id AND ar.attendance_date BETWEEN '$start_date' AND '$end_date') as total_overtime_minutes,
                    
                    (SELECT COALESCE(SUM(lr.total_days), 0) FROM leave_requests lr JOIN leave_types lt ON lr.leave_type_id = lt.id WHERE lr.employee_id = e.id AND lr.status = 'approved' AND lt.leave_code IN ('ANNUAL', 'CUTI_TAHUNAN') AND lr.start_date BETWEEN '$start_date' AND '$end_date') as total_cuti,
                    
                    (SELECT COALESCE(SUM(lr.total_days), 0) FROM leave_requests lr JOIN leave_types lt ON lr.leave_type_id = lt.id WHERE lr.employee_id = e.id AND lr.status = 'approved' AND lt.leave_code NOT IN ('ANNUAL', 'CUTI_TAHUNAN', 'DL', 'DINAS_LUAR') AND lr.start_date BETWEEN '$start_date' AND '$end_date') as total_ijin_sakit,
                    
                    (SELECT COALESCE(SUM(lr.total_days), 0) FROM leave_requests lr JOIN leave_types lt ON lr.leave_type_id = lt.id WHERE lr.employee_id = e.id AND lr.status = 'approved' AND lt.leave_code IN ('DL', 'DINAS_LUAR') AND lr.start_date BETWEEN '$start_date' AND '$end_date') as total_dl,
                    
                    (SELECT COALESCE(SUM(btr.total_days), 0) FROM business_trip_requests btr WHERE btr.employee_id = e.id AND btr.status = 'approved' AND btr.start_date BETWEEN '$start_date' AND '$end_date') as total_sppd,

                    -- DATA LEMBUR DIAMBIL DI SINI
                    (SELECT COUNT(orq.id) FROM overtime_requests orq WHERE orq.employee_id = e.id AND orq.status = 'approved' AND orq.overtime_date BETWEEN '$start_date' AND '$end_date') as total_lembur_requests

                FROM employees e
                LEFT JOIN departments d ON e.department_id = d.id
                WHERE e.is_active = 1 ";

        if ($dept_id !== 'all') {
            $sql .= " AND e.department_id = :dept";
        }
        $sql .= " ORDER BY d.department_name ASC, e.full_name ASC";

        $this->db->query($sql);
        
        if ($dept_id !== 'all') {
            $this->db->bind(':dept', $dept_id);
        }
        
        $reports = $this->db->resultSet();

        $this->db->query("SELECT * FROM departments ORDER BY department_name ASC");
        $departments = $this->db->resultSet();

        $data = [
            'title' => 'Laporan Bulanan',
            'content_view' => 'admin/reports/monthly',
            'reports' => $reports,
            'departments' => $departments,
            'month_year' => $month_year, 
            'period_text' => $period_text, 
            'start_date' => $start_date,
            'end_date' => $end_date,
            'selected_dept' => $dept_id
        ];
        // ... baris akhir function monthly() sebelum render view

            if (isset($_GET['export']) && $_GET['export'] === 'print') {
                extract($data); // <--- TAMBAHKAN BARIS INI
                require_once '../app/Views/admin/reports/print_monthly.php';
                exit;
            }

            // Render view normal dengan layout
            $data['content_view'] = 'admin/reports/monthly';
            $this->view('admin/layouts/admin-layout', $data);
    }

    // --- LAPORAN TAHUNAN (REKAP ACCORDION 12 BULAN) ---
    public function annually() {
        $year = $_GET['year'] ?? date('Y');
        $dept_id = $_GET['dept_id'] ?? 'all';

        $emp_sql = "SELECT e.id, e.first_name, e.last_name, e.employee_number, e.annual_leave_balance, d.department_name
                    FROM employees e
                    LEFT JOIN departments d ON e.department_id = d.id
                    WHERE e.is_active = 1 ";
        if ($dept_id !== 'all') $emp_sql .= " AND e.department_id = :dept";
        $emp_sql .= " ORDER BY d.department_name ASC, e.first_name ASC";
        
        $this->db->query($emp_sql);
        if ($dept_id !== 'all') $this->db->bind(':dept', $dept_id);
        $employees = $this->db->resultSet();

        $att_sql = "SELECT 
            sa.employee_id, 
            MONTH(sa.assignment_date) as month,
            COUNT(sa.id) as jadwal,
            SUM(CASE WHEN ar.status IN ('present', 'late', 'early_out') THEN 1 ELSE 0 END) as hadir,
            SUM(CASE WHEN ar.is_late = 1 THEN 1 ELSE 0 END) as telat,
            SUM(CASE WHEN ar.is_early_out = 1 THEN 1 ELSE 0 END) as plg_awal,
            SUM(CASE WHEN ar.status = 'absent' THEN 1 ELSE 0 END) as alpha
            FROM shift_assignments sa
            LEFT JOIN attendance_records ar ON sa.employee_id = ar.employee_id AND sa.assignment_date = ar.attendance_date
            WHERE YEAR(sa.assignment_date) = :y
            GROUP BY sa.employee_id, MONTH(sa.assignment_date)";
        $this->db->query($att_sql);
        $this->db->bind(':y', $year);
        $attendance_data = $this->db->resultSet();

        $leave_sql = "SELECT 
            lr.employee_id, 
            MONTH(lr.start_date) as month,
            SUM(CASE WHEN lt.leave_code IN ('ANNUAL', 'CUTI_TAHUNAN') THEN lr.total_days ELSE 0 END) as cuti,
            SUM(CASE WHEN lt.leave_code NOT IN ('ANNUAL', 'CUTI_TAHUNAN', 'DL', 'DINAS_LUAR') THEN lr.total_days ELSE 0 END) as ijin,
            SUM(CASE WHEN lt.leave_code IN ('DL', 'DINAS_LUAR') THEN lr.total_days ELSE 0 END) as dl
            FROM leave_requests lr
            JOIN leave_types lt ON lr.leave_type_id = lt.id
            WHERE YEAR(lr.start_date) = :y AND lr.status = 'approved'
            GROUP BY lr.employee_id, MONTH(lr.start_date)";
        $this->db->query($leave_sql);
        $this->db->bind(':y', $year);
        $leave_data = $this->db->resultSet();

        $sppd_sql = "SELECT 
            employee_id, 
            MONTH(start_date) as month,
            SUM(total_days) as sppd
            FROM business_trip_requests 
            WHERE YEAR(start_date) = :y AND status = 'approved'
            GROUP BY employee_id, MONTH(start_date)";
        $this->db->query($sppd_sql);
        $this->db->bind(':y', $year);
        $sppd_data = $this->db->resultSet();

        $ot_sql = "SELECT 
            employee_id, 
            MONTH(overtime_date) as month,
            COUNT(id) as lembur
            FROM overtime_requests 
            WHERE YEAR(overtime_date) = :y AND status = 'approved'
            GROUP BY employee_id, MONTH(overtime_date)";
        $this->db->query($ot_sql);
        $this->db->bind(':y', $year);
        $ot_data = $this->db->resultSet();

        $reports = [];
        foreach($employees as $emp) {
            $eid = $emp['id'];
            $reports[$eid] = [
                'info' => $emp,
                'totals' => ['jadwal'=>0, 'hadir'=>0, 'telat'=>0, 'plg_awal'=>0, 'lembur'=>0, 'cuti'=>0, 'ijin'=>0, 'dl'=>0, 'sppd'=>0, 'alpha'=>0],
                'months' => []
            ];
            for($m=1; $m<=12; $m++) {
                $reports[$eid]['months'][$m] = ['jadwal'=>0, 'hadir'=>0, 'telat'=>0, 'plg_awal'=>0, 'lembur'=>0, 'cuti'=>0, 'ijin'=>0, 'dl'=>0, 'sppd'=>0, 'alpha'=>0];
            }
        }

        foreach($attendance_data as $row) {
            $eid = $row['employee_id']; $m = $row['month'];
            if(isset($reports[$eid])) {
                $reports[$eid]['months'][$m]['jadwal'] = $row['jadwal'];
                $reports[$eid]['months'][$m]['hadir'] = $row['hadir'];
                $reports[$eid]['months'][$m]['telat'] = $row['telat'];
                $reports[$eid]['months'][$m]['plg_awal'] = $row['plg_awal'];
                $reports[$eid]['months'][$m]['alpha'] = $row['alpha'];
                
                $reports[$eid]['totals']['jadwal'] += $row['jadwal'];
                $reports[$eid]['totals']['hadir'] += $row['hadir'];
                $reports[$eid]['totals']['telat'] += $row['telat'];
                $reports[$eid]['totals']['plg_awal'] += $row['plg_awal'];
                $reports[$eid]['totals']['alpha'] += $row['alpha'];
            }
        }

        foreach($leave_data as $row) {
            $eid = $row['employee_id']; $m = $row['month'];
            if(isset($reports[$eid])) {
                $reports[$eid]['months'][$m]['cuti'] = $row['cuti'];
                $reports[$eid]['months'][$m]['ijin'] = $row['ijin'];
                $reports[$eid]['months'][$m]['dl']   = $row['dl'];
                
                $reports[$eid]['totals']['cuti'] += $row['cuti'];
                $reports[$eid]['totals']['ijin'] += $row['ijin'];
                $reports[$eid]['totals']['dl']   += $row['dl'];
            }
        }

        foreach($sppd_data as $row) {
            $eid = $row['employee_id']; $m = $row['month'];
            if(isset($reports[$eid])) {
                $reports[$eid]['months'][$m]['sppd'] = $row['sppd'];
                $reports[$eid]['totals']['sppd'] += $row['sppd'];
            }
        }

        foreach($ot_data as $row) {
            $eid = $row['employee_id']; $m = $row['month'];
            if(isset($reports[$eid])) {
                $reports[$eid]['months'][$m]['lembur'] = $row['lembur'];
                $reports[$eid]['totals']['lembur'] += $row['lembur'];
            }
        }

        $this->db->query("SELECT * FROM departments ORDER BY department_name ASC");
        $departments = $this->db->resultSet();

        $data = [
            'title' => 'Laporan Tahunan',
            'content_view' => 'admin/reports/annually',
            'reports' => $reports,
            'departments' => $departments,
            'year' => $year,
            'selected_dept' => $dept_id
        ];
        // ... (kode di dalam function annually)

        // CEK JIKA MINTA VERSI PRINT TAHUNAN
        if (isset($_GET['export']) && $_GET['export'] === 'print') {
            extract($data);
            require_once '../app/Views/admin/reports/print_annually.php';
            exit;
        }

        // Render view normal dengan layout
        $data['content_view'] = 'admin/reports/annually';
        $this->view('admin/layouts/admin-layout', $data);
    }
}