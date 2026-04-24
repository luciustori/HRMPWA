<div x-data="{ 
    openModal: false, 
    editMode: false,
    year: '<?= $year ?>',
    formData: { id: '', date: '', name: '', type: 'national', desc: '' },

    openCreate() {
        this.editMode = false;
        this.formData = { id: '', date: '', name: '', type: 'national', desc: '' };
        this.openModal = true;
    },

    openEdit(h) {
        this.editMode = true;
        this.formData = { 
            id: h.id, 
            date: h.holiday_date, 
            name: h.holiday_name, 
            type: h.holiday_type, 
            desc: h.description 
        };
        this.openModal = true;
    }
}">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Hari Libur & Cuti Bersama</h1>
            <p class="text-sm text-gray-500">Manajemen tanggal merah tahun <?= $year ?>.</p>
        </div>
        <div class="flex gap-2 items-center">
            <form method="GET" class="flex gap-2">
                <select name="year" onchange="this.form.submit()" class="border-gray-300 rounded-lg text-sm py-2 px-4 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <?php for($y=date('Y')-1; $y<=date('Y')+1; $y++): ?>
                        <option value="<?= $y ?>" <?= $year==$y ? 'selected':'' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </form>

            <a href="<?= BASEURL ?>/Admin/Schedules/holidays_sync_api?year=<?= $year ?>" 
               class="swal-link bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md flex items-center transition-colors"
               data-action="sync"
               data-msg="Data tahun <?= $year ?> akan ditarik ulang dari API Pemerintah. Data custom tidak akan ditimpa.">
                <i class="fas fa-cloud-download-alt mr-2"></i> Sync API
            </a>

            <button @click="openCreate()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md flex items-center transition-colors">
                <i class="fas fa-plus mr-2"></i> Tambah Manual
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-700 font-bold border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Keterangan</th>
                    <th class="px-6 py-4">Tipe</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if(empty($holidays)): ?>
                    <tr><td colspan="4" class="text-center py-8 text-gray-400">Belum ada data libur. Klik Sync API atau Tambah Manual.</td></tr>
                <?php endif; ?>

                <?php foreach($holidays as $h): ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-mono font-bold text-indigo-600">
                        <?= date('d M Y', strtotime($h['holiday_date'])) ?>
                        <div class="text-xs text-gray-400"><?= date('l', strtotime($h['holiday_date'])) ?></div>
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-700">
                        <?= $h['holiday_name'] ?>
                        <div class="text-xs text-gray-400 font-normal"><?= $h['description'] ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-md text-xs font-bold <?= $h['holiday_type']=='national' ? 'bg-red-100 text-red-600' : ($h['holiday_type']=='company' ? 'bg-indigo-100 text-indigo-600' : 'bg-yellow-100 text-yellow-600') ?>">
                            <?= ucfirst($h['holiday_type']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button @click="openEdit(<?= htmlspecialchars(json_encode($h)) ?>)" class="text-yellow-600 hover:text-yellow-800 mx-1 transition-colors" title="Edit">
                            <i class="fas fa-pen"></i>
                        </button>
                        
                        <a href="<?= BASEURL ?>/Admin/Schedules/holidays_delete/<?= $h['id'] ?>" 
                           class="swal-link text-red-600 hover:text-red-800 mx-1 transition-colors"
                           data-action="delete"
                           data-msg="Yakin ingin menghapus libur <?= htmlspecialchars($h['holiday_name']) ?>?"
                           title="Hapus">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm" style="display: none;" x-transition>
        <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl" @click.away="openModal = false">
            <h3 class="text-lg font-bold text-gray-800 mb-4" x-text="editMode ? 'Edit Hari Libur' : 'Tambah Hari Libur'"></h3>
            
            <form class="swal-form" data-title="Simpan Hari Libur?" data-msg="Pastikan tanggal dan keterangan sudah benar." :action="editMode ? '<?= BASEURL ?>/Admin/Schedules/holidays_update/' + formData.id : '<?= BASEURL ?>/Admin/Schedules/holidays_store'" method="POST">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal</label>
                        <input type="date" name="holiday_date" x-model="formData.date" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Libur</label>
                        <input type="text" name="holiday_name" x-model="formData.name" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tipe</label>
                        <select name="holiday_type" x-model="formData.type" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <option value="national">Nasional</option>
                            <option value="company">Perusahaan</option>
                            <option value="religious">Keagamaan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Deskripsi</label>
                        <textarea name="description" x-model="formData.desc" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" rows="2"></textarea>
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" @click="openModal=false" class="px-5 py-2 bg-gray-100 text-gray-600 font-bold rounded-lg hover:bg-gray-200 transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 text-white font-bold rounded-lg shadow-md hover:bg-indigo-700 transition-colors">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>