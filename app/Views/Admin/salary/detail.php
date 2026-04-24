<div class="max-w-6xl mx-auto p-6">
    
    <div class="mb-6 flex items-center gap-4">
        <a href="<?= BASEURL ?>/admin/salary" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left text-gray-600"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900"><?= $employee['first_name'] . ' ' . $employee['last_name'] ?></h1>
            <p class="text-sm text-gray-500 font-mono"><?= $employee['employee_number'] ?> &bull; <?= $employee['department_name'] ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">Komponen Gaji Aktif</h3>
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded font-bold">Effective: Now</span>
                </div>
                
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left">Nama Komponen</th>
                            <th class="px-6 py-3 text-left">Tipe</th>
                            <th class="px-6 py-3 text-right">Nominal (Rp)</th>
                            <th class="px-6 py-3 text-center">Hapus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if(empty($current)): ?>
                            <tr><td colspan="4" class="p-6 text-center text-gray-400">Belum ada komponen gaji. Silakan tambah di kanan.</td></tr>
                        <?php else: ?>
                            <?php foreach($current as $row): ?>
                            <tr class="hover:bg-gray-50 group">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800"><?= $row['component_name'] ?></div>
                                    <div class="text-xs text-gray-400 font-mono"><?= $row['component_code'] ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if($row['component_type'] == 'earning'): ?>
                                        <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded">Pendapatan</span>
                                    <?php else: ?>
                                        <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-1 rounded">Potongan</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="<?= BASEURL ?>/admin/salary/update_component" method="POST">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="employee_id" value="<?= $employee['id'] ?>">
                                        <input type="text" name="amount" value="<?= number_format($row['amount'], 0, ',', '.') ?>" 
                                               class="w-32 text-right font-mono font-bold border-b border-transparent hover:border-gray-300 focus:border-indigo-500 focus:outline-none bg-transparent transition py-1"
                                               onchange="this.value = this.value.replace(/\./g, ''); this.form.submit()">
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php if($row['component_code'] == 'BASIC'): ?>
                                        <i class="fas fa-lock text-gray-300 cursor-not-allowed" title="Gaji Pokok Wajib Ada"></i>
                                    <?php else: ?>
                                        <a href="<?= BASEURL ?>/admin/salary/delete_component/<?= $row['id'] ?>/<?= $employee['id'] ?>" 
                                           onclick="return confirm('Hapus komponen ini?')"
                                           class="text-gray-300 hover:text-red-500 transition opacity-0 group-hover:opacity-100">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <div class="bg-yellow-50 p-4 text-xs text-yellow-800 border-t border-yellow-100">
                    <i class="fas fa-info-circle mr-1"></i> <strong>Tips:</strong> Klik angka nominal untuk mengedit, lalu tekan Enter atau klik sembarang tempat untuk menyimpan.
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg border border-indigo-100 p-6 sticky top-6">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                        <i class="fas fa-plus"></i>
                    </div>
                    Tambah Komponen
                </h3>
                
                <form action="<?= BASEURL ?>/admin/salary/add_component" method="POST">
                    <input type="hidden" name="employee_id" value="<?= $employee['id'] ?>">
                    
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pilih Komponen</label>
                        <select name="component_id" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none" required>
                            <option value="">-- Pilih --</option>
                            <?php foreach($available as $av): ?>
                                <option value="<?= $av['id'] ?>">
                                    <?= $av['component_name'] ?> (<?= ucfirst($av['component_type']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="text-[10px] text-gray-400 mt-1">Hanya menampilkan komponen yang belum dimiliki.</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nominal (Rp)</label>
                        <input type="number" name="amount" placeholder="0" 
                               class="w-full border border-gray-300 rounded-lg p-2.5 text-sm font-mono font-bold focus:ring-2 focus:ring-indigo-500 outline-none" required>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg shadow-lg transform active:scale-95 transition flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>