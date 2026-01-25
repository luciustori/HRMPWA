<aside :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in lg:translate-x-0 lg:static lg:inset-auto'" 
       class="fixed inset-y-0 left-0 z-30 transition-all duration-300 transform bg-white border-r border-gray-200 flex flex-col"
       :class="sidebarCondensed ? 'w-20' : 'w-64'">
    
    <div class="flex items-center justify-center h-16 bg-white border-b border-gray-100 transition-all duration-300">
        <span x-show="!sidebarCondensed" class="text-2xl font-bold text-primary-600 tracking-wider whitespace-nowrap overflow-hidden transition-all duration-300">
            CHAMP<span class="text-gray-700">ADMIN</span>
        </span>
        <span x-show="sidebarCondensed" class="text-2xl font-bold text-primary-600" style="display: none;">
            CA
        </span>
    </div>

    <nav class="flex-1 mt-5 px-2 space-y-2 overflow-y-auto">
        
        <p x-show="!sidebarCondensed" class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider transition-opacity duration-300">Main Menu</p>

        <a href="<?= BASEURL ?>/admin/dashboard" 
           class="flex items-center py-3 transition-colors transform rounded-lg hover:bg-primary-50 hover:text-primary-600 group <?= (strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false) ? 'bg-primary-50 text-primary-600' : 'text-gray-600' ?>"
           :class="sidebarCondensed ? 'justify-center px-0' : 'px-4'">
            <i class="fas fa-home w-5 h-5 transition-transform duration-300" :class="sidebarCondensed ? 'text-xl' : ''"></i>
            <span x-show="!sidebarCondensed" class="mx-4 font-medium whitespace-nowrap transition-opacity duration-300">Dashboard</span>
            
            <div x-show="sidebarCondensed" class="absolute left-16 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none z-50 whitespace-nowrap">Dashboard</div>
        </a>

        <a href="<?= BASEURL ?>/admin/employees" 
           class="flex items-center py-3 transition-colors transform rounded-lg hover:bg-primary-50 hover:text-primary-600 group <?= (strpos($_SERVER['REQUEST_URI'], 'employees') !== false) ? 'bg-primary-50 text-primary-600' : 'text-gray-600' ?>"
           :class="sidebarCondensed ? 'justify-center px-0' : 'px-4'">
            <i class="fas fa-users w-5 h-5 transition-transform duration-300" :class="sidebarCondensed ? 'text-xl' : ''"></i>
            <span x-show="!sidebarCondensed" class="mx-4 font-medium whitespace-nowrap transition-opacity duration-300">Karyawan</span>
        </a>

        <a href="<?= BASEURL ?>/admin/tasks" 
           class="flex items-center py-3 transition-colors transform rounded-lg hover:bg-primary-50 hover:text-primary-600 group <?= (strpos($_SERVER['REQUEST_URI'], 'tasks') !== false) ? 'bg-primary-50 text-primary-600' : 'text-gray-600' ?>"
           :class="sidebarCondensed ? 'justify-center px-0' : 'px-4'">
            <i class="fas fa-tasks w-5 h-5 transition-transform duration-300" :class="sidebarCondensed ? 'text-xl' : ''"></i>
            <span x-show="!sidebarCondensed" class="mx-4 font-medium whitespace-nowrap transition-opacity duration-300">Tugas & KPI</span>
        </a>

        <div class="my-4 border-t border-gray-100"></div>

        <a href="<?= BASEURL ?>/auth/logout" 
           class="flex items-center py-3 text-red-500 hover:bg-red-50 rounded-lg transition-colors group"
           :class="sidebarCondensed ? 'justify-center px-0' : 'px-4'">
            <i class="fas fa-sign-out-alt w-5 h-5 transition-transform duration-300" :class="sidebarCondensed ? 'text-xl' : ''"></i>
            <span x-show="!sidebarCondensed" class="mx-4 font-medium whitespace-nowrap transition-opacity duration-300">Logout</span>
        </a>

    </nav>
</aside>