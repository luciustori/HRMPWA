<!-- app/Views/Admin/pages/employee-form.php -->
<div class="max-w-2xl">
    <h2 class="text-2xl font-bold mb-6">
        <?= !empty($employee) ? 'Edit Karyawan' : 'Tambah Karyawan' ?>
    </h2>

    <form method="POST" action="/admin/employees/store" class="space-y-4">
        <?php if (!empty($employee)): ?>
        <input type="hidden" name="id" value="<?= $employee['id'] ?>">
        <?php endif; ?>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">No Karyawan</label>
                <input type="text" name="employee_number" class="w-full px-3 py-2 bg-gray-700 rounded border border-gray-600"
                       value="<?= $employee['employee_number'] ?? '' ?>" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
                <input type="text" name="full_name" class="w-full px-3 py-2 bg-gray-700 rounded border border-gray-600"
                       value="<?= $employee['full_name'] ?? '' ?>" required>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" class="w-full px-3 py-2 bg-gray-700 rounded border border-gray-600"
                       value="<?= $employee['email'] ?? '' ?>" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Telepon</label>
                <input type="tel" name="phone" class="w-full px-3 py-2 bg-gray-700 rounded border border-gray-600"
                       value="<?= $employee['phone'] ?? '' ?>">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Departemen</label>
                <select name="department_id" class="w-full px-3 py-2 bg-gray-700 rounded border border-gray-600">
                    <option value="">Pilih Departemen</option>
                    <?php foreach ($departments as $dept): ?>
                    <option value="<?= $dept['id'] ?>" 
                            <?= ($employee['department_id'] ?? '') == $dept['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($dept['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Level</label>
                <select name="level_id" class="w-full px-3 py-2 bg-gray-700 rounded border border-gray-600">
                    <option value="">Pilih Level</option>
                    <?php foreach ($levels as $lvl): ?>
                    <option value="<?= $lvl['id'] ?>" 
                            <?= ($employee['level_id'] ?? '') == $lvl['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($lvl['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Posisi</label>
                <input type="text" name="position" class="w-full px-3 py-2 bg-gray-700 rounded border border-gray-600"
                       value="<?= $employee['position'] ?? '' ?>">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Gaji Pokok</label>
                <input type="number" name="base_salary" class="w-full px-3 py-2 bg-gray-700 rounded border border-gray-600"
                       value="<?= $employee['base_salary'] ?? 0 ?>" step="1000">
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg">Simpan</button>
            <a href="/admin/employees" class="px-6 py-2 bg-gray-600 text-white rounded-lg">Batal</a>
        </div>
    </form>
</div>
