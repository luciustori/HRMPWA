<?php
// FILE: public/api/attendance/reset_today.php
// Script untuk MENGHAPUS absen hari ini (Khusus Testing)

header("Access-Control-Allow-Origin: *");
header("Content-Type: text/plain");

require_once '../config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    $today = date('Y-m-d');

    // HAPUS SEMUA DATA HARI INI
    // (Dalam mode production, harusnya filter by user_id, tapi untuk testing kita sikat semua dulu)
    $query = "DELETE FROM attendance_records WHERE attendance_date = :today";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":today", $today);
    
    if ($stmt->execute()) {
        echo "✅ BERHASIL: Data absen tanggal $today sudah dihapus.\n";
        echo "Silakan coba Check-In lagi dari aplikasi.";
    } else {
        echo "❌ Gagal menghapus data.";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>