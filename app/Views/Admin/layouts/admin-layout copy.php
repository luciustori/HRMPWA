<?php require_once __DIR__ . '/header.php'; ?>

<div x-data="{ sidebarExpanded: true }" class="relative w-full bg-slate-50 min-h-screen font-sans">

    <aside class="sidebar-wrapper"
           :class="sidebarExpanded ? 'sidebar-expanded' : 'sidebar-collapsed'">
        <?php require_once __DIR__ . '/sidebar.php'; ?>
    </aside>

    <div class="main-content-wrapper flex flex-col min-h-screen"
         :class="sidebarExpanded ? 'content-expanded' : 'content-collapsed'">
        
        <nav class="sticky top-0 z-30 w-full bg-white/80 backdrop-blur-md border-b border-slate-200/60 px-8 py-4 transition-all">
            <div class="flex items-center justify-between">
                
                <div class="flex items-center gap-4">
                    <button @click="sidebarExpanded = !sidebarExpanded" 
                            class="p-2 text-slate-500 rounded-xl hover:bg-slate-100 hover:text-indigo-600 transition-all focus:outline-none active:scale-95">
                        <i class="text-xl" :class="sidebarExpanded ? 'ri-menu-fold-line' : 'ri-menu-unfold-line'"></i>
                    </button>
                    
                    <div>
                        <h2 class="text-lg font-bold text-slate-800 tracking-tight">
                            <?= $data['title'] ?? 'Dashboard' ?>
                        </h2>
                    </div>
                </div>

                <div class="flex items-center gap-5">
                    <button class="relative text-slate-400 hover:text-indigo-600 transition-colors">
                        <i class="ri-notification-3-line text-xl"></i>
                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
                    </button>
                    <div class="h-8 w-px bg-slate-200"></div>
                    <p class="text-sm font-medium text-slate-500"><?= date('l, d M Y') ?></p>
                </div>
            </div>
        </nav>

        <main class="flex-1 p-8">
            <div class="max-w-7xl mx-auto w-full">
                <?php 
                    if (isset($content_view) && file_exists('../app/Views/' . $content_view . '.php')) {
                        require_once '../app/Views/' . $content_view . '.php';
                    } else {
                        echo "<div class='text-center py-20 text-slate-400'>View not found.</div>";
                    }
                ?>
            </div>
        </main>

        <?php require_once __DIR__ . '/footer.php'; ?>
        
    </div>

</div>

</body>
</html>