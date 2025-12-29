-- FILE: database/schema.sql

CREATE DATABASE IF NOT EXISTS hrm_pwa;
USE hrm_pwa;

-- Companies (Perusahaan)
CREATE TABLE companies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    logo_path VARCHAR(255),
    address TEXT,
    phone VARCHAR(20),
    email VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Offices (Kantor)
CREATE TABLE offices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    company_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    address TEXT,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    radius_m INT DEFAULT 100,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id)
);

-- Departments (Departemen)
CREATE TABLE departments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    company_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    manager_id INT,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id)
);

-- Employee Levels (Struktur Organisasi)
CREATE TABLE employee_levels (
    id INT PRIMARY KEY AUTO_INCREMENT,
    company_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    order_level INT,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id)
);

-- Users (Pengguna)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT UNIQUE,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('super_admin', 'admin', 'manager', 'employee') DEFAULT 'employee',
    is_active BOOLEAN DEFAULT TRUE,
    photo_path VARCHAR(255),
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Employees (Karyawan)
CREATE TABLE employees (
    id INT PRIMARY KEY AUTO_INCREMENT,
    company_id INT NOT NULL,
    user_id INT,
    employee_number VARCHAR(50) UNIQUE NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    nik VARCHAR(20) UNIQUE,
    gender ENUM('M', 'F'),
    date_of_birth DATE,
    phone VARCHAR(20),
    email VARCHAR(255),
    address TEXT,
    department_id INT,
    level_id INT,
    position VARCHAR(255),
    hire_date DATE,
    status ENUM('active', 'inactive', 'resign') DEFAULT 'active',
    annual_leave_quota INT DEFAULT 12,
    annual_leave_used INT DEFAULT 0,
    annual_leave_remaining INT DEFAULT 12,
    base_salary DECIMAL(12, 2),
    face_recognition_data LONGTEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (department_id) REFERENCES departments(id),
    FOREIGN KEY (level_id) REFERENCES employee_levels(id)
);

-- Shifts (Jadwal Kerja)
CREATE TABLE shifts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    company_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    start_time TIME,
    end_time TIME,
    work_hours INT,
    is_mod BOOLEAN DEFAULT FALSE,
    mod_bonus DECIMAL(10, 2) DEFAULT 100000,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id)
);

-- Employee Shift Schedule
CREATE TABLE employee_shifts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    shift_id INT NOT NULL,
    date DATE NOT NULL,
    is_modified BOOLEAN DEFAULT FALSE,
    modified_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (shift_id) REFERENCES shifts(id),
    UNIQUE KEY unique_employee_date (employee_id, date)
);

-- National Holidays & Cuti Bersama
CREATE TABLE holidays (
    id INT PRIMARY KEY AUTO_INCREMENT,
    company_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    holiday_date DATE NOT NULL,
    is_national BOOLEAN DEFAULT TRUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id)
);

-- Attendance (Absensi)
CREATE TABLE attendance (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    shift_id INT,
    check_in_time DATETIME,
    check_out_time DATETIME,
    check_in_location_lat DECIMAL(10, 8),
    check_in_location_lng DECIMAL(11, 8),
    check_out_location_lat DECIMAL(10, 8),
    check_out_location_lng DECIMAL(11, 8),
    is_on_time BOOLEAN,
    is_late_minutes INT DEFAULT 0,
    work_duration_hours DECIMAL(5, 2),
    status ENUM('present', 'late', 'absent', 'permit', 'leave', 'sick') DEFAULT 'present',
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (shift_id) REFERENCES shifts(id)
);

-- Permissions (Izin)
CREATE TABLE permissions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    permission_type ENUM('permit', 'annual_leave', 'sick_leave', 'overtime', 'business_trip') NOT NULL,
    start_date DATE,
    end_date DATE,
    duration_days INT,
    reason TEXT,
    attachment_path VARCHAR(255),
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    approved_by INT,
    approval_note TEXT,
    approval_date DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (approved_by) REFERENCES users(id)
);

