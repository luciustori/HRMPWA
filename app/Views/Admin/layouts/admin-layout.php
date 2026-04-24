<?php require_once __DIR__ . '/header.php'; ?>

<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div x-data="{ sidebarOpen: true }" class="flex h-screen bg-slate-50 font-sans text-slate-700 antialiased overflow-hidden">

    <aside class="shrink-0 transition-all duration-300 ease-in-out bg-white border-r border-slate-200 z-20 flex flex-col shadow-sm"
           :class="sidebarOpen ? 'w-64' : 'w-20'">
        <?php require_once __DIR__ . '/sidebar.php'; ?>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden min-w-0 relative">
        
        <?php require_once __DIR__ . '/topnavbar.php'; ?>
        
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-6 md:p-8">
            <div class="max-w-8xl mx-auto w-full">
                
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                <script>
                    class SwalCRUD {
                        // A. Nampilin Notifikasi Hasil (Pengganti Flasher biasa)
                        static notify(icon, title, text) {
                            Swal.fire({
                                icon: icon,
                                title: title,
                                text: text,
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                customClass: { popup: 'rounded-2xl shadow-xl border border-slate-100' }
                            });
                        }

                        // B. Nangkap Klik Link (Untuk Delete / Sync)
                        static handleLinks(e) {
                            const btn = e.target.closest('.swal-link');
                            if (!btn) return;
                            
                            e.preventDefault();
                            const url = btn.getAttribute('href');
                            const action = btn.getAttribute('data-action') || 'delete'; 
                            const msg = btn.getAttribute('data-msg') || 'Apakah Anda yakin?';
                            
                            let title = 'Konfirmasi';
                            let icon = 'warning';
                            let color = '#4f46e5'; // Indigo (Default)
                            let confirmTxt = 'Ya, Lanjutkan!';

                            if (action === 'delete') {
                                title = 'Hapus Data?';
                                color = '#ef4444'; // Merah
                                confirmTxt = 'Ya, Hapus!';
                            } else if (action === 'sync') {
                                title = 'Sinkronisasi API?';
                                icon = 'info';
                                color = '#16a34a'; // Hijau
                                confirmTxt = 'Ya, Tarik Data!';
                            }

                            Swal.fire({
                                title: title,
                                text: msg,
                                icon: icon,
                                showCancelButton: true,
                                confirmButtonColor: color,
                                cancelButtonColor: '#94a3b8',
                                confirmButtonText: confirmTxt,
                                cancelButtonText: 'Batal',
                                customClass: { popup: 'rounded-2xl shadow-xl' }
                            }).then((result) => {
                                if (result.isConfirmed) window.location.href = url;
                            });
                        }

                        // C. Nangkap Form Submit (Untuk Create / Update)
                        static handleForms(e) {
                            const form = e.target.closest('.swal-form');
                            if (!form) return;

                            e.preventDefault(); // Tahan form agar tidak langsung terkirim
                            
                            const msg = form.getAttribute('data-msg') || 'Pastikan data yang diisi sudah benar.';
                            const title = form.getAttribute('data-title') || 'Simpan Data?';
                            
                            Swal.fire({
                                title: title,
                                text: msg,
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#4f46e5',
                                cancelButtonColor: '#94a3b8',
                                confirmButtonText: 'Ya, Simpan!',
                                cancelButtonText: 'Batal',
                                customClass: { popup: 'rounded-2xl shadow-xl' }
                            }).then((result) => {
                                if (result.isConfirmed) form.submit(); // Loloskan submit jika user klik Ya
                            });
                        }

                        // D. Pasang "Satpam" Event Listener
                        static init() {
                            document.body.addEventListener('click', this.handleLinks);
                            document.body.addEventListener('submit', this.handleForms);
                        }
                    }

                    // Jalankan Class saat web diload
                    document.addEventListener('DOMContentLoaded', () => SwalCRUD.init());
                </script>

                <?php 
                    if (isset($_SESSION['flash'])) {
                        $pesan = $_SESSION['flash']['pesan'] ?? '';
                        $tipe = $_SESSION['flash']['tipe'] ?? 'success';
                        
                        $icon = ($tipe === 'danger' || $tipe === 'error') ? 'error' : 'success';
                        $title = ($icon === 'error') ? 'Gagal!' : 'Berhasil!';
                        
                        echo "<script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    SwalCRUD.notify('$icon', '$title', '" . addslashes($pesan) . "');
                                });
                              </script>";
                        unset($_SESSION['flash']);
                    }
                    
                    // Render Konten (Jangan dihapus)
                    if (isset($content_view) && file_exists('../app/Views/' . $content_view . '.php')) {
                        require_once '../app/Views/' . $content_view . '.php';
                    } else {
                        echo "
                        <div class='flex flex-col items-center justify-center h-96 text-center text-slate-400'>
                            <i class='ri-file-search-line text-5xl mb-3'></i>
                            <h3 class='text-lg font-semibold text-slate-600'>Halaman Tidak Ditemukan</h3>
                            <p>View <b>{$content_view}</b> belum dibuat.</p>
                        </div>";
                    }
                ?>
            </div>
        </main>

        <?php require_once __DIR__ . '/footer.php'; ?>
        
    </div>
</div>

<style>
    /* Custom Scrollbar Halus untuk Desktop */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    [x-cloak] { display: none !important; }
</style>

</body>
</html>