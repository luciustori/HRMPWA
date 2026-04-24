<?php
// FILE: public/api/attendance/checkout.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

ini_set('display_errors', 0); 
error_reporting(E_ALL);
date_default_timezone_set('Asia/Jakarta'); 

// Handle Preflight Request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { 
    http_response_code(200); 
    exit(); 
}

require_once '../config/database.php';

try {
    $db = (new Database())->getConnection();
    $input = file_get_contents("php://input");
    $data = json_decode($input);

    // 1. VALIDASI INPUT AWAL (Validasi foto DIHAPUS!)
    if (empty($data->user_id)) throw new Exception("User ID tidak terbaca");
    if (empty($data->latitude) || empty($data->longitude)) throw new Exception("Koordinat lokasi tidak terbaca");

    $stmtUser = $db->prepare("SELECT employee_id FROM users WHERE id = ?");
    $stmtUser->execute([$data->user_id]);
    $employeeId = $stmtUser->fetchColumn();
    
    if (!$employeeId) throw new Exception("User ID tidak valid di database");

    $reason = $data->reason ?? null;
    $today = date('Y-m-d');
    $jamSekarang = date("H:i:s");
    $waktuDatabase = date("Y-m-d H:i:s");
    
    // Asumsi jam shift reguler pulang adalah 17:00
    $jamPulangNormal = "17:00:00"; 

    // 2. CARI ABSEN HARI INI YANG BELUM CHECKOUT
    $stmtCek = $db->prepare("SELECT id FROM attendance_records WHERE employee_id = ? AND attendance_date = ? AND check_out_time IS NULL ORDER BY id DESC LIMIT 1");
    $stmtCek->execute([$employeeId, $today]);
    $record = $stmtCek->fetch(PDO::FETCH_ASSOC);

    if (!$record) {
        throw new Exception("Anda belum Check-In atau sudah melakukan Check-Out sebelumnya!");
    }
    $currentAttendanceId = $record['id'];

    // 3. CEK LOGIC PULANG AWAL
    $isEarlyCheckout = false;
    if ($jamSekarang < $jamPulangNormal) {
        if (empty($reason)) {
            // Minta Frontend untuk munculin SweetAlert Form
            echo json_encode([
                "status" => "need_reason", 
                "message" => "Anda pulang lebih awal (sebelum 17:00). Wajib mengisi alasan secara jelas."
            ]);
            exit();
        }
        $isEarlyCheckout = true;
    }

    // (PROSES SIMPAN FOTO CHECKOUT DIHAPUS TOTAL DARI SINI)

    // 4. UPDATE TABEL UTAMA (Hanya update jam dan lokasi)
    $sql = "UPDATE attendance_records 
            SET check_out_time = :waktu_db, 
                check_out_latitude = :lat, 
                check_out_longitude = :lng 
            WHERE id = :id";
            
    $stmt = $db->prepare($sql);
    $exec = $stmt->execute([
        ':waktu_db' => $waktuDatabase,
        ':lat'      => $data->latitude,
        ':lng'      => $data->longitude,
        ':id'       => $currentAttendanceId
    ]);

    if ($exec) {
        // 5. JIKA PULANG AWAL, CATAT KE ATTENDANCE_ISSUES
        if ($isEarlyCheckout && !empty($reason)) {
            $sqlIssue = "INSERT INTO attendance_issues 
                         (attendance_id, employee_id, issue_type, issue_description, submitted_at, employee_reason, status) 
                         VALUES (?, ?, 'early_checkout', 'Pulang sebelum jam shift berakhir', NOW(), ?, 'pending')";
            $stmtIssue = $db->prepare($sqlIssue);
            $stmtIssue->execute([$currentAttendanceId, $employeeId, $reason]);
        }

        echo json_encode([
            "status" => true, 
            "message" => "Berhasil Check-Out Pukul " . $jamSekarang
        ]);
    } else {
        throw new Exception("Gagal update database");
    }

} catch (Exception $e) {
    http_response_code(200); 
    echo json_encode(["status" => false, "message" => $e->getMessage()]);
}
?>