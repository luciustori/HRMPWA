# ACTION PLAN: HRIS PRO & ATTENDANCE SYSTEM
**Project:** HRIS PWA / APK Transformation
**Target:** Modern SaaS Aesthetic, High Performance, "Elderly-Proof" UX.
**Last Update:** 29 Jan 2026

---

## 🧠 I. CORE PHILOSOPHY (The Big Picture)

1.  **Split Experience (Dua Dunia):**
    * **Desktop Web (The Workspace):** Fokus pada Manajemen, Produktivitas, Task, dan Laporan. *Tidak ada tombol absen mencolok di sini.*
    * **Mobile PWA/APK (The Remote):** Fokus pada Absensi Cepat (Sat-set), Approval Kilat, dan Notifikasi.

2.  **Development Strategy ("Pecah Telur"):**
    * **Phase 1:** Admin (Otak & Pengaturan) -> *Current Status*
    * **Phase 2:** Me Zone (Jantung Operasional/Desktop)
    * **Phase 3:** Mobile PWA (Wajah Depan/APK)
    * **Phase 4:** Reporting (Rekapitulasi)

---

## 🎨 II. UI/UX & DESIGN LANGUAGE

* **Style:** **"Modern SaaS"** (Clean, Airy, Professional).
* **Typography:** **Plus Jakarta Sans** (Bold Headers, Readable Body).
* **Layout Concept:**
    * **Admin/Desktop:** **Bento Grid** (Kotak-kotak modular rapi).
    * **Navigation:** **Grouping Sidebar** (No Dropdown, Section Headers).
    * **Mobile:** **Soft Glassmorphism** + **Floating Dock**.
* **Interactions:**
    * Toggle Switches (bukan Checkbox kaku).
    * Slide-to-Action (untuk Absen).
    * Hover Lift Effects.

---

## 🛠 III. TECHNICAL ARCHITECTURE

* **Backend:** PHP Native (MVC Structure)
* **Frontend:** TailwindCSS + AlpineJS (Ringan, Tanpa Build ribet).
* **Mobile Wrapper:** **Ionic Capacitor** (Mengubah Web jadi APK Native).
* **Database:** MySQL (Mempertahankan struktur tabel `role_permissions` lama).
* **Storage Strategy:** Client-side Image Compression (.webp) + Cron Job Auto-Delete (2 Bulan).

---

## 🚀 IV. MODULE BREAKDOWN & LOGIC

### 1. ADMIN MODULE (The Gatekeeper) - *PRIORITAS SEKARANG*
* **Role & Permission:**
    * UI: Grid Card per Modul (bukan tabel panjang).
    * Logic: Toggle Switch Parent-Child.
* **Sidebar Dinamis:** Menu muncul berdasarkan Permission User.
* **Structure:** Folder Controller & View terisolasi di `app/Controllers/Admin/` dan `app/Views/admin/`.

### 2. ME ZONE (Desktop User Dashboard)
* **Dashboard:**
    * Widget Kiri: Identitas & Status Absen (Read Only).
    * Widget Tengah: Task Board (To Do / In Progress / Submit).
    * Widget Kanan: Quota Cuti & Info.
* **Logic:** Absensi via Desktop disembunyikan/dibatasi (Wajib HP), kecuali IP Kantor (Opsional).

### 3. MOBILE PWA (Attendance Machine)
* **Absensi Logic:**
    * **Radius Lock:** GPS User vs GPS Kantor.
    * **Selfie Verification:** Direct Camera Only (No Upload).
    * **Anti-Cheat:**
        * **Overlay Guide:** Siluet Kepala & Bahu (Visual Trap).
        * **Gyroscope Lock:** Wajib Portrait & HP Tegak (Cegah absen sambil rebahan).
* **Elderly-Proofing (Sepuh Friendly):**
    * **Blocking UI:** Jika GPS/Kamera mati, layar merah penuh -> Tombol "Fix Now" -> Membuka Native Android Settings via Capacitor Bridge.

---

## ✅ V. STATUS & NEXT STEPS

### 🟢 Selesai (Done)
1.  Setup Virtual Host (`absenpwa.test`).
2.  Restrukturisasi Folder Controller (`app/Controllers/Admin/`).
3.  Desain Ulang **Login Page** (Modern Style).
4.  Desain Ulang **Sidebar** (Grouping Style).
5.  Desain Ulang **Header/Topnav**.

### 🚧 Sedang Dikerjakan (In Progress)
**Fokus: Integrasi Login & Dashboard Admin**

1.  **Fix Login Logic:**
    * Pastikan form login di `Views/admin/auth/login.php` mengarah ke `Controllers/Admin/Auth.php`.
    * Redirect sukses harus ke `Admin/Dashboard.php`.

2.  **Dashboard Admin:**
    * Buat Controller `Admin/Dashboard.php` (Proteksi Session).
    * Buat View `Views/admin/dashboard/index.php`.
    * Implementasi Layout baru (`admin-layout.php`) yang sudah direvisi dengan Sidebar baru.

3.  **Role & Permission UI:**
    * Implementasi desain "Bento Grid" Toggle ke dalam View Admin.
    * Koneksi data Dummy ke Database.

---

## 📝 TODO LIST (Immediate Action)

* [ ] **Test Login:** Pastikan Login Admin berhasil redirect ke Dashboard.
* [ ] **Create Dashboard:** Buat file `app/Views/admin/dashboard/index.php` (Kosong dulu gapapa, yang penting sidebar muncul).
* [ ] **Migrate Roles View:** Pindahkan codingan HTML `role-settings.html` (yang tadi kita buat) ke `app/Views/admin/roles/index.php`.