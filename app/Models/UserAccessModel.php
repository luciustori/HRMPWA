<?php

class UserAccessModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Ambil semua user buat ditampilkan di List
    public function getAllUsers() {
        // PERBAIKAN: Join ke tabel employees untuk ambil nama asli
        $this->db->query("
            SELECT u.id, u.username, 
                   -- Jika ada data employee, gabungkan namanya. Jika tidak, pakai username.
                   COALESCE(NULLIF(TRIM(CONCAT(e.first_name, ' ', e.last_name)), ''), u.username) as full_name, 
                   u.role, r.role_name 
            FROM users u 
            LEFT JOIN roles r ON u.role = r.role_slug 
            LEFT JOIN employees e ON u.employee_id = e.id -- JOIN PENTING
            WHERE u.is_active = 1 
            ORDER BY u.username ASC
        ");
        return $this->db->resultSet();
    }

    public function getUserDetail($user_id) {
        // PERBAIKAN: Join ke employees juga di sini
        $this->db->query("
            SELECT u.*, 
                   COALESCE(NULLIF(TRIM(CONCAT(e.first_name, ' ', e.last_name)), ''), u.username) as full_name,
                   r.role_name, r.id as role_id 
            FROM users u 
            LEFT JOIN roles r ON u.role = r.role_slug 
            LEFT JOIN employees e ON u.employee_id = e.id -- JOIN PENTING
            WHERE u.id = :id
        ");
        $this->db->bind(':id', $user_id);
        return $this->db->single();
    }

    // --- LOGIC MATRIX: Membedakan Permission Role vs User ---
    public function getUserPermissionsMatrix($user_id, $role_slug) {
        // 1. Ambil Semua Permission (Master Data)
        $this->db->query("SELECT * FROM permissions ORDER BY module_name ASC, id ASC");
        $all_perms = $this->db->resultSet();

        // 2. Ambil Permission milik ROLE (Bawaan Jabatan)
        $this->db->query("
            SELECT rp.permission_id 
            FROM role_permissions rp
            JOIN roles r ON rp.role_id = r.id
            WHERE r.role_slug = :role_slug
        ");
        $this->db->bind(':role_slug', $role_slug);
        $role_perms_raw = $this->db->resultSet();
        $role_perm_ids = array_column($role_perms_raw, 'permission_id'); 

        // 3. Ambil Permission SPESIFIK USER (Akses Tambahan)
        $this->db->query("SELECT permission_id FROM user_specific_permissions WHERE user_id = :user_id");
        $this->db->bind(':user_id', $user_id);
        $user_perms_raw = $this->db->resultSet();
        $user_perm_ids = array_column($user_perms_raw, 'permission_id'); 

        // 4. Gabungkan & Grouping per Module
        $grouped = [];
        foreach ($all_perms as $p) {
            // Tandai status permission
            $p['is_role_access'] = in_array($p['id'], $role_perm_ids);   // True = Locked Green
            $p['is_extra_access'] = in_array($p['id'], $user_perm_ids);  // True = Active Blue
            
            $grouped[$p['module_name']][] = $p;
        }

        return $grouped;
    }

    // Update permission tambahan user
    public function updateUserExtraPermissions($user_id, $permission_ids) {
        try {
            $this->db->beginTransaction();

            // 1. Hapus semua akses tambahan lama user ini
            $this->db->query("DELETE FROM user_specific_permissions WHERE user_id = :user_id");
            $this->db->bind(':user_id', $user_id);
            $this->db->execute();

            // 2. Insert akses tambahan baru (jika ada)
            if (!empty($permission_ids)) {
                foreach ($permission_ids as $perm_id) {
                    $this->db->query("INSERT INTO user_specific_permissions (user_id, permission_id) VALUES (:uid, :pid)");
                    $this->db->bind(':uid', $user_id);
                    $this->db->bind(':pid', $perm_id);
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