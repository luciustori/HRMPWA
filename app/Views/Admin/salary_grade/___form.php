<div class="max-w-xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2"><?= $grade ? 'Edit Grade' : 'Tambah Grade Baru' ?></h2>
        
        <form action="<?= BASEURL ?>/admin/salary_grade/store" method="POST">
            <?php if($grade): ?>
                <input type="hidden" name="id" value="<?= $grade['id'] ?>">
            <?php endif; ?>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kode Grade</label>
                    <input type="text" name="grade_code" value="<?= $grade['grade_code'] ?? '' ?>" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500" placeholder="I, II, MGR" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Grade</label>
                    <input type="text" name="grade_name" value="<?= $grade['grade_name'] ?? '' ?>" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500" placeholder="Staff, Manager" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">Gaji Pokok (Base Salary)</label>
                <div class="relative">
                    <span class="absolute left-3 top-2 text-gray-500 font-bold">Rp</span>
                    <input type="text" name="base_salary" value="<?= number_format($grade['base_salary'] ?? 0, 0, ',', '.') ?>" class="w-full pl-10 border-gray-300 rounded-lg focus:ring-indigo-500 font-mono font-bold text-lg" onkeyup="this.value=this.value.replace(/\./g,'').replace(/\B(?=(\d{3})+(?!\d))/g,'.')" required>
                </div>
                <p class="text-xs text-gray-500 mt-1">Gaji dasar sebelum ditambah komponen lain.</p>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500"><?= $grade['description'] ?? '' ?></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <a href="<?= BASEURL ?>/admin/salary_grade" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-600 font-bold hover:bg-gray-50">Batal</a>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-bold hover:bg-indigo-700 shadow-lg transition">Simpan</button>
            </div>
        </form>
    </div>
</div>