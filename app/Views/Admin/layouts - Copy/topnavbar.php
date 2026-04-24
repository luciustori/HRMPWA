<nav class="fixed top-0 right-0 z-20 bg-white/90 backdrop-blur-sm border-b border-gray-200 h-16 transition-all duration-300 ease-in-out"
     :class="sidebarCondensed ? 'left-0 md:left-20' : 'left-0 md:left-64'">
    
    <div class="px-4 h-full flex items-center justify-between">
        
        <div class="flex items-center gap-4">
            
            <button @click="sidebarCondensed = !sidebarCondensed" 
                    class="hidden md:flex items-center justify-center p-2 text-gray-500 rounded-lg hover:bg-gray-100 hover:text-indigo-600 focus:outline-none transition-transform active:scale-95">
                <i class="fas" :class="sidebarCondensed ? 'fa-indent' : 'fa-outdent'"></i>
            </button>

            <button @click="sidebarOpen = !sidebarOpen" 
                    class="md:hidden flex items-center justify-center p-2 text-gray-500 rounded-lg hover:bg-gray-100 hover:text-indigo-600 focus:outline-none">
                <i class="fas fa-bars text-xl"></i>
            </button>
            
            <h2 class="text-lg font-bold text-gray-700 tracking-tight hidden sm:block">
                <?= $title ?? 'Admin Panel' ?>
            </h2>
        </div>

        <div class="flex items-center gap-3">
            
            <div class="text-right hidden sm:block">
                <p class="text-sm font-bold text-gray-800 leading-none"><?= $_SESSION['full_name'] ?? 'Admin' ?></p>
                <p class="text-xs text-gray-400 mt-1 leading-none"><?= ucfirst($_SESSION['user_role'] ?? 'Administrator') ?></p>
            </div>

            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center focus:outline-none">
                    <img class="h-9 w-9 rounded-full object-cover border-2 border-indigo-100 hover:border-indigo-300 transition" 
                         src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['full_name'] ?? 'U') ?>&background=random&color=fff" 
                         alt="Profile">
                </button>

                <div x-show="open" @click.away="open = false" 
                     class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50 transform origin-top-right transition-all duration-200"
                     style="display: none;">
                    
                    <div class="px-4 py-2 border-b border-gray-50 sm:hidden">
                        <p class="text-sm font-bold text-gray-800"><?= $_SESSION['full_name'] ?? 'User' ?></p>
                    </div>

                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <i class="far fa-user mr-2 text-gray-400"></i> Profil Saya
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <i class="fas fa-cog mr-2 text-gray-400"></i> Settings
                    </a>
                    <div class="border-t border-gray-100 my-1"></div>
                    <a href="<?= BASEURL ?>/auth/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-medium">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                </div>
            </div>

        </div>
    </div>
</nav>