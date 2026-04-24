<?php
// File: app/Controllers/Admin/Settings.php

class Settings extends Controller {
    private $db;

    public function __construct() {
        parent::__construct();
        if (!session_id()) session_start();
        
        // Security Check
        $allowed_roles = ['super_admin', 'admin'];
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], $allowed_roles)) {
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }
        
        $this->db = new Database;
    }

    public function index() {
        // Ambil semua data settings
        $this->db->query("SELECT setting_key, setting_value FROM app_settings");
        $results = $this->db->resultSet();

        $s = [];
        if ($results) {
            foreach ($results as $row) {
                $s[$row['setting_key']] = $row['setting_value'];
            }
        }

        $data = [
            'title' => 'Pengaturan Sistem',
            'content_view' => 'admin/settings/index', // View terpusat
            's' => $s
        ];

        // Load Master Layout
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // 1. DATA PERUSAHAAN (Untuk Report)
                $company_keys = ['company_name', 'company_address', 'company_phone', 'company_email', 'company_website'];
                
                // 2. DATA APLIKASI & SISTEM (Branding + Logic)
                $app_keys = ['app_name', 'app_tagline', 'primary_color', 'late_tolerance', 'payslip_format'];

                $all_keys = array_merge($company_keys, $app_keys);

                foreach ($all_keys as $key) {
                    if (isset($_POST[$key])) {
                        $this->saveSetting($key, $_POST[$key]);
                    }
                }

                // 3. HANDLE UPLOAD (Bisa upload salah satu atau keduanya)
                
                // A. Logo Aplikasi (Sidebar/Favicon)
                if (!empty($_FILES['app_logo']['name'])) {
                    $this->processUpload($_FILES['app_logo'], 'app_logo', 'logo_app_');
                }

                // B. Logo Perusahaan (Kop Surat/Laporan)
                if (!empty($_FILES['company_logo']['name'])) {
                    $this->processUpload($_FILES['company_logo'], 'company_logo', 'logo_company_');
                }

                Flasher::setFlash('Berhasil', 'Pengaturan sistem telah diperbarui.', 'success');
                header('Location: ' . BASEURL . '/admin/settings');
                exit;

            } catch (Exception $e) {
                Flasher::setFlash('Gagal', 'Error: ' . $e->getMessage(), 'error');
                header('Location: ' . BASEURL . '/admin/settings');
                exit;
            }
        }
    }

    // Fungsi Save Pintar (Upsert)
    private function saveSetting($key, $value) {
        $this->db->query("SELECT id FROM app_settings WHERE setting_key = :key");
        $this->db->bind(':key', $key);
        
        if($this->db->single()){
            $query = "UPDATE app_settings SET setting_value = :val, updated_at = NOW() WHERE setting_key = :key";
        } else {
            $query = "INSERT INTO app_settings (setting_key, setting_value, setting_type) VALUES (:key, :val, 'text')";
        }
        
        $this->db->query($query);
        $this->db->bind(':val', $value);
        $this->db->bind(':key', $key);
        $this->db->execute();
    }

    // Fungsi Upload Generic
    private function processUpload($file, $settingKey, $prefix) {
        $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
        
        if (!in_array($ext, $allowed)) return;

        // Path ke folder public/uploads/settings
        $targetDir = dirname(__DIR__, 3) . "/public/uploads/settings/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $fileName = $prefix . time() . '.' . $ext;
        
        if(move_uploaded_file($file["tmp_name"], $targetDir . $fileName)) {
            // Hapus file lama dari DB
            $this->db->query("SELECT setting_value FROM app_settings WHERE setting_key = :key");
            $this->db->bind(':key', $settingKey);
            $old = $this->db->single();
            
            if($old && !empty($old['setting_value'])) {
                $oldFile = dirname(__DIR__, 3) . "/public/" . $old['setting_value'];
                if(file_exists($oldFile)) unlink($oldFile);
            }

            // Simpan path relative baru
            $this->saveSetting($settingKey, 'uploads/settings/' . $fileName);
        }
    }
}