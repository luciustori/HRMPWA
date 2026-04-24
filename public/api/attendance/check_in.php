<?php
// FILE: public/api/attendance/check_in.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

ini_set('display_errors', 0);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Jakarta'); 

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { http_response_code(200); exit(); }

require_once '../config/database.php';

try {
    $db = (new Database())->getConnection();
    $input = file_get_contents("php://input");
    $data = json_decode($input);

    if (empty($data->user_id)) throw new Exception("User ID tidak terbaca");
    if (empty($data->photo_base64)) throw new Exception("Foto tidak terkirim");
    if (empty($data->latitude)) throw new Exception("Koordinat GPS kosong");

    $stmtUser = $db->prepare("SELECT employee_id FROM users WHERE id = ?");
    $stmtUser->execute([$data->user_id]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
    if (!$user) throw new Exception("User ID tidak valid di database");
    $employeeId = $user['employee_id'];

    $reason = $data->reason ?? null;
    $today = date('Y-m-d');
    
    // 1. CEK RIWAYAT HARI INI
    $stmtCek = $db->prepare("SELECT id, check_out_time FROM attendance_records WHERE employee_id = ? AND attendance_date = ? ORDER BY id DESC LIMIT 1");
    $stmtCek->execute([$employeeId, $today]);
    $lastRecord = $stmtCek->fetch(PDO::FETCH_ASSOC);

    // 2. LOGIC ABSEN KEDUA (LEMBUR/EVENT XT SQUARE)
    $isMultipleCheckin = false;
    if ($lastRecord) {
        if (empty($lastRecord['check_out_time'])) {
            throw new Exception("Anda belum Check-Out dari sesi sebelumnya!");
        }
        
        if (empty($reason)) {
            echo json_encode([
                "status" => "need_reason", 
                "message" => "Ini adalah absen ke-2 Anda hari ini. Wajib mengisi alasan (contoh: Lembur Event Malam)."
            ]);
            exit();
        }
        $isMultipleCheckin = true;
    }

    // 3. PROSES SIMPAN FOTO
    $uploadDir = "../../uploads/attendance/";
    if (!file_exists($uploadDir)) { mkdir($uploadDir, 0777, true); }

    $imgParts = explode(";base64,", $data->photo_base64);
    $imgExt = explode("image/", $imgParts[0])[1] ?? 'jpg';
    $imgBase64 = base64_decode($imgParts[1] ?? $data->photo_base64);

    $fileName = "IN_" . $employeeId . "_" . time() . "." . $imgExt;
    $filePath = $uploadDir . $fileName;

    if (!file_put_contents($filePath, $imgBase64)) throw new Exception("Gagal menyimpan foto");

    // 4. SIAPKAN DATA DATABASE
    $jamSekarang = date("H:i:s");           
    $waktuDatabase = date("Y-m-d H:i:s");   
    $batasTelat = "08:15:00"; 
    $status = ($jamSekarang > $batasTelat) ? "late" : "present";
    
    $lateMinutes = 0;
    if ($status == "late") {
        $lateMinutes = round(abs(strtotime($jamSekarang) - strtotime("08:00:00")) / 60, 0);
    }

    // 5. INSERT KE ATTENDANCE_RECORDS
    $sql = "INSERT INTO attendance_records 
            (employee_id, attendance_date, check_in_time, status, check_in_latitude, check_in_longitude, check_in_photo, check_in_location_id, is_late, late_duration_minutes) 
            VALUES 
            (:eid, :date, :waktu_db, :status, :lat, :lng, :photo, :office, :is_late, :late_min)";
            
    $stmt = $db->prepare($sql);
    $exec = $stmt->execute([
        ':eid'      => $employeeId,
        ':date'     => $today,
        ':waktu_db' => $waktuDatabase, 
        ':status'   => $status,
        ':lat'      => $data->latitude,
        ':lng'      => $data->longitude,
        ':photo'    => $fileName,
        ':office'   => $data->office_id ?? null,
        ':is_late'  => ($status == 'late' ? 1 : 0),
        ':late_min' => $lateMinutes
    ]);

    if ($exec) {
        $newAttendanceId = $db->lastInsertId();

        // 6. JIKA INI ABSEN KEDUA, CATAT KE ATTENDANCE_ISSUES
        if ($isMultipleCheckin && !empty($reason)) {
            $sqlIssue = "INSERT INTO attendance_issues 
                         (attendance_id, employee_id, issue_type, issue_description, submitted_at, employee_reason, status) 
                         VALUES (?, ?, 'multiple_checkin', 'Melakukan Check-In lebih dari 1 kali dalam sehari', NOW(), ?, 'pending')";
            $stmtIssue = $db->prepare($sqlIssue);
            $stmtIssue->execute([$newAttendanceId, $employeeId, $reason]);
        }

        echo json_encode([
            "status" => true, 
            "message" => "Berhasil Check-In Pukul " . $jamSekarang,
            "data" => ["time" => $jamSekarang, "status" => $status]
        ]);
    } else {
        throw new Exception("Gagal insert database");
    }

} catch (Exception $e) {
    http_response_code(200); 
    echo json_encode(["status" => false, "message" => $e->getMessage()]);
}
?>