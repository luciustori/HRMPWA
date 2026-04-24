<?php

class Schedules extends Controller {

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
        $db = new Database;
        $employeeId = $_SESSION['employee_id']; 
        $userId = $_SESSION['user_id'];

        // 0. Ambil Data Dept ID Karyawan (PENTING untuk filter event departemen)
        $emp = $db->fetchOne("SELECT department_id FROM employees WHERE id = :id", [':id' => $employeeId]);
        $deptId = $emp['department_id'] ?? 0;

        // Navigasi Bulan
        $month = isset($_GET['month']) ? $_GET['month'] : date('m');
        $year  = isset($_GET['year']) ? $_GET['year'] : date('Y');

        // ==========================================================
        // 1. DATA JADWAL KERJA
        // ==========================================================
        $scheduleSql = "
            SELECT 
                sa.assignment_date as date,
                sa.is_mod,
                COALESCE(ws.shift_name, 'Shift') as shift_name,
                COALESCE(ws.shift_code, 'S') as shift_code,
                ws.start_time as shift_start,
                ws.end_time as shift_end
            FROM shift_assignments sa
            LEFT JOIN work_shifts ws ON sa.shift_id = ws.id
            WHERE sa.employee_id = :eid
            AND MONTH(sa.assignment_date) = :m
            AND YEAR(sa.assignment_date) = :y
        ";
        
        $db->query($scheduleSql);
        $db->bind(':eid', $employeeId);
        $db->bind(':m', $month);
        $db->bind(':y', $year);
        try { $scheduleRaw = $db->resultSet(); } catch (Exception $e) { $scheduleRaw = []; }

        // ==========================================================
        // 2. DATA ABSENSI
        // ==========================================================
        $attendanceSql = "
            SELECT ar.attendance_date as date, ar.status, ar.check_in_time as clock_in, ar.check_out_time as clock_out, ar.is_late
            FROM attendance_records ar
            WHERE ar.employee_id = :eid AND MONTH(ar.attendance_date) = :m AND YEAR(ar.attendance_date) = :y
        ";
        
        $db->query($attendanceSql);
        $db->bind(':eid', $employeeId);
        $db->bind(':m', $month);
        $db->bind(':y', $year);
        $attendanceRaw = $db->resultSet();

        // ==========================================================
        // 3. EVENTS / AGENDA (BARU)
        // ==========================================================
        // Logic: Ambil yang is_event = 1 DAN Targetnya sesuai (Global/Dept/Specific)
        $eventSql = "
            SELECT 
                title, 
                event_date as date, 
                event_time, 
                event_location,
                type as badge_type -- info, warning, danger, success
            FROM announcements
            WHERE is_event = 1
            AND MONTH(event_date) = :m AND YEAR(event_date) = :y
            AND (
                target_type = 'all'
                OR (target_type = 'department' AND target_id = :deptId)
                OR (target_type = 'specific' AND FIND_IN_SET(:uid, target_employee_ids))
            )
        ";
        
        $db->query($eventSql);
        $db->bind(':m', $month);
        $db->bind(':y', $year);
        $db->bind(':deptId', $deptId);
        $db->bind(':uid', $userId);
        try { $eventsRaw = $db->resultSet(); } catch (Exception $e) { $eventsRaw = []; }

        // ==========================================================
        // 4. HOLIDAY & REQUESTS
        // ==========================================================
        $db->query("SELECT holiday_date, holiday_name FROM holidays WHERE MONTH(holiday_date)=:m AND YEAR(holiday_date)=:y");
        $db->bind(':m', $month);
        $db->bind(':y', $year);
        try { $holidaysRaw = $db->resultSet(); } catch(Exception $e){ $holidaysRaw=[]; }

        $requestSql = "
            SELECT request_date as date, 'leave' as type, reason as note FROM leave_requests WHERE employee_id=:eid AND status='approved' AND MONTH(request_date)=:m AND YEAR(request_date)=:y
            UNION
            SELECT start_date as date, 'trip' as type, destination as note FROM business_trip_requests WHERE employee_id=:eid AND status='approved' AND MONTH(start_date)=:m AND YEAR(start_date)=:y
        ";
        $db->query($requestSql);
        $db->bind(':eid', $employeeId);
        $db->bind(':m', $month);
        $db->bind(':y', $year);
        try { $requestsRaw = $db->resultSet(); } catch(Exception $e){ $requestsRaw=[]; }


