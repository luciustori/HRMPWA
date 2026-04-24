<?php
// public/api/leaves/cancel_request.php

ini_set('display_errors', 0);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

// --- PATH DATABASE FIX ---
$dbPath = '../config/database.php';
if (!file_exists($dbPath)) {
    echo json_encode(["status" => false, "message" => "Config DB error"]);
    exit;
}
require_once $dbPath;

$db = (new Database())->getConnection();
$input = json_decode(file_get_contents("php://input"));

$id = $input->id ?? 0;
$source = $input->source ?? 'leave';

if(!$id) {
    echo json_encode(["status" => false, "message" => "ID Invalid"]);
    exit;
}

try {
    $table = ($source === 'sppd') ? 'business_trip_requests' : 'leave_requests';

    // 1. Cek Status dulu (Hanya 'pending' yang boleh batal)
    $stmtCheck = $db->prepare("SELECT status FROM $table WHERE id = ?");
    $stmtCheck->execute([$id]);
    $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(["status" => false, "message" => "Data tidak ditemukan."]);
        exit;
    }

    if ($row['status'] !== 'pending') {
        echo json_encode(["status" => false, "message" => "Gagal: Status pengajuan sudah " . $row['status']]);
        exit;
    }

    // 2. Hapus Data
    $stmtDel = $db->prepare("DELETE FROM $table WHERE id = ?");
    if ($stmtDel->execute([$id])) {
        echo json_encode(["status" => true, "message" => "Pengajuan berhasil dibatalkan."]);
    } else {
        echo json_encode(["status" => false, "message" => "Gagal menghapus database."]);
    }

} catch (Exception $e) {
    echo json_encode(["status" => false, "message" => $e->getMessage()]);
}
?>