<?php

class LoginController extends Controller {
    
    public function index() {
        // FIX: Cek kedua kunci session biar aman (user_role & role)
        if (isset($_SESSION['user_id'])) {
            // Ambil role, prioritaskan 'user_role' (standar lama)
            $role = $_SESSION['user_role'] ?? $_SESSION['role'] ?? '';
            $role = trim($role);
            
            if ($role === 'super_admin' || $role === 'admin') {
                header('Location: ' . BASEURL . '/admin/dashboard');
            } else {
                header('Location: ' . BASEURL . '/staff/dashboard');
            }
            exit;
        }
        
        $data['title'] = 'Login Admin';
        $this->view('admin/auth/login', $data);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $userModel = $this->model('User'); 
            $user = $userModel->findUserByUsername($username);

            if ($user) {
                // Verifikasi Password
                if (password_verify($password, $user['password'])) { 
                    
                    $clean_role = strtolower(trim($user['role'])); 
                    
// AMBIL NAMA ASLI (FIX NIK JADI NAMA)
$fullName = $user['username']; // Default NIK
                    
// Kalau ada data karyawan, gabung nama depan + belakang
if (!empty($user['first_name'])) {
    $fullName = trim($user['first_name'] . ' ' . ($user['last_name'] ?? ''));
}

$_SESSION['full_name'] = $fullName; // Simpan Nama Asli!

                    // 1. REGENERATE SESSION (Wajib)
                    session_regenerate_id(true);

                    // 2. SET SESSION LENGKAP (Kunci Masalahnya Disini!)
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    
                    // PENTING: Kita set DUA NAMA agar cocok dengan sistem lama & baru
                    $_SESSION['role'] = $clean_role;      // Buat LoginController
                    $_SESSION['user_role'] = $clean_role; // [FIX] Buat Dashboard Admin Lama
                    
                    // Tambahan data biar Dashboard gak error "Undefined Index"
                    $_SESSION['employee_id'] = $user['employee_id'] ?? 0;
                    
                    // Ambil nama lengkap (opsional, biar dashboard ada namanya)
                    $fullName = $user['username']; 
                    if(isset($user['first_name'])) {
                        $fullName = $user['first_name'] . ' ' . ($user['last_name'] ?? '');
                    }
                    $_SESSION['full_name'] = $fullName;

                    // 3. REDIRECT
                    if ($clean_role === 'super_admin' || $clean_role === 'admin') {
                        header('Location: ' . BASEURL . '/admin/dashboard');
                    } else {
                        header('Location: ' . BASEURL . '/staff/dashboard');
                    }
                    exit;

                } else {
                    Flasher::setFlash('Password salah!', 'danger');
                }
            } else {
                Flasher::setFlash('Username tidak ditemukan!', 'danger');
            }
            
            // Balik ke login controller admin
            header('Location: ' . BASEURL . '/Admin/LoginController');
            exit;
        }
    }
    
    public function logout() {
        $_SESSION = [];
        session_unset();
        session_destroy();
        
        // FIX: Redirect balik ke LoginController, BUKAN Auth
        header('Location: ' . BASEURL . '/Admin/LoginController');
        exit;
    }
}