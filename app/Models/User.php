<?php
// File: app/Models/User.php

class User {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Cari user berdasarkan username
    public function findUserByUsername($username) {
        $this->db->query("SELECT * FROM users WHERE username = :username AND is_active = 1");
        $this->db->bind(':username', $username);
        return $this->db->single();
    }

    // Ambil Permission User (Kombinasi Role + Specific Permission)
    public function getPermissions($userId) {
        // Kita ambil dari VIEW 'v_user_permissions' yang sudah Anda buat di SQL
        $this->db->query("SELECT permission_slug FROM v_user_permissions WHERE user_id = :id");
        $this->db->bind(':id', $userId);
        
        $results = $this->db->resultSet();
        
        // Convert ke array flat sederhana: ['attendance.view', 'task.create', ...]
        $permissions = [];
        foreach ($results as $row) {
            if (!empty($row['permission_slug'])) {
                $permissions[] = $row['permission_slug'];
            }
        }
        return $permissions;
    }

    // Update waktu login terakhir
    public function updateLastLogin($userId) {
        $this->db->query("UPDATE users SET last_login = NOW() WHERE id = :id");
        $this->db->bind(':id', $userId);
        $this->db->execute();
    }
}