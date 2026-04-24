<?php
// public/api/leaves/get_detail.php

ini_set('display_errors', 0);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$dbPath = '../config/database.php';
if (!file_exists($dbPath)) { echo json_encode(["status" => false]); exit; }
require_once $dbPath;

try {
    $db = (new Database())->getConnection();
    $id = $_GET['id'] ?? 0;
    $source = $_GET['source'] ?? 'leave';

    $data = null;

    if ($source === 'sppd') {
        $sql = "SELECT id, trip_purpose as reason, destination, start_date, end_date, total_days, estimated_budget, supporting_document, status, created_at FROM business_trip_requests WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if($data) {
            $data['category_label'] = "SPPD - " . $data['destination'];
            $data['type'] = 'sppd';
        }

    } else if ($source === 'lembur') {
        // FETCH LEMBUR
        $sql = "SELECT id, overtime_date as start_date, overtime_date as end_date, start_time, end_time, total_hours, reason, status, created_at FROM overtime_requests WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if($data) {
            $data['category_label'] = "Lembur";
            $data['type'] = 'lembur';
            // Gabungkan jam untuk display durasi
            $data['total_days'] = $data['total_hours'] . " Jam"; 
        }

    } else {
        $sql = "SELECT r.*, lt.leave_type_name FROM leave_requests r LEFT JOIN leave_types lt ON r.leave_type_id = lt.id WHERE r.id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if($data) {
            $data['category_label'] = $data['leave_type_name'] ?? 'Ijin';
            $data['type'] = 'leave';
        }
    }

    if ($data) echo json_encode(["status" => true, "data" => $data]);
    else echo json_encode(["status" => false, "message" => "Data kosong"]);

} catch (Exception $e) {
    echo json_encode(["status" => false, "message" => $e->getMessage()]);
}
?>