<?php
// File: app/Helpers/PermissionHelper.php

class PermissionHelper {
    
    /**
     * Cek apakah user memiliki permission tertentu
     * Contoh: PermissionHelper::has('attendance.approve')
     */
    public static function has($permissionSlug) {
        // 1. Pastikan user sudah login
        if (!isset($_SESSION['user_permissions'])) {
            return false;
        }

        // 2. Super Admin selalu boleh (Bypass)
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'super_admin') {
            return true;
        }

        // 3. Cek apakah permission ada di array session
        return in_array($permissionSlug, $_SESSION['user_permissions']);
    }

    /**
     * Middleware-like check: Redirect jika tidak punya akses
     * Contoh: PermissionHelper::require('payroll.view')
     */
    public static function require($permissionSlug) {
        if (!self::has($permissionSlug)) {
            // Redirect ke halaman 403 Forbidden atau Dashboard
            header('Location: ' . BASEURL . '/admin/dashboard?error=unauthorized');
            exit;
        }
    }
}