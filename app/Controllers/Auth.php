<?php
// File: app/Controllers/Auth.php

class Auth extends Controller {

    public function index() {
        // Jika sudah login, langsung lempar sesuai role
        if (isset($_SESSION['user_id'])) {
            $this->redirectBasedOnRole();
        }
        
        $data = ['title' => 'Login - AbsenPWA'];
        $this->view('admin/auth/login', $data);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $db = new Database;
            
            // 1. Cari User berdasarkan Username (NIK) & harus Aktif
            $db->query("SELECT u.*, e.first_name, e.last_name 
                        FROM users u
                        JOIN employees e ON u.employee_id = e.id
                        WHERE u.username = :user AND u.is_active = 1");
            
            $db->bind(':user', $username);
            $user = $db->single();

            // 2. Verifikasi Password
            if ($user && password_verify($password, $user['password'])) {
                
                // 3. Set Session Penting
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['employee_id'] = $user['employee_id'];
                $_SESSION['user_role'] = trim($user['role']); // Simpan role yang sudah bersih dari spasi
                $_SESSION['full_name'] = $user['first_name'] . ' ' . $user['last_name'];
                
                // 4. Redirect berdasarkan role
                $this->redirectBasedOnRole();
                
            } else {
                // Login Gagal
                Flasher::setFlash('Username atau Password salah!', 'danger');
                header('Location: ' . BASEURL . '/auth');
                exit;
            }
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: ' . BASEURL . '/auth');
        exit;
    }

    // Helper: Tentukan tujuan redirect
    private function redirectBasedOnRole() {
        // AMBIL DARI SESSION (Fix Undefined Variable $user)
        $role = $_SESSION['user_role'] ?? '';

        if ($role === 'super_admin' || $role === 'admin') {
            header('Location: ' . BASEURL . '/admin/dashboard');
        } else {
            header('Location: ' . BASEURL . '/staff/dashboard');
        }
        exit;
    }
}