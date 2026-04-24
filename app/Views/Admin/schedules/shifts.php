<div x-data="{ 
    openModal: false, 
    editMode: false,
    formData: { id: '', name: '', code: '', start: '', end: '', tolerance: 15, active: 1 },

    openCreate() {
        this.editMode = false;
        this.formData = { id: '', name: '', code: '', start: '', end: '', tolerance: 15, active: 1 };
        this.openModal = true;
    },

    openEdit(shift) {
        this.editMode = true;
        this.formData = { 
            id: shift.id, 
            name: shift.shift_name, 
            code: shift.shift_code, 
            start: shift.start_time, 
            end: shift.end_time, 
            tolerance: shift.late_tolerance_minutes, 
            active: shift.is_active 
        };
        this.openModal = true;
    }
}">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Master Shift Kerja</h1>
            <p class="text-sm text-gray-500">Atur jam kerja (Pagi, Siang, Malam).</p>
        </div>
        <button @click="openCreate()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md flex items-center transition-colors">
            <i class="fas fa-plus mr-2"></i> Tambah Shift
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b border-gray-100 text-gray-700 font-bold">
                <tr>
                    <th class="px-6 py-4">Kode</th>
                    <th class="px-6 py-4">Nama Shift</th>
                    <th class="px-6 py-4">Jam Kerja</th>
                    <th class="px-6 py-4">Toleransi</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach($shifts as $s): ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-bold text-indigo-600"><?= $s['shift_code'] ?></td>
                    <td class="px-6 py-4 font-medium"><?= $s['shift_name'] ?></td>
                    <td class="px-6 py-4">
                        <?= substr($s['start_time'],0,5) ?> - <?= substr($s['end_time'],0,5) ?>
                    </td>
                    <td class="px-6 py-4"><?= $s['late_tolerance_minutes'] ?> Menit</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs font-bold <?= $s['is_active'] ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' ?>">
                            <?= $s['is_active'] ? 'AKTIF' : 'NONAKTIF' ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button @click="openEdit(<?= htmlspecialchars(json_encode($s)) ?>)" class="text-yellow-600 hover:text-yellow-800 mx-1 transition-colors" title="Edit">
                            <i class="fas fa-pen"></i>
                        </button>
                        
                        <a href="<?= BASEURL ?>/Admin/Schedules/shifts_delete/<?= $s['id'] ?>" 
                           class="swal-link text-red-600 hover:text-red-800 mx-1 transition-colors"
                           data-action="delete"
                           data-msg="Yakin ingin menghapus shift <?= htmlspecialchars($s['shift_name']) ?>?"
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
        <div class="bg-white rounded-2xl w-full max-w-lg p-6 shadow-2xl" @click.away="openModal = false">
            <h3 class="text-lg font-bold text-gray-900 mb-4" x-text="editMode ? 'Edit Shift' : 'Tambah Shift'"></h3>
            
            <form class="swal-form" data-title="Simpan Data Shift?" data-msg="Pastikan jam kerja dan toleransi sudah benar." :action="editMode ? '<?= BASEURL ?>/Admin/Schedules/shifts_update/' + formData.id : '<?= BASEURL ?>/Admin/Schedules/shifts_store'" method="POST">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Shift</label>
                        <input type="text" name="shift_name" x-model="formData.name" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Kode (Singkat)</label>
                        <input type="text" name="shift_code" x-model="formData.code" required class="w-full rounded-lg border-gray-300 shadow-sm uppercase placeholder-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ex: PAGI">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Toleransi (Menit)</label>
                        <input type="number" name="late_tolerance_minutes" x-model="formData.tolerance" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Jam Masuk</label>
                        <input type="time" name="start_time" x-model="formData.start" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Jam Pulang</label>
                        <input type="time" name="end_time" x-model="formData.end" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="col-span-2 flex items-center pt-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" :checked="formData.active == 1" value="1" class="rounded text-indigo-600 mr-2 focus:ring-indigo-500 w-5 h-5">
                            <span class="text-sm font-bold text-gray-700">Status Aktif</span>
                        </label>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" @click="openModal = false" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">Batal</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium shadow-sm transition-colors">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>