# 📋 PROJECT TRACKING: HRM-PWA (KPI & TASK MODULE)

**Project Name:** HRM System PWA (Absensi & Kinerja)
**Current Version:** v1.2.0-beta (Dev)
**Last Update:** 27 Januari 2026
**Status:** 🚧 *Refactoring Phase (Perbaikan Fitur Inti)*

---

## 📌 1. STATUS VERSI & FITUR (CURRENT STATE)

### **Core Modules (Selesai ✅)**
1.  **Sistem Absensi (HRM Core):**
    * Absensi Harian (Masuk/Pulang).
    * Pengajuan Ijin/Cuti.
    * Data Karyawan & Departemen.
2.  **Database Struktur (`mobile_db`):**
    * Tabel `tasks` (Updated dengan kolom `is_recurring`, `recurrence_type`).
    * Tabel `kpi_summary`, `task_attachments`, `task_checklist`.

### **Module KPI (Selesai ✅)**
* **Dashboard KPI (`admin/kpi`):** Menampilkan statistik perusahaan & top employees.
* **Profil KPI (`admin/kpi/detail/{id}`):**
    * Skor Live (Tugas + Absensi).
    * Proteksi Akses (Karyawan hanya bisa lihat data sendiri).
    * History & Grafik Tren Kinerja.

### **Module Tasks (Partial / Perlu Perbaikan ⚠️)**
* **List View (`admin/tasks`):** ✅ Menampilkan daftar tugas.
* **Kanban Board (`admin/tasks/kanban`):** ✅ Drag & Drop status.
* **Detail Tugas (`admin/tasks/details`):** ✅ Tabulasi Diskusi, Checklist, File.
* **Create/Add Task:** ❌ **BROKEN** (Error `Headers already sent`, Logic PHP campur di View).
* **Edit Task:** ⚠️ **UNSTABLE** (Perlu refactor ke MVC murni).
* **Recurring Task:** ❓ **UNTESTED** (Logic sudah ada di Controller, belum bisa dites karena Create error).

---

## 🚨 2. MASALAH KRITIS (PRIORITAS PERBAIKAN)

Saat ini pengembangan dihentikan sementara untuk melakukan **RESET & REFACTOR** pada fitur manajemen tugas karena kode yang "spaghetti" (tercampur antara View dan Controller).

### **A. Error `Create Task`**
* **Gejala:** Halaman blank atau error header saat submit form.
* **Penyebab:** File `add.php` mengandung logika simpan database (`POST`) di bagian atas file view.
* **Solusi:** Pindahkan semua logika `POST` ke Controller `Tasks.php` method `store()`. Jadikan `add.php` murni HTML.

### **B. Error `Pilih PIC` (Dropdown)**
* **Gejala:** Dropdown PIC Karyawan kosong atau tidak mau difilter berdasarkan Departemen.
* **Penyebab:** JavaScript filter di `add.php` hilang/rusak saat refactoring sebelumnya.
* **Solusi:** Kembalikan script JS filter departemen dan pastikan Controller mengirim data `departments` ke View.

---

## 📝 3. RENCANA KERJA (ACTION PLAN)

Langkah-langkah yang **WAJIB** dilakukan saat sesi coding berikutnya dimulai:

### **Langkah 1: Fix View `add.php`**
* [ ] Hapus semua tag `<?php ... ?>` yang berisi logika database di baris-baris awal file.
* [ ] Pastikan form `action` mengarah ke `<?= BASEURL ?>/admin/tasks/store`.
* [ ] Pastikan ID elemen (`departmentSelect`, `assignedTo`) sesuai dengan script JavaScript di bawahnya.
* [ ] Tambahkan panel UI untuk **Recurring Task** (Toggle Switch & Dropdown Tanggal).

### **Langkah 2: Update Controller `Tasks.php`**
* [ ] Perbaiki method `create()`: Kirim data `employees`, `categories`, DAN `departments`.
* [ ] Perbaiki method `store()`:
    * Tangkap inputan Recurring (`is_recurring`, `recurrence_type`).
    * Validasi input wajib.
    * Generate Kode Tugas (`TSK-XXXX`).
    * Insert ke database.
    * Handle Upload File Attachment.
* [ ] Aktifkan `generateRecurringTasks()` di method `index()`.

### **Langkah 3: Testing & Verifikasi**
* [ ] Coba buat Tugas Biasa -> Sukses?
* [ ] Coba buat Tugas Rutin (Set tanggal hari ini) -> Apakah otomatis muncul tugas duplikatnya?
* [ ] Cek Dashboard KPI -> Apakah skor bertambah saat tugas diselesaikan?

---

## 📂 FILE PENTING (SOURCE CODE)

File-file kunci yang harus dilampirkan saat meminta bantuan AI selanjutnya:

1.  **`app/Controllers/Admin/Tasks.php`** (Controller Utama)
2.  **`app/Views/admin/tasks/add.php`** (View Create yang bermasalah)
3.  **`KPI_Tasks_Struktur.sql`** (Referensi nama kolom database)

---

*Dokumen ini dibuat otomatis oleh AI Assistant untuk keperluan tracking development HRM-PWA.*