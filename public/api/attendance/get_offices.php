<?php
// FILE: public/api/attendance/get_offices.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once '../config/database.php';

try {
    $db = (new Database())->getConnection();
    
    // Ambil daftar kantor yang aktif
    $sql = "SELECT id, office_name, latitude, longitude, radius_meters 
            FROM office_locations 
            WHERE is_active = 1";
            
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $offices = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["status" => "success", "data" => $offices]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>