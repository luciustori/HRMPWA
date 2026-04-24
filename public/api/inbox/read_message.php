<?php
// File: api/inbox/read_message.php

// --- HEADER ANTI-CORS SUPER KUAT ---
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle Pre-flight Request (Browser cek izin dulu)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Ambil data JSON
    $json = file_get_contents("php://input");
    $data = json_decode($json);
    
    if (!$data || empty($data->user_id) || empty($data->announcement_id)) {
        echo json_encode(["status" => "error", "message" => "Parameter tidak lengkap"]);
        exit;
    }
    
    $userId = $data->user_id;
    $annId = $data->announcement_id;

    // 1. Tandai Sudah Baca (Gunakan INSERT IGNORE agar tidak error jika sudah ada)
    $stmtRead = $db->prepare("INSERT IGNORE INTO announcement_reads (announcement_id, user_id, read_at) VALUES (?, ?, NOW())");
    $stmtRead->execute([$annId, $userId]);

    // 2. Ambil Detail Konten
    $stmtDetail = $db->prepare("SELECT * FROM announcements WHERE id = ?");
    $stmtDetail->execute([$annId]);
    $msg = $stmtDetail->fetch(PDO::FETCH_ASSOC);

    if ($msg) {
        echo json_encode(["status" => "success", "data" => $msg]);
    } else {
        echo json_encode(["status" => "error", "message" => "Pesan tidak ditemukan"]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Server Error: " . $e->getMessage()]);
}