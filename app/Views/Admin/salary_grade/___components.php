<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="<?= BASEURL ?>/admin/salary_grade" class="text-gray-400 hover:text-gray-600"><i class="ri-arrow-left-line"></i> Kembali</a>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Atur Komponen Gaji</h1>
            <p class="text-sm text-gray-500">Grade: <span class="font-bold text-indigo-600"><?= $grade['grade_name'] ?> (<?= $grade['grade_code'] ?>)</span></p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="md:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <h3 class="font-bold text-gray-800 mb-4 pb-2 border-b">Tambah Komponen</h3>
                <form action="<?= BASEURL ?>/admin/salary_grade/store_component" method="POST">
                    <input type="hidden" name="grade_id" value="<?= $grade['id'] ?>">
                    
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 mb-2">Pilih Komponen</label>
                        <select name="component_id" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500" required>
                            <option value="">-- Pilih --</option>
                            <?php foreach($available as $av): ?>
                                <option value="<?= $av['id'] ?>"><?= $av['component_name'] ?> (<?= ucfirst($av['component_type']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(empty($available)): ?>
                            <p class="text-[10px] text-red-500 mt-1">* Semua komponen sudah ditambahkan</p>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 mb-2">Nominal (Rp)</label>
                        <input type="text" name="amount" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500" placeholder="0" onkeyup="this.value=this.value.replace(/\./g,'').replace(/\B(?=(\d{3})+(?!\d))/g,'.')" required>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-2 rounded-lg hover:bg-indigo-700 transition shadow-lg">Simpan</button>
                </form>
            </div>
        </div>

        <div class="md:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 font-bold uppercase text-xs border-b">
                        <tr>
                            <th class="px-6 py-3">Komponen</th>
                            <th class="px-6 py-3 text-right">Nominal Standar</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr class="bg-indigo-50/50">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">Gaji Pokok</div>
                                <div class="text-xs text-indigo-500 font-mono">BASIC_SALARY</div>
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-gray-700">
                                Rp <?= number_format($grade['base_salary'], 0, ',', '.') ?>
                            </td>
                            <td class="px-6 py-4 text-center text-xs text-gray-400 italic">
                                (Edit di menu Edit Grade)
                            </td>
                        </tr>

                        <?php foreach($existing as $ex): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800"><?= $ex['component_name'] ?></div>
                                <?php if($ex['component_type'] == 'earning'): ?>
                                    <span class="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded font-bold">EARNING</span>
                                <?php else: ?>
                                    <span class="text-[10px] bg-red-100 text-red-700 px-2 py-0.5 rounded font-bold">DEDUCTION</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-gray-600">
                                Rp <?= number_format($ex['amount'], 0, ',', '.') ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="<?= BASEURL ?>/admin/salary_grade/delete_component/<?= $ex['id'] ?>/<?= $grade['id'] ?>" onclick="return confirm('Hapus komponen ini dari grade?')" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-full transition" title="Hapus">
                                    <i class="ri-delete-bin-line text-lg"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($existing)): ?>
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-400 italic">
                                Belum ada komponen tambahan.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>