<div class="max-w-6xl mx-auto">
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tambah Departemen</h1>
            <p class="text-sm text-gray-500">Buat divisi atau unit kerja baru.</p>
        </div>
        <a href="<?= BASEURL ?>/admin/departments" class="bg-white border text-gray-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 shadow-sm transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <form action="<?= BASEURL ?>/admin/departments/store" method="POST" class="space-y-6">
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Departemen</label>
                        <input type="text" name="department_name" placeholder="Contoh: Marketing, Finance, IT Dev" required 
                               class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Kode Singkatan</label>
                            <input type="text" name="department_code" placeholder="MKT / IT / FIN" required maxlength="5"
                                   class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm uppercase font-mono">
                            <p class="text-xs text-gray-500 mt-1">Maksimal 5 karakter (A-Z).</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Kepala Divisi (Manager)</label>
                            <select name="manager_id" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                <option value="">-- Pilih Karyawan --</option>
                                <?php foreach($employees as $emp): ?>
                                    <option value="<?= $emp['id'] ?>">
                                        <?= $emp['first_name'] . ' ' . $emp['last_name'] ?> 
                                        <?= !empty($emp['position']) ? '('.$emp['position'].')' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="pt-4 border-t flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 shadow-lg transition transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2"></i> Simpan Departemen
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <div class="lg:col-span-1">
             <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-6 sticky top-24">
                <h3 class="font-bold text-indigo-800 flex items-center gap-2 mb-4">
                    <i class="fas fa-info-circle text-xl"></i> Informasi
                </h3>
                <ul class="text-sm text-indigo-900 space-y-3">
                    <li class="flex gap-2">
                        <i class="fas fa-check mt-1 text-indigo-500"></i>
                        <span><strong>Kode Singkatan</strong> digunakan untuk penomoran surat atau NIK otomatis (misal: IT-2024).</span>
                    </li>
                    <li class="flex gap-2">
                        <i class="fas fa-check mt-1 text-indigo-500"></i>
                        <span><strong>Manager</strong> akan memiliki akses untuk menyetujui Cuti/Izin anggota departemennya.</span>
                    </li>
                </ul>
                <div class="mt-6 p-3 bg-white rounded border border-indigo-100 text-xs text-gray-500">
                    Pastikan nama departemen unik agar tidak membingungkan saat rekapitulasi gaji.
                </div>
             </div>
        </div>

    </div>
</div>