# ROADMAP & CHANGELOG: ABSENPWA
**Project:** HRM & PWA System (Native PHP MVC)
**Database:** `sketz_db_mvp1-0-0.sql` (MariaDB/MySQL)

---

## 📅 PHASE 1: CORE ENGINE & RBAC (Current Priority)
**Goal:** Sistem Login aman dengan pemisahan hak akses (Admin vs Karyawan).

- [ ] **1.1. Core Setup**
    - [x] Struktur Folder (PowerShell Script)
    - [ ] `Database.php` Wrapper (PDO Transaction Support)
    - [ ] `App.php` & `Router.php` (Routing System)
- [ ] **1.2. Authentication (Auth)**
    - [ ] Login View (`app/Views/admin/auth/login.php`)
    - [ ] Auth Controller (Login Logic, Session Handling)
    - [ ] Password Hashing (Bcrypt)
- [ ] **1.3. RBAC (Role-Based Access Control)**
    - [ ] `User` Model (Get user data + role)
    - [ ] `PermissionHelper` (Cek tabel `v_user_permissions`)
    - [ ] `AuthMiddleware` (Proteksi Halaman Admin)

## 📅 PHASE 2: MASTER DATA & PWA PREP
**Goal:** Admin bisa input data karyawan, Karyawan bisa login di HP.

- [ ] **2.1. Employee Management**
    - [ ] CRUD Karyawan (Create/Edit Data)
    - [ ] Auto-generate akun login (`pwa_auth`)
- [ ] **2.2. PWA Basic Interface**
    - [ ] Layout Mobile (Bottom Nav)
    - [ ] Home Dashboard Karyawan

## 📅 PHASE 3: ATTENDANCE & TASKS (The Features)
**Goal:** Absensi Geolocation & Task Management.

- [ ] **3.1. Absensi System**
    - [ ] Capture Lokasi & Foto
    - [ ] Logic `attendance_records` (Clock In/Out)
    - [ ] Kalkulasi Terlambat Otomatis (via Trigger DB)
- [ ] **3.2. Task Management**
    - [ ] Admin: Assign Task
    - [ ] PWA: Update Task Status & Log Time
    - [ ] Logic: Trigger `update_task_hours`

## 📅 PHASE 4: KPI & PAYROLL (Advanced)
**Goal:** Penilaian Kinerja & Penggajian Otomatis.

- [ ] **4.1. KPI Engine**
    - [ ] Script Kalkulasi Bulanan (`kpi_summary`)
- [ ] **4.2. Payroll**
    - [ ] Generate Slip Gaji (Basic Salary + Allowances - Deductions)

---

## 📝 CHANGELOG

### [v0.1.0] - Setup Awal
- **Added:** Struktur Folder MVC Unified.
- **Added:** Konfigurasi `.htaccess` untuk `absenpwa`.
- **Config:** Base URL set to `/absenpwa/public`.