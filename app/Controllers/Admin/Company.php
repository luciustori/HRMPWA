<?php
// File: app/Controllers/Admin/Company.php

class Company extends Controller {

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
        $db = new Database;
        $db->query("SELECT * FROM companies ORDER BY id ASC");
        $companies = $db->resultSet();

        $data = [
            'title' => 'Profil Perusahaan',
            'content_view' => 'admin/company/index',
            'companies' => $companies
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function edit($id) {
        $db = new Database;
        $db->query("SELECT * FROM companies WHERE id = :id");
        $db->bind(':id', $id);
        $company = $db->single();

        $data = [
            'title' => 'Edit Perusahaan',
            'content_view' => 'admin/company/edit',
            'company' => $company
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db = new Database;

            // 1. LOGIC UPLOAD LOGO
            $logo_path = $_POST['old_logo']; // Default pakai logo lama
            
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
                $allowed = ['jpg', 'jpeg', 'png'];
                $filename = $_FILES['logo']['name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                if (in_array($ext, $allowed)) {
                    // Buat nama unik
                    $new_name = 'logo_' . time() . '.' . $ext;
                    $upload_dir = '../public/uploads/logos/';
                    
                    // Buat folder jika belum ada
                    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

                    if (move_uploaded_file($_FILES['logo']['tmp_name'], $upload_dir . $new_name)) {
                        $logo_path = 'uploads/logos/' . $new_name;
                        // Hapus logo lama jika ada (optional)
                    }
                }
            }

            // 2. UPDATE DATABASE
            $query = "UPDATE companies SET 
                        company_name = :name,
                        company_code = :code,
                        address = :addr,
                        phone = :phone,
                        email = :email,
                        website = :web,
                        logo_path = :logo,
                        updated_at = NOW()
                      WHERE id = :id";
            
            $db->query($query);
            $db->bind(':id', $id);
            $db->bind(':name', $_POST['company_name']);
            $db->bind(':code', strtoupper($_POST['company_code']));
            $db->bind(':addr', $_POST['address']);
            $db->bind(':phone', $_POST['phone']);
            $db->bind(':email', $_POST['email']);
            $db->bind(':web', $_POST['website']);
            $db->bind(':logo', $logo_path);

            if ($db->execute()) {
                header('Location: ' . BASEURL . '/admin/company');
            } else {
                die("Gagal update profil perusahaan.");
            }
        }
    }
}