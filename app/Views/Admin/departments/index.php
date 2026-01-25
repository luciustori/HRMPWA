<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Data Departemen</h1>
        <p class="text-sm text-gray-500">Atur struktur organisasi perusahaan Anda.</p>
    </div>
    <a href="<?= BASEURL ?>/admin/departments/create" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md transition flex items-center">
        <i class="fas fa-plus mr-2"></i> Tambah Divisi
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 font-bold text-gray-700">Kode</th>
                    <th class="px-6 py-4 font-bold text-gray-700">Nama Departemen</th>
                    <th class="px-6 py-4 font-bold text-gray-700">Kepala Divisi (Manager)</th>
                    <th class="px-6 py-4 font-bold text-gray-700 text-center">Jumlah Personil</th>
                    <th class="px-6 py-4 font-bold text-gray-700 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (!empty($departments)): ?>
                    <?php foreach ($departments as $dept): ?>
                    <tr class="hover:bg-indigo-50/30 transition group">
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-mono font-bold border border-gray-200">
                                <?= $dept['department_code'] ?>
                            </span>
                        </td>

                        <td class="px-6 py-4 font-medium text-gray-800">
                            <?= $dept['department_name'] ?>
                        </td>

                        <td class="px-6 py-4">
                            <?php if ($dept['manager_name']): ?>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">
                                        <?= substr($dept['manager_name'], 0, 1) ?>
                                    </div>
                                    <span class="text-gray-700 text-xs font-semibold"><?= $dept['manager_name'] ?></span>
                                </div>
                            <?php else: ?>
                                <span class="text-gray-400 text-xs italic">- Belum ditentukan -</span>
                            <?php endif; ?>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-bold">
                                <?= $dept['total_members'] ?> Orang
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="<?= BASEURL ?>/admin/departments/edit/<?= $dept['id'] ?>" class="w-8 h-8 rounded-full bg-yellow-50 text-yellow-600 hover:bg-yellow-100 flex items-center justify-center transition" title="Edit">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                                <button onclick="if(confirm('Hapus departemen ini?')) window.location.href='<?= BASEURL ?>/admin/departments/delete/<?= $dept['id'] ?>'" class="w-8 h-8 rounded-full bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition" title="Hapus">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="p-8 text-center text-gray-500">Belum ada departemen. Silakan tambah baru.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>