<?php
// File: app/Controllers/Admin/Attendance.php

class Attendance extends Controller {

    private $db;

    public function __construct() {
        parent::__construct();
        if (!session_id()) session_start();

        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'super_admin'])) {
            header('Location: ' . BASEURL . '/Admin/LoginController');
            exit;
        }

        $this->db = new Database;
    }

    private function getDataAccess() {
        $role = $_SESSION['role'] ?? 'admin';
        if ($role === 'super_admin') return ['type' => 'all'];

        $this->db->query("SELECT department_id FROM employees WHERE id = :eid");
        $this->db->bind(':eid', $_SESSION['employee_id']);
        $emp = $this->db->single();

        return ['type' => 'restricted', 'dept_id' => $emp['department_id'] ?? 0];
    }

    // --- MESIN WAKTU KHUSUS MODE HISTORY (FIXED SCHEMA MATCH) ---
    private function getHistoryTimeline($filter_emp, $start_date, $end_date) {
        $timeline = [];
        $current = strtotime($start_date);
        $end = strtotime($end_date);
        
        // 1. Siapkan kanvas kosong per hari
        while ($current <= $end) {
            $d = date('Y-m-d', $current);
            $timeline[$d] = [
                'date' => $d,
                'shift_name' => '-',
                'is_mod' => 0,
                'in' => '-',
                'out' => '-',
                'status' => '',
                'late_mins' => 0,
                'early_mins' => 0,
                'overtime_mins' => 0,
                'note' => '',
                'attendance_id' => null,
                'is_weekend' => (date('N', $current) >= 6)
            ];
            $current = strtotime('+1 day', $current);
        }

        // 2. Tempel Jadwal Shift
        $this->db->query("SELECT sa.assignment_date, sa.is_mod, ws.shift_name 
                          FROM shift_assignments sa LEFT JOIN work_shifts ws ON sa.shift_id = ws.id 
                          WHERE sa.employee_id = :emp AND sa.assignment_date BETWEEN :start AND :end");
        $this->db->bind(':emp', $filter_emp); $this->db->bind(':start', $start_date); $this->db->bind(':end', $end_date);
        foreach ($this->db->resultSet() as $a) {
            $d = $a['assignment_date'];
            if(isset($timeline[$d])) {
                $timeline[$d]['shift_name'] = $a['shift_name'];
                $timeline[$d]['is_mod'] = $a['is_mod'];
                $timeline[$d]['status'] = 'Alpha'; // Set default 'Alpha' kalau punya shift tapi gaada absen
            }
        }

        // 3. Tempel Data Absen Real
        $this->db->query("SELECT id, attendance_date, check_in_time, check_out_time, is_late, late_duration_minutes, is_early_out, early_out_minutes, overtime_minutes, check_in_notes 
                          FROM attendance_records WHERE employee_id = :emp AND attendance_date BETWEEN :start AND :end");
        $this->db->bind(':emp', $filter_emp); $this->db->bind(':start', $start_date); $this->db->bind(':end', $end_date);
        foreach ($this->db->resultSet() as $a) {
            $d = $a['attendance_date'];
            if(isset($timeline[$d])) {
                $timeline[$d]['attendance_id'] = $a['id'];
                $timeline[$d]['in'] = substr($a['check_in_time'], 11, 5);
                $timeline[$d]['out'] = $a['check_out_time'] ? substr($a['check_out_time'], 11, 5) : '-';
                $timeline[$d]['late_mins'] = $a['late_duration_minutes'];
                $timeline[$d]['early_mins'] = $a['early_out_minutes'];
                $timeline[$d]['overtime_mins'] = $a['overtime_minutes'];
                $timeline[$d]['note'] = trim($a['check_in_notes'] ?? '');
                
                if ($a['is_late']) $timeline[$d]['status'] = 'Telat';
                elseif ($a['is_early_out']) $timeline[$d]['status'] = 'Plg Awal';
                else $timeline[$d]['status'] = 'Hadir';
            }
        }

        // 4. Tempel Cuti & Ijin (FIX: Pakai leave_type_name)
        $this->db->query("SELECT lr.start_date, lr.end_date, lr.reason, lt.leave_code, lt.leave_type_name 
                          FROM leave_requests lr JOIN leave_types lt ON lr.leave_type_id = lt.id 
                          WHERE lr.employee_id = :emp AND lr.status = 'approved' AND (lr.start_date <= :end AND lr.end_date >= :start)");
        $this->db->bind(':emp', $filter_emp); $this->db->bind(':start', $start_date); $this->db->bind(':end', $end_date);
        foreach ($this->db->resultSet() as $l) {
            $c = strtotime(max($start_date, $l['start_date'])); $e = strtotime(min($end_date, $l['end_date']));
            $stat = 'Ijin';
            if (in_array($l['leave_code'], ['ANNUAL', 'CUTI_TAHUNAN'])) $stat = 'Cuti';
            if (in_array($l['leave_code'], ['DL', 'DINAS_LUAR'])) $stat = 'Dinas';
            while ($c <= $e) {
                $d = date('Y-m-d', $c);
                if(isset($timeline[$d])) { 
                    $timeline[$d]['status'] = $stat; 
                    $timeline[$d]['note'] = $l['leave_type_name'] . ' - ' . $l['reason']; 
                }
                $c = strtotime('+1 day', $c);
            }
        }

        // 5. Tempel SPPD (FIX: Pakai trip_purpose)
        $this->db->query("SELECT start_date, end_date, destination, trip_purpose FROM business_trip_requests 
                          WHERE employee_id = :emp AND status = 'approved' AND (start_date <= :end AND end_date >= :start)");
        $this->db->bind(':emp', $filter_emp); $this->db->bind(':start', $start_date); $this->db->bind(':end', $end_date);
        foreach ($this->db->resultSet() as $s) {
            $c = strtotime(max($start_date, $s['start_date'])); $e = strtotime(min($end_date, $s['end_date']));
            while ($c <= $e) {
                $d = date('Y-m-d', $c);
                if(isset($timeline[$d])) { 
                    $timeline[$d]['status'] = 'SPPD'; 
                    $timeline[$d]['note'] = 'SPPD: '.$s['destination'] . ' (' . $s['trip_purpose'] . ')'; 
                }
                $c = strtotime('+1 day', $c);
            }
        }

        // 6. Tempel Data Lembur (FIX: Pakai total_hours)
        $this->db->query("SELECT overtime_date, total_hours, reason FROM overtime_requests 
                          WHERE employee_id = :emp AND status = 'approved' AND overtime_date BETWEEN :start AND :end");
        $this->db->bind(':emp', $filter_emp); $this->db->bind(':start', $start_date); $this->db->bind(':end', $end_date);
        foreach ($this->db->resultSet() as $o) {
            $d = $o['overtime_date'];
            if(isset($timeline[$d])) {
                // Konversi total_hours (desimal) jadi menit
                $timeline[$d]['overtime_mins'] += ($o['total_hours'] * 60);
                $timeline[$d]['note'] .= ($timeline[$d]['note'] ? ' | ' : '') . 'Lembur: ' . $o['reason'];
            }
        }

        // 7. Styling & Finalisasi Badges
        $today = date('Y-m-d');
        foreach ($timeline as $d => &$t) {
            if ($t['status'] === 'Alpha' && $d > $today) $t['status'] = '-';
            elseif ($t['status'] === '' && $t['shift_name'] === '-') $t['status'] = 'Off';
            elseif ($t['status'] === '') $t['status'] = ($d > $today) ? '-' : 'Alpha';

            switch($t['status']) {
                case 'Hadir': $t['badge'] = '<span class="px-2 py-1 text-xs font-bold rounded bg-emerald-100 text-emerald-700">Normal</span>'; break;
                case 'Telat': $t['badge'] = '<span class="px-2 py-1 text-xs font-bold rounded bg-red-100 text-red-700">Telat ' . $t['late_mins'] . 'm</span>'; break;
                case 'Plg Awal': $t['badge'] = '<span class="px-2 py-1 text-xs font-bold rounded bg-yellow-100 text-yellow-700">Plg Awal ' . $t['early_mins'] . 'm</span>'; break;
                case 'Cuti': $t['badge'] = '<span class="px-2 py-1 text-xs font-bold rounded bg-emerald-50 text-emerald-600 border border-emerald-200">Cuti</span>'; break;
                case 'Ijin': $t['badge'] = '<span class="px-2 py-1 text-xs font-bold rounded bg-orange-100 text-orange-600 border border-orange-200">Ijin</span>'; break;
                case 'Dinas': $t['badge'] = '<span class="px-2 py-1 text-xs font-bold rounded bg-blue-100 text-blue-600 border border-blue-200">Dinas</span>'; break;
                case 'SPPD': $t['badge'] = '<span class="px-2 py-1 text-xs font-bold rounded bg-purple-100 text-purple-600 border border-purple-200">SPPD</span>'; break;
                case 'Alpha': $t['badge'] = '<span class="px-2 py-1 text-xs font-bold rounded bg-red-50 text-red-600 border border-red-200">Alpha</span>'; break;
                case 'Off': $t['badge'] = '<span class="px-2 py-1 text-[10px] font-bold rounded bg-gray-100 text-gray-400 border border-gray-200">OFF</span>'; break;
                default: $t['badge'] = '<span class="text-gray-300">-</span>'; break;
            }
        }
        return array_values($timeline);
    }

    private function buildLogQuery($mode, $start_date, $end_date, $filter_emp, $access) {
        $sql = ""; $params = [];
        if ($mode == 'daily') {
            $sql = "SELECT ar.*, e.first_name, e.last_name, e.employee_number, e.position, d.department_name, ws.shift_name, ol.office_name as check_in_location_name
                    FROM attendance_records ar JOIN employees e ON ar.employee_id = e.id LEFT JOIN departments d ON e.department_id = d.id LEFT JOIN work_shifts ws ON ar.shift_id = ws.id LEFT JOIN office_locations ol ON ar.check_in_location_id = ol.id
                    WHERE ar.attendance_date = :start";
            $params = [':start' => $start_date];
            if ($access['type'] === 'restricted') { $sql .= " AND e.department_id = :dept_id "; $params[':dept_id'] = $access['dept_id']; }
            if (!empty($filter_emp)) { $sql .= " AND ar.employee_id = :filter_emp "; $params[':filter_emp'] = $filter_emp; }
            $sql .= " ORDER BY ar.check_in_time DESC";
        } else {
            $sql = "SELECT e.id as employee_id, e.first_name, e.last_name, e.employee_number, e.position, d.department_name,
                        COUNT(ar.id) as total_present, SUM(CASE WHEN ar.is_late = 1 THEN 1 ELSE 0 END) as total_late, SUM(CASE WHEN ar.is_early_out = 1 THEN 1 ELSE 0 END) as total_early_out, COALESCE(SUM(ar.work_duration_minutes), 0) as total_minutes, COALESCE(SUM(ar.overtime_minutes), 0) as total_overtime
                    FROM employees e LEFT JOIN departments d ON e.department_id = d.id LEFT JOIN attendance_records ar ON e.id = ar.employee_id AND ar.attendance_date BETWEEN :start AND :end
                    WHERE e.is_active = 1";
            $params = [':start' => $start_date, ':end' => $end_date];
            if ($access['type'] === 'restricted') { $sql .= " AND e.department_id = :dept_id "; $params[':dept_id'] = $access['dept_id']; }
            if (!empty($filter_emp)) { $sql .= " AND e.id = :filter_emp "; $params[':filter_emp'] = $filter_emp; }
            $sql .= " GROUP BY e.id, e.first_name, e.last_name, e.employee_number, e.position, d.department_name ORDER BY d.department_name ASC, e.first_name ASC";
        }
        return ['sql' => $sql, 'params' => $params];
    }

    public function index() {
        $access = $this->getDataAccess();
        $mode = $_GET['mode'] ?? 'daily';
        $filter_emp = $_GET['employee_id'] ?? '';
        
        if ($mode == 'weekly') {
            $week_str = $_GET['week'] ?? date('Y-\WW'); 
            $dto = new DateTime(); $dto->setISODate(substr($week_str, 0, 4), substr($week_str, 6));
            $start_date = $dto->format('Y-m-d'); $dto->modify('+6 days'); $end_date = $dto->format('Y-m-d');
            $filter_value = $week_str; 
        } elseif ($mode == 'monthly') {
            $month_str = $_GET['month'] ?? date('Y-m'); 
            list($year, $month) = explode('-', $month_str);
            $end_date   = "$year-$month-24"; $start_date = date('Y-m-d', strtotime("$year-$month-25 -1 month")); 
            $filter_value = $month_str;
        } elseif ($mode == 'history') {
            $start_date = $_GET['start']; $end_date   = $_GET['end']; $filter_value = ''; 
        } else { 
            $date_str = $_GET['date'] ?? date('Y-m-d');
            $start_date = $date_str; $end_date = $date_str; $filter_value = $date_str;
        }

        $logs = [];
        $stats = ['total_hadir' => 0, 'total_telat' => 0, 'total_pulang_awal' => 0];

        if ($mode == 'history' && !empty($filter_emp)) {
            $logs = $this->getHistoryTimeline($filter_emp, $start_date, $end_date);
            foreach ($logs as $t) {
                if (in_array($t['status'], ['Hadir', 'Telat', 'Plg Awal'])) $stats['total_hadir']++;
                if ($t['status'] == 'Telat') $stats['total_telat']++;
                if ($t['status'] == 'Plg Awal') $stats['total_pulang_awal']++;
            }
        } else {
            $queryData = $this->buildLogQuery($mode, $start_date, $end_date, $filter_emp, $access);
            $this->db->query($queryData['sql']);
            foreach($queryData['params'] as $key => $val) $this->db->bind($key, $val);
            $logs = $this->db->resultSet();

            $today = date('Y-m-d');
            $this->db->query("SELECT COUNT(*) as hadir, SUM(CASE WHEN is_late=1 THEN 1 ELSE 0 END) as telat, SUM(CASE WHEN is_early_out=1 THEN 1 ELSE 0 END) as pulang_awal FROM attendance_records WHERE attendance_date = :today");
            $this->db->bind(':today', $today);
            $resStats = $this->db->single();
            $stats = ['total_hadir' => $resStats['hadir']??0, 'total_telat' => $resStats['telat']??0, 'total_pulang_awal' => $resStats['pulang_awal']??0];
        }

        $employee_detail = null;
        if ($mode == 'history' && !empty($filter_emp)) {
            $this->db->query("SELECT first_name, last_name, employee_number, position, department_id FROM employees WHERE id = :id");
            $this->db->bind(':id', $filter_emp);
            $employee_detail = $this->db->single();
        }

        $sql_approve = "SELECT ai.id as issue_id, ai.issue_type, ai.issue_description, ai.employee_reason, ai.status,
                               ar.attendance_date, ar.check_in_time, ar.check_out_time, 
                               e.first_name, e.last_name, e.full_name, e.employee_number, d.department_name 
                        FROM attendance_issues ai JOIN attendance_records ar ON ai.attendance_id = ar.id 
                        JOIN employees e ON ai.employee_id = e.id LEFT JOIN departments d ON e.department_id = d.id WHERE ai.status = 'pending'";
        if ($access['type'] === 'restricted') $sql_approve .= " AND e.department_id = :dept_id";
        $this->db->query($sql_approve);
        if ($access['type'] === 'restricted') $this->db->bind(':dept_id', $access['dept_id']);
        $pending_approvals = $this->db->resultSet();

        $sql_emp = "SELECT id, first_name, last_name, employee_number FROM employees WHERE is_active = 1";
        if ($access['type'] === 'restricted') $sql_emp .= " AND department_id = :dept_id";
        $sql_emp .= " ORDER BY first_name ASC";
        $this->db->query($sql_emp);
        if ($access['type'] === 'restricted') $this->db->bind(':dept_id', $access['dept_id']);
        $all_employees = $this->db->resultSet();

        $this->db->query("SELECT id, office_name FROM office_locations WHERE is_active = 1");
        $offices = $this->db->resultSet();

        $period_info = ($mode == 'daily') ? date('d F Y', strtotime($start_date)) : date('d M Y', strtotime($start_date)) . ' - ' . date('d M Y', strtotime($end_date));

        $data = [
            'title' => 'Monitoring Absensi',
            'content_view' => 'admin/attendance/index',
            'logs' => $logs,
            'stats' => $stats,
            'pending_approvals' => $pending_approvals,
            'all_employees' => $all_employees,
            'offices' => $offices,
            'mode' => $mode,
            'filter_value' => $filter_value,
            'filter_emp' => $filter_emp,
            'period_info' => $period_info,
            'raw_start' => $start_date,
            'raw_end' => $end_date,
            'employee_detail' => $employee_detail
        ];
        
        $this->view('admin/layouts/admin-layout', $data);
    }

    // --- 1. PROSES MANUAL CHECK-IN ---
    public function manual_checkin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $emp_id = $_POST['employee_id'] ?? null;
            $date = $_POST['attendance_date'] ?? null;
            $time = $_POST['check_in_time'] ?? null;
            $notes = $_POST['notes'] ?? '';

            if (!$emp_id || !$date || !$time) {
                $_SESSION['flash'] = ['pesan' => 'Gagal!', 'aksi' => 'Form tidak lengkap.', 'tipe' => 'error'];
                header('Location: ' . BASEURL . '/admin/attendance');
                exit;
            }

            // Gabungkan tanggal dan jam sesuai format DATETIME MySQL
            $datetime_in = $date . ' ' . $time . ':00';

            // Cari shift yang dipakai hari ini
            $this->db->query("SELECT shift_id FROM shift_assignments WHERE employee_id = :eid AND assignment_date = :date");
            $this->db->bind(':eid', $emp_id);
            $this->db->bind(':date', $date);
            $shift = $this->db->single();
            
            if ($shift && !empty($shift['shift_id'])) {
                $shift_id = $shift['shift_id'];
            } else {
                // FIX: Ambil ID shift pertama yang valid dari database sebagai default
                $this->db->query("SELECT id FROM work_shifts ORDER BY id ASC LIMIT 1");
                $default_shift = $this->db->single();
                $shift_id = $default_shift ? $default_shift['id'] : null;
            }

            // Cek apakah sudah absen di tanggal ini agar tidak duplikat
            $this->db->query("SELECT id FROM attendance_records WHERE employee_id = :eid AND attendance_date = :date");
            $this->db->bind(':eid', $emp_id);
            $this->db->bind(':date', $date);
            if ($this->db->single()) {
                $_SESSION['flash'] = ['pesan' => 'Ditolak!', 'aksi' => 'Pegawai ini sudah memiliki data absensi di tanggal tersebut.', 'tipe' => 'warning'];
            } else {
                // Insert Data
                $this->db->query("INSERT INTO attendance_records (employee_id, attendance_date, shift_id, check_in_time, check_in_notes, status, check_in_address) 
                                  VALUES (:eid, :date, :sid, :time_in, :notes, 'present', 'Manual by Admin')");
                $this->db->bind(':eid', $emp_id);
                $this->db->bind(':date', $date);
                $this->db->bind(':sid', $shift_id);
                $this->db->bind(':time_in', $datetime_in);
                $this->db->bind(':notes', $notes);

                if ($this->db->execute()) {
                    $_SESSION['flash'] = ['pesan' => 'Berhasil!', 'aksi' => 'Absensi masuk manual berhasil disimpan.', 'tipe' => 'success'];
                } else {
                    $_SESSION['flash'] = ['pesan' => 'Gagal!', 'aksi' => 'Terjadi kesalahan saat menyimpan ke database.', 'tipe' => 'error'];
                }
            }
            
            header('Location: ' . BASEURL . '/admin/attendance?mode=daily&date=' . $date);
            exit;
        }
    }

    // --- 2. PROSES MANUAL CHECK-OUT ---
    public function manual_checkout() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['attendance_id'] ?? null;
            $time = $_POST['check_out_time'] ?? null;

            if ($id && $time) {
                // Ambil data tanggal absensinya untuk menggabungkan DATETIME
                $this->db->query("SELECT attendance_date FROM attendance_records WHERE id = :id");
                $this->db->bind(':id', $id);
                $att = $this->db->single();
                
                if($att) {
                    $date = $att['attendance_date'];
                    $datetime_out = $date . ' ' . $time . ':00';

                    $this->db->query("UPDATE attendance_records SET check_out_time = :time_out, check_out_address = 'Manual by Admin', updated_at = NOW() WHERE id = :id");
                    $this->db->bind(':time_out', $datetime_out);
                    $this->db->bind(':id', $id);
                    
                    if ($this->db->execute()) {
                        $_SESSION['flash'] = ['pesan' => 'Berhasil!', 'aksi' => 'Check-out manual berhasil diproses.', 'tipe' => 'success'];
                    } else {
                        $_SESSION['flash'] = ['pesan' => 'Gagal!', 'aksi' => 'Gagal menyimpan waktu check-out.', 'tipe' => 'error'];
                    }
                    header('Location: ' . BASEURL . '/admin/attendance?mode=daily&date=' . $date);
                    exit;
                }
            }
        }
        header('Location: ' . BASEURL . '/admin/attendance');
        exit;
    }

    // --- 3. PROSES EDIT DATA ABSENSI ---
    public function edit_record() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['attendance_id'];
            $in_time = $_POST['check_in_time'];
            $out_time = $_POST['check_out_time'];
            $notes = $_POST['notes'];

            $this->db->query("SELECT attendance_date FROM attendance_records WHERE id = :id");
            $this->db->bind(':id', $id);
            $att = $this->db->single();

            if($att) {
                $date = $att['attendance_date'];
                $datetime_in = $date . ' ' . $in_time . ':00';
                $datetime_out = !empty($out_time) ? ($date . ' ' . $out_time . ':00') : null;

                $this->db->query("UPDATE attendance_records SET check_in_time = :in, check_out_time = :out, check_in_notes = :notes, updated_at = NOW() WHERE id = :id");
                $this->db->bind(':in', $datetime_in);
                $this->db->bind(':out', $datetime_out);
                $this->db->bind(':notes', $notes);
                $this->db->bind(':id', $id);

                if ($this->db->execute()) {
                    $_SESSION['flash'] = ['pesan' => 'Terupdate!', 'aksi' => 'Data absensi berhasil diubah.', 'tipe' => 'success'];
                } else {
                    $_SESSION['flash'] = ['pesan' => 'Gagal!', 'aksi' => 'Gagal mengupdate data.', 'tipe' => 'error'];
                }
                header('Location: ' . BASEURL . '/admin/attendance?mode=daily&date=' . $date);
                exit;
            }
        }
        header('Location: ' . BASEURL . '/admin/attendance');
        exit;
    }
    
    public function process_approval() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $issue_id = $_POST['attendance_id']; $action = $_POST['action']; $status = ($action === 'approve') ? 'approved' : 'rejected';
            $this->db->query("UPDATE attendance_issues SET status = :status WHERE id = :id");
            $this->db->bind(':status', $status); $this->db->bind(':id', $issue_id); $this->db->execute();
            header('Location: ' . BASEURL . '/admin/attendance?tab=approval'); exit;
        }
    }

    public function delete_record($id) {
        $this->db->query("DELETE FROM attendance_records WHERE id = :id");
        $this->db->bind(':id', $id);
        try { $this->db->execute(); Flasher::setFlash('Log Kehadiran dihapus', 'success'); } catch (Exception $e) { Flasher::setFlash('Gagal hapus log', 'error'); }
        header('Location: ' . $_SERVER['HTTP_REFERER']); exit;
    }

    public function export() {
        $access = $this->getDataAccess();
        $mode = $_GET['mode'] ?? 'daily';
        $start_date = $_GET['start'] ?? date('Y-m-d');
        $end_date = $_GET['end'] ?? date('Y-m-d');
        $filter_emp = $_GET['employee_id'] ?? '';
        $type = $_GET['type'] ?? 'csv';

        if ($mode == 'history' && !empty($filter_emp)) {
            $logs = $this->getHistoryTimeline($filter_emp, $start_date, $end_date);
        } else {
            $queryData = $this->buildLogQuery($mode, $start_date, $end_date, $filter_emp, $access);
            $this->db->query($queryData['sql']);
            foreach($queryData['params'] as $key => $val) $this->db->bind($key, $val);
            $logs = $this->db->resultSet();
        }

        $employee_detail = null;
        if (!empty($filter_emp)) {
            $this->db->query("SELECT first_name, last_name, employee_number, position, department_id, department_name FROM employees LEFT JOIN departments ON employees.department_id = departments.id WHERE employees.id = :id");
            $this->db->bind(':id', $filter_emp);
            $employee_detail = $this->db->single();
        }

        $this->db->query("SELECT * FROM companies LIMIT 1");
        $company = $this->db->single();

        $period_info = ($mode == 'daily') ? date('d F Y', strtotime($start_date)) : date('d M Y', strtotime($start_date)) . ' - ' . date('d M Y', strtotime($end_date));

        if ($type == 'print') {
            $data = [
                'title' => 'Laporan Absensi', 'logs' => $logs, 'mode' => $mode, 'period_info' => $period_info,
                'employee_detail' => $employee_detail, 'company' => $company, 'generated_at' => date('d M Y H:i'),
                'generated_by' => $_SESSION['full_name'] ?? 'Admin'
            ];
            $this->view('admin/attendance/print', $data);
            return;
        }

        $filename = "Laporan_Absensi_" . ucfirst($mode) . "_" . date('Ymd') . ".xls";
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        echo '<table border="1">';
        echo '<tr>';
        if ($mode == 'history') {
            echo '<th style="background:#f0f0f0;">Tanggal</th><th>Shift</th><th>Masuk</th><th>Pulang</th><th>Status</th><th>Lembur(M)</th><th>Ket</th>';
        } elseif ($mode == 'daily') {
            echo '<th style="background:#f0f0f0;">Tanggal</th><th>NIK</th><th>Nama Lengkap</th><th>Jabatan</th><th>Departemen</th><th>Shift</th><th>Masuk</th><th>Pulang</th><th>Status</th><th>Ket</th>';
        } else {
            echo '<th style="background:#f0f0f0;">NIK</th><th>Nama Lengkap</th><th>Jabatan</th><th>Departemen</th><th>Hadir</th><th>Telat</th><th>Plg Awal</th><th>Total Jam</th><th>Lembur</th>';
        }
        echo '</tr>';
        
        foreach ($logs as $row) {
            echo '<tr>';
            if ($mode == 'history') {
                echo '<td>' . $row['date'] . '</td>';
                echo '<td>' . $row['shift_name'] . '</td>';
                echo '<td>' . $row['in'] . '</td>';
                echo '<td>' . $row['out'] . '</td>';
                echo '<td>' . strip_tags($row['badge']) . '</td>'; // Hilangin HTML tag buat di excel
                echo '<td>' . $row['overtime_mins'] . '</td>';
                echo '<td>' . $row['note'] . '</td>';
            } elseif ($mode == 'daily') {
                $status = ($row['is_late'] ? 'Telat' : ($row['is_early_out'] ? 'Plg Awal' : 'Normal'));
                echo '<td>' . date('d/m/Y', strtotime($row['attendance_date'])) . '</td>';
                echo '<td>' . $row['employee_number'] . '</td>';
                echo '<td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>';
                echo '<td>' . ($row['position'] ?? '-') . '</td>';
                echo '<td>' . $row['department_name'] . '</td>';
                echo '<td>' . ($row['shift_name'] ?? '-') . '</td>';
                echo '<td>' . substr($row['check_in_time'], 11, 5) . '</td>';
                echo '<td>' . ($row['check_out_time'] ? substr($row['check_out_time'], 11, 5) : '-') . '</td>';
                echo '<td>' . $status . '</td>';
                echo '<td>' . ($row['check_in_notes'] ?? '') . '</td>';
            } else {
                echo '<td>' . $row['employee_number'] . '</td>';
                echo '<td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>';
                echo '<td>' . ($row['position'] ?? '-') . '</td>';
                echo '<td>' . $row['department_name'] . '</td>';
                echo '<td>' . $row['total_present'] . '</td>';
                echo '<td>' . $row['total_late'] . '</td>';
                echo '<td>' . $row['total_early_out'] . '</td>';
                echo '<td>' . number_format($row['total_minutes']/60, 1) . '</td>';
                echo '<td>' . number_format($row['total_overtime']/60, 1) . '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
        exit;
    }
}