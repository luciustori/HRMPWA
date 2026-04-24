<?php
// File: app/Controllers/Admin/PayslipTemplates.php

class PayslipTemplates extends Controller {
    private $db;

    public function __construct() {
        parent::__construct();
        if (!session_id()) session_start();
        
        // Security: Hanya Admin/Super Admin
        $allowed = ['super_admin', 'admin'];
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], $allowed)) {
            header('Location: ' . BASEURL . '/admin/dashboard');
            exit;
        }
        $this->db = new Database;
    }

    public function index() {
        $this->db->query("SELECT * FROM payslip_templates ORDER BY is_active DESC, template_name ASC");
        $data = [
            'title' => 'Template Slip Gaji',
            'content_view' => 'admin/settings/payslip_templates/index',
            'templates' => $this->db->resultSet()
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function create() {
        $data = [
            'title' => 'Buat Template Baru',
            'content_view' => 'admin/settings/payslip_templates/form',
            'template' => null
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function edit($id) {
        $this->db->query("SELECT * FROM payslip_templates WHERE id = :id");
        $this->db->bind(':id', $id);
        $template = $this->db->single();

        $data = [
            'title' => 'Edit Template',
            'content_view' => 'admin/settings/payslip_templates/form',
            'template' => $template
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? null;
            $name = $_POST['template_name'];
            $content = $_POST['content']; // HTML Code

            if ($id) {
                // Update
                $sql = "UPDATE payslip_templates SET template_name = :name, content = :content WHERE id = :id";
                $this->db->query($sql);
                $this->db->bind(':id', $id);
            } else {
                // Insert
                $sql = "INSERT INTO payslip_templates (template_name, content, is_active) VALUES (:name, :content, 0)";
                $this->db->query($sql);
            }
            $this->db->bind(':name', $name);
            $this->db->bind(':content', $content);
            $this->db->execute();

            Flasher::setFlash('Berhasil', 'Template berhasil disimpan', 'success');
            header('Location: ' . BASEURL . '/admin/paysliptemplates');
        }
    }

    public function set_active($id) {
        // Matikan semua dulu
        $this->db->query("UPDATE payslip_templates SET is_active = 0");
        $this->db->execute();

        // Aktifkan yang dipilih
        $this->db->query("UPDATE payslip_templates SET is_active = 1 WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();

        // Update juga di app_settings biar konsisten (optional, jika masih pakai app_settings)
        // $this->db->query("UPDATE app_settings SET setting_value = 'custom_db' WHERE setting_key = 'payslip_format'");
        // $this->db->execute();

        Flasher::setFlash('Berhasil', 'Template aktif telah diubah', 'success');
        header('Location: ' . BASEURL . '/admin/paysliptemplates');
    }

    public function delete($id) {
        // Jangan hapus jika sedang aktif
        $this->db->query("SELECT is_active FROM payslip_templates WHERE id = :id");
        $this->db->bind(':id', $id);
        $tmpl = $this->db->single();

        if ($tmpl && $tmpl['is_active'] == 1) {
            Flasher::setFlash('Gagal', 'Tidak bisa menghapus template yang sedang aktif!', 'danger');
        } else {
            $this->db->query("DELETE FROM payslip_templates WHERE id = :id");
            $this->db->bind(':id', $id);
            $this->db->execute();
            Flasher::setFlash('Berhasil', 'Template dihapus', 'success');
        }
        header('Location: ' . BASEURL . '/admin/paysliptemplates');
    }
}