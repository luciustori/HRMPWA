<?php
// checkout.php - TUTUP SESI AKTIF

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

try {
    if(empty($data->user_id)) throw new Exception("User ID kosong.");

    // 1. Cari Employee
    $stmtUser = $db->prepare("SELECT employee_id FROM users WHERE id = :uid");
    $stmtUser->execute([':uid' => $data->user_id]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
    if (!$user) throw new Exception("User invalid.");
    $employeeId = $user['employee_id'];

    // 2. Cari Sesi AKTIF (Check Out masih NULL)
    $today = date('Y-m-d');
    $check = $db->prepare("SELECT id FROM attendance_records 
                           WHERE employee_id = ? AND attendance_date = ? AND check_out_time IS NULL 
                           ORDER BY id DESC LIMIT 1");
    $check->execute([$employeeId, $today]);
    $record = $check->fetch(PDO::FETCH_ASSOC);

    if (!$record) throw new Exception("Anda belum Check-In atau sesi sudah ditutup.");

    // 3. UPDATE PULANG
    $sql = "UPDATE attendance_records SET check_out_time = NOW() WHERE id = :id";
    $stmt = $db->prepare($sql);
    
    if($stmt->execute([':id' => $record['id']])) {
        echo json_encode(["status" => true, "message" => "Sesi ditutup. Istirahatlah!"]);
    } else {
        throw new Exception("Gagal update database.");
    }

} catch (Exception $e) {
    echo json_encode(["status" => false, "message" => $e->getMessage()]);
}
?>