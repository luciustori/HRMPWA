<!-- File: app/Views/Admin/components/sidebar.php -->
<?php
// Collapsible Sidebar Component
$user_role = isset($_SESSION['user']['role']) ? $_SESSION['user']['role'] : null;
?>

<aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-gray-900 text-white transform -translate-x-full lg:translate-x-0 transition-transform duration-300 z-40">
    <div class="p-6">
        <h2 class="text-xl font-bold">Menu</h2>
    </div>

    <nav class="space-y-2 px-4">
        <!-- Dashboard -->
        <a href="/admin" class="block px-4 py-2 rounded hover:bg-gray-800">
            Dashboard
        </a>

        <?php if($user_role === 'super_admin'): ?>
        <!-- Admin Only Menu -->
        <div class="pt-4">
            <h3 class="px-4 py-2 text-xs uppercase font-bold text-gray-400">PENGATURAN</h3>
            
            <a href="/admin/company" class="block px-4 py-2 rounded hover:bg-gray-800">
                Pengaturan Perusahaan
            </a>
            <a href="/admin/offices" class="block px-4 py-2 rounded hover:bg-gray-800">
                Kantor
            </a>
            <a href="/admin/departments" class="block px-4 py-2 rounded hover:bg-gray-800">
                Departemen
            </a>
            <a href="/admin/employee-levels" class="block px-4 py-2 rounded hover:bg-gray-800">
                Level Karyawan
            </a>
        </div>

        <div class="pt-4">
            <h3 class="px-4 py-2 text-xs uppercase font-bold text-gray-400">MANAJEMEN</h3>
            
            <a href="/admin/employees" class="block px-4 py-2 rounded hover:bg-gray-800">
                Kepegawaian
            </a>
            <a href="/admin/shifts" class="block px-4 py-2 rounded hover:bg-gray-800">
                Jadwal Kerja
            </a>
            <a href="/admin/attendances" class="block px-4 py-2 rounded hover:bg-gray-800">
                Absensi
            </a>
        </div>

        <div class="pt-4">
            <h3 class="px-4 py-2 text-xs uppercase font-bold text-gray-400">KEUANGAN</h3>
            
            <a href="/admin/payroll" class="block px-4 py-2 rounded hover:bg-gray-800">
                Penggajian
            </a>
            <a href="/admin/salary-components" class="block px-4 py-2 rounded hover:bg-gray-800">
                Komponen Gaji
            </a>
            <a href="/admin/salary-slips" class="block px-4 py-2 rounded hover:bg-gray-800">
                Slip Gaji
            </a>
        </div>

        <div class="pt-4">
            <h3 class="px-4 py-2 text-xs uppercase font-bold text-gray-400">APPROVAL</h3>
            
            <a href="/admin/approvals" class="block px-4 py-2 rounded hover:bg-gray-800">
                Pengajuan
            </a>
        </div>

        <div class="pt-4">
            <h3 class="px-4 py-2 text-xs uppercase font-bold text-gray-400">KONTEN</h3>
            
            <a href="/admin/announcements" class="block px-4 py-2 rounded hover:bg-gray-800">
                Pengumuman
            </a>
            <a href="/admin/tasks" class="block px-4 py-2 rounded hover:bg-gray-800">
                Pekerjaan
            </a>
        </div>

        <div class="pt-4">
            <h3 class="px-4 py-2 text-xs uppercase font-bold text-gray-400">SISTEM</h3>
            
            <a href="/admin/settings" class="block px-4 py-2 rounded hover:bg-gray-800">
                Pengaturan Sistem
            </a>
            <a href="/admin/roles" class="block px-4 py-2 rounded hover:bg-gray-800">
                Manajemen Role
            </a>
        </div>

        <?php elseif($user_role === 'manager'): ?>
        <!-- Manager Menu -->
        <div class="pt-4">
            <h3 class="px-4 py-2 text-xs uppercase font-bold text-gray-400">DEPARTEMEN SAYA</h3>
            
            <a href="/admin/department-employees" class="block px-4 py-2 rounded hover:bg-gray-800">
                Karyawan
            </a>
            <a href="/admin/approvals" class="block px-4 py-2 rounded hover:bg-gray-800">
                Persetujuan Pengajuan
            </a>
            <a href="/admin/tasks" class="block px-4 py-2 rounded hover:bg-gray-800">
                Pekerjaan
            </a>
            <a href="/admin/announcements" class="block px-4 py-2 rounded hover:bg-gray-800">
                Pengumuman
            </a>
        </div>
        <?php endif; ?>
    </nav>
</aside>

<!-- Overlay untuk mobile -->
<div id="sidebar-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 lg:hidden z-30"></div>
