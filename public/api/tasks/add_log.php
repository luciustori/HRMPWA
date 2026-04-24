<?php
// FILE: public/api/tasks/add_log.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once '../config/database.php';

try {
    $db = (new Database())->getConnection();
    $data = json_decode(file_get_contents("php://input"));

    if(empty($data->task_id) || empty($data->user_id) || empty($data->description)) {
        throw new Exception("Data tidak lengkap");
    }

    $sql = "INSERT INTO task_time_logs (task_id, user_id, log_date, hours_spent, description, created_at) 
            VALUES (:tid, :uid, NOW(), 0, :desc, NOW())";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':tid' => $data->task_id,
        ':uid' => $data->user_id,
        ':desc' => $data->description
    ]);

    echo json_encode(["status" => "success", "message" => "Log tersimpan"]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>