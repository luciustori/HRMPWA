<nav class="fixed top-0 z-40 w-full bg-white border-b border-gray-200 sm:pl-64 h-16 transition-all duration-300">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            
            <div class="flex items-center justify-start">
                <button data-drawer-target="sidebar" data-drawer-toggle="sidebar" aria-controls="sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <span class="sr-only">Open sidebar</span>
                    <i class="fas fa-bars w-6 h-6"></i>
                </button>
                
                <div class="hidden md:block ml-4 relative w-64">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Cari menu...">
                </div>
            </div>

            <div class="flex items-center gap-4">
                
                <button type="button" class="relative p-2 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100">
                    <span class="sr-only">View notifications</span>
                    <i class="far fa-bell w-6 h-6"></i>
                    <div class="absolute inline-flex items-center justify-center w-2 h-2 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-0 -right-0"></div>
                </button>

                <div class="relative ml-3" x-data="{ open: false }">
                    <div>
                        <button @click="open = !open" type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300" aria-expanded="false">
                            <span class="sr-only">Open user menu</span>
                            <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['full_name'] ?? 'User') ?>&background=random" alt="user photo">
                        </button>
                    </div>
                    
                    <div x-show="open" @click.away="open = false" class="z-50 absolute right-0 mt-2 w-48 text-base list-none bg-white divide-y divide-gray-100 rounded shadow" style="display: none;">
                        <div class="px-4 py-3" role="none">
                            <p class="text-sm text-gray-900" role="none"><?= $_SESSION['full_name'] ?? 'User' ?></p>
                            <p class="text-sm font-medium text-gray-900 truncate" role="none"><?= $_SESSION['employee_number'] ?? 'ID' ?></p>
                        </div>
                        <ul class="py-1" role="none">
                            <li>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Profile</a>
                            </li>
                            <li>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Settings</a>
                            </li>
                            <li>
                                <a href="<?= BASEURL ?>/auth/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100" role="menuitem">Sign out</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>