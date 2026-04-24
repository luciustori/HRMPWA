<?php
// File: app/Controllers/Auth.php

class Auth extends Controller {

    public function index() {
        // Jika sudah login, langsung lempar sesuai role
        if (isset($_SESSION['user_id'])) {
            $this->redirectBasedOnRole();
        }
        
        $data = ['title' => 'Login - AbsenPWA'];
        $this->view('admin/auth/login', $data); // Tampilkan halaman login tanpa layout admin
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $db = new Database;
            
            // 1. Cari User berdasarkan Username (NIK) & harus Aktif
            $db->query("SELECT u.*, e.first_name, e.last_name, e.employee_number, e.department_id 
                        FROM users u
                        JOIN employees e ON u.employee_id = e.id
                        WHERE u.username = :user AND u.is_active = 1");
            
            $db->bind(':user', $username);
            $user = $db->single();

            // 2. Verifikasi Password
            if ($user && password_verify($password, $user['password'])) {
                
                // 3. Set Session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['employee_id'] = $user['employee_id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['full_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['employee_number'] = $user['employee_number'];
                
                // 4. Redirect Cerdas
                $this->redirectBasedOnRole();

                // --- JEBAKAN DEBUG (Paste ini sementara) ---
            echo "<pre>";
            echo "<h1>DEBUG MODE</h1>";
            echo "Username: " . $user['username'] . "<br>";
            echo "Role dari DB: [" . $user['role'] . "]<br>"; // Kita kasih kurung siku biar kelihatan kalau ada spasi
            echo "Tipe Data: " . gettype($user['role']) . "<br>";
            
            var_dump($user); // Cek isi full array user
            echo "</pre>";
            die(); // Matikan proses di sini biar gak redirect
            // ------------------------------------------
                
            } else {
                // Login Gagal
                $_SESSION['flash_error'] = "Username atau Password salah!";
                header('Location: ' . BASEURL . '/auth');
                exit;
            }
        }
    }

    public function logout() {
        session_destroy();
        header('Location: ' . BASEURL . '/auth');
        exit;
    }

    // Helper: Tentukan tujuan redirect
    private function redirectBasedOnRole() {
        $role = $_SESSION['user_role'];

    // --- LOGIC REDIRECT PINTAR ---
    if ($user['role'] == 'super_admin' || $user['role'] == 'admin') {
        // Kalau Bos -> Masuk Ruang Kontrol
        header('Location: ' . BASEURL . '/admin/dashboard');
    } else {
        // Kalau Karyawan -> Masuk Dashboard Staff/PWA
        header('Location: ' . BASEURL . '/staff/dashboard');
    }
    exit;
    }
}