<?php
// File: app/Helpers/PermissionHelper.php

class PermissionHelper {
    // Menyimpan daftar permission (slug) yang dimiliki user saat ini
    private static $permissions = [];
    private static $role = '';
    private static $department_id = 0;
    private static $division_id = 0; // <-- INI YANG KURANG TADI
    private static $user_id = 0;
    private static $initialized = false;

    public static function init($user_id) {
        // Cek agar tidak init berulang kali untuk user yang sama
        if (self::$initialized && self::$user_id == $user_id) {
            return;
        }

        self::$user_id = $user_id;
        self::$initialized = true;

        $db = new Database;
        
        // 1. Ambil Info Basic User (Role, Dept, & DIVISI)
        // Kita tambah kolom e.division_id di sini
        $db->query("
            SELECT u.role, e.department_id, e.division_id
            FROM users u 
            LEFT JOIN employees e ON u.employee_id = e.id 
            WHERE u.id = :id
        ");
        $db->bind(':id', $user_id);
        $user = $db->single();

        if ($user) {
            self::$role = $user['role'];
            self::$department_id = $user['department_id'];
            self::$division_id = $user['division_id']; // <-- Assign ke variabel static
        }

        // 2. Logic Super Admin (Bypass Query Permission)
        if (self::$role === 'super_admin') {
            return; 
        }

        // 3. Ambil Permission List dari Database
        // Logic: permission bisa dari Role (role_permissions) ATAU User Spesifik (user_permissions)
        $sql = "
            SELECT p.permission_slug 
            FROM permissions p
            JOIN role_permissions rp ON p.id = rp.permission_id
            JOIN roles r ON rp.role_id = r.id
            WHERE r.role_slug = :role
            
            UNION
            
            SELECT p.permission_slug
            FROM permissions p
            JOIN user_permissions up ON p.id = up.permission_id
            WHERE up.user_id = :user_id
        ";

        $db->query($sql);
        $db->bind(':role', self::$role);
        $db->bind(':user_id', $user_id);
        $rows = $db->resultSet();

        // Simpan slug ke array static biar gampang dicek
        self::$permissions = array_column($rows, 'permission_slug');
    }

    public static function can($permission) {
        // Super Admin Sakti: Boleh akses apa saja
        if (self::$role === 'super_admin') {
            return true;
        }

        // Cek apakah slug permission ada di array yang kita ambil dari DB tadi
        return in_array($permission, self::$permissions);
    }

    // --- Helper Wrapper ---

    public static function has($permission) {
        return self::can($permission);
    }

    public static function canViewAll($module) {
        return (self::$role === 'admin' || self::$role === 'super_admin');
    }

    // --- Getters untuk Data Scope ---

    public static function getDepartmentId() {
        return self::$department_id;
    }

    // INI METHOD YANG DICARI CONTROLLER TADI
    public static function getDivisionId() {
        return self::$division_id;
    }
    
    public static function getRole() {
        return self::$role;
    }

    // Cek Scope Data (Untuk Filter Query di Controller)
    public static function getScope() {
        if (self::$role === 'super_admin') {
            return 'global'; // Bisa lihat semua data
        }
        
        if (self::$role === 'admin') {
            // Admin biasanya bisa lihat global atau terbatas departemen (tergantung kebijakan lo)
            // Di sini kita set global dulu, atau bisa 'department' kalau mau strik.
            return 'global'; 
        }

        if (self::$role === 'author') {
            return 'division'; // Author cuma bisa lihat divisinya
        }

        if (self::$role === 'manager') {
            return 'department'; // Manager bisa lihat satu departemen
        }

        return 'self'; // Staff cuma bisa lihat diri sendiri
    }
}