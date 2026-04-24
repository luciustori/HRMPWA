<?php
// File: app/Controllers/Staff/Attendance.php

class Attendance extends Controller {

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
        $db = new Database;
        $employeeId = $_SESSION['employee_id'];
        
        // Filter Bulan & Tahun (Default: Bulan Ini)
        $month = isset($_GET['month']) ? $_GET['month'] : date('m');
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');

        // --- 1. DATA HARI INI (CEK JADWAL & LIBUR) ---
        $today = date('Y-m-d');
        $db->query("SELECT 
                        ar.check_in_time as clock_in,
                        ar.check_out_time as clock_out,
                        ws.shift_name, 
                        ws.start_time, 
                        ws.end_time 
                    FROM attendance_records ar 
                    LEFT JOIN work_shifts ws ON ar.shift_id = ws.id
                    WHERE ar.employee_id = :eid AND ar.attendance_date = :today");
        $db->bind(':eid', $employeeId);
        $db->bind(':today', $today);
        $todayData = $db->single();

        // Fallback jika belum absen atau shift kosong, cari di shift_assignments
        if (!$todayData || empty($todayData['shift_name'])) {
            $db->query("SELECT ws.shift_name, ws.start_time, ws.end_time 
                        FROM shift_assignments sa 
                        JOIN work_shifts ws ON sa.shift_id = ws.id
                        WHERE sa.employee_id = :eid AND sa.assignment_date = :today");
            $db->bind(':eid', $employeeId);
            $db->bind(':today', $today);
            $shiftOnly = $db->single();
            
            if (!$shiftOnly) {
                // JIKA KOSONG = LIBUR!
                $todayData = [
                    'clock_in' => $todayData['clock_in'] ?? null, 
                    'clock_out' => $todayData['clock_out'] ?? null, 
                    'shift_name' => 'LIBUR / OFF',
                    'start_time' => null,
                    'end_time' => null,
                    'is_off' => true
                ];
            } else {
                $todayData = [
                    'clock_in' => $todayData['clock_in'] ?? null, 
                    'clock_out' => $todayData['clock_out'] ?? null, 
                    'shift_name' => $shiftOnly['shift_name'],
                    'start_time' => $shiftOnly['start_time'],
                    'end_time' => $shiftOnly['end_time'],
                    'is_off' => false
                ];
            }
        } else {
            $todayData['is_off'] = false;
        }

        // --- 2. STATISTIK ABSENSI BULANAN ---
        $db->query("SELECT 
            COUNT(CASE WHEN status = 'present' AND is_late = 0 THEN 1 END) as total_ontime,
            COUNT(CASE WHEN is_late = 1 THEN 1 END) as total_late,
            COUNT(CASE WHEN status = 'absent' THEN 1 END) as total_alpha,
            COUNT(CASE WHEN status IN ('present', 'late', 'early_out') THEN 1 END) as total_masuk
        FROM attendance_records 
        WHERE employee_id = :eid AND MONTH(attendance_date) = :m AND YEAR(attendance_date) = :y");
        $db->bind(':eid', $employeeId);
        $db->bind(':m', $month);
        $db->bind(':y', $year);
        $stats = $db->single();

        // --- 3. HITUNG LEMBUR (OVERTIME) BULAN INI ---
        $db->query("SELECT SUM(overtime_minutes) as total_minutes
                    FROM attendance_records
                    WHERE employee_id = :eid 
                    AND MONTH(attendance_date) = :m 
                    AND YEAR(attendance_date) = :y");
        $db->bind(':eid', $employeeId);
        $db->bind(':m', $month);
        $db->bind(':y', $year);
        $ovt = $db->single();
        $totalLemburJam = floor(($ovt['total_minutes'] ?? 0) / 60);

        // --- 4. SISA CUTI TAHUNAN (SAMA SEPERTI DASHBOARD) ---
        $db->query("SELECT annual_leave_balance FROM employees WHERE id = :eid");
        $db->bind(':eid', $employeeId);
        $emp = $db->single();
        $baseQuota = $emp['annual_leave_balance'] ?? 12;

        $db->query("SELECT COALESCE(SUM(lr.total_days), 0) as used_days
                    FROM leave_requests lr
                    JOIN leave_types lt ON lr.leave_type_id = lt.id
                    WHERE lr.employee_id = :eid 
                      AND lr.status = 'approved'
                      AND YEAR(lr.start_date) = :year
                      AND lt.leave_code IN ('ANNUAL', 'CUTI_TAHUNAN')");
        $db->bind(':eid', $employeeId);
        $db->bind(':year', date('Y')); // Cuti dihitung tahun berjalan
        $used = $db->single();
        $sisaCuti = max(0, $baseQuota - ($used['used_days'] ?? 0));

        // --- 5. DATA IJIN, DINAS, & SPPD (Sesuai Bulan Filter) ---
        $db->query("SELECT 
                        SUM(CASE WHEN lt.leave_code NOT IN ('ANNUAL', 'CUTI_TAHUNAN', 'DL', 'DINAS_LUAR') THEN lr.total_days ELSE 0 END) as total_izin,
                        SUM(CASE WHEN lt.leave_code IN ('DL', 'DINAS_LUAR') THEN lr.total_days ELSE 0 END) as total_dinas
                    FROM leave_requests lr
                    JOIN leave_types lt ON lr.leave_type_id = lt.id
                    WHERE lr.employee_id = :eid AND lr.status = 'approved' AND MONTH(lr.start_date) = :m AND YEAR(lr.start_date) = :y");
        $db->bind(':eid', $employeeId); $db->bind(':m', $month); $db->bind(':y', $year);
        $leaveStats = $db->single();
        $stats['total_izin'] = $leaveStats['total_izin'] ?? 0;
        $stats['total_dinas'] = $leaveStats['total_dinas'] ?? 0;

        $db->query("SELECT COALESCE(SUM(total_days), 0) as total_sppd FROM business_trip_requests 
                    WHERE employee_id = :eid AND status = 'approved' AND MONTH(start_date) = :m AND YEAR(start_date) = :y");
        $db->bind(':eid', $employeeId); $db->bind(':m', $month); $db->bind(':y', $year);
        $sppdStats = $db->single();
        $stats['total_sppd'] = $sppdStats['total_sppd'] ?? 0;

        // --- 6. HISTORY TABLE (LIST ABSENSI) ---
        $db->query("SELECT 
                        ar.attendance_date,
                        ar.check_in_time as clock_in,
                        ar.check_out_time as clock_out,
                        ar.status,
                        ar.is_late,
                        ar.check_in_notes as notes,
                        ws.shift_name
                    FROM attendance_records ar
                    LEFT JOIN work_shifts ws ON ar.shift_id = ws.id
                    WHERE ar.employee_id = :eid 
                    AND MONTH(ar.attendance_date) = :m 
                    AND YEAR(ar.attendance_date) = :y
                    ORDER BY ar.attendance_date DESC");
        $db->bind(':eid', $employeeId);
        $db->bind(':m', $month);
        $db->bind(':y', $year);
        $history = $db->resultSet();

        $data = [
            'title' => 'Absensi Saya',
            'today' => $todayData,
            'stats' => $stats,
            'extra' => [
                'sisa_cuti' => $sisaCuti,
                'jam_lembur' => $totalLemburJam
            ],
            'history' => $history,
            'filter_month' => $month,
            'filter_year' => $year
        ];

        $data['content_view'] = 'staff/attendance/index';
        $this->view('staff/layouts/staff-layout', $data);
    }
}