        // ==========================================================
        // 5. MERGE DATA KE CALENDAR
        // ==========================================================
        $calendarData = [];

        // A. Layer Jadwal
        if(!empty($scheduleRaw)) {
            foreach ($scheduleRaw as $row) {
                $calendarData[$row['date']] = [
                    'has_shift' => true, 
                    'shift_name' => $row['shift_name'],
                    'shift_code' => $row['shift_code'],
                    'shift_start' => $row['shift_start'],
                    'shift_end' => $row['shift_end'],
                    'is_mod' => $row['is_mod']
                ];
            }
        }

        // B. Layer Absen
        if(!empty($attendanceRaw)) {
            foreach ($attendanceRaw as $row) {
                $d = $row['date'];
                if (!isset($calendarData[$d])) $calendarData[$d] = [];
                $calendarData[$d]['status'] = $row['status'];
                $calendarData[$d]['clock_in'] = $row['clock_in'];
                $calendarData[$d]['clock_out'] = $row['clock_out'];
                $calendarData[$d]['is_late'] = $row['is_late'];
            }
        }

        // C. Layer Holiday & Requests
        if(!empty($holidaysRaw)) {
            foreach ($holidaysRaw as $h) {
                $calendarData[$h['holiday_date']]['is_holiday'] = true;
                $calendarData[$h['holiday_date']]['holiday_name'] = $h['holiday_name'];
            }
        }
        if(!empty($requestsRaw)) {
            foreach ($requestsRaw as $r) {
                $calendarData[$r['date']]['request_type'] = $r['type'];
                $calendarData[$r['date']]['request_note'] = $r['note'];
            }
        }

        // D. Layer EVENTS (NEW CODE)
        // Kita loop eventsRaw dan masukkan ke calendarData['events']
        // Karena satu hari bisa ada banyak event, kita simpan dalam array []
        if(!empty($eventsRaw)) {
            foreach ($eventsRaw as $ev) {
                $d = $ev['date'];
                if (!isset($calendarData[$d])) $calendarData[$d] = [];
                
                // Inisialisasi array events jika belum ada
                if (!isset($calendarData[$d]['events'])) {
                    $calendarData[$d]['events'] = [];
                }

                $calendarData[$d]['events'][] = [
                    'title' => $ev['title'],
                    'time' => $ev['event_time'],
                    'location' => $ev['event_location'],
                    'type' => $ev['badge_type']
                ];
            }
        }

        // ==========================================================
        // 6. STATS
        // ==========================================================
        $statsSql = "
            SELECT 
                COUNT(CASE WHEN status = 'present' THEN 1 END) as total_present,
                COUNT(CASE WHEN is_late = 1 THEN 1 END) as total_late,
                COUNT(CASE WHEN status = 'leave' THEN 1 END) as total_leave,
                COUNT(CASE WHEN status IN ('absent','alpha') THEN 1 END) as total_alpha
            FROM attendance_records 
            WHERE employee_id = :eid AND MONTH(attendance_date) = :m AND YEAR(attendance_date) = :y
        ";
        
        $db->query($statsSql);
        $db->bind(':eid', $employeeId);
        $db->bind(':m', $month);
        $db->bind(':y', $year);
        $stats = $db->single();

        // Render Data
        $data = [
            'title' => 'Jadwal & Absensi',
            'user_name' => $_SESSION['full_name'],
            'stats' => $stats,
            'calendar' => [
                'month' => $month, 'year' => $year,
                'days_in_month' => cal_days_in_month(CAL_GREGORIAN, $month, $year),
                'start_day_offset' => date('N', strtotime("$year-$month-01")) - 1,
                'data' => $calendarData
            ],
            'content_view' => 'staff/schedules/index'
        ];

        $this->view('staff/layouts/staff-layout', $data);
    }
}