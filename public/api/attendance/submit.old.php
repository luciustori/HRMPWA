<?php
// submit.php - MULTIPLE CHECK-IN (Bisa Lembur)

ini_set('display_errors', 0);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { http_response_code(200); exit(); }

require_once '../config/database.php';
$db = (new Database())->getConnection();

$data = json_decode(file_get_contents("php://input"));

if(empty($data->user_id) || empty($data->photo) || empty($data->latitude)) {
    echo json_encode(["status" => false, "message" => "Data tidak lengkap!"]);
    exit();
}

try {
    // 1. Cari Employee ID
    $stmtUser = $db->prepare("SELECT employee_id FROM users WHERE id = :uid");
    $stmtUser->execute([':uid' => $data->user_id]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
    if (!$user) throw new Exception("User invalid.");
    $employeeId = $user['employee_id'];

    // 2. LOGIC BARU: Cek apakah masih ada sesi yang "GANTUNG" (Belum Checkout)
    // Kalau ada record hari ini yang check_out_time-nya NULL, berarti dia belum pulang.
    $today = date('Y-m-d');
    $check = $db->prepare("SELECT id FROM attendance_records WHERE employee_id = ? AND attendance_date = ? AND check_out_time IS NULL");
    $check->execute([$employeeId, $today]);
    
    if ($check->rowCount() > 0) {
        throw new Exception("Selesaikan sesi sebelumnya (Checkout) sebelum Masuk lagi!");
    }

    // 3. Simpan Foto
    $photo_parts = explode(";base64,", $data->photo);
    $image_base64 = base64_decode($photo_parts[1] ?? '');
    $fileName = 'checkin_' . $employeeId . '_' . time() . '.jpg';
    $uploadDir = __DIR__ . '/../../../uploads/attendance/'; 
    if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
    file_put_contents($uploadDir . $fileName, $image_base64);
    $dbPath = 'uploads/attendance/' . $fileName; 

    // 4. INSERT SESI BARU
    // Status default 'present' (atau sesuaikan logika jam kerja jika mau 'late')
    $sql = "INSERT INTO attendance_records 
            (employee_id, attendance_date, check_in_time, check_in_photo, check_in_latitude, check_in_longitude, status, created_at) 
            VALUES (:eid, :today, NOW(), :photo, :lat, :long, 'present', NOW())";

    $stmt = $db->prepare($sql);
    $params = [
        ':eid' => $employeeId,
        ':today' => $today,
        ':photo' => $dbPath,
        ':lat' => $data->latitude,
        ':long' => $data->longitude
    ];

    if($stmt->execute($params)) {
        echo json_encode(["status" => true, "message" => "Check-In Berhasil! Semangat."]);
    } else {
        throw new Exception("Gagal Insert Database.");
    }

} catch (Exception $e) {
    echo json_encode(["status" => false, "message" => $e->getMessage()]);
}
?>