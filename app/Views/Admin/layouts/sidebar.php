<?php
// File: app/Views/layouts/sidebar.php

// ==========================================
// 1. DATA & PERMISSIONS
// ==========================================
$db = new Database;
$db->query("SELECT setting_key, setting_value FROM app_settings WHERE setting_key IN ('app_name', 'app_logo', 'app_tagline')");
$settings_raw = $db->resultSet();
$appSet = [];
foreach($settings_raw as $r) $appSet[$r['setting_key']] = $r['setting_value'];

$appNameStr = $appSet['app_name'] ?? 'HRIS System';
$appTagline = $appSet['app_tagline'] ?? 'Management';
$appLogoPath = $appSet['app_logo'] ?? '';

$nameParts = explode(' ', $appNameStr);
$firstName = array_shift($nameParts);
$restName = implode(' ', $nameParts);

$showAppLogo = false; $logoUrl = '';
if (!empty($appLogoPath)) {
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $appLogoPath)) {
        $showAppLogo = true; $logoUrl = BASEURL . '/' . $appLogoPath;
    } elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . '/public/' . $appLogoPath)) {
        $showAppLogo = true; $logoUrl = BASEURL . '/public/' . $appLogoPath;
    }
}

$role = $_SESSION['role'] ?? '';
$isStaffMode = isset($layout_mode) && $layout_mode === 'staff';
$isAdmin = ($role === 'admin' || $role === 'super_admin');
$isSuperAdmin = ($role === 'super_admin');

$p = [
    'org'       => in_array($role, ['super_admin', 'admin', 'hr_manager']),
    'people'    => in_array($role, ['super_admin', 'admin', 'hr_staff']),
    'finance'   => in_array($role, ['super_admin', 'admin', 'finance']),
    'work'      => in_array($role, ['super_admin', 'admin', 'coordinator', 'manager']),
    'attend'    => in_array($role, ['super_admin', 'admin', 'coordinator']),
    'kpi'       => in_array($role, ['super_admin', 'admin', 'manager', 'coordinator']),
    'report'    => in_array($role, ['super_admin', 'admin', 'manager', 'finance']),
    'system'    => in_array($role, ['super_admin']),
];

$uri = $_SERVER['REQUEST_URI'];

// ==========================================
// 2. CONFIG MENU (Versi Rapi dari sidebar-copy)
// ==========================================
$adminMenu = [
    ['type' => 'header', 'label' => 'MAIN MENU'],
    ['type' => 'link', 'label' => 'Dashboard', 'url' => '/admin/dashboard', 'icon' => 'ri-dashboard-3-line', 'show' => true],
    ['type' => 'link', 'label' => 'Pengumuman', 'url' => '/admin/announcements', 'icon' => 'ri-notification-3-line', 'show' => $isAdmin],

    ['type' => 'header', 'label' => 'DATA MASTER HR'],
    ['type' => 'dropdown', 'label' => 'Organisasi', 'icon' => 'ri-building-4-line', 'show' => $p['org'], 'items' => [
        ['label' => 'Perusahaan', 'url' => '/admin/company', 'show' => true],
        ['label' => 'Kantor Cabang', 'url' => '/admin/offices', 'show' => true],
        ['label' => 'Departemen', 'url' => '/admin/departments', 'show' => true],
    ]],
    ['type' => 'dropdown', 'label' => 'Karyawan', 'icon' => 'ri-team-line', 'show' => $p['people'], 'items' => [
        ['label' => 'Data Karyawan', 'url' => '/admin/employees', 'show' => true],
        ['label' => 'Kontrak & File', 'url' => '/admin/contracts', 'show' => true],
    ]],

    ['type' => 'header', 'label' => 'OPERASIONAL'],
    ['type' => 'dropdown', 'label' => 'Kehadiran & Izin', 'icon' => 'ri-fingerprint-line', 'show' => $p['work'], 'items' => [
        ['label' => 'Data Kehadiran', 'url' => '/admin/attendance', 'show' => $p['attend']],
        ['label' => 'Approval Izin', 'url' => '/admin/requests', 'show' => true],
    ]],
    ['type' => 'dropdown', 'label' => 'Jadwal & Shift', 'icon' => 'ri-calendar-event-line', 'show' => $p['attend'], 'items' => [
        ['label' => 'Kalender Shift', 'url' => '/admin/schedules', 'show' => true],
        ['label' => 'Master Shift', 'url' => '/admin/schedules/shifts', 'show' => true],
        ['label' => 'Hari Libur', 'url' => '/admin/schedules/holidays', 'show' => true],
    ]],
    ['type' => 'dropdown', 'label' => 'Kinerja & Tugas', 'icon' => 'ri-task-line', 'show' => ($p['kpi'] || $p['work']), 'items' => [
        ['label' => 'Tugas & Project', 'url' => '/admin/tasks', 'show' => $p['kpi']],
        ['label' => 'KPI & Performa', 'url' => '/admin/kpi', 'show' => $p['kpi']],
    ]],

    ['type' => 'header', 'label' => 'KEUANGAN & PAYROLL'],
    ['type' => 'dropdown', 'label' => 'Penggajian', 'icon' => 'ri-wallet-3-line', 'show' => $p['finance'], 'items' => [
        ['label' => 'Golongan Gaji', 'url' => '/admin/salary_grade', 'show' => true],
        ['label' => 'Master Gaji', 'url' => '/admin/salary', 'show' => true],
        ['label' => 'Riwayat Payroll', 'url' => '/admin/payroll', 'show' => true],
    ]],

    ['type' => 'header', 'label' => 'SISTEM & PENGATURAN'],
    ['type' => 'link', 'label' => 'Laporan Rekap', 'url' => '/admin/reports', 'icon' => 'ri-file-chart-line', 'show' => $p['report']],
    ['type' => 'dropdown', 'label' => 'Pengaturan', 'icon' => 'ri-settings-3-line', 'show' => $p['system'], 'items' => [
        ['label' => 'Role & Hak Akses', 'url' => '/admin/roles', 'show' => $isSuperAdmin],
        ['label' => 'User Access', 'url' => '/admin/useraccess', 'show' => true],
        ['label' => 'Pengaturan App', 'url' => '/admin/settings', 'show' => true],
    ]],
];

