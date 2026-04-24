<?php
// FILE: public/api/attendance/dashboard_data.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

require_once '../config/database.php';

try {
    $db = (new Database())->getConnection();
    $user_id = $_GET['user_id'] ?? 0;

    // 1. QUERY USER & EMPLOYEE (PERBAIKAN: CONCAT first_name & last_name sebagai full_name)
    $query = "SELECT 
                u.employee_id, 
                e.first_name, 
                e.last_name, 
                CONCAT(e.first_name, ' ', COALESCE(e.last_name, '')) as full_name,
                e.position, 
                e.profile_photo_path,
                e.annual_leave_balance,
                'Kantor Pusat' as office_name 
              FROM users u
              JOIN employees e ON u.employee_id = e.id
              WHERE u.id = ?";
    
    $stmt = $db->prepare($query);
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) { 
        echo json_encode(["status" => false, "message" => "User not found"]); 
        exit(); 
    }
    $eid = $user['employee_id'];

    // 2. LOGIC NAMA DEPAN
    $firstName = !empty($user['first_name']) ? $user['first_name'] : explode(' ', trim($user['full_name']))[0];
    $firstName = ucfirst(strtolower($firstName));

    // 3. CEK ABSEN HARI INI
    $todayDate = date('Y-m-d');
    $stmtToday = $db->prepare("SELECT check_in_time, check_out_time 
                               FROM attendance_records 
                               WHERE employee_id = ? AND attendance_date = ? 
                               ORDER BY id DESC LIMIT 1");
    $stmtToday->execute([$eid, $todayDate]);
    $lastSession = $stmtToday->fetch(PDO::FETCH_ASSOC);

    // 4. HISTORY (7 Hari Terakhir)
    $stmtHist = $db->prepare("SELECT attendance_date, check_in_time, check_out_time, status, check_in_address 
                              FROM attendance_records 
                              WHERE employee_id = ? 
                              ORDER BY attendance_date DESC, check_in_time DESC LIMIT 7");
    $stmtHist->execute([$eid]);
    $rawHistory = $stmtHist->fetchAll(PDO::FETCH_ASSOC);

    $history = [];
    foreach($rawHistory as $row) {
        $durasi = "-";
        if($row['check_in_time'] && $row['check_out_time']) {
            $t1 = strtotime($row['check_in_time']);
            $t2 = strtotime($row['check_out_time']);
            $diff = $t2 - $t1;
            $h = floor($diff / 3600);
            $m = floor(($diff % 3600) / 60);
            $durasi = "{$h}j {$m}m";
        }
        $row['work_duration'] = $durasi;
        $row['location'] = !empty($row['check_in_address']) ? $row['check_in_address'] : $user['office_name'];
        $history[] = $row;
    }

    // 5. STATISTIK BULANAN
    $month = date('m'); $year = date('Y');
    $stmtStats = $db->prepare("SELECT 
        SUM(CASE WHEN status IN ('present', 'on_time') THEN 1 ELSE 0 END) as total_present,
        SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as total_late
        FROM attendance_records 
        WHERE employee_id = ? AND MONTH(attendance_date) = ? AND YEAR(attendance_date) = ?");
    $stmtStats->execute([$eid, $month, $year]);
    $stats = $stmtStats->fetch(PDO::FETCH_ASSOC);

    // 6. HITUNG SISA CUTI AKURAT
    $stmtCuti = $db->prepare("SELECT COALESCE(SUM(lr.total_days), 0) as used_days
                              FROM leave_requests lr
                              JOIN leave_types lt ON lr.leave_type_id = lt.id
                              WHERE lr.employee_id = ? AND lr.status = 'approved' 
                              AND YEAR(lr.start_date) = ? AND lt.leave_code IN ('ANNUAL', 'CUTI_TAHUNAN')");
    $stmtCuti->execute([$eid, $year]);
    $cuti = $stmtCuti->fetch(PDO::FETCH_ASSOC);
    $base_quota = $user['annual_leave_balance'] ?? 12;
    $sisa_cuti = max(0, $base_quota - ($cuti['used_days'] ?? 0));

    // --- 7. CEK JADWAL SHIFT HARI INI ---
    $stmtShift = $db->prepare("SELECT ws.shift_name, ws.start_time, ws.end_time 
                               FROM shift_assignments sa 
                               JOIN work_shifts ws ON sa.shift_id = ws.id 
                               WHERE sa.employee_id = ? AND sa.assignment_date = ?");
    $stmtShift->execute([$eid, $todayDate]);
    $shiftInfo = $stmtShift->fetch(PDO::FETCH_ASSOC);

    // Fallback: Jika di kalender ga ada, cek apakah record absen hari ini menyimpan shift_id
    if (!$shiftInfo) {
        $stmtAttShift = $db->prepare("SELECT ws.shift_name, ws.start_time, ws.end_time 
                                      FROM attendance_records ar 
                                      JOIN work_shifts ws ON ar.shift_id = ws.id 
                                      WHERE ar.employee_id = ? AND ar.attendance_date = ?");
        $stmtAttShift->execute([$eid, $todayDate]);
        $shiftInfo = $stmtAttShift->fetch(PDO::FETCH_ASSOC);
    }

    if ($shiftInfo) {
        $shiftStr = strtoupper($shiftInfo['shift_name']) . " (" . substr($shiftInfo['start_time'], 0, 5) . " - " . substr($shiftInfo['end_time'], 0, 5) . ")";
    } else {
        $shiftStr = "REGULAR (08:00 - 17:00)"; // Default jika off/tidak ada jadwal
    }

    echo json_encode([
        "status" => true,
        "user_info" => [
            "first_name" => $firstName,
            "full_name" => $user['full_name'],
            "position" => $user['position'] ?? 'Karyawan',
            "location" => $user['office_name'],
            "profile_photo" => $user['profile_photo_path'] 
        ],
        "today_shift" => $shiftStr,
        "last_session" => $lastSession,
        "stats" => [
            "total_present" => $stats['total_present'] ?? 0,
            "total_late" => $stats['total_late'] ?? 0,
            "sisa_cuti" => $sisa_cuti 
        ],
        "history" => $history
    ]);

} catch (Exception $e) {
    // Ngirim error ke browser biar lu bisa inspect lewat console / network!
    echo json_encode(["status" => false, "message" => $e->getMessage()]);
}
?>