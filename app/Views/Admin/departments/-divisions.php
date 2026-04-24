<?php
// app/Views/admin/departments/divisions.php
?>

<div class="px-4 pt-4 pb-6 mx-auto max-w-7xl lg:px-6">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col items-start justify-between gap-4 mb-6 md:flex-row md:items-center">
        <div>
            <div class="flex items-center text-xs text-gray-500">
                <a href="<?= BASEURL ?>/admin/departments" class="hover:text-gray-700">Departemen</a>
                <span class="mx-1">/</span>
                <span class="text-gray-700">Divisi</span>
            </div>
            <h1 class="mt-1 text-2xl font-bold text-gray-900">
                Divisi - <?= htmlspecialchars($department['department_name'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Kelola divisi/sub bagian dalam departemen ini.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="<?= BASEURL ?>/admin/departments"
               class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>

            <a href="<?= BASEURL ?>/admin/departments/create_division/<?= (int)($department['id'] ?? 0) ?>"
               class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Divisi
            </a>
        </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-3">
        <div class="p-4 bg-white border rounded-xl border-gray-100 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Divisi</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        <?= $stats['total'] ?? 0 ?>
                    </p>
                </div>
                <div class="p-3 bg-purple-50 rounded-lg">
                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 7v10a2 2 0 002 2h3m10-12h1a2 2 0 012 2v10a2 2 0 01-2 2h-6m-4-4h4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="p-4 bg-white border rounded-xl border-gray-100 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Aktif</p>
                    <p class="mt-2 text-2xl font-bold text-emerald-600">
                        <?= $stats['active'] ?? 0 ?>
                    </p>
                </div>
                <div class="p-3 bg-emerald-50 rounded-lg">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="p-4 bg-white border rounded-xl border-gray-100 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Nonaktif</p>
                    <p class="mt-2 text-2xl font-bold text-rose-600">
                        <?= $stats['inactive'] ?? 0 ?>
                    </p>
                </div>
                <div class="p-3 bg-rose-50 rounded-lg">
                    <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Divisi -->
    <div class="overflow-hidden bg-white border rounded-xl border-gray-100 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold tracking-wide text-gray-700 uppercase">
                Daftar Divisi
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                        Divisi
                    </th>
                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                        Kode
                    </th>                    
                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                        Koordinator
                    </th>
                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-center text-gray-500 uppercase">
                        Karyawan
                    </th>
                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-center text-gray-500 uppercase">
                        Status
                    </th>
                    <th class="px-6 py-3 text-xs font-semibold tracking-wider text-right text-gray-500 uppercase">
                        Aksi
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                <?php if (!empty($divisions) && is_array($divisions)) : ?>
                    <?php foreach ($divisions as $div): ?>
                        <tr class="hover:bg-gray-50">
                            <!-- Divisi -->
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div
                                        class="flex items-center justify-center w-10 h-10 mr-3 rounded-lg bg-purple-50 text-purple-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M3 7v10a2 2 0 002 2h3m10-12h1a2 2 0 012 2v10a2 2 0 01-2 2h-6m-4-4h4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">
                                            <?= htmlspecialchars($div['division_name'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                                        </div>
                                        <?php if (!empty($div['description'])): ?>
                                            <div class="text-xs text-gray-500">
                                                <?= htmlspecialchars($div['description'], ENT_QUOTES, 'UTF-8') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>

                            <!-- Kode -->
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <?= htmlspecialchars($div['division_code'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                            </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
            <div class="flex items-center">
                <i class="ri-user-star-line mr-2 text-purple-500"></i>
                <?= $div['coordinator_name'] ?? '<span class="text-gray-400 italic">Belum diset</span>' ?>
            </div>
        </td>
                            <!-- Karyawan -->
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900">
                                        <?= (int)($div['total_employees'] ?? 0) ?>
                                    </span>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 text-center">
                                <?php if (!empty($div['is_active'])): ?>
                                    <span
                                        class="px-3 py-1 text-xs font-semibold text-emerald-800 bg-emerald-100 rounded-full">
                                        Aktif
                                    </span>
                                <?php else: ?>
                                    <span
                                        class="px-3 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded-full">
                                        Nonaktif
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Aksi -->
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="<?= BASEURL ?>/admin/departments/edit_division/<?= (int)$div['id'] ?>"
                                       class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100">
                                        Edit
                                    </a>
                                    <form action="<?= BASEURL ?>/admin/departments/delete_division/<?= (int)$div['id'] ?>"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus divisi ini?')">
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1 text-xs font-medium text-rose-700 bg-rose-50 rounded-lg hover:bg-rose-100">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-6 text-sm text-center text-gray-500">
                            Belum ada divisi yang terdaftar untuk departemen ini.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
            <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
    <tr>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Divisi</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Koordinator</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
    </tr>
</thead>
<tbody class="bg-white divide-y divide-gray-200">
    <?php foreach ($data['divisions'] as $div): ?>
    <tr>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
            <div class="text-xs text-gray-500"><?= $div['division_code'] ?></div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
            <?= htmlspecialchars($div['division_name']) ?>
            <div class="text-xs text-gray-500"><?= $div['division_code'] ?></div>
        </td>        
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
            <?= $div['coordinator_name'] ?? '<span class="text-gray-400 italic">Belum diset</span>' ?>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <?php if ($div['is_active'] == 1): ?>
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
            <?php else: ?>
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Nonaktif</span>
            <?php endif; ?>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <div class="flex justify-end space-x-2">
                <a href="<?= BASEURL ?>/admin/departments/edit_division/<?= $div['id'] ?>" class="text-indigo-600 hover:text-indigo-900">
                    <i class="ri-edit-line text-lg"></i>
                </a>
                <a href="<?= BASEURL ?>/admin/departments/delete_division/<?= $div['id'] ?>" 
                   onclick="return confirm('Hapus divisi ini?')" 
                   class="text-red-600 hover:text-red-900">
                    <i class="ri-delete-bin-line text-lg"></i>
                </a>
            </div>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>
                </table>
        </div>
    </div>
</div>
