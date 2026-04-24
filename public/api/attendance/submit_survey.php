<?php
// File: public/api/attendance/submit_survey.php

// 1. Header & Error Reporting (Penting buat debugging)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../config/database.php';

// 2. Tangkap Data JSON
$data = json_decode(file_get_contents("php://input"));

// Cek data masuk gak?
if (empty($data->user_id) || empty($data->mood)) {
    echo json_encode([
        "status" => "error", 
        "message" => "Data tidak lengkap. ID: " . ($data->user_id ?? 'Kosong') . ", Mood: " . ($data->mood ?? 'Kosong')
    ]);
    exit();
}

try {
    $db = (new Database())->getConnection();

    // 3. Cek apakah user sudah isi hari ini? (Biar gak double)
    // DATE(created_at) mengambil tanggal saja (YYYY-MM-DD)
    $checkSql = "SELECT id FROM employee_daily_moods 
                 WHERE user_id = :uid AND DATE(created_at) = CURDATE()";
    
    $stmtCheck = $db->prepare($checkSql);
    $stmtCheck->execute([':uid' => $data->user_id]);
    
    if ($stmtCheck->rowCount() > 0) {
        // UPDATE (Kalau berubah pikiran hari ini)
        $sql = "UPDATE employee_daily_moods 
                SET mood = :mood, created_at = NOW() 
                WHERE user_id = :uid AND DATE(created_at) = CURDATE()";
    } else {
        // INSERT BARU
        $sql = "INSERT INTO employee_daily_moods (user_id, mood, created_at) 
                VALUES (:uid, :mood, NOW())";
    }

    $stmt = $db->prepare($sql);
    $exec = $stmt->execute([
        ':uid' => $data->user_id,
        ':mood' => $data->mood
    ]);

    if ($exec) {
        echo json_encode(["status" => "success", "message" => "Mood berhasil disimpan"]);
    } else {
        throw new Exception("Gagal eksekusi query");
    }

} catch (Exception $e) {
    // Tampilkan error asli database biar tau salahnya dimana
    echo json_encode([
        "status" => "error", 
        "message" => "DB Error: " . $e->getMessage()
    ]);
}
?>