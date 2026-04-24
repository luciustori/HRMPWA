<?php
// FILE: public/api/tasks/update_task.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once '../config/database.php';

try {
    $db = (new Database())->getConnection();
    $json = file_get_contents("php://input");
    $data = json_decode($json);

    if (empty($data->task_id) || empty($data->type)) {
        throw new Exception("Data tidak lengkap");
    }

    if ($data->type == 'status') {
        // --- LOGIC UPDATE STATUS ---
        $status = $data->status;
        
        // Cek jika status selesai, catat waktunya
        $completedAt = null;
        if ($status == 'submitted' || $status == 'completed') {
            $completedAt = date('Y-m-d H:i:s');
        }

        $sql = "UPDATE tasks SET status = :status, updated_at = NOW()";
        if ($completedAt) {
            $sql .= ", completed_at = :cat";
        }
        $sql .= " WHERE id = :id";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':id', $data->task_id);
        if ($completedAt) {
            $stmt->bindValue(':cat', $completedAt);
        }
        
        $stmt->execute();
        echo json_encode(["status" => "success", "message" => "Status berhasil diperbarui"]);

    } elseif ($data->type == 'checklist') {
        // --- LOGIC CHECKLIST ---
        $stmt = $db->prepare("UPDATE task_checklist SET is_completed = :c WHERE id = :id");
        $stmt->bindValue(':c', $data->is_completed);
        $stmt->bindValue(':id', $data->item_id);
        $stmt->execute();
        
        echo json_encode(["status" => "success", "message" => "Checklist diperbarui"]);
    } else {
        throw new Exception("Tipe aksi tidak valid");
    }

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>