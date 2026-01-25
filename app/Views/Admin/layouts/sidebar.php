<aside class="fixed inset-y-0 left-0 z-30 bg-white border-r border-gray-200 shadow-sm transition-all duration-300 ease-in-out transform"
       :class="[
           sidebarOpen ? 'translate-x-0' : '-translate-x-full', 
           sidebarCondensed ? 'md:w-20' : 'md:w-64',
           'md:translate-x-0'
       ]"
       x-cloak>

    <div class="h-16 flex items-center justify-center bg-gradient-to-r from-indigo-600 to-indigo-700 text-white transition-all duration-300 overflow-hidden whitespace-nowrap">
        <div x-show="!sidebarCondensed" class="flex items-center gap-2 font-bold text-xl tracking-wider">
            <i class="fas fa-fingerprint"></i>
            <span>ABSEN<span class="font-light text-indigo-200">PWA</span></span>
        </div>
        <div x-show="sidebarCondensed" class="text-xl font-bold">
            <i class="fas fa-fingerprint"></i>
        </div>
    </div>

    <nav class="flex-1 px-3 space-y-2 overflow-y-auto mt-4 pb-20 custom-scroll">
        
        <?php 
        $uri = $_SERVER['REQUEST_URI'];
        function menuActive($key) {
            global $uri;
            return strpos($uri, $key) !== false 
                   ? 'bg-indigo-50 text-indigo-700 font-bold shadow-sm' 
                   : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900';
        }
        ?>

        <a href="<?= BASEURL ?>/admin/dashboard" 
           class="flex items-center py-3 px-3 rounded-lg transition-all duration-200 group relative <?= menuActive('dashboard') ?>">
            <i class="fas fa-home w-6 text-center text-lg"></i>
            <span x-show="!sidebarCondensed" class="ml-3 text-sm transition-opacity duration-300">Dashboard</span>
            
            <div x-show="sidebarCondensed" class="absolute left-14 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 z-50 pointer-events-none whitespace-nowrap transition-opacity">
                Dashboard
            </div>
        </a>

        <div x-show="!sidebarCondensed" class="mt-6 mb-2 px-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Master Data</div>
        <div x-show="sidebarCondensed" class="mt-4 border-t border-gray-100"></div>

        <a href="<?= BASEURL ?>/admin/company" class="flex items-center py-3 px-3 rounded-lg transition-all duration-200 group relative <?= menuActive('company') ?>">
            <i class="fas fa-building w-6 text-center text-lg"></i>
            <span x-show="!sidebarCondensed" class="ml-3 text-sm">Perusahaan</span>
            <div x-show="sidebarCondensed" class="absolute left-14 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 z-50 pointer-events-none">Profil</div>
        </a>

        <a href="<?= BASEURL ?>/admin/offices" class="flex items-center py-3 px-3 rounded-lg transition-all duration-200 group relative <?= menuActive('offices') ?>">
            <i class="fas fa-map-marked-alt w-6 text-center text-lg"></i>
            <span x-show="!sidebarCondensed" class="ml-3 text-sm">Lokasi Kantor</span>
            <div x-show="sidebarCondensed" class="absolute left-14 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 z-50 pointer-events-none">Lokasi</div>
        </a>

        <a href="<?= BASEURL ?>/admin/departments" class="flex items-center py-3 px-3 rounded-lg transition-all duration-200 group relative <?= menuActive('departments') ?>">
            <i class="fas fa-sitemap w-6 text-center text-lg"></i>
            <span x-show="!sidebarCondensed" class="ml-3 text-sm">Departemen</span>
            <div x-show="sidebarCondensed" class="absolute left-14 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 z-50 pointer-events-none">Divisi</div>
        </a>

        <a href="<?= BASEURL ?>/admin/employees" class="flex items-center py-3 px-3 rounded-lg transition-all duration-200 group relative <?= menuActive('employees') ?>">
            <i class="fas fa-users w-6 text-center text-lg"></i>
            <span x-show="!sidebarCondensed" class="ml-3 text-sm">Karyawan</span>
            <div x-show="sidebarCondensed" class="absolute left-14 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 z-50 pointer-events-none">Pegawai</div>
        </a>

        <div class="mt-auto pt-6">
             <a href="<?= BASEURL ?>/auth/logout" class="flex items-center py-3 px-3 text-red-600 hover:bg-red-50 rounded-lg transition-all group relative">
                <i class="fas fa-sign-out-alt w-6 text-center text-lg"></i>
                <span x-show="!sidebarCondensed" class="ml-3 text-sm font-medium">Logout</span>
                 <div x-show="sidebarCondensed" class="absolute left-14 bg-red-600 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 z-50 pointer-events-none">Keluar</div>
            </a>
        </div>

    </nav>
</aside>