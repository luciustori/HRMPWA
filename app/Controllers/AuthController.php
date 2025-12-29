<?php
// File: app/Controllers/AuthController.php

namespace App\Controllers;

use App\Models\User;
use App\Models\Employee;

class AuthController {
    
    public function loginPage() {
        // Load login view
        require_once '../app/Views/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->loginPage();
        }

        $employee_number = $_POST['employee_number'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!$employee_number || !$password) {
            $_SESSION['error'] = 'Employee number dan password harus diisi';
            return $this->loginPage();
        }

        // Find employee by number
        $user_model = new User();
        $user = $user_model->findByEmployeeNumber($employee_number);

        if (!$user) {
            $_SESSION['error'] = 'Nomor karyawan atau password salah';
            return $this->loginPage();
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            $_SESSION['error'] = 'Nomor karyawan atau password salah';
            return $this->loginPage();
        }

        // Check if user is active
        if (!$user['is_active']) {
            $_SESSION['error'] = 'Akun Anda telah dinonaktifkan';
            return $this->loginPage();
        }

        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role'],
            'photo_path' => $user['photo_path'],
            'full_name' => $user['full_name'] ?? 'User'
        ];

        // Update last login
        $user_model->updateLastLogin($user['id']);

        // Redirect based on role
        if ($user['role'] === 'employee') {
            header('Location: /pwa/dashboard');
        } else {
            header('Location: /admin/dashboard');
        }
        exit;
    }

    public function logout() {
        session_destroy();
        header('Location: /login');
        exit;
    }
}