// Menu khusus MeZone / Mode Karyawan
$staffMenus = [
    ['/staff/dashboard', 'ri-dashboard-line', 'Dashboard'],
    ['/staff/inbox', 'ri-inbox-line', 'Inbox'],
    ['/staff/attendance', 'ri-fingerprint-line', 'Absensi Saya'],
    ['/staff/tasks', 'ri-task-line', 'Tugas & KPI'],
    ['/staff/requests', 'ri-mail-send-line', 'Pengajuan Izin'],
    ['/staff/payroll', 'ri-wallet-line', 'Slip Gaji'],
    ['/staff/schedules', 'ri-calendar-2-line', 'Jadwal Shift'],
    ['/staff/profile', 'ri-user-settings-line', 'Profil Saya'],
];

function isActive($url, $currentUri) { return ($url === $currentUri || (strpos($currentUri, $url) === 0 && $url !== '/admin/dashboard' && $url !== '/staff/dashboard')); }
function isGroupActive($items, $currentUri) { foreach($items as $i) if (isActive($i['url'], $currentUri)) return true; return false; }
?>

<div class="flex flex-col h-full bg-white relative">
    
    <div class="h-16 flex items-center justify-center shrink-0 border-b border-slate-100 px-4">
        <div class="flex items-center gap-3 w-full" :class="sidebarOpen ? 'justify-start' : 'justify-center'">
            
            <?php if ($showAppLogo): ?>
                <img src="<?= $logoUrl ?>" class="h-9 w-auto object-contain shrink-0 transition-transform duration-300" :class="sidebarOpen ? '' : 'scale-110'">
            <?php else: ?>
                <div class="w-9 h-9 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-lg shadow-md shrink-0">H</div>
            <?php endif; ?>
            
            <div class="overflow-hidden whitespace-nowrap" x-show="sidebarOpen">
                <h1 class="text-lg font-bold text-slate-800 leading-tight">
                    <?= $firstName ?><span class="text-indigo-600"><?= !empty($restName) ? ' '.$restName : '' ?></span>
                </h1>
            </div>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1 no-scrollbar">
        
        <?php if (!$isStaffMode): ?>
            <a href="<?= BASEURL ?>/staff/dashboard" 
               class="flex items-center gap-3 px-3 py-2.5 mb-6 rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-indigo-100 hover:shadow-lg transition-all group overflow-hidden"
               title="Masuk ke Mode Karyawan (MeZone)">
                <i class="ri-smartphone-line text-xl shrink-0 transition-transform group-hover:scale-110"></i>
                <span x-show="sidebarOpen" class="font-semibold text-sm whitespace-nowrap">Mode Karyawan</span>
            </a>

            <?php foreach ($adminMenu as $m): ?>
                <?php if (isset($m['show']) && $m['show'] === false) continue; ?>

                <?php if($m['type'] === 'header'): ?>
                    <div x-show="sidebarOpen" class="px-3 pt-4 pb-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"><?= $m['label'] ?></span>
                    </div>
                    <div x-show="!sidebarOpen" class="my-4 border-t border-slate-100 mx-2"></div>

                <?php elseif($m['type'] === 'link'): 
                    $active = isActive($m['url'], $uri);
                    $activeCls = $active ? "bg-indigo-50 text-indigo-600 font-semibold" : "text-slate-500 hover:bg-slate-50 hover:text-slate-900";
                ?>
                    <a href="<?= BASEURL . $m['url'] ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors group relative <?= $activeCls ?>" title="<?= $m['label'] ?>">
                        <i class="<?= $m['icon'] ?> text-xl shrink-0"></i>
                        <span x-show="sidebarOpen" class="whitespace-nowrap"><?= $m['label'] ?></span>
                        
                        <div x-show="!sidebarOpen" x-cloak class="absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 pointer-events-none z-50 whitespace-nowrap">
                            <?= $m['label'] ?>
                        </div>
                    </a>

                <?php elseif($m['type'] === 'dropdown'): 
                    $isOpen = isGroupActive($m['items'], $uri);
                    $activeIconCls = $isOpen ? "text-indigo-600" : "";
                ?>
                    <div x-data="{ open: <?= $isOpen ? 'true' : 'false' ?> }">
                        <button @click="open = !open" 
                                class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg transition-colors group text-slate-500 hover:bg-slate-50 hover:text-slate-900"
                                :class="open && sidebarOpen ? 'bg-slate-50' : ''"
                                title="<?= $m['label'] ?>">
                            <div class="flex items-center gap-3">
                                <i class="<?= $m['icon'] ?> text-xl shrink-0 <?= $activeIconCls ?>"></i>
                                <span x-show="sidebarOpen" class="whitespace-nowrap" :class="open ? 'text-slate-900 font-medium' : ''"><?= $m['label'] ?></span>
                            </div>
                            <i x-show="sidebarOpen" class="ri-arrow-down-s-line text-lg transition-transform" :class="open ? 'rotate-180' : ''"></i>
                        </button>

                        <div x-show="open && sidebarOpen" x-collapse>
                            <div class="mt-1 space-y-1">
                                <?php foreach($m['items'] as $item): 
                                    if(empty($item['show'])) continue;
                                    $subActive = isActive($item['url'], $uri);
                                    $subCls = $subActive ? 'text-indigo-600 bg-indigo-50 font-medium' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50';
                                ?>
                                    <a href="<?= BASEURL . $item['url'] ?>" class="flex items-center gap-2 pl-10 pr-3 py-2 rounded-lg text-sm transition-colors <?= $subCls ?>">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current opacity-40"></span>
                                        <?= $item['label'] ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

        <?php else: ?>
            <?php if ($isAdmin): ?>
                <a href="<?= BASEURL ?>/admin/dashboard" class="flex items-center gap-3 px-3 py-2.5 mb-4 rounded-lg bg-slate-800 text-white hover:bg-slate-700 shadow-lg group overflow-hidden" title="Kembali ke Admin">
                    <i class="ri-dashboard-3-line text-xl shrink-0"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Kembali ke Admin</span>
                    <div x-show="!sidebarOpen" x-cloak class="absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 pointer-events-none z-50 whitespace-nowrap">Kembali ke Admin</div>
                </a>
            <?php endif; ?>

            <div x-show="sidebarOpen" class="px-3 mt-4 mb-2">
                <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest">MeZone</span>
            </div>

            <?php foreach($staffMenus as $sm): 
                $active = isActive($sm[0], $uri);
                $cls = $active ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900';
            ?>
                <a href="<?= BASEURL . $sm[0] ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors group relative <?= $cls ?>" title="<?= $sm[2] ?>">
                    <i class="<?= $sm[1] ?> text-xl shrink-0"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap"><?= $sm[2] ?></span>
                    <div x-show="!sidebarOpen" x-cloak class="absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 pointer-events-none z-50 whitespace-nowrap">
                        <?= $sm[2] ?>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
        
    </nav>

    <div class="p-4 border-t border-slate-100 shrink-0 bg-white">
        <?php 
            $uProf = $data['user_profile'] ?? ['first_name' => $_SESSION['full_name'] ?? 'User', 'last_name' => '', 'position' => $_SESSION['role'] ?? 'Staff', 'profile_photo_path' => null];
            $imgUrl = (!empty($uProf['profile_photo_path']) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $uProf['profile_photo_path'])) 
                ? BASEURL . '/' . $uProf['profile_photo_path'] 
                : "https://ui-avatars.com/api/?name=" . urlencode(substr($uProf['first_name'],0,1)) . "&background=6366f1&color=fff";
        ?>
        <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer group relative">
            <img src="<?= $imgUrl ?>" class="w-9 h-9 rounded-full object-cover shadow-sm ring-2 ring-white group-hover:ring-indigo-100 shrink-0">
            
            <div class="overflow-hidden whitespace-nowrap" x-show="sidebarOpen">
                <p class="text-sm font-bold text-slate-800 truncate"><?= $uProf['first_name'] ?></p>
                <p class="text-[10px] text-slate-500 truncate capitalize"><?= $uProf['position'] ?></p>
            </div>
            
            <a x-show="sidebarOpen" href="<?= BASEURL ?>/Admin/LoginController/logout" class="ml-auto text-slate-400 hover:text-red-500 transition-colors shrink-0" title="Logout">
                <i class="ri-logout-box-r-line text-lg"></i>
            </a>

            <div x-show="!sidebarOpen" x-cloak class="absolute left-full bottom-0 ml-2 px-3 py-2 bg-slate-800 shadow-xl rounded-lg w-40 opacity-0 group-hover:opacity-100 transition-opacity z-50 pointer-events-none">
                <p class="text-sm font-bold text-white truncate"><?= $uProf['first_name'] ?></p>
                <p class="text-[10px] text-slate-300 mb-2 truncate capitalize"><?= $uProf['position'] ?></p>
                <p class="text-[10px] text-red-400 font-medium">Klik untuk menu profil</p>
            </div>
        </div>
    </div>
</div>