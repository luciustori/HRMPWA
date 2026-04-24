<header class="bg-white border-b border-slate-200 px-6 py-3 h-16 flex items-center justify-between shrink-0 z-10">
    
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" 
                class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 hover:text-indigo-600 transition-colors focus:outline-none">
            <i class="text-xl transition-transform duration-300" :class="sidebarOpen ? 'ri-menu-fold-line' : 'ri-menu-unfold-line'"></i>
        </button>
        
        <h2 class="text-lg font-bold text-slate-800 tracking-tight">
            <?= $title ?? 'Dashboard' ?>
        </h2>
    </div>

    <div class="flex items-center gap-4">
        <?php 
            $initials = substr($_SESSION['full_name'] ?? 'U', 0, 1);
        ?>
        <div x-data="{ profileOpen: false }" class="relative">
            <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false" 
                    class="flex items-center gap-2 focus:outline-none">
                <div class="w-9 h-9 rounded-full shadow-sm bg-indigo-600 hover:bg-indigo-700 transition-colors flex items-center justify-center text-white font-bold text-sm ring-2 ring-white">
                    <?= strtoupper($initials) ?>
                </div>
            </button>
            
            <div x-show="profileOpen" x-transition x-cloak
                 class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-2 z-50">
                 <div class="px-4 py-2 border-b border-slate-50 text-xs font-bold text-slate-400">
                    <?= $_SESSION['full_name'] ?? 'User' ?>
                 </div>
                 
                <a href="<?= BASEURL ?>/Admin/LoginController/logout" class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors mt-1">
                    <i class="ri-logout-box-r-line"></i> Keluar
                </a>
            </div>
        </div>
    </div>

</header>