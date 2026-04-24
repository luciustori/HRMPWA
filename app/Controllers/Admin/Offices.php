<?php
// File: app/Controllers/Admin/Offices.php

class Offices extends Controller {

    private $db; // Pastikan ada properti ini

    public function __construct() {
        // --- BARIS WAJIB (INI YANG KURANG) ---
        parent::__construct(); 
        // -------------------------------------

        // Session start di bawah ini sebenarnya sudah di-handle oleh parent,
        // tapi dibiarkan ada juga tidak apa-apa (aman).
        if (!session_id()) session_start();

        // 1. CEK LOGIN
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/Admin/LoginController');
            exit;
        }

        // 2. CEK ROLE
        $role = strtolower($_SESSION['role'] ?? '');
        if ($role !== 'admin' && $role !== 'super_admin') {
            header('Location: ' . BASEURL . '/staff/dashboard');
            exit;
        }

        // 3. INIT DATABASE
        $this->db = new Database;
    }

    public function index() {
        $db = new Database;
        $query = "SELECT o.*, c.company_name 
                  FROM office_locations o
                  JOIN companies c ON o.company_id = c.id
                  ORDER BY o.office_name ASC";
        $db->query($query);
        $offices = $db->resultSet();

        $data = [
            'title' => 'Lokasi Kantor',
            'content_view' => 'admin/offices/index',
            'offices' => $offices
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function create() {
        $db = new Database;
        $db->query("SELECT * FROM companies");
        $companies = $db->resultSet();

        $data = [
            'title' => 'Tambah Kantor',
            'content_view' => 'admin/offices/create',
            'companies' => $companies
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db = new Database;
            $query = "INSERT INTO office_locations (company_id, office_name, address, latitude, longitude, radius_meters, is_active, created_at, updated_at) 
                      VALUES (:comp, :name, :addr, :lat, :long, :rad, :active, NOW(), NOW())";
            
            $db->query($query);
            $db->bind(':comp', $_POST['company_id']);
            $db->bind(':name', $_POST['office_name']);
            $db->bind(':addr', $_POST['address']);
            $db->bind(':lat', $_POST['latitude']);
            $db->bind(':long', $_POST['longitude']);
            $db->bind(':rad', $_POST['radius_meters']);
            $db->bind(':active', 1);

            if ($db->execute()) {
                header('Location: ' . BASEURL . '/admin/offices');
            } else {
                die("Gagal simpan kantor.");
            }
        }
    }

    public function edit($id) {
        $db = new Database;
        $db->query("SELECT * FROM office_locations WHERE id = :id");
        $db->bind(':id', $id);
        $office = $db->single();

        $db->query("SELECT * FROM companies");
        $companies = $db->resultSet();

        $data = [
            'title' => 'Edit Kantor',
            'content_view' => 'admin/offices/edit',
            'office' => $office,
            'companies' => $companies
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db = new Database;
            $query = "UPDATE office_locations SET 
                        company_id = :comp, office_name = :name, address = :addr,
                        latitude = :lat, longitude = :long, radius_meters = :rad, is_active = :active, updated_at = NOW()
                      WHERE id = :id";
            
            $db->query($query);
            $db->bind(':id', $id);
            $db->bind(':comp', $_POST['company_id']);
            $db->bind(':name', $_POST['office_name']);
            $db->bind(':addr', $_POST['address']);
            $db->bind(':lat', $_POST['latitude']);
            $db->bind(':long', $_POST['longitude']);
            $db->bind(':rad', $_POST['radius_meters']);
            $db->bind(':active', $_POST['is_active']);

            if ($db->execute()) {
                header('Location: ' . BASEURL . '/admin/offices');
            } else {
                die("Gagal update kantor.");
            }
        }
    }

    public function delete($id) {
        $db = new Database;
        $db->query("DELETE FROM office_locations WHERE id = :id");
        $db->bind(':id', $id);
        $db->execute();
        header('Location: ' . BASEURL . '/admin/offices');
    }
}