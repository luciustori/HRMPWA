<?php

class RoleModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Ambil semua Role (Admin, Staff, dll)
    public function getAllRoles() {
        $this->db->query("SELECT * FROM roles ORDER BY id ASC");
        return $this->db->resultSet();
    }

    // Ambil semua Permission dikelompokkan per Module
    public function getAllPermissionsGrouped() {
        // PERBAIKAN: Ganti 'module' jadi 'module_name' sesuai database kamu
        $this->db->query("SELECT * FROM permissions ORDER BY module_name ASC, id ASC");
        $results = $this->db->resultSet();

        $grouped = [];
        foreach ($results as $row) {
            // PERBAIKAN: Grouping array pakai key 'module_name'
            $grouped[$row['module_name']][] = $row;
        }
        return $grouped;
    }

    // Ambil permission yang aktif untuk Role tertentu
    public function getRolePermissions($role_id) {
        $this->db->query("SELECT permission_id FROM role_permissions WHERE role_id = :role_id");
        $this->db->bind(':role_id', $role_id);
        $rows = $this->db->resultSet();
        
        // Return array simple: [1, 5, 8, ...]
        return array_column($rows, 'permission_id');
    }

    // Update Permission (Hapus semua lama, insert yang baru)
    public function updatePermissions($role_id, $permission_ids) {
        try {
            $this->db->beginTransaction();

            // 1. Hapus permission lama
            $this->db->query("DELETE FROM role_permissions WHERE role_id = :role_id");
            $this->db->bind(':role_id', $role_id);
            $this->db->execute();

            // 2. Insert permission baru (Bulk Insert)
            if (!empty($permission_ids)) {
                foreach ($permission_ids as $perm_id) {
                    $this->db->query("INSERT INTO role_permissions (role_id, permission_id) VALUES (:role_id, :perm_id)");
                    $this->db->bind(':role_id', $role_id);
                    $this->db->bind(':perm_id', $perm_id);
                    $this->db->execute();
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}