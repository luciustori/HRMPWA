<?php
// File: app/Controllers/Staff/Profile.php

class Profile extends Controller {

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
        $db = new Database;
        $employeeId = $_SESSION['employee_id'];

        // 1. AMBIL DATA KARYAWAN (FIX: Hapus JOIN ke tabel positions)
        // Kita ambil kolom 'position' langsung dari tabel employees
        $db->query("SELECT 
                        e.*, 
                        d.department_name 
                    FROM employees e
                    LEFT JOIN departments d ON e.department_id = d.id
                    WHERE e.id = :id");
        $db->bind(':id', $employeeId);
        $emp = $db->single();

        // Safety jika data kosong
        if(!$emp) {
            // Coba query simple tanpa join
            $db->query("SELECT * FROM employees WHERE id = :id");
            $db->bind(':id', $employeeId);
            $emp = $db->single();
        }

        // 2. Ambil Data Login (Username/Email) dari tabel Users
        $db->query("SELECT username, email FROM users WHERE id = :uid");
        $db->bind(':uid', $_SESSION['user_id']);
        $user = $db->single();

        $data = [
            'title' => 'My Profile',
            'employee' => $emp,
            'user' => $user,
            'content_view' => 'staff/profile/index'
        ];

        $this->view('staff/layouts/staff-layout', $data);
    }

    // UPDATE DATA DIRI (HP, Email Pribadi, Alamat)
    public function update_info() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db = new Database;
            $employeeId = $_SESSION['employee_id'];
            
            $phone = $_POST['phone'] ?? '';
            $email = $_POST['email'] ?? ''; 
            $address = $_POST['address'] ?? '';

            try {
                $db->query("UPDATE employees SET phone = :ph, email = :em, address = :addr WHERE id = :id");
                $db->bind(':ph', $phone);
                $db->bind(':em', $email);
                $db->bind(':addr', $address);
                $db->bind(':id', $employeeId);
                $db->execute();
                
                Flasher::setFlash('Berhasil', 'Data profil berhasil diperbarui', 'success');
            } catch (Exception $e) {
                Flasher::setFlash('Gagal', 'Terjadi kesalahan sistem: ' . $e->getMessage(), 'danger');
            }

            header('Location: ' . BASEURL . '/staff/profile');
            exit;
        }
    }

    // UPDATE PASSWORD
    public function update_password() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db = new Database;
            $userId = $_SESSION['user_id'];
            
            $old_pass = $_POST['old_password'];
            $new_pass = $_POST['new_password'];
            $confirm_pass = $_POST['confirm_password'];

            // 1. Cek Password Lama
            $db->query("SELECT password FROM users WHERE id = :id");
            $db->bind(':id', $userId);
            $user = $db->single();

            if (!password_verify($old_pass, $user['password'])) {
                Flasher::setFlash('Gagal', 'Password lama tidak sesuai', 'danger');
                header('Location: ' . BASEURL . '/staff/profile');
                exit;
            }

            // 2. Cek Konfirmasi
            if ($new_pass !== $confirm_pass) {
                Flasher::setFlash('Gagal', 'Konfirmasi password baru tidak cocok', 'danger');
                header('Location: ' . BASEURL . '/staff/profile');
                exit;
            }

            // 3. Update Password Baru
            $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
            $db->query("UPDATE users SET password = :pass WHERE id = :id");
            $db->bind(':pass', $hashed);
            $db->bind(':id', $userId);
            $db->execute();

            Flasher::setFlash('Berhasil', 'Password berhasil diubah', 'success');
            header('Location: ' . BASEURL . '/staff/profile');
            exit;
        }
    }

    // UPLOAD FOTO PROFIL
    public function upload_photo() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['photo'])) {
            $file = $_FILES['photo'];
            $employeeId = $_SESSION['employee_id'];

            // Validasi Ekstensi
            $allowed = ['jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            
            if (!in_array($ext, $allowed)) {
                Flasher::setFlash('Gagal', 'Format file harus JPG atau PNG', 'danger');
                header('Location: ' . BASEURL . '/staff/profile');
                exit;
            }

            // Folder Upload (Pastikan Permission Writeable)
            // Gunakan path relative yang aman
            $uploadPath = 'uploads/profiles/';
            $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/' . $uploadPath;
            
            // Cek folder public/uploads/profiles juga untuk jaga-jaga struktur folder
            if (!is_dir($targetDir)) {
                // Coba path alternatif jika document root tidak langsung ke public
                $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/public/' . $uploadPath;
            }

            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

            $newName = 'profile_' . $employeeId . '_' . time() . '.' . $ext;
            
            if (move_uploaded_file($file['tmp_name'], $targetDir . $newName)) {
                // Update Database dengan path relative
                $db = new Database;
                $db->query("UPDATE employees SET profile_photo_path = :pic WHERE id = :id");
                $db->bind(':pic', $uploadPath . $newName);
                $db->bind(':id', $employeeId);
                $db->execute();

                Flasher::setFlash('Berhasil', 'Foto profil diperbarui', 'success');
            } else {
                Flasher::setFlash('Gagal', 'Gagal memindahkan file ke server', 'danger');
            }
            
            header('Location: ' . BASEURL . '/staff/profile');
            exit;
        }
    }
}