-- Salary Components (Komponen Gaji)
CREATE TABLE salary_components (
    id INT PRIMARY KEY AUTO_INCREMENT,
    company_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    component_type ENUM('allowance', 'deduction') NOT NULL,
    description TEXT,
    is_fixed BOOLEAN DEFAULT TRUE,
    percentage DECIMAL(5, 2),
    amount DECIMAL(12, 2),
    apply_to_levels TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id)
);

-- Employee Salary (Gaji Karyawan)
CREATE TABLE employee_salary (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    base_salary DECIMAL(12, 2) NOT NULL,
    period_month INT,
    period_year INT,
    total_allowance DECIMAL(12, 2) DEFAULT 0,
    total_deduction DECIMAL(12, 2) DEFAULT 0,
    take_home_pay DECIMAL(12, 2),
    is_processed BOOLEAN DEFAULT FALSE,
    processed_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    UNIQUE KEY unique_salary_period (employee_id, period_month, period_year)
);

-- Salary Slip (Slip Gaji)
CREATE TABLE salary_slips (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_salary_id INT NOT NULL,
    employee_id INT NOT NULL,
    basic_salary DECIMAL(12, 2),
    components_detail JSON,
    total_earnings DECIMAL(12, 2),
    total_deductions DECIMAL(12, 2),
    net_salary DECIMAL(12, 2),
    template_used VARCHAR(100),
    is_edited BOOLEAN DEFAULT FALSE,
    edited_by INT,
    edit_note TEXT,
    printed_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_salary_id) REFERENCES employee_salary(id),
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (edited_by) REFERENCES users(id)
);

-- Tasks (Pekerjaan dari Manager)
CREATE TABLE tasks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    manager_id INT NOT NULL,
    assigned_to INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    start_date DATE,
    end_date DATE,
    duration_hours INT,
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    completion_percentage INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (manager_id) REFERENCES users(id),
    FOREIGN KEY (assigned_to) REFERENCES employees(id)
);

-- Announcements (Pengumuman)
CREATE TABLE announcements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    created_by INT NOT NULL,
    announcement_type ENUM('global', 'department') DEFAULT 'global',
    target_department_id INT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    attachment_path VARCHAR(255),
    visibility ENUM('all', 'manager_only', 'admin_only') DEFAULT 'all',
    is_pinned BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (target_department_id) REFERENCES departments(id)
);

-- Approvals (Approval Workflow)
CREATE TABLE approvals (
    id INT PRIMARY KEY AUTO_INCREMENT,
    permission_id INT NOT NULL,
    approver_id INT NOT NULL,
    approval_level ENUM('manager', 'director') NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    note TEXT,
    approved_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (permission_id) REFERENCES permissions(id),
    FOREIGN KEY (approver_id) REFERENCES users(id)
);

-- Notifications (Notifikasi)
CREATE TABLE notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    type ENUM('approval_request', 'announcement', 'task', 'reminder') DEFAULT 'announcement',
    title VARCHAR(255),
    content TEXT,
    related_id INT,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Settings
CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    company_id INT NOT NULL,
    setting_key VARCHAR(255) UNIQUE NOT NULL,
    setting_value LONGTEXT,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id)
);

-- Audit Log
CREATE TABLE audit_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(255) NOT NULL,
    module VARCHAR(100),
    details JSON,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Indexes
CREATE INDEX idx_user_employee ON users(employee_id);
CREATE INDEX idx_employee_company ON employees(company_id);
CREATE INDEX idx_employee_department ON employees(department_id);
CREATE INDEX idx_attendance_employee ON attendance(employee_id);
CREATE INDEX idx_attendance_date ON attendance(created_at);
CREATE INDEX idx_permission_employee ON permissions(employee_id);
CREATE INDEX idx_announcement_date ON announcements(created_at);
CREATE INDEX idx_notification_user ON notifications(user_id);
