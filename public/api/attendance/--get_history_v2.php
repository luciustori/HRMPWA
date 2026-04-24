<?php
// FILE: public/api/attendance/get_history_v2.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require_once '../config/database.php';

try {
    $db = (new Database())->getConnection();
    
    $user_id = $_GET['user_id'] ?? 0;
    $month   = $_GET['month'] ?? date('m');
    $year    = $_GET['year'] ?? date('Y');

    // 1. Ambil Employee ID
    $stmt = $db->prepare("SELECT employee_id FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) { echo json_encode(["status" => false, "message" => "User invalid"]); exit(); }
    $eid = $user['employee_id'];

    // 2. Query Data
    $sql = "
        SELECT 'attendance' as type, id, attendance_date as date, check_in_time, check_out_time, status, location, NULL as reason
        FROM attendance_records 
        WHERE employee_id = :eid1 AND MONTH(attendance_date) = :m1 AND YEAR(attendance_date) = :y1
        
        UNION ALL
        
        SELECT 'leave' as type, id, start_date as date, NULL, NULL, status, NULL, reason
        FROM leave_requests
        WHERE employee_id = :eid2 AND MONTH(start_date) = :m2 AND YEAR(start_date) = :y2
        
        UNION ALL
        
        SELECT 'sppd' as type, id, start_date as date, NULL, NULL, status, destination as location, trip_purpose as reason
        FROM business_trip_requests
        WHERE employee_id = :eid3 AND MONTH(start_date) = :m3 AND YEAR(start_date) = :y3

        ORDER BY date DESC
    ";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':eid1'=>$eid, ':m1'=>$month, ':y1'=>$year,
        ':eid2'=>$eid, ':m2'=>$month, ':y2'=>$year,
        ':eid3'=>$eid, ':m3'=>$month, ':y3'=>$year
    ]);
    $raw = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $finalData = [];
    $summary = ['hadir' => 0, 'telat' => 0, 'tepat' => 0, 'alpha' => 0, 'izin'=>0, 'dinas'=>0, 'cuti'=>0, 'sppd'=>0];

    foreach($raw as $r) {
        // INI BAGIAN PENTINGNYA: FORMATTING DATA
        // Kita siapkan default value agar tidak undefined
        $jamMasuk = '-';
        $jamPulang = '-';

        // Jika tipe absen, kita format jamnya
        if ($r['type'] == 'attendance') {
            // Ambil 5 karakter pertama dari jam (08:00:00 -> 08:00)
            $jamMasuk = $r['check_in_time'] ? date('H:i', strtotime($r['check_in_time'])) : '--:--';
            $jamPulang = $r['check_out_time'] ? date('H:i', strtotime($r['check_out_time'])) : '--:--';
            
            $summary['hadir']++;
            if($r['status'] == 'late') $summary['telat']++;
            else $summary['tepat']++;
        } 
        else if ($r['type'] == 'leave') $summary['izin']++;
        else if ($r['type'] == 'sppd') $summary['sppd']++;

        // Masukkan ke array dengan kunci 'time_start' dan 'time_end' yang dicari HTML
        $finalData[] = [
            'id' => $r['id'],
            'date' => $r['date'],
            'type' => $r['type'],
            'status' => $r['status'],
            'category' => ucfirst($r['type']),
            'time_start' => $jamMasuk,  // <--- HTML BACA INI
            'time_end' => $jamPulang,   // <--- HTML BACA INI
            'location' => $r['location'] ?? 'Kantor',
            'reason' => $r['reason'] ?? '',
            'source' => $r['type']
        ];
    }

    echo json_encode([
        "status" => true,
        "stats" => [
            "total_hadir" => $summary['hadir'],
            "terlambat" => $summary['telat'],
            "tepat_waktu" => $summary['tepat'],
            "tidak_hadir" => 0,
            "izin" => $summary['izin'], 
            "dinas" => 0, 
            "cuti" => 0, 
            "sppd" => $summary['sppd']
        ],
        "data" => $finalData
    ]);

} catch (Exception $e) {
    echo json_encode(["status" => false, "message" => $e->getMessage()]);
}
?>