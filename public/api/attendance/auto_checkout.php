<?php
// File ini akan dijalankan otomatis oleh Cron Job Server
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once '../../config/database.php'; // Sesuaikan path ke database.php

try {
    $database = new Database();
    $db = $database->getConnection();

    // 1. Cari semua absen yang masih gantung (belum check_out) sampai hari ini
    // Kita set jam pulang otomatis ke 17:00 (atau sesuaikan dengan jam shift normal loe)
    // Kita tambahkan tanda di 'notes' atau kolom khusus agar HRD tahu ini Auto-Checkout
    
    $query = "UPDATE attendance_records 
              SET check_out_time = CONCAT(attendance_date, ' 17:00:00'),
                  status = 'completed' /* Sesuaikan dengan status absen selesai loe */
              WHERE check_out_time IS NULL 
              AND check_in_time IS NOT NULL 
              AND attendance_date <= CURDATE()";

    $stmt = $db->prepare($query);
    $stmt->execute();

    $affectedRows = $stmt->rowCount();

    // Opsional: Bikin log (catatan) di file txt kalau proses ini jalan
    $logMsg = "[" . date('Y-m-d H:i:s') . "] Auto Check-Out berhasil. " . $affectedRows . " sesi ditutup otomatis.\n";
    file_put_contents(__DIR__ . '/cron_log.txt', $logMsg, FILE_APPEND);

    echo json_encode([
        "status" => true,
        "message" => "$affectedRows sesi berhasil di-checkout otomatis."
    ]);

} catch (Exception $e) {
    $errorMsg = "[" . date('Y-m-d H:i:s') . "] ERROR: " . $e->getMessage() . "\n";
    file_put_contents(__DIR__ . '/cron_log.txt', $errorMsg, FILE_APPEND);
    
    echo json_encode([
        "status" => false,
        "message" => "Gagal: " . $e->getMessage()
    ]);
}
?>