<div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Penempatan Golongan Karyawan</h1>
            <p class="text-sm text-gray-500">Tentukan golongan gaji untuk setiap karyawan agar perhitungan payroll otomatis.</p>
        </div>
        <a href="<?= BASEURL ?>/admin/salary_grade" class="text-indigo-600 font-bold hover:underline text-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke Master
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-700 font-bold border-b border-gray-200">
                <tr>
                    <th class="p-4">Karyawan</th>
                    <th class="p-4">Departemen</th>
                    <th class="p-4">Golongan Saat Ini</th>
                    <th class="p-4">Ubah Golongan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach($employees as $emp): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4">
                        <div class="font-bold text-gray-800"><?= $emp['first_name'] . ' ' . $emp['last_name'] ?></div>
                        <div class="text-xs text-gray-500 font-mono"><?= $emp['employee_number'] ?></div>
                    </td>
                    <td class="p-4 text-gray-600">
                        <?= $emp['department_name'] ?? '-' ?>
                    </td>
                    <td class="p-4">
                        <?php if($emp['grade_code']): ?>
                            <span class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded font-bold text-xs border border-indigo-200">
                                <?= $emp['grade_code'] ?> - <?= $emp['grade_name'] ?>
                            </span>
                        <?php else: ?>
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded font-bold text-xs border border-red-200">
                                Belum Diset
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="p-4">
                        <form action="<?= BASEURL ?>/admin/salary_grade/update_employee_grade" method="POST" class="flex gap-2">
                            <input type="hidden" name="employee_id" value="<?= $emp['id'] ?>">
                            <select name="salary_grade_id" class="border border-gray-300 rounded px-2 py-1 text-xs focus:ring-indigo-500 focus:border-indigo-500 outline-none" onchange="this.form.submit()">
                                <option value="">-- Pilih --</option>
                                <?php foreach($grades as $g): ?>
                                    <option value="<?= $g['id'] ?>" <?= ($emp['grade_code'] == $g['grade_code']) ? 'selected' : '' ?>>
                                        <?= $g['grade_code'] ?> - <?= $g['grade_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>