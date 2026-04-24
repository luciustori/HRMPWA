<?php
// public/api/leaves/submit_leave.php

ini_set('display_errors', 0);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

// Path Database
$dbPath = '../config/database.php';
if (!file_exists($dbPath)) {
    echo json_encode(["status" => false, "message" => "Config DB error"]);
    exit;
}
require_once $dbPath;

$db = (new Database())->getConnection();
$data = json_decode(file_get_contents("php://input"));

if(empty($data->user_id)) {
    echo json_encode(["status"=>false, "message"=>"User ID required"]);
    exit;
}

try {
    // 1. Get Employee ID
    $stmt = $db->prepare("SELECT employee_id FROM users WHERE id = ?");
    $stmt->execute([$data->user_id]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$u) throw new Exception("User ID invalid");
    $eid = $u['employee_id'];

    // 2. Upload File (Base64)
    $docPath = null;
    if (!empty($data->attachment)) {
        $parts = explode(";base64,", $data->attachment);
        if(isset($parts[1])) {
            $image_base64 = base64_decode($parts[1]);
            $file = 'doc_' . time() . '.jpg';
            $uploadDir = __DIR__ . '/../../uploads/docs/'; 
            if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            file_put_contents($uploadDir . $file, $image_base64);
            $docPath = $file;
        }
    }

    // === 3. LOGIC INSERT BERDASARKAN KATEGORI ===

    // A. LEMBUR (OVERTIME) - BARU DITAMBAHKAN
    if ($data->category === 'lembur') {
        // Hitung Total Jam
        $t1 = strtotime($data->start_time);
        $t2 = strtotime($data->end_time);
        
        // Handle jika lembur lintas hari (selesai besok pagi)
        if ($t2 < $t1) $t2 += 24 * 3600;
        
        $diff = $t2 - $t1;
        $hours = round($diff / 3600, 2); // 2 desimal

        $sql = "INSERT INTO overtime_requests (employee_id, overtime_date, start_time, end_time, total_hours, reason, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())";
        
        $db->prepare($sql)->execute([
            $eid, 
            $data->overtime_date, // Pastikan field ini dikirim dari frontend
            $data->start_time, 
            $data->end_time, 
            $hours, 
            $data->reason
        ]);
    }
    
    // B. SPPD
    else if ($data->category === 'sppd') {
        $days = (new DateTime($data->start_date))->diff(new DateTime($data->end_date))->days + 1;
        $sql = "INSERT INTO business_trip_requests (employee_id, trip_purpose, destination, start_date, end_date, total_days, estimated_budget, supporting_document, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";
        $db->prepare($sql)->execute([$eid, $data->reason, $data->destination, $data->start_date, $data->end_date, $days, $data->budget, $docPath]);
    } 
    
    // C. IJIN / CUTI / DINAS
    else {
        $days = (new DateTime($data->start_date))->diff(new DateTime($data->end_date))->days + 1;
        $ltid = ($data->category == 'cuti') ? $data->annual_id : (($data->category == 'dinas_luar') ? $data->dl_id : $data->leave_type_id);
        
        $sql = "INSERT INTO leave_requests (employee_id, leave_type_id, request_date, start_date, end_date, total_days, reason, supporting_document, status, created_at) 
                VALUES (?, ?, CURDATE(), ?, ?, ?, ?, ?, 'pending', NOW())";
        $db->prepare($sql)->execute([$eid, $ltid, $data->start_date, $data->end_date, $days, $data->reason, $docPath]);
    }

    echo json_encode(["status" => true, "message" => "Berhasil dikirim!"]);

} catch (Exception $e) {
    echo json_encode(["status" => false, "message" => $e->getMessage()]);
}
?>