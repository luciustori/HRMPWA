<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-indigo-500 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Total Pegawai</p>
            <h2 class="text-3xl font-bold text-gray-800"><?= $stats['total'] ?></h2>
        </div>
        <div class="p-3 bg-indigo-50 rounded-full text-indigo-600">
            <i class="fas fa-users text-2xl"></i>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Status Aktif</p>
            <h2 class="text-3xl font-bold text-gray-800"><?= $stats['active'] ?></h2>
        </div>
        <div class="p-3 bg-green-50 rounded-full text-green-600">
            <i class="fas fa-user-check text-2xl"></i>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-gray-400 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Non-Aktif / Cuti</p>
            <h2 class="text-3xl font-bold text-gray-800"><?= $stats['inactive'] ?></h2>
        </div>
        <div class="p-3 bg-gray-100 rounded-full text-gray-500">
            <i class="fas fa-user-clock text-2xl"></i>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Direktori Karyawan</h2>
            <p class="text-sm text-gray-500">Kelola data personalia dan akses sistem.</p>
        </div>
        
        <div class="flex flex-wrap gap-2">
            <?php if (PermissionHelper::has('employees.create')): ?>
                <a href="<?= BASEURL ?>/admin/employees/sync" 
                   onclick="return confirm('Sistem akan membuatkan akun login (username=NIK, pass=password123) untuk semua karyawan yang belum punya akun. Lanjutkan?')"
                   class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg shadow transition flex items-center text-sm">
                   <i class="fas fa-sync-alt mr-2"></i> Sync Akun Login
                </a>

                <a href="<?= BASEURL ?>/admin/employees/create" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow transition flex items-center text-sm">
                   <i class="fas fa-user-plus mr-2"></i> Karyawan Baru
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 uppercase text-xs leading-normal">
                    <th class="py-3 px-6 font-semibold">Profil</th>
                    <th class="py-3 px-6 font-semibold">Jabatan & Divisi</th>
                    <th class="py-3 px-6 font-semibold">Status Karyawan</th>
                    <th class="py-3 px-6 font-semibold text-center">Akun Login</th>
                    <th class="py-3 px-6 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <?php foreach ($employees as $emp): ?>
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    
                    <td class="py-4 px-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold mr-3">
                                <?= substr($emp['first_name'], 0, 1) ?>
                            </div>
                            <div>
                                <span class="block font-bold text-gray-800"><?= $emp['first_name'] . ' ' . $emp['last_name'] ?></span>
                                <span class="block text-xs text-gray-500 font-mono"><?= $emp['employee_number'] ?></span>
                                <span class="block text-xs text-gray-400"><?= $emp['email'] ?></span>
                            </div>
                        </div>
                    </td>

                    <td class="py-4 px-6">
                        <p class="font-medium text-gray-800"><?= $emp['position'] ?? 'Staff' ?></p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="bg-blue-50 text-blue-600 py-0.5 px-2 rounded text-xs font-semibold uppercase">
                                <?= $emp['employee_level'] ?>
                            </span>
                            <span class="text-gray-400 text-xs">•</span>
                            <span class="text-gray-600 text-xs">
                                <?= $emp['department_name'] ?? '-' ?>
                            </span>
                        </div>
                    </td>

                    <td class="py-4 px-6">
                        <?php if ($emp['employee_status'] == 'active'): ?>
                            <span class="bg-green-100 text-green-700 py-1 px-3 rounded-full text-xs font-bold">Active</span>
                        <?php else: ?>
                            <span class="bg-red-100 text-red-700 py-1 px-3 rounded-full text-xs font-bold"><?= ucfirst($emp['employee_status']) ?></span>
                        <?php endif; ?>
                    </td>

                    <td class="py-4 px-6 text-center">
                        <?php if ($emp['has_account']): ?>
                            <div class="flex flex-col items-center">
                                <span class="text-green-500 text-lg"><i class="fas fa-check-circle"></i></span>
                                <span class="text-xs text-gray-500">Terdaftar</span>
                            </div>
                        <?php else: ?>
                            <div class="flex flex-col items-center">
                                <span class="text-gray-300 text-lg"><i class="fas fa-times-circle"></i></span>
                                <span class="text-xs text-gray-400">Belum Sync</span>
                            </div>
                        <?php endif; ?>
                    </td>

                    <td class="py-4 px-6 text-center">
                        <div class="flex item-center justify-center gap-2">
                            <a href="<?= BASEURL ?>/admin/employees/edit/<?= $emp['id'] ?>" class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center hover:bg-yellow-200 transition" title="Edit">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                            <a href="<?= BASEURL ?>/admin/employees/delete/<?= $emp['id'] ?>" 
                               onclick="return confirm('Hapus data karyawan ini?')" 
                               class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-200 transition" title="Hapus">
                                <i class="fas fa-trash text-xs"></i>
                            </a>
                        </div>
                    </td>

                </tr>
                <?php endforeach; ?>
                
                <?php if (empty($employees)): ?>
                <tr>
                    <td colspan="5" class="py-12 text-center text-gray-400">
                        <i class="fas fa-folder-open text-5xl mb-4 block text-gray-300"></i>
                        <p>Belum ada data karyawan.</p>
                        <p class="text-sm">Klik tombol "Karyawan Baru" untuk memulai.</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>