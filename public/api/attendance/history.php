<?php
// public/api/attendance/history.php
// API untuk Cek Riwayat Absen HARI INI

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { http_response_code(200); exit(); }

// 1. Koneksi Database
require_once '../config/database.php';
$database = new Database();
$db = $database->getConnection();

// 2. Ambil User ID dari Parameter URL (?user_id=1)
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : die();

try {
    // Cari Employee ID dulu
    $queryUser = "SELECT employee_id FROM users WHERE id = :uid LIMIT 1";
    $stmtUser = $db->prepare($queryUser);
    $stmtUser->bindParam(":uid", $user_id);
    $stmtUser->execute();
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["status" => false, "message" => "User tidak ditemukan"]);
        exit();
    }

    // Cari Absen HARI INI
    $today = date('Y-m-d');
    $queryHistory = "SELECT check_in_time, check_out_time, status 
                     FROM attendance_records 
                     WHERE employee_id = :eid AND attendance_date = :today 
                     LIMIT 1";
    
    $stmtHistory = $db->prepare($queryHistory);
    $stmtHistory->bindParam(":eid", $user['employee_id']);
    $stmtHistory->bindParam(":today", $today);
    $stmtHistory->execute();
    
    $history = $stmtHistory->fetch(PDO::FETCH_ASSOC);

    if ($history) {
        // SUDAH ABSEN
        echo json_encode([
            "status" => true,
            "data" => $history
        ]);
    } else {
        // BELUM ABSEN
        echo json_encode([
            "status" => false,
            "message" => "Belum ada data"
        ]);
    }

} catch (PDOException $e) {
    echo json_encode(["status" => false, "message" => "Db Error: " . $e->getMessage()]);
}
?>