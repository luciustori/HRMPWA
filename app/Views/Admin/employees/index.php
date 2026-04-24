<div class="max-w-7xl mx-auto p-6">
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Pegawai</p>
                <h2 class="text-3xl font-bold text-slate-800"><?= $stats['total'] ?></h2>
            </div>
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl">
                <i class="ri-team-line"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Aktif Bekerja</p>
                <h2 class="text-3xl font-bold text-emerald-600"><?= $stats['active'] ?></h2>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl">
                <i class="ri-user-smile-line"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Non-Aktif / Cuti</p>
                <h2 class="text-3xl font-bold text-rose-600"><?= $stats['inactive'] ?></h2>
            </div>
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center text-xl">
                <i class="ri-user-unfollow-line"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        
        <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Direktori Karyawan</h2>
                <p class="text-sm text-slate-500">Kelola data personalia, struktur jabatan, dan golongan gaji.</p>
            </div>
            
            <div class="flex gap-3">
                <button type="button" id="btnSyncAkun" data-url="<?= BASEURL ?>/admin/employees/sync" 
   class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 font-bold text-sm hover:bg-slate-200 transition flex items-center gap-2">
   <i class="ri-shield-user-line"></i> Sync Akun
</button>
                <a href="<?= BASEURL ?>/admin/employees/create" 
                   class="px-6 py-2.5 rounded-xl bg-slate-900 text-white font-bold text-sm hover:bg-slate-800 shadow-lg shadow-slate-300/50 transition flex items-center gap-2">
                   <i class="ri-add-line text-lg"></i> Karyawan Baru
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50/50 text-slate-500 font-bold uppercase text-xs tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Nama & NIK</th>
                        <th class="px-6 py-4">Jabatan</th>
                        <th class="px-6 py-4 text-center">Grade Gaji</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if(empty($employees)): ?>
                        <tr><td colspan="5" class="p-8 text-center text-slate-400 italic">Belum ada data karyawan.</td></tr>
                    <?php else: ?>
                        <?php foreach($employees as $emp): ?>
                        <tr class="hover:bg-indigo-50/30 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold border border-slate-200">
                                        <?= substr($emp['first_name'], 0, 1) ?>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800"><?= $emp['first_name'] . ' ' . $emp['last_name'] ?></div>
                                        <div class="text-xs font-mono text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded inline-block mt-1">
                                            <?= $emp['employee_number'] ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-700"><?= $emp['position'] ?></div>
                                <div class="text-xs text-slate-500 mt-0.5">
                                    <?= $emp['department_name'] ?> 
                                    <?php if($emp['division_name']): ?>
                                        <span class="text-slate-300 mx-1">|</span> <?= $emp['division_name'] ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if(!empty($emp['grade_name'])): ?>
                                    <span class="inline-flex flex-col items-center px-3 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        <span class="font-bold text-[10px] uppercase tracking-wide"><?= $emp['grade_code'] ?></span>
                                        <span class="font-bold text-xs"><?= $emp['grade_name'] ?></span>
                                    </span>
                                <?php else: ?>
                                    <span class="text-xs text-slate-400 italic">Unset</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-bold 
                                    <?= $emp['is_active'] ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' ?>">
                                    <?= $emp['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <a href="<?= BASEURL ?>/admin/employees/edit/<?= $emp['id'] ?>" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-indigo-600 flex items-center justify-center hover:bg-indigo-50 shadow-sm transition" title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <a href="<?= BASEURL ?>/admin/employees/delete/<?= $emp['id'] ?>" onclick="return confirm('Hapus karyawan ini?')" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-rose-600 flex items-center justify-center hover:bg-rose-50 shadow-sm transition" title="Hapus">
                                        <i class="ri-delete-bin-line"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnSync = document.getElementById('btnSyncAkun');
    
    if (btnSync) {
        btnSync.addEventListener('click', function() {
            const url = this.getAttribute('data-url');

            Swal.fire({
                title: 'Sync Akun Karyawan?',
                text: "Sistem akan membuatkan akun (NIK & Password Default) untuk karyawan yang belum memiliki akses login.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5', // Warna Indigo biar senada sama tema lo
                cancelButtonColor: '#f43f5e', // Warna Rose
                confirmButtonText: '<i class="ri-check-line"></i> Ya, Sync Sekarang!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tambahan: Munculin loading state pas lagi proses redirect
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Eksekusi arahkan ke URL Sync
                    window.location.href = url;
                }
            });
        });
    }
});
</script>