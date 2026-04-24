<div class="max-w-[1600px] mx-auto p-6">
    
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Tugas</h1>
            <p class="text-gray-500 text-sm">Perbarui informasi dan jadwal tugas #<?= $task['task_code'] ?></p>
        </div>
        <div class="flex gap-2">
            <a href="<?= BASEURL ?>/admin/tasks/details?id=<?= $task['id'] ?>" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm font-bold">
                Batal
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
        
        <div class="xl:col-span-3">
            <form action="<?= BASEURL ?>/admin/tasks/update_full" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <input type="hidden" name="id" value="<?= $task['id'] ?>">

                <div class="mb-8">
                    <h3 class="font-bold text-gray-800 border-b border-gray-100 pb-2 mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 rounded bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs">1</span>
                        Informasi Dasar
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Judul Tugas <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:bg-white transition">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kategori</label>
                            <select name="category_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-200">
                                <option value="">- Pilih Kategori -</option>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $task['category_id'] ? 'selected' : '' ?>>
                                        <?= $cat['category_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Prioritas</label>
                            <div class="flex gap-4">
                                <?php foreach(['medium', 'high', 'urgent'] as $prio): ?>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="priority" value="<?= $prio ?>" <?= $task['priority'] == $prio ? 'checked' : '' ?> class="peer sr-only">
                                    <div class="text-center py-3 rounded-lg border border-gray-200 bg-gray-50 text-gray-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-600 peer-checked:border-indigo-200 font-medium transition capitalize">
                                        <?= $prio ?>
                                    </div>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Deskripsi Detail</label>
                        <textarea name="description" rows="4" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-200"><?= htmlspecialchars($task['description']) ?></textarea>
                    </div>
                </div>

                <div class="mb-8">
                    <h3 class="font-bold text-gray-800 border-b border-gray-100 pb-2 mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 rounded bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs">2</span>
                        Penugasan & Waktu
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Ditugaskan Kepada (PIC) <span class="text-red-500">*</span></label>
                            <select name="assigned_to" required class="w-full px-4 py-3 bg-indigo-50 border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-200">
                                <option value="">- Cari Nama Karyawan -</option>
                                <?php foreach($employees as $emp): ?>
                                    <option value="<?= $emp['user_id'] ?>" <?= $emp['user_id'] == $task['assigned_to'] ? 'selected' : '' ?>>
                                        <?= $emp['first_name'] . ' ' . $emp['last_name'] ?> (<?= $emp['department_name'] ?? 'Umum' ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="<?= $task['start_date'] ?>" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tenggat Waktu</label>
                            <input type="date" name="due_date" value="<?= $task['due_date'] ?>" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Estimasi Jam</label>
                            <div class="relative">
                                <input type="number" name="estimated_hours" value="<?= $task['estimated_hours'] ?>" step="0.5" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg">
                                <span class="absolute right-4 top-3 text-gray-400 text-sm">Jam</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Bobot KPI</label>
                            <div class="relative">
                                <input type="number" name="kpi_weight" value="<?= $task['kpi_weight'] ?>" min="1" max="100" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg">
                                <span class="absolute right-4 top-3 text-gray-400 text-sm">Poin</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-8 bg-purple-50 rounded-xl p-5 border border-purple-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-purple-900 flex items-center gap-2">
                            <i class="fas fa-sync-alt"></i> Pengaturan Tugas Rutin
                        </h3>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_recurring" value="1" <?= $task['is_recurring'] ? 'checked' : '' ?> class="sr-only peer" id="toggleRecurring" onchange="toggleRecurrence()">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-700">Aktifkan</span>
                        </label>
                    </div>

                    <div id="recurrenceOptions" class="<?= $task['is_recurring'] ? 'grid' : 'hidden' ?> grid-cols-1 md:grid-cols-2 gap-6 transition-all duration-300">
                        <div>
                            <label class="block text-xs font-bold text-purple-800 uppercase mb-2">Ulangi Setiap</label>
                            <select name="recurrence_type" class="w-full px-4 py-2 bg-white border border-purple-200 rounded-lg text-purple-900">
                                <option value="daily" <?= $task['recurrence_type'] == 'daily' ? 'selected' : '' ?>>Hari (Daily)</option>
                                <option value="weekly" <?= $task['recurrence_type'] == 'weekly' ? 'selected' : '' ?>>Minggu (Weekly)</option>
                                <option value="monthly" <?= $task['recurrence_type'] == 'monthly' ? 'selected' : '' ?>>Bulan (Monthly)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-purple-800 uppercase mb-2">Pada Tanggal/Hari</label>
                            <input type="number" name="recurrence_day" value="<?= $task['recurrence_day'] ?>" class="w-full px-4 py-2 bg-white border border-purple-200 rounded-lg">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                    <a href="<?= BASEURL ?>/admin/tasks/details?id=<?= $task['id'] ?>" class="px-6 py-3 rounded-lg text-gray-600 font-bold hover:bg-gray-100 transition">Batal</a>
                    <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-lg font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-1 transition flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>

        <div class="xl:col-span-1 space-y-6">
            
            <div class="bg-gradient-to-b from-blue-50 to-white border border-blue-200 rounded-xl p-6 shadow-sm sticky top-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg">
                        <i class="fas fa-edit"></i>
                    </div>
                    <h3 class="font-bold text-gray-800">Mode Edit</h3>
                </div>

                <div class="space-y-4">
                    <div class="flex gap-3">
                        <span class="text-blue-500 font-bold">01.</span>
                        <p class="text-sm text-gray-600 leading-snug">
                            <strong class="text-gray-800">Perubahan Data:</strong><br>
                            Perubahan akan langsung terupdate di dashboard karyawan terkait.
                        </p>
                    </div>
                    <div class="flex gap-3">
                        <span class="text-blue-500 font-bold">02.</span>
                        <p class="text-sm text-gray-600 leading-snug">
                            <strong class="text-gray-800">Ganti PIC?</strong><br>
                            Jika Anda mengganti PIC, tugas ini akan hilang dari dashboard PIC lama dan muncul di PIC baru.
                        </p>
                    </div>
                    <div class="flex gap-3">
                        <span class="text-blue-500 font-bold">03.</span>
                        <p class="text-sm text-gray-600 leading-snug">
                            <strong class="text-gray-800">Checklist:</strong><br>
                            Untuk mengedit item checklist, silakan lakukan di halaman <a href="<?= BASEURL ?>/admin/tasks/details?id=<?= $task['id'] ?>" class="text-indigo-600 underline font-bold">Detail Tugas</a>.
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
function toggleRecurrence() {
    const check = document.getElementById('toggleRecurring');
    const panel = document.getElementById('recurrenceOptions');
    
    if(check.checked) {
        panel.classList.remove('hidden');
        panel.classList.add('grid');
    } else {
        panel.classList.add('hidden');
        panel.classList.remove('grid');
    }
}
</script>