<?php 
$layout_mode = 'staff';
require_once __DIR__ . '/../../admin/layouts/header.php'; 
?>

<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div x-data="{ 
        sidebarOpen: localStorage.getItem('sidebarOpen') === null ? true : localStorage.getItem('sidebarOpen') === 'true',
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
            localStorage.setItem('sidebarOpen', this.sidebarOpen);
        }
    }" 
    class="flex h-screen bg-slate-50 font-sans text-slate-600 antialiased overflow-hidden">

    <aside class="shrink-0 transition-all duration-300 ease-in-out border-r border-slate-200 bg-white z-20 flex flex-col"
           :class="sidebarOpen ? 'w-64' : 'w-20'">
        <?php require_once __DIR__ . '/../../admin/layouts/sidebar.php'; ?>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden min-w-0">
        
        <?php require_once __DIR__ . '/../../admin/layouts/topnavbar.php'; ?>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-6 md:p-8">
            <div class="max-w-7xl mx-auto w-full">
                <?php 
                    if (isset($content_view) && file_exists('../app/Views/' . $content_view . '.php')) {
                        require_once '../app/Views/' . $content_view . '.php';
                    } else {
                        if (file_exists('../app/Views/staff/dashboard/index.php')) {
                            require_once '../app/Views/staff/dashboard/index.php';
                        } else {
                             echo "<div class='text-center py-20 text-slate-400'>Halaman Belum Ada</div>";
                        }
                    }
                ?>
            </div>
        </main>

        <?php require_once __DIR__ . '/../../admin/layouts/footer.php'; ?>
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    [x-cloak] { display: none !important; }
</style>

</body>
</html>