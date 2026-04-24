<!-- Breadcrumb -->
<div class="mb-6">
    <nav class="flex items-center text-sm text-gray-600 mb-3">
        <a href="<?= BASEURL ?>/admin/departments" class="hover:text-blue-600">Departemen</a>
        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="<?= BASEURL ?>/admin/departments/divisions/<?= $data['department']['id'] ?>" class="hover:text-blue-600">
            <?= htmlspecialchars($data['department']['department_name']) ?>
        </a>
        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-900 font-medium">Tambah Divisi</span>
    </nav>
    <h1 class="text-2xl font-bold text-gray-800">Tambah Divisi Baru</h1>
    <p class="text-sm text-gray-600 mt-1">Tambahkan divisi/sub bagian ke departemen <?= htmlspecialchars($data['department']['department_name']) ?></p>
</div>

<!-- Alert Error -->
<?php if (isset($_GET['error'])): ?>
<div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
    Gagal menambahkan divisi! Silakan coba lagi.
</div>
<?php endif; ?>

<!-- Form Card -->
<div class="bg-white rounded-lg shadow-md">
    <form action="<?= BASEURL ?>/admin/departments/store_division" method="POST" class="p-6">
        <input type="hidden" name="department_id" value="<?= $data['department']['id'] ?>">
        
        <!-- Departemen Info -->
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <div>
                    <p class="text-sm text-gray-600">Departemen</p>
                    <p class="font-semibold text-gray-900"><?= htmlspecialchars($data['department']['department_name']) ?></p>
                </div>
            </div>
        </div>

        <!-- Nama Divisi -->
        <div class="mb-5">
            <label for="division_name" class="block text-sm font-medium text-gray-700 mb-2">
                Nama Divisi <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   id="division_name" 
                   name="division_name" 
                   required
                   placeholder="Contoh: Software Development"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            <p class="mt-1 text-sm text-gray-500">Nama lengkap divisi/sub bagian</p>
        </div>

        <!-- Kode Divisi -->
        <div class="mb-5">
            <label for="division_code" class="block text-sm font-medium text-gray-700 mb-2">
                Kode Divisi <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   id="division_code" 
                   name="division_code" 
                   required
                   placeholder="Contoh: IT-DEV"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            <p class="mt-1 text-sm text-gray-500">Kode singkat untuk identifikasi (format: DEPT-DIV)</p>
        </div>

        <div class="mb-5">
    <label for="coordinator_id" class="block text-sm font-medium text-gray-700 mb-2">
        Koordinator Divisi <span class="text-gray-400">(Opsional)</span>
    </label>
    <select id="coordinator_id" 
            name="coordinator_id" 
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
        <option value="">-- Pilih Koordinator --</option>
        <?php foreach ($data['employees'] as $emp): ?>
            <option value="<?= $emp['id'] ?>">
                <?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?> (<?= $emp['employee_number'] ?>)
            </option>
        <?php endforeach; ?>
    </select>
    <p class="text-xs text-gray-500 mt-1">Person-in-charge (PIC) untuk divisi ini.</p>
</div>

        <!-- Deskripsi -->
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                Deskripsi
            </label>
            <textarea id="description" 
                      name="description" 
                      rows="3"
                      placeholder="Deskripsi singkat tentang divisi ini (opsional)"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"></textarea>
        </div>

        <!-- Buttons -->
        <div class="flex items-center justify-end space-x-3 pt-4 border-t">
            <a href="<?= BASEURL ?>/admin/departments/divisions/<?= $data['department']['id'] ?>" 
               class="px-5 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit" 
                    class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition">
                <span class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Divisi
                </span>
            </button>
        </div>
    </form>
</div>
