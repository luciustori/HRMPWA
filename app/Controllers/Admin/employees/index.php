<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Direktori Pegawai</h1>
        <p class="text-sm text-gray-500">Kelola data personalia dan akses sistem.</p>
    </div>
    <div class="flex gap-3">
        <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition shadow-sm">
            <i class="fas fa-file-export mr-2"></i> Export
        </button>
        <a href="<?= BASEURL ?>/admin/employees/create" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md transition flex items-center">
            <i class="fas fa-plus mr-2"></i> Pegawai Baru
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-indigo-500">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase">Total Pegawai</p>
                <h3 class="text-2xl font-bold text-gray-800"><?= $stats['total'] ?? 0 ?></h3>
            </div>
            <div class="p-3 bg-indigo-50 rounded-full text-indigo-600">
                <i class="fas fa-users text-xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-emerald-500">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase">Status Aktif</p>
                <h3 class="text-2xl font-bold text-gray-800"><?= $stats['active'] ?? 0 ?></h3>
            </div>
            <div class="p-3 bg-emerald-50 rounded-full text-emerald-600">
                <i class="fas fa-user-check text-xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-gray-400">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase">Non-Aktif / Cuti</p>
                <h3 class="text-2xl font-bold text-gray-800"><?= $stats['inactive'] ?? 0 ?></h3>
            </div>
            <div class="p-3 bg-gray-100 rounded-full text-gray-500">
                <i class="fas fa-user-slash text-xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-10">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 font-bold">Profil</th>
                    <th class="px-6 py-4 font-bold">Jabatan & Divisi</th>
                    <th class="px-6 py-4 font-bold">Status Akun</th>
                    <th class="px-6 py-4 font-bold w-1/5">Performa & Gaji</th>
                    <th class="px-6 py-4 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (!empty($employees)): ?>
                    <?php foreach ($employees as $emp): ?>
                    <tr class="hover:bg-indigo-50/30 transition group">
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <img class="h-10 w-10 rounded-full object-cover border border-gray-200" 
                                         src="https://ui-avatars.com/api/?name=<?= urlencode($emp['first_name']) ?>&background=random&color=fff" alt="">
                                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full border-2 border-white <?= ($emp['is_active']) ? 'bg-green-500' : 'bg-gray-400' ?>"></span>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900"><?= $emp['first_name'] . ' ' . ($emp['last_name'] ?? '') ?></div>
                                    <div class="text-xs text-gray-500 font-mono"><?= $emp['employee_number'] ?></div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800"><?= $emp['position'] ?></div>
                            <div class="text-xs text-gray-500">
                                <?= $emp['department_name'] ?? '-' ?>
                            </div>
                            <span class="inline-block mt-1 px-2 py-0.5 text-[10px] rounded font-semibold bg-gray-100 text-gray-600">
                                <?= $emp['is_active'] ? 'Active' : 'Non-Active' ?>
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <?php if ($emp['has_account']): ?>
                                <div class="flex items-center text-green-600 text-xs font-bold bg-green-50 px-3 py-1.5 rounded-lg w-fit">
                                    <i class="fas fa-check-circle mr-2"></i> Terdaftar
                                </div>
                                <div class="text-[10px] text-gray-400 mt-1 pl-1">User: <?= $emp['account_username'] ?></div>
                            <?php else: ?>
                                <div class="flex flex-col items-start gap-1">
                                    <span class="text-xs text-gray-400 italic">Belum Sync</span>
                                    <button class="text-[10px] bg-indigo-50 text-indigo-600 px-2 py-1 rounded hover:bg-indigo-100 transition">
                                        + Buat Akun
                                    </button>
                                </div>
                            <?php endif; ?>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs text-gray-500">Gaji:</span>
                                <span class="font-bold text-gray-700 text-xs">Rp <?= number_format($emp['salary'], 0, ',', '.') ?></span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5 mt-2">
                                <div class="<?= $emp['score_color'] ?> h-1.5 rounded-full" style="width: <?= $emp['score'] ?>%"></div>
                            </div>
                            <div class="flex justify-between mt-1">
                                <span class="text-[10px] <?= $emp['text_color'] ?>">Grade <?= $emp['grade'] ?></span>
                                <span class="text-[10px] text-gray-400"><?= $emp['score'] ?>/100</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button class="w-8 h-8 rounded-full bg-yellow-50 text-yellow-600 hover:bg-yellow-100 flex items-center justify-center transition" title="Edit">
                                    <i class="fas fa-pen text-xs"></i>
                                </button>
                                <button class="w-8 h-8 rounded-full bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition" title="Hapus">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="p-8 text-center text-gray-500">Data Kosong</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-8 mb-12">
    <div class="text-center mb-6">
        <h2 class="text-lg font-bold text-gray-800">🏆 Top Performers (KPI)</h2>
    </div>
    <div class="flex flex-col md:flex-row justify-center items-end gap-4 px-4">
        <?php if(isset($top_performers[1])): $p2 = $top_performers[1]; ?>
        <div class="bg-white p-4 rounded-xl shadow-sm border w-full md:w-48 flex flex-col items-center relative order-2 md:order-1">
            <div class="absolute -top-3 w-6 h-6 bg-gray-300 text-white text-xs font-bold rounded-full flex items-center justify-center border-2 border-white">2</div>
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($p2['first_name']) ?>&background=random" class="w-12 h-12 rounded-full mb-2">
            <h3 class="font-bold text-gray-800 text-xs text-center"><?= $p2['first_name'] ?></h3>
            <span class="text-xs font-bold text-indigo-600"><?= $p2['score'] ?></span>
        </div>
        <?php endif; ?>

        <?php if(isset($top_performers[0])): $p1 = $top_performers[0]; ?>
        <div class="bg-indigo-50 p-6 rounded-xl shadow-md border-2 border-indigo-100 w-full md:w-56 flex flex-col items-center relative order-1 md:order-2 z-10 -mb-2">
            <div class="absolute -top-4 text-2xl text-yellow-400"><i class="fas fa-crown"></i></div>
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($p1['first_name']) ?>&background=random" class="w-16 h-16 rounded-full mb-3 border-2 border-indigo-200">
            <h3 class="font-bold text-gray-900 text-sm text-center"><?= $p1['first_name'] ?></h3>
            <span class="text-sm font-bold text-indigo-700"><?= $p1['score'] ?> Poin</span>
        </div>
        <?php endif; ?>

        <?php if(isset($top_performers[2])): $p3 = $top_performers[2]; ?>
        <div class="bg-white p-4 rounded-xl shadow-sm border w-full md:w-48 flex flex-col items-center relative order-3">
            <div class="absolute -top-3 w-6 h-6 bg-orange-300 text-white text-xs font-bold rounded-full flex items-center justify-center border-2 border-white">3</div>
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($p3['first_name']) ?>&background=random" class="w-12 h-12 rounded-full mb-2">
            <h3 class="font-bold text-gray-800 text-xs text-center"><?= $p3['first_name'] ?></h3>
            <span class="text-xs font-bold text-indigo-600"><?= $p3['score'] ?></span>
        </div>
        <?php endif; ?>
    </div>
</div>