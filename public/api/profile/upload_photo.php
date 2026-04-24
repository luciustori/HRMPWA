<?php
// File: api/profile/upload_photo.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { http_response_code(200); exit(); }

require_once '../config/database.php';

try {
    $db = (new Database())->getConnection();
    
    if (!isset($_FILES['photo']) || !isset($_POST['user_id'])) {
        echo json_encode(["status" => false, "message" => "Data tidak lengkap"]);
        exit;
    }

    $userId = $_POST['user_id'];
    $file = $_FILES['photo'];
    
    // 1. Ambil Employee ID
    $stmt = $db->prepare("SELECT employee_id FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);
    $empId = $u['employee_id'];

    // 2. Validasi File
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png'];
    
    if (!in_array($ext, $allowed)) {
        echo json_encode(["status" => false, "message" => "Format harus JPG/PNG"]);
        exit;
    }

    // 3. Setup Folder & Nama File
    $dir = "../../uploads/profiles/";
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    
    $newName = "profile_" . $empId . "_" . time() . "." . $ext;
    $target = $dir . $newName;
    $dbPath = "uploads/profiles/" . $newName;

    // 4. Proses Upload & Update DB
    if (move_uploaded_file($file['tmp_name'], $target)) {
        $update = $db->prepare("UPDATE employees SET profile_photo_path = ? WHERE id = ?");
        $update->execute([$dbPath, $empId]);
        
        echo json_encode(["status" => true, "message" => "Foto berhasil diperbarui", "path" => $dbPath]);
    } else {
        echo json_encode(["status" => false, "message" => "Gagal upload ke server"]);
    }

} catch (Exception $e) {
    echo json_encode(["status" => false, "message" => $e->getMessage()]);
}