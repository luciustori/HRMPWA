<div class="max-w-6xl mx-auto">
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Departemen</h1>
            <p class="text-sm text-gray-500">Perbarui data divisi: <strong><?= $department['department_name'] ?></strong></p>
        </div>
        <a href="<?= BASEURL ?>/admin/departments" class="bg-white border text-gray-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 shadow-sm transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <form action="<?= BASEURL ?>/admin/departments/update/<?= $department['id'] ?>" method="POST" class="space-y-6">
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Departemen</label>
                        <input type="text" name="department_name" value="<?= $department['department_name'] ?>" required 
                               class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Kode Singkatan</label>
                            <input type="text" name="department_code" value="<?= $department['department_code'] ?>" required maxlength="5"
                                   class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm uppercase font-mono">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Kepala Divisi (Manager)</label>
                            <select name="manager_id" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                <option value="">-- Pilih Karyawan --</option>
                                <?php foreach($employees as $emp): ?>
                                    <option value="<?= $emp['id'] ?>" <?= $emp['id'] == $department['manager_id'] ? 'selected' : '' ?>>
                                        <?= $emp['first_name'] . ' ' . $emp['last_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="pt-4 border-t flex justify-end gap-3">
                        <button type="button" onclick="history.back()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">Batal</button>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 shadow-lg transition transform hover:-translate-y-0.5">
                            <i class="fas fa-check-circle mr-2"></i> Update Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <div class="lg:col-span-1">
             <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 sticky top-24">
                <h3 class="font-bold text-yellow-800 flex items-center gap-2 mb-4">
                    <i class="fas fa-exclamation-triangle text-xl"></i> Peringatan
                </h3>
                <div class="text-sm text-yellow-900 space-y-3">
                    <p>Mengubah <strong>Kode Departemen</strong> dapat mempengaruhi format nomor surat yang sudah terbit.</p>
                    <p>Jika <strong>Manager</strong> diubah, approval cuti untuk karyawan di departemen ini akan dialihkan ke manager baru.</p>
                </div>
                <div class="mt-6 pt-4 border-t border-yellow-200 text-xs text-yellow-700">
                    Terakhir diupdate: <?= date('d M Y', strtotime($department['updated_at'])) ?>
                </div>
             </div>
        </div>

    </div>
</div>