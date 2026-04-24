<div class="max-w-[1600px] mx-auto p-6">
    
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Buat Tugas Baru</h1>
            <p class="text-gray-500 text-sm">Assign tugas ke karyawan, atur jadwal, dan rincian pekerjaan.</p>
        </div>
        <a href="<?= BASEURL ?>/admin/tasks" class="text-gray-500 hover:text-indigo-600 text-sm font-medium">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
        
        <div class="xl:col-span-3">
            <form action="<?= BASEURL ?>/admin/tasks/store" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                
                <div class="mb-8">
                    <h3 class="font-bold text-gray-800 border-b border-gray-100 pb-2 mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 rounded bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs">1</span>
                        Informasi Dasar
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Judul Tugas <span class="text-red-500">*</span></label>
                            <input type="text" name="title" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:bg-white transition" placeholder="Contoh: Laporan Keuangan Bulan Januari">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kategori</label>
                            <select name="category_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:bg-white">
                                <option value="">- Pilih Kategori -</option>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= $cat['category_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Prioritas</label>
                            <div class="flex gap-4">
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="priority" value="medium" checked class="peer sr-only">
                                    <div class="text-center py-3 rounded-lg border border-gray-200 bg-gray-50 text-gray-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 peer-checked:border-blue-200 font-medium transition">
                                        Medium
                                    </div>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="priority" value="high" class="peer sr-only">
                                    <div class="text-center py-3 rounded-lg border border-gray-200 bg-gray-50 text-gray-500 peer-checked:bg-orange-50 peer-checked:text-orange-600 peer-checked:border-orange-200 font-medium transition">
                                        High
                                    </div>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="priority" value="urgent" class="peer sr-only">
                                    <div class="text-center py-3 rounded-lg border border-gray-200 bg-gray-50 text-gray-500 peer-checked:bg-red-50 peer-checked:text-red-600 peer-checked:border-red-200 font-medium transition">
                                        Urgent
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Deskripsi Detail</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:bg-white transition" placeholder="Jelaskan deskripsi umum tugas..."></textarea>
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
                                    <option value="<?= $emp['user_id'] ?>">
                                        <?= $emp['first_name'] . ' ' . $emp['last_name'] ?> (<?= $emp['department_name'] ?? 'Umum' ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="<?= date('Y-m-d') ?>" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tenggat Waktu (Deadline)</label>
                            <input type="date" name="due_date" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Estimasi Jam Kerja</label>
                            <div class="relative">
                                <input type="number" name="estimated_hours" value="1" min="0" step="0.5" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg">
                                <span class="absolute right-4 top-3 text-gray-400 text-sm">Jam</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Bobot KPI</label>
                            <div class="relative">
                                <input type="number" name="kpi_weight" value="10" min="1" max="100" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg">
                                <span class="absolute right-4 top-3 text-gray-400 text-sm">Poin</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h3 class="font-bold text-gray-800 border-b border-gray-100 pb-2 mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 rounded bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs">3</span>
                        Rincian Pekerjaan (Checklist)
                    </h3>
                    
                    <div id="checklistContainer" class="space-y-3 mb-3">
                        <div class="flex gap-2">
                            <div class="w-8 flex items-center justify-center text-gray-300">
                                <i class="far fa-square"></i>
                            </div>
                            <input type="text" name="checklist_items[]" class="flex-1 px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:bg-white text-sm" placeholder="Contoh: Kumpulkan data mentah">
                        </div>
                        <div class="flex gap-2">
                            <div class="w-8 flex items-center justify-center text-gray-300">
                                <i class="far fa-square"></i>
                            </div>
                            <input type="text" name="checklist_items[]" class="flex-1 px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:bg-white text-sm" placeholder="Contoh: Validasi angka dengan divisi Finance">
                        </div>
                    </div>

                    <button type="button" onclick="addChecklistItem()" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-2 ml-10">
                        <i class="fas fa-plus-circle"></i> Tambah Item Checklist
                    </button>
                </div>

                <div class="mb-8 bg-purple-50 rounded-xl p-5 border border-purple-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-purple-900 flex items-center gap-2">
                            <i class="fas fa-sync-alt"></i> Pengaturan Tugas Rutin
                        </h3>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_recurring" value="1" class="sr-only peer" id="toggleRecurring" onchange="toggleRecurrence()">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-700">Aktifkan</span>
                        </label>
                    </div>

                    <div id="recurrenceOptions" class="hidden grid-cols-1 md:grid-cols-2 gap-6 transition-all duration-300">
                        <div>
                            <label class="block text-xs font-bold text-purple-800 uppercase mb-2">Ulangi Setiap</label>
                            <select name="recurrence_type" class="w-full px-4 py-2 bg-white border border-purple-200 rounded-lg text-purple-900 focus:ring-2 focus:ring-purple-200">
                                <option value="daily">Hari (Daily)</option>
                                <option value="weekly">Minggu (Weekly)</option>
                                <option value="monthly">Bulan (Monthly)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-purple-800 uppercase mb-2">Pada Tanggal/Hari</label>
                            <input type="number" name="recurrence_day" placeholder="Contoh: 25 (Tgl) atau 1 (Senin)" class="w-full px-4 py-2 bg-white border border-purple-200 rounded-lg text-purple-900 focus:ring-2 focus:ring-purple-200">
                            <p class="text-[10px] text-purple-600 mt-1">*Jika Bulanan isi tanggal (1-31). Jika Mingguan isi urutan hari (1=Senin).</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                    <button type="button" onclick="history.back()" class="px-6 py-3 rounded-lg text-gray-600 font-bold hover:bg-gray-100 transition">Batal</button>
                    <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-lg font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-1 transition flex items-center gap-2">
                        <i class="fas fa-paper-plane"></i> Terbitkan Tugas
                    </button>
                </div>

            </form>
        </div>

        <div class="xl:col-span-1 space-y-6">
            
            <div class="bg-gradient-to-b from-yellow-50 to-white border border-yellow-200 rounded-xl p-6 shadow-sm sticky top-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600 font-bold text-lg">
                        <i class="far fa-lightbulb"></i>
                    </div>
                    <h3 class="font-bold text-gray-800">Tips Efektif</h3>
                </div>

                <div class="space-y-4">
                    <div class="flex gap-3">
                        <span class="text-yellow-500 font-bold">01.</span>
                        <p class="text-sm text-gray-600 leading-snug">
                            <strong class="text-gray-800">Gunakan Checklist:</strong><br>
                            Pecah tugas besar menjadi item-item kecil di bagian "Rincian Pekerjaan" agar PIC lebih mudah tracking progress.
                        </p>
                    </div>
                    <div class="flex gap-3">
                        <span class="text-yellow-500 font-bold">02.</span>
                        <p class="text-sm text-gray-600 leading-snug">
                            <strong class="text-gray-800">Tugas Rutin:</strong><br>
                            Aktifkan fitur Recurrence untuk tupoksi bulanan agar Anda tidak perlu input ulang bulan depan.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
// JS untuk Checklist
function addChecklistItem() {
    const container = document.getElementById('checklistContainer');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <div class="w-8 flex items-center justify-center text-gray-300">
            <i class="far fa-square"></i>
        </div>
        <input type="text" name="checklist_items[]" class="flex-1 px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:bg-white text-sm" placeholder="Rincian sub-pekerjaan...">
        <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 px-2">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

// JS untuk Recurrence
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