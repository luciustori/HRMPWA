<?php
// public/api/attendance/get_history_v2.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once '../config/database.php';

try {
    $db = (new Database())->getConnection();
    $user_id = $_GET['user_id'] ?? 0;
    $month   = $_GET['month'] ?? date('m');
    $year    = $_GET['year'] ?? date('Y');

    // 1. Get Employee ID & Office
    $stmtUser = $db->prepare("SELECT u.employee_id, 'Kantor Pusat' as office_name FROM users u JOIN employees e ON u.employee_id = e.id WHERE u.id = ?");
    $stmtUser->execute([$user_id]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
    if (!$user) { echo json_encode(["status" => false]); exit(); }
    $eid = $user['employee_id'];
    $office = $user['office_name'];

    // 2. QUERY GABUNGAN
    $query = "
        -- 1. ABSENSI
        SELECT 
            'attendance' as type,
            attendance_date as date,
            check_in_time as time_start,
            check_out_time as time_end,
            status,
            'Hadir' as category,
            CASE WHEN status = 'late' THEN 'Terlambat' ELSE 'Tepat Waktu' END as sub_status,
            '$office' as location
        FROM attendance_records 
        WHERE employee_id = :eid1 AND MONTH(attendance_date) = :m1 AND YEAR(attendance_date) = :y1

        UNION ALL

        -- 2. LEAVE (Ijin/Cuti/Dinas)
        SELECT 
            'leave' as type,
            start_date as date,
            NULL as time_start,
            NULL as time_end,
            status,
            CASE 
                WHEN lt.leave_code IN ('ANNUAL', 'CUTI_TAHUNAN') THEN 'Cuti'
                WHEN lt.leave_code IN ('DL', 'DINAS_LUAR') THEN 'Dinas'
                ELSE 'Izin'
            END as category,
            lt.leave_type_name as sub_status,
            '-' as location
        FROM leave_requests lr
        JOIN leave_types lt ON lr.leave_type_id = lt.id
        WHERE lr.employee_id = :eid2 AND MONTH(start_date) = :m2 AND YEAR(start_date) = :y2

        UNION ALL

        -- 3. SPPD
        SELECT 
            'sppd' as type,
            start_date as date,
            NULL as time_start,
            NULL as time_end,
            status,
            'SPPD' as category,
            destination as sub_status,
            destination as location
        FROM business_trip_requests
        WHERE employee_id = :eid3 AND MONTH(start_date) = :m3 AND YEAR(start_date) = :y3

        ORDER BY date DESC
    ";

    $stmt = $db->prepare($query);
    $params = [
        ':eid1' => $eid, ':m1' => $month, ':y1' => $year,
        ':eid2' => $eid, ':m2' => $month, ':y2' => $year,
        ':eid3' => $eid, ':m3' => $month, ':y3' => $year
    ];
    $stmt->execute($params);
    $allData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. STATS & FORMATTING
    $stats = [
        'total_hadir' => 0, 'terlambat' => 0, 'tepat_waktu' => 0, 'tidak_hadir' => 0,
        'izin' => 0, 'dinas' => 0, 'cuti' => 0, 'sppd' => 0
    ];

    $finalData = [];
    foreach($allData as $row) {
        // Hitung Stats
        if ($row['type'] == 'attendance') {
            $stats['total_hadir']++;
            if ($row['status'] == 'late') $stats['terlambat']++;
            else $stats['tepat_waktu']++;
            
            // Hitung Durasi
            $durasi = "-";
            if($row['time_start'] && $row['time_end']) {
                $t1 = strtotime($row['time_start']);
                $t2 = strtotime($row['time_end']);
                $diff = $t2 - $t1;
                $h = floor($diff / 3600);
                $m = floor(($diff % 3600) / 60);
                $durasi = "{$h}j {$m}m";
            }
            $row['duration'] = $durasi;
        }
        else if ($row['type'] == 'leave') {
            if ($row['category'] == 'Cuti') $stats['cuti']++;
            else if ($row['category'] == 'Dinas') $stats['dinas']++;
            else $stats['izin']++;
            $row['duration'] = "Full Day";
        }
        else if ($row['type'] == 'sppd') {
            $stats['sppd']++;
            $row['duration'] = "Luar Kota";
        }
        
        $finalData[] = $row;
    }

    echo json_encode([
        "status" => true,
        "stats" => $stats,
        "data" => $finalData
    ]);

} catch (Exception $e) {
    echo json_encode(["status" => false, "message" => $e->getMessage()]);
}
?>