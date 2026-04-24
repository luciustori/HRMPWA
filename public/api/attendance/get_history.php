<?php
// FILE: public/api/attendance/get_history.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { http_response_code(200); exit(); }

ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once '../config/database.php';

try {
    $db = (new Database())->getConnection();
    
    $user_id = $_GET['user_id'] ?? 0;
    $month   = $_GET['month'] ?? date('n');
    $year    = $_GET['year'] ?? date('Y');

    // 2. VALIDASI USER
    $stmt = $db->prepare("SELECT employee_id FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) { echo json_encode(["status" => false, "message" => "User invalid"]); exit(); }
    $eid = $user['employee_id'];

    // 3. LOGIC CUTOFF (25 Bulan Lalu - 24 Bulan Ini)
    $endDate = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-24";
    $prevMonth = $month - 1;
    $prevYear  = $year;
    if ($prevMonth == 0) { $prevMonth = 12; $prevYear = $year - 1; }
    $startDate = "$prevYear-" . str_pad($prevMonth, 2, '0', STR_PAD_LEFT) . "-25";

    // 4. QUERY GABUNGAN (UNION 4 TABEL: ABSEN, CUTI, SPPD, LEMBUR)
    $sql = "
        /* 1. ABSENSI */
        SELECT 'attendance' as type, id, attendance_date as date, check_in_time as time_start, check_out_time as time_end, status, 'Kantor Pusat' as location, NULL as reason, NULL as category_label
        FROM attendance_records 
        WHERE employee_id = :eid1 AND (attendance_date BETWEEN :start1 AND :end1)
        
        UNION ALL
        
        /* 2. CUTI / IJIN */
        SELECT 'leave' as type, id, start_date as date, NULL, NULL, status, 'Pengajuan' as location, reason, 
        (SELECT leave_type_name FROM leave_types WHERE id = leave_requests.leave_type_id) as category_label
        FROM leave_requests
        WHERE employee_id = :eid2 AND (start_date BETWEEN :start2 AND :end2)
        
        UNION ALL
        
        /* 3. SPPD */
        SELECT 'sppd' as type, id, start_date as date, NULL, NULL, status, destination as location, trip_purpose as reason, 'Perjalanan Dinas' as category_label
        FROM business_trip_requests
        WHERE employee_id = :eid3 AND (start_date BETWEEN :start3 AND :end3)

        UNION ALL

        /* 4. LEMBUR */
        SELECT 'overtime' as type, id, overtime_date as date, start_time, end_time, status, 'Lembur' as location, reason, 'Lembur' as category_label
        FROM overtime_requests
        WHERE employee_id = :eid4 AND (overtime_date BETWEEN :start4 AND :end4)

        ORDER BY date DESC
    ";

    $stmt = $db->prepare($sql);
    $params = [
        ':eid1'=>$eid, ':start1'=>$startDate, ':end1'=>$endDate,
        ':eid2'=>$eid, ':start2'=>$startDate, ':end2'=>$endDate,
        ':eid3'=>$eid, ':start3'=>$startDate, ':end3'=>$endDate,
        ':eid4'=>$eid, ':start4'=>$startDate, ':end4'=>$endDate
    ];
    $stmt->execute($params);
    $raw = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 5. OLAH DATA
    $finalData = [];
    $stats = [
        'Hadir' => 0, 
        'Bolos' => 0, // Placeholder (Logic Bolos butuh generate calendar)
        'Lembur'=> 0,
        'Ijin'  => 0, 
        'Cuti'  => 0, 
        'SPPD'  => 0
    ];

    foreach($raw as $r) {
        $item = [
            'id' => $r['id'],
            'date' => $r['date'],
            'type' => $r['type'],
            'status' => $r['status'],
            'time_start' => '--:--',
            'time_end' => '--:--',
            'location' => $r['location'] ?? '-',
            'category' => '', // Untuk Filter UI: Hadir, Lembur, Ijin, Cuti, SPPD, Bolos
            'sub_status' => '',
            'work_duration' => '-',
            'source' => $r['type']
        ];

        // --- FORMATTING & KATEGORISASI ---
        if ($r['type'] == 'attendance') {
            $item['category'] = 'Hadir';
            $item['time_start'] = $r['time_start'] ? date('H:i', strtotime($r['time_start'])) : '--:--';
            $item['time_end']   = $r['time_end'] ? date('H:i', strtotime($r['time_end'])) : '--:--';
            
            // Hitung Durasi
            if($r['time_start'] && $r['time_end']) {
                $t1 = strtotime($r['time_start']);
                $t2 = strtotime($r['time_end']);
                $diff = $t2 - $t1;
                $h = floor($diff / 3600);
                $m = floor(($diff % 3600) / 60);
                $item['work_duration'] = "{$h}j {$m}m";
            }
            
            // Cek status khusus jika ada (Misal alpha/absent di table attendance)
            if ($r['status'] == 'absent' || $r['status'] == 'alpha') {
                $item['category'] = 'Bolos';
                $stats['Bolos']++;
            } else {
                $stats['Hadir']++;
            }

        } elseif ($r['type'] == 'leave') {
            $label = $r['category_label'] ?? 'Ijin';
            // Deteksi Cuti vs Ijin dari Nama Tipe
            if (stripos($label, 'Cuti') !== false) {
                $item['category'] = 'Cuti';
                $stats['Cuti']++;
            } else {
                $item['category'] = 'Ijin';
                $stats['Ijin']++;
            }
            $item['sub_status'] = $label;

        } elseif ($r['type'] == 'sppd') {
            $item['category'] = 'SPPD';
            $item['sub_status'] = $r['location']; 
            $stats['SPPD']++;

        } elseif ($r['type'] == 'overtime') {
            $item['category'] = 'Lembur';
            $item['sub_status'] = $r['reason'];
            $item['time_start'] = substr($r['time_start'], 0, 5);
            $item['time_end'] = substr($r['time_end'], 0, 5);
            $stats['Lembur']++;
        }

        $finalData[] = $item;
    }

    echo json_encode([
        "status" => true,
        "summary" => $stats,
        "data" => $finalData
    ]);

} catch (Exception $e) {
    echo json_encode(["status" => false, "message" => $e->getMessage()]);
}
?>