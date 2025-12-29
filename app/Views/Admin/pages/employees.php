<!-- app/Views/Admin/pages/employees.php -->
<div class="space-y-4">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold">Kepegawaian</h2>
        <a href="/admin/employees/create" class="px-4 py-2 bg-blue-500 text-white rounded-lg">
            + Tambah Karyawan
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
    <div class="p-4 bg-green-100 text-green-700 rounded-lg">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
    <?php endif; ?>

    <div class="overflow-x-auto bg-gray-800 rounded-lg">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-4 py-3">No Karyawan</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Departemen</th>
                    <th class="px-4 py-3">Level</th>
                    <th class="px-4 py-3">Posisi</th>
                    <th class="px-4 py-3">Gaji Pokok</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                <?php foreach ($employees as $emp): ?>
                <tr class="hover:bg-gray-700">
                    <td class="px-4 py-3"><?= htmlspecialchars($emp['employee_number']) ?></td>
                    <td class="px-4 py-3 font-medium"><?= htmlspecialchars($emp['full_name']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($emp['department_name'] ?? '-') ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($emp['level_name'] ?? '-') ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($emp['position'] ?? '-') ?></td>
                    <td class="px-4 py-3">Rp. <?= number_format($emp['base_salary'] ?? 0, 0, ',', '.') ?></td>
                    <td class="px-4 py-3 flex gap-2">
                        <a href="/admin/employees/<?= $emp['id'] ?>/edit" class="px-3 py-1 bg-yellow-500 text-white rounded text-xs">
                            Edit
                        </a>
                        <button class="px-3 py-1 bg-red-500 text-white rounded text-xs delete-btn" 
                                data-id="<?= $emp['id'] ?>">
                            Hapus
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        if (confirm('Yakin ingin menghapus?')) {
            window.location.href = `/admin/employees/${this.dataset.id}/delete`;
        }
    });
});
</script>
