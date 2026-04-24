<div class="mb-6">
    <nav class="flex items-center text-sm text-gray-600 mb-3">
        <a href="<?= BASEURL ?>/admin/departments" class="hover:text-blue-600">Departemen</a>
        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-900 font-medium">Tambah Departemen</span>
    </nav>
    <h1 class="text-2xl font-bold text-gray-800">Tambah Departemen Baru</h1>
    <p class="text-sm text-gray-600 mt-1">Buat departemen baru dalam struktur organisasi</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6">
        <form action="<?= BASEURL ?>/admin/departments/store" method="POST">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="department_code" class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Departemen <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="department_code" 
                           name="department_code" 
                           required
                           placeholder="Cth: IT, HR, FIN"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent uppercase">
                    <p class="text-xs text-gray-500 mt-1">Maksimal 10 karakter, unik.</p>
                </div>

                <div>
                    <label for="department_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Departemen <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="department_name" 
                           name="department_name" 
                           required
                           placeholder="Cth: Information Technology"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <div class="mb-6">
                <label for="manager_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Kepala Departemen / Manager <span class="text-gray-400">(Opsional)</span>
                </label>
                <select id="manager_id" 
                        name="manager_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">-- Pilih Manager --</option>
                    <?php 
                    // Pastikan Controller mengirim $data['employees']
                    if(isset($data['employees'])): 
                        foreach ($data['employees'] as $emp): 
                    ?>
                        <option value="<?= $emp['id'] ?>">
                            <?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?> (<?= $emp['employee_number'] ?>)
                        </option>
                    <?php 
                        endforeach; 
                    endif; 
                    ?>
                </select>
                <p class="text-xs text-gray-500 mt-1">Anda bisa menambahkannya nanti jika belum ada karyawan.</p>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-100">
                <a href="<?= BASEURL ?>/admin/departments" 
                   class="px-5 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition shadow-sm hover:shadow">
                    Simpan Departemen
                </button>
            </div>

        </form>
    </div>
</div>