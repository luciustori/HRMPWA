<div class="max-w-6xl mx-auto p-6">
    
    <div class="mb-6 flex items-center gap-4">
        <a href="<?= BASEURL ?>/admin/salary_grade" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left text-gray-600"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Setting Gaji: <?= $grade['grade_name'] ?> (<?= $grade['grade_code'] ?>)</h1>
            <p class="text-sm text-gray-500">Semua karyawan di golongan ini akan otomatis mendapatkan komponen di bawah ini.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">Komponen Standar Golongan <?= $grade['grade_code'] ?></h3>
                </div>
                
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left">Nama Komponen</th>
                            <th class="px-6 py-3 text-left">Tipe</th>
                            <th class="px-6 py-3 text-right">Nominal (Rp)</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if(empty($current)): ?>
                            <tr><td colspan="4" class="p-8 text-center text-gray-400 italic">Belum ada komponen gaji diset untuk golongan ini.</td></tr>
                        <?php else: ?>
                            <?php foreach($current as $row): ?>
                            <tr class="hover:bg-gray-50 group">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800"><?= $row['component_name'] ?></div>
                                    <?php if($row['component_code'] == 'BASIC'): ?>
                                        <span class="text-[10px] bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded-full font-bold">GAJI POKOK</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-bold <?= $row['component_type']=='earning' ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50' ?> px-2 py-1 rounded">
                                        <?= ucfirst($row['component_type']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="<?= BASEURL ?>/admin/salary_grade/update_component" method="POST">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="grade_id" value="<?= $grade['id'] ?>">
                                        <input type="text" name="amount" value="<?= number_format($row['amount'], 0, ',', '.') ?>" 
                                               class="w-32 text-right font-mono font-bold border-b border-transparent hover:border-gray-300 focus:border-indigo-500 focus:outline-none bg-transparent transition py-1 cursor-pointer"
                                               onchange="this.value = this.value.replace(/\./g, ''); this.form.submit()">
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php if($row['component_code'] != 'BASIC'): ?>
                                        <a href="<?= BASEURL ?>/admin/salary_grade/delete_component/<?= $row['id'] ?>/<?= $grade['id'] ?>" 
                                           onclick="return confirm('Hapus komponen ini dari standar golongan?')"
                                           class="text-gray-300 hover:text-red-500 transition opacity-0 group-hover:opacity-100">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php else: ?>
                                        <i class="fas fa-lock text-gray-200"></i>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg border border-indigo-100 p-6 sticky top-6">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-plus-circle text-indigo-600"></i> Tambah Komponen
                </h3>
                
                <form action="<?= BASEURL ?>/admin/salary_grade/add_component" method="POST">
                    <input type="hidden" name="grade_id" value="<?= $grade['id'] ?>">
                    
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
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nominal Standar (Rp)</label>
                        <input type="number" name="amount" placeholder="0" 
                               class="w-full border border-gray-300 rounded-lg p-2.5 text-sm font-mono font-bold focus:ring-2 focus:ring-indigo-500 outline-none" required>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg shadow-lg transform active:scale-95 transition flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Simpan ke Golongan
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>