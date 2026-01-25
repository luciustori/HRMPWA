<?php
// File: app/Middleware/AuthMiddleware.php

class AuthMiddleware {
    
    // Cek apakah user sudah login (Role apa saja)
    public static function check() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    // Cek apakah user adalah ADMIN atau SUPER ADMIN
    public static function checkAdmin() {
        // 1. Pastikan login dulu
        self::check();

        // 2. Cek Role
        $role = $_SESSION['user_role'] ?? '';
        
        if ($role !== 'super_admin' && $role !== 'admin') {
            // Jika dia Employee biasa coba masuk URL admin, tendang ke PWA
            header('Location: ' . BASEURL . '/pwa/home');
            exit;
        }
    }
}