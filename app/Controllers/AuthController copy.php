<?php
// File: app/Controllers/AuthController.php

class AuthController extends Controller {
    
    public function index() {
        // Jika sudah login, lempar ke dashboard sesuai role
        if (isset($_SESSION['user_id'])) {
            $this->redirectBasedOnRole();
        }

        // Tampilkan View Login
        $data = [
            'title' => 'Login - ' . APP_NAME
        ];
        $this->view('admin/auth/login', $data);
    }

    public function login() {
        // Cek Method Post
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // 1. Sanitasi Input
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
            $password = $_POST['password'];

            // 2. Panggil Model
            $userModel = $this->model('User');
            $user = $userModel->findUserByUsername($username);

            // 3. Verifikasi Password
            if ($user && password_verify($password, $user['password'])) {
                
                // --- LOGIN SUKSES ---
                
                // Set Session Utama
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['employee_id'] = $user['employee_id'];

                // Set Permission Session (PENTING UNTUK RBAC!)
                $permissions = $userModel->getPermissions($user['id']);
                $_SESSION['user_permissions'] = $permissions;

                // Update Last Login
                $userModel->updateLastLogin($user['id']);

                // Redirect
                $this->redirectBasedOnRole();

            } else {
                // --- LOGIN GAGAL ---
                // Flash message manual (atau pakai library flash kalau nanti kita buat)
                $data = [
                    'title' => 'Login Failed',
                    'error' => 'Username atau Password salah!'
                ];
                $this->view('admin/auth/login', $data);
            }
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('/login');
    }

    // Helper: Redirect sesuai Role
    private function redirectBasedOnRole() {
        if ($_SESSION['user_role'] == 'employee') {
            $this->redirect('/pwa/home'); // Ke Halaman Mobile
        } else {
            $this->redirect('/admin/dashboard'); // Ke Halaman Admin
        }
    }
}