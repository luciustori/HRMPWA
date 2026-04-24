<?php
// public/api/leaves/get_leaves.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

ini_set('display_errors', 0);
error_reporting(E_ALL);

// 1. KONEKSI DATABASE (Robust)
$dbPath = '../config/database.php';
if (!file_exists($dbPath)) {
    $dbPath = __DIR__ . '/../../../config/database.php';
}
if (!file_exists($dbPath)) {
    echo json_encode(["status" => false, "message" => "Database config not found."]);
    exit;
}
require_once $dbPath;

try {
    $db = (new Database())->getConnection();
    
    $user_id = $_GET['user_id'] ?? 0;
    // Tambahan Filter Tanggal
    $month   = $_GET['month'] ?? date('m');
    $year    = $_GET['year'] ?? date('Y');

    // 2. Employee ID
    $stmt = $db->prepare("SELECT employee_id FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$u) { echo json_encode(["status"=>true, "data"=>[], "stats"=>[]]); exit; }
    $eid = $u['employee_id'];

    // 3. Query Union (Requests + SPPD + Lembur) dengan Filter Bulan
    // Kita filter berdasarkan start_date / overtime_date
    $sql = "
        SELECT 'leave' as source, id, leave_type_id as type_id, reason, start_date, end_date, status, created_at, NULL as extra_info 
        FROM leave_requests 
        WHERE employee_id = :eid1 AND MONTH(start_date) = :m1 AND YEAR(start_date) = :y1
        
        UNION ALL
        
        SELECT 'sppd' as source, id, 0 as type_id, trip_purpose as reason, start_date, end_date, status, created_at, destination as extra_info
        FROM business_trip_requests 
        WHERE employee_id = :eid2 AND MONTH(start_date) = :m2 AND YEAR(start_date) = :y2
        
        UNION ALL
        
        SELECT 'lembur' as source, id, 0 as type_id, reason, overtime_date as start_date, overtime_date as end_date, status, created_at, CONCAT(start_time, ' - ', end_time) as extra_info
        FROM overtime_requests 
        WHERE employee_id = :eid3 AND MONTH(overtime_date) = :m3 AND YEAR(overtime_date) = :y3
        
        ORDER BY start_date DESC
    ";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':eid1'=>$eid, ':m1'=>$month, ':y1'=>$year,
        ':eid2'=>$eid, ':m2'=>$month, ':y2'=>$year,
        ':eid3'=>$eid, ':m3'=>$month, ':y3'=>$year
    ]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 4. Mapping Nama
    $stmtT = $db->query("SELECT id, leave_type_name FROM leave_types");
    $typesMap = $stmtT->fetchAll(PDO::FETCH_KEY_PAIR);

    $final = [];
    $stats = ['pending'=>0, 'approved'=>0, 'rejected'=>0];

    foreach($rows as $r) {
        if(isset($stats[$r['status']])) $stats[$r['status']]++;
        
        if($r['source'] == 'leave') {
            $r['type_name'] = $typesMap[$r['type_id']] ?? 'Ijin';
        } else if($r['source'] == 'sppd') {
            $r['type_name'] = "SPPD - " . $r['extra_info'];
        } else if($r['source'] == 'lembur') {
            $r['type_name'] = "Lembur (" . $r['extra_info'] . ")";
        }
        
        $final[] = $r;
    }

    echo json_encode([
        "status" => true,
        "data" => $final,
        "stats" => $stats
    ]);

} catch (Exception $e) {
    echo json_encode(["status" => false, "message" => $e->getMessage()]);
}
?>