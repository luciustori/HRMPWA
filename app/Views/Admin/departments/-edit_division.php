<?php
// app/Views/admin/departments/edit_division.php
?>

<div class="px-4 pt-4 pb-8 mx-auto max-w-3xl lg:px-6">
    <!-- Header & Breadcrumb -->
    <div class="mb-6">
        <div class="flex items-center text-xs text-gray-500">
            <a href="<?= BASEURL ?>/admin/departments" class="hover:text-gray-700">Departemen</a>
            <span class="mx-1">/</span>
            <a href="<?= BASEURL ?>/admin/departments/divisions/<?= (int)($division['department_id'] ?? 0) ?>"
               class="hover:text-gray-700">Divisi</a>
            <span class="mx-1">/</span>
            <span class="text-gray-700">Edit Divisi</span>
        </div>
        <h1 class="mt-2 text-2xl font-bold text-gray-900">
            Edit Divisi
        </h1>
        <p class="mt-1 text-sm text-gray-500">
            Ubah informasi divisi untuk departemen
            <span class="font-semibold">
                <?= htmlspecialchars($division['department_name'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
            </span>
        </p>
    </div>

    <!-- Card -->
    <div class="p-6 bg-white border border-gray-100 rounded-xl shadow-sm">
        <form action="<?= BASEURL ?>/admin/departments/update_division/<?= (int)($division['id'] ?? 0) ?>"
              method="POST" class="space-y-6">
            <!-- Hidden department id -->
            <input type="hidden" name="department_id" value="<?= (int)($division['department_id'] ?? 0) ?>">

            <!-- Nama Divisi -->
            <div>
                <label for="division_name" class="block text-sm font-medium text-gray-700">
                    Nama Divisi
                </label>
                <input
                    type="text"
                    id="division_name"
                    name="division_name"
                    required
                    value="<?= htmlspecialchars($division['division_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    class="block w-full mt-1 text-sm border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Contoh: Software Development"
                >
            </div>

            <!-- Kode Divisi -->
            <div>
                <label for="division_code" class="block text-sm font-medium text-gray-700">
                    Kode Divisi
                </label>
                <input
                    type="text"
                    id="division_code"
                    name="division_code"
                    value="<?= htmlspecialchars($division['division_code'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    class="block w-full mt-1 text-sm uppercase border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Contoh: IT-DEV"
                >
                <p class="mt-1 text-xs text-gray-500">
                    Kode singkat untuk divisi, digunakan di laporan dan referensi internal.
                </p>
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
            <option value="<?= $emp['id'] ?>" <?= ($data['division']['coordinator_id'] == $emp['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?> (<?= $emp['employee_number'] ?>)
            </option>
        <?php endforeach; ?>
    </select>
</div>
            <!-- Deskripsi -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">
                    Deskripsi
                </label>
                <textarea
                    id="description"
                    name="description"
                    rows="3"
                    class="block w-full mt-1 text-sm border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Jelaskan fungsi utama divisi ini"
                ><?= htmlspecialchars($division['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Status
                </label>
                <?php
                $isActive = (isset($division['is_active']) ? (int)$division['is_active'] : 1);
                ?>
                <div class="flex items-center gap-4">
                    <label class="inline-flex items-center">
                        <input
                            type="radio"
                            name="is_active"
                            value="1"
                            class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                            <?= $isActive === 1 ? 'checked' : '' ?>
                        >
                        <span class="ml-2 text-sm text-gray-700">Aktif</span>
                    </label>

                    <label class="inline-flex items-center">
                        <input
                            type="radio"
                            name="is_active"
                            value="0"
                            class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                            <?= $isActive === 0 ? 'checked' : '' ?>
                        >
                        <span class="ml-2 text-sm text-gray-700">Nonaktif</span>
                    </label>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="flex items-center justify-between pt-4 mt-4 border-t border-gray-100">
                <a href="<?= BASEURL ?>/admin/departments/divisions/<?= (int)($division['department_id'] ?? 0) ?>"
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
                    Batal
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
