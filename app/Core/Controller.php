<?php
// File: app/Core/Controller.php

class Controller {
    
    public function __construct() {
        // 1. Pastikan Session Start
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        /* * --- PERBAIKAN: DITEMUKAN SUMBER ERROR DISINI ---
         * Kode di bawah ini dimatikan (komentar) karena tabel 'user_permissions' tidak ada.
         * Kita sekarang menggunakan logika cek role manual di Attendance.php & Requests.php.
         */
        
        // if (isset($_SESSION['user_id'])) {
        //     require_once __DIR__ . '/../Helpers/PermissionHelper.php';
        //     PermissionHelper::init($_SESSION['user_id']);
        // }
    }

    // Load Model
    public function model($model) {
        // Pastikan path model benar
        if (file_exists('../app/Models/' . $model . '.php')) {
            require_once '../app/Models/' . $model . '.php';
            return new $model;
        } else {
            // Optional: Error handling jika model tidak ketemu
            die("Model '$model' does not exist.");
        }
    }

    // Load View
    public function view($view, $data = []) {
        extract($data);
        if (file_exists('../app/Views/' . $view . '.php')) {
            require_once '../app/Views/' . $view . '.php';
        } else {
            die("View '$view' does not exist.");
        }
    }
    
    // Redirect Helper
    public function redirect($url) {
        header('Location: ' . BASEURL . $url);
        exit;
    }
}