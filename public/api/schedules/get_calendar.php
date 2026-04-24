<?php
// FILE: public/api/schedules/get_calendar.php

ini_set('display_errors', 0);
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

$dbPath = '../config/database.php'; 
if (!file_exists($dbPath)) { 
    $dbPath = __DIR__ . '/../../../config/database.php';
}
require_once $dbPath;

try {
    $db = (new Database())->getConnection();

    $user_id = $_GET['user_id'] ?? 0;
    $month   = $_GET['month'] ?? date('m');
    $year    = $_GET['year'] ?? date('Y');

    // AMBIL DATA EMPLOYEE
    $stmt = $db->prepare("SELECT e.id as employee_id, e.department_id FROM users u JOIN employees e ON u.employee_id = e.id WHERE u.id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $eid = $user['employee_id'] ?? 0;
    $dept_id = $user['department_id'] ?? 0;

    // KERANGKA KALENDER
    $calendar = [];
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    for ($i = 1; $i <= $daysInMonth; $i++) {
        $dateStr = sprintf("%04d-%02d-%02d", $year, $month, $i);
        $calendar[$dateStr] = [
            'date' => $dateStr,
            'day' => $i,
            'is_today' => ($dateStr == date('Y-m-d')),
            'holiday' => null,
            'shift' => null,
            'attendance' => null,
            'tasks' => [],
            'events' => [],
            'leaves' => [],
            'duties' => []
        ];
    }

    // 1. HOLIDAYS
    $stmt = $db->prepare("SELECT holiday_date, holiday_name FROM holidays WHERE MONTH(holiday_date)=:m AND YEAR(holiday_date)=:y");
    $stmt->execute([':m' => $month, ':y' => $year]);
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        if(isset($calendar[$row['holiday_date']])) $calendar[$row['holiday_date']]['holiday'] = $row['holiday_name'];
    }

    // 2. SHIFT
    $stmt = $db->prepare("SELECT assignment_date, ws.shift_name, ws.start_time, ws.end_time FROM shift_assignments sa JOIN work_shifts ws ON sa.shift_id = ws.id WHERE sa.employee_id = :eid AND MONTH(assignment_date)=:m AND YEAR(assignment_date)=:y");
    $stmt->execute([':eid' => $eid, ':m' => $month, ':y' => $year]);
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        if(isset($calendar[$row['assignment_date']])){
            $calendar[$row['assignment_date']]['shift'] = [
                'name' => $row['shift_name'],
                'time' => substr($row['start_time'],0,5)." - ".substr($row['end_time'],0,5)
            ];
        }
    }

    // 3. ATTENDANCE
    $stmt = $db->prepare("SELECT attendance_date, check_in_time, check_out_time, is_late FROM attendance_records WHERE employee_id = :eid AND MONTH(attendance_date)=:m AND YEAR(attendance_date)=:y");
    $stmt->execute([':eid' => $eid, ':m' => $month, ':y' => $year]);
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        if(isset($calendar[$row['attendance_date']])){
            $calendar[$row['attendance_date']]['attendance'] = [
                'in' => $row['check_in_time'] ? substr($row['check_in_time'],11,5) : '--:--',
                'out' => $row['check_out_time'] ? substr($row['check_out_time'],11,5) : '--:--',
                'is_late' => $row['is_late'] == 1
            ];
        }
    }

    // 4. CUTI / IJIN / DINAS LUAR (Menggunakan BETWEEN & Menambahkan logic Kategori)
    $stmtLeave = $db->prepare("SELECT r.start_date, r.end_date, lt.leave_type_name, lt.leave_code, r.reason 
                               FROM leave_requests r 
                               JOIN leave_types lt ON r.leave_type_id = lt.id 
                               WHERE r.employee_id = :eid AND r.status = 'approved' 
                               AND ((MONTH(r.start_date) = :m AND YEAR(r.start_date) = :y) OR (MONTH(r.end_date) = :m2 AND YEAR(r.end_date) = :y2))");
    $stmtLeave->execute([':eid' => $eid, ':m' => $month, ':y' => $year, ':m2' => $month, ':y2' => $year]);
    
    while($lv = $stmtLeave->fetch(PDO::FETCH_ASSOC)) {
        // Tentukan Kategori Berdasarkan Kode
        $code = strtoupper($lv['leave_code']);
        $cat = 'ijin';
        if (in_array($code, ['ANNUAL', 'CUTI_TAHUNAN'])) $cat = 'cuti';
        elseif (in_array($code, ['DL', 'DINAS_LUAR'])) $cat = 'dl';

        // Loop untuk menyisipkan ke rentang tanggal di kalender
        $currentDate = strtotime($lv['start_date']);
        $endDate = strtotime($lv['end_date']);
        
        while ($currentDate <= $endDate) {
            $dateKey = date('Y-m-d', $currentDate);
            if (isset($calendar[$dateKey])) {
                $calendar[$dateKey]['leaves'][] = [
                    "category" => $cat,
                    "type_name" => $lv['leave_type_name'],
                    "reason" => $lv['reason']
                ];
            }
            $currentDate = strtotime("+1 day", $currentDate);
        }
    }

    // 5. SPPD
    $stmtSppd = $db->prepare("SELECT start_date, end_date, destination, trip_purpose 
                              FROM business_trip_requests 
                              WHERE employee_id = :eid AND status = 'approved' 
                              AND ((MONTH(start_date) = :m AND YEAR(start_date) = :y) OR (MONTH(end_date) = :m2 AND YEAR(end_date) = :y2))");
    $stmtSppd->execute([':eid' => $eid, ':m' => $month, ':y' => $year, ':m2' => $month, ':y2' => $year]);
    
    while($sppd = $stmtSppd->fetch(PDO::FETCH_ASSOC)) {
        $currentDate = strtotime($sppd['start_date']);
        $endDate = strtotime($sppd['end_date']);
        
        while ($currentDate <= $endDate) {
            $dateKey = date('Y-m-d', $currentDate);
            if (isset($calendar[$dateKey])) {
                $calendar[$dateKey]['duties'][] = [
                    "title" => $sppd['destination'], 
                    "loc" => $sppd['trip_purpose']
                ];
            }
            $currentDate = strtotime("+1 day", $currentDate);
        }
    }

    $monthName = date('F', mktime(0, 0, 0, $month, 10));
    $firstDay = date('w', strtotime("$year-$month-01"));
    $start_offset = ($firstDay == 0) ? 6 : $firstDay - 1; 

    echo json_encode([
        "status" => true,
        "meta" => ["month_name" => $monthName, "year" => $year, "start_offset" => $start_offset],
        "data" => array_values($calendar) // Re-index array
    ]);

} catch (Exception $e) { echo json_encode(["status" => false, "message" => $e->getMessage()]); }
?>