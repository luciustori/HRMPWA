<?php
// app/Models/Approval.php

namespace App\Models;

use PDO;

class Approval extends BaseModel {
    protected $table = 'approvals';

    public function pendingForUser($userId) {
        $sql = "SELECT a.*, 
                       p.permission_type,
                       p.start_date,
                       p.end_date,
                       p.reason,
                       e.full_name,
                       e.employee_number
                FROM {$this->table} a
                JOIN permissions p ON a.permission_id = p.id
                JOIN employees e ON p.employee_id = e.id
                WHERE a.approver_id = :uid AND a.status = 'pending'
                ORDER BY a.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countPending($userId) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}
                WHERE approver_id = :uid AND status = 'pending'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':uid' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function updateStatus($id, $status, $note) {
        $sql = "UPDATE {$this->table} SET
                status = :status,
                note = :note,
                approved_at = NOW()
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'     => $id,
            ':status' => $status,
            ':note'   => $note,
        ]);
    }
}
?>
