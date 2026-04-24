<?php
// File: api/profile/update_password.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle Pre-flight Request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { http_response_code(200); exit(); }

// 1. LOAD DATABASE CONFIG (Cari file config secara otomatis)
if (file_exists('../config/database.php')) {
    require_once '../config/database.php';
} elseif (file_exists('../../api/config/database.php')) {
    require_once '../../api/config/database.php';
} else {
    require_once '../../config/database.php';
}

$data = json_decode(file_get_contents("php://input"));

// 2. VALIDASI INPUT
if (empty($data->user_id) || empty($data->old_password) || empty($data->new_password)) {
    echo json_encode(["status" => false, "message" => "Semua kolom wajib diisi!"]);
    exit();
}

$user_id = $data->user_id;
$old_pass = $data->old_password;
$new_pass = $data->new_password;

try {
    // 3. KONEKSI DATABASE
    $database = new Database();
    $db = $database->getConnection();

    // 4. AMBIL PASSWORD LAMA (HASH) DARI DB
    // Cek tabel 'users' kolom 'password'
    $stmt = $db->prepare("SELECT password FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["status" => false, "message" => "User tidak ditemukan."]);
        exit();
    }

    // 5. CEK PASSWORD LAMA
    if (!password_verify($old_pass, $user['password'])) {
        echo json_encode(["status" => false, "message" => "Password Lama Salah!"]);
        exit();
    }

    // 6. UPDATE PASSWORD BARU (HASH)
    $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
    
    $update = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
    if($update->execute([$new_hash, $user_id])) {
        echo json_encode(["status" => true, "message" => "Password berhasil diubah!"]);
    } else {
        echo json_encode(["status" => false, "message" => "Gagal update database."]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => false, "message" => "Server Error: " . $e->getMessage()]);
}
?>