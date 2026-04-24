<?php
// File: app/Controllers/Staff/Dashboard.php

class Dashboard extends Controller {

    private $db;

    public function __construct() {
        if (!session_id()) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
        $this->db = new Database;
    }

    public function index() {
        $employeeId = $_SESSION['employee_id'];
        $today = date('Y-m-d');
        
        // --- 1. DATA KARYAWAN ---
        $this->db->query("SELECT first_name, last_name, position, annual_leave_balance, department_id FROM employees WHERE id = :id");
        $this->db->bind(':id', $employeeId);
        $emp = $this->db->single();

        // --- 2. ABSENSI HARI INI ---
        $this->db->query("SELECT * FROM attendance_records WHERE employee_id = :id AND attendance_date = :today");
        $this->db->bind(':id', $employeeId);
        $this->db->bind(':today', $today);
        $attendance = $this->db->single();

        // --- 3. JADWAL SHIFT HARI INI ---
        $shift = null;
        if ($attendance && !empty($attendance['shift_id'])) {
            $this->db->query("SELECT shift_name, start_time, end_time FROM work_shifts WHERE id = :sid");
            $this->db->bind(':sid', $attendance['shift_id']);
            $shift = $this->db->single();
        } 
        if (!$shift) {
            $this->db->query("SELECT ws.shift_name, ws.start_time, ws.end_time 
                        FROM shift_assignments sa
                        JOIN work_shifts ws ON sa.shift_id = ws.id
                        WHERE sa.employee_id = :eid AND sa.assignment_date = :today");
            $this->db->bind(':eid', $employeeId);
            $this->db->bind(':today', $today);
            $shift = $this->db->single();
        }
        
        // Jika tidak ada jadwal sama sekali, berarti LIBUR
        if (!$shift) {
            $shift = [
                'shift_name' => 'LIBUR / OFF', 
                'start_time' => null, 
                'end_time' => null,
                'is_off' => true
            ];
        } else {
            $shift['is_off'] = false;
        }

        // --- 4. STATISTIK BULAN INI ---
        $currentMonth = date('n');
        $currentYear  = date('Y');

        $this->db->query("SELECT DAY(attendance_date) as day_num, status, is_late 
                          FROM attendance_records 
                          WHERE employee_id = :eid 
                          AND MONTH(attendance_date) = :m 
                          AND YEAR(attendance_date) = :y");
        $this->db->bind(':eid', $employeeId);
        $this->db->bind(':m', $currentMonth);
        $this->db->bind(':y', $currentYear);
        $attendanceHistory = $this->db->resultSet();
        
        $calendarMap = [];
        $hariMasuk = 0;

        foreach($attendanceHistory as $rec) {
            $calendarMap[$rec['day_num']] = [
                'status' => $rec['status'],
                'is_late' => $rec['is_late']
            ];
            if ($rec['status'] == 'present' || $rec['status'] == 'late') {
                $hariMasuk++;
            }
        }

        $this->db->query("SELECT SUM(work_duration_minutes) as total_mins 
                          FROM attendance_records 
                          WHERE employee_id = :eid 
                          AND MONTH(attendance_date) = :m AND YEAR(attendance_date) = :y");
        $this->db->bind(':eid', $employeeId);
        $this->db->bind(':m', $currentMonth);
        $this->db->bind(':y', $currentYear);
        $resMins = $this->db->single();
        $jamKerja = floor(($resMins['total_mins'] ?? 0) / 60);

        // --- 5. MENGHITUNG SISA CUTI TAHUNAN AKURAT ---
        $this->db->query("SELECT COALESCE(SUM(lr.total_days), 0) as used_days
                          FROM leave_requests lr
                          JOIN leave_types lt ON lr.leave_type_id = lt.id
                          WHERE lr.employee_id = :eid 
                            AND lr.status = 'approved'
                            AND YEAR(lr.start_date) = :year
                            AND lt.leave_code IN ('ANNUAL', 'CUTI_TAHUNAN')");
        $this->db->bind(':eid', $employeeId);
        $this->db->bind(':year', $currentYear);
        $used = $this->db->single();
        
        $baseQuota = $emp['annual_leave_balance'] ?? 12;
        $sisaCuti = max(0, $baseQuota - ($used['used_days'] ?? 0));

        // --- 6. SKOR KINERJA (KPI) ---
        $kpiScore = 0;
        $this->db->query("SELECT kpi_score FROM kpi_summary 
                          WHERE employee_id = :eid ORDER BY year DESC, month DESC LIMIT 1");
        $this->db->bind(':eid', $employeeId);
        $kpiData = $this->db->single();
        
        if($kpiData) {
            $kpiScore = floatval($kpiData['kpi_score']);
        } else {
            $this->db->query("SELECT AVG(kpi_score_final) as score FROM tasks 
                              WHERE assigned_to = :eid AND status = 'completed'");
            $this->db->bind(':eid', $employeeId);
            $taskKpi = $this->db->single();
            $kpiScore = $taskKpi['score'] ? round($taskKpi['score'], 1) : 0;
        }

        // --- 7. TUGAS PENDING ---
        $this->db->query("SELECT title, due_date, priority 
                          FROM tasks 
                          WHERE assigned_to = :eid 
                          AND status NOT IN ('completed', 'cancelled')
                          ORDER BY due_date ASC 
                          LIMIT 3");
        $this->db->bind(':eid', $employeeId);
        $tasks = $this->db->resultSet();

        // --- 8. PENGUMUMAN TERBARU ---
        $this->db->query("SELECT title, content, created_at 
                          FROM announcements 
                          WHERE is_event = 0 
                          ORDER BY created_at DESC LIMIT 1");
        $announcement = $this->db->single();

        // --- 9. PREPARE DATA VIEW ---
        $hariIndo = ['Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
        $bulanIndo = ['Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'Mei','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Agt','Sep'=>'Sep','Oct'=>'Okt','Nov'=>'Nov','Dec'=>'Des'];
        $dateString = $hariIndo[date('l')] . ', ' . date('d') . ' ' . $bulanIndo[date('M')] . ' ' . date('Y');

        $data = [
            'title' => 'Dashboard Staff',
            'employee' => $emp,
            'attendance' => $attendance,
            'shift' => $shift,
            'date_indo' => $dateString,
            'calendar_map' => $calendarMap, 
            'tasks' => $tasks,             
            'announcement' => $announcement,
            'stats' => [
                'sisa_cuti'   => $sisaCuti, // Menggunakan variabel $sisaCuti yang sudah akurat
                'jam_kerja'   => $jamKerja,
                'hari_masuk'  => $hariMasuk,
                'score_val'   => $kpiScore,
                'score_grade' => $this->getKpiGrade($kpiScore)
            ]
        ];

        $this->view('staff/layouts/staff-layout', $data);
    }

    private function getKpiGrade($score) {
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';
        return 'E';
    }
}