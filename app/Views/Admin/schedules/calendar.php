<style>
    .sticky-col { position: sticky; left: 0; background: white; z-index: 20; border-right: 2px solid #f3f4f6; }
    .sticky-header { position: sticky; top: 0; z-index: 30; background: #f9fafb; }
    /* Biar header nama gak ketimpa pas scroll */
    thead th { z-index: 30; } 
    thead th.sticky-col { z-index: 40; }
</style>

<div x-data="calendarApp()">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kalender Shift</h1>
            <p class="text-sm text-gray-500">Klik tanggal untuk ubah shift, atau gunakan Bulk Assign.</p>
        </div>
        <div class="flex gap-2 items-center">
            <button @click="openBulk()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-md flex items-center transition-colors">
                <i class="fas fa-layer-group mr-2"></i> Bulk Assign
            </button>
            <form method="GET" class="flex">
                <input type="month" name="current_month" value="<?= $year . '-' . $month ?>" 
                       onchange="location.href='?month='+this.value.split('-')[1]+'&year='+this.value.split('-')[0]" 
                       class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto max-h-[75vh]">
        <table class="w-full text-xs border-collapse">
            <thead class="sticky-header">
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="sticky-col px-4 py-3 text-left w-64 text-gray-700 font-bold shadow-sm">KARYAWAN</th>
                    <?php 
                    $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    for($d=1; $d<=$days_in_month; $d++): 
                        $date = sprintf('%04d-%02d-%02d', $year, $month, $d);
                        $is_weekend = in_array(date('N', strtotime($date)), [6,7]);
                        $day_name = date('D', strtotime($date));
                        $is_today = ($date == date('Y-m-d'));
                    ?>
                    <th class="px-2 py-3 border-b text-center min-w-[40px] <?= $is_weekend ? 'bg-red-50 text-red-600' : ($is_today ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600') ?>">
                        <div class="font-bold text-sm"><?= $d ?></div>
                        <div class="text-[10px] uppercase"><?= $day_name ?></div>
                    </th>
                    <?php endfor; ?>
                </tr>
            </thead>
            <tbody>
                <?php 
                $last_dept = '';
                $last_div = '';
                
                if(empty($employees)): ?>
                <tr><td colspan="<?= $days_in_month + 1 ?>" class="p-8 text-center text-gray-400">Tidak ada data karyawan di divisi/departemen ini.</td></tr>
                <?php endif; 

                foreach($employees as $e): 
                    if($last_dept != $e['department_name']): ?>
                        <tr class="bg-gray-200"><td colspan="<?= $days_in_month + 1 ?>" class="px-4 py-2 font-bold text-gray-800 text-sm sticky-col z-10">
                            <i class="fas fa-building mr-2 text-gray-500"></i> DEPT: <?= strtoupper($e['department_name']) ?>
                        </td></tr>
                    <?php $last_dept = $e['department_name']; $last_div = ''; endif; 
                    
                    if($last_div != $e['division_name']): ?>
                        <tr class="bg-gray-100"><td colspan="<?= $days_in_month + 1 ?>" class="px-6 py-1 font-bold text-gray-600 text-xs sticky-col z-10 pl-8">
                            <i class="fas fa-folder-open mr-2 text-gray-400"></i> DIV: <?= strtoupper($e['division_name']) ?>
                        </td></tr>
                    <?php $last_div = $e['division_name']; endif; ?>

                    <tr class="hover:bg-indigo-50 transition-colors border-b border-gray-100 group">
                        <td class="sticky-col px-4 py-2 bg-white group-hover:bg-indigo-50 border-r font-medium text-gray-700 flex flex-col justify-center h-12">
                            <span><?= $e['first_name'] ?> <?= $e['last_name'] ?></span>
                        </td>
                        <?php for($d=1; $d<=$days_in_month; $d++): 
                            $date = sprintf('%04d-%02d-%02d', $year, $month, $d);
                            $key = $date . '_' . $e['id'];
                            $assign = $assignment_map[$key] ?? null;
                        ?>
                        <td class="p-0 border-r border-gray-100 text-center cursor-pointer relative" 
                            @click="openSingle('<?= $e['id'] ?>', '<?= addslashes($e['first_name']) ?>', '<?= $date ?>', '<?= $assign['shift_id'] ?? '' ?>', '<?= $assign['is_mod'] ?? 0 ?>')">
                            
                            <div class="w-full h-full flex items-center justify-center py-3">
                                <?php if($assign): ?>
                                    <span class="block px-1.5 py-0.5 rounded text-[10px] font-bold shadow-sm bg-indigo-600 text-white whitespace-nowrap">
                                        <?= $assign['shift_code'] ?>
                                        <?php if($assign['is_mod']): ?><span class="text-yellow-300 text-[8px] ml-1">★</span><?php endif; ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-200 text-lg leading-none hover:text-indigo-300">&middot;</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <?php endfor; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div x-show="singleModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-60 backdrop-blur-sm" style="display: none;" x-transition>
        <div class="bg-white rounded-2xl w-full max-w-sm p-6 shadow-2xl transform transition-all" @click.away="singleModal = false">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Edit Shift</h3>
                    <p class="text-sm text-gray-500 font-medium"><span x-text="single.name"></span></p>
                    <p class="text-xs text-indigo-600 font-mono mt-1" x-text="formatDate(single.date)"></p>
                </div>
                <button @click="singleModal=false" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-lg"></i></button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Pilih Shift</label>
                    <select x-model="single.shift_id" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        <option value="">-- LIBUR / OFF --</option>
                        <?php foreach($shifts as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= $s['shift_name'] ?> (<?= substr($s['start_time'],0,5) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-100">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" x-model="single.is_mod" class="rounded text-yellow-600 focus:ring-yellow-500 mr-3 h-5 w-5">
                        <span class="text-sm font-bold text-gray-700">Set sebagai MOD (Manager on Duty)</span>
                    </label>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button @click="submitSingle()" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg font-bold shadow-md transition-colors">Simpan Perubahan</button>
            </div>
        </div>
    </div>

    <div x-show="bulkModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 backdrop-blur-sm" style="display: none;" x-transition>
        <div class="bg-white rounded-2xl w-full max-w-3xl p-0 shadow-2xl flex flex-col max-h-[90vh]" @click.away="bulkModal = false">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-2xl">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Bulk Assign (Jadwal Massal)</h3>
                    <p class="text-xs text-gray-500">Set jadwal untuk banyak karyawan sekaligus.</p>
                </div>
                <button @click="bulkModal=false" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
            </div>

            <form class="swal-form flex flex-col flex-1 overflow-hidden" 
                  data-title="Terapkan Jadwal Massal?" 
                  data-msg="Jadwal karyawan yang dipilih akan diupdate secara otomatis." 
                  action="<?= BASEURL ?>/Admin/Schedules/assign_store" method="POST">
                
                <div class="p-6 overflow-y-auto no-scrollbar">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 bg-indigo-50 p-4 rounded-xl border border-indigo-100">
                        <div>
                            <label class="block text-xs font-bold uppercase text-indigo-800 mb-1">Dari Tanggal</label>
                            <input type="date" name="start_date" x-model="bulk.start_date" required class="w-full rounded-lg border-indigo-200 focus:border-indigo-500 text-sm shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-indigo-800 mb-1">Sampai Tanggal</label>
                            <input type="date" name="end_date" x-model="bulk.end_date" required class="w-full rounded-lg border-indigo-200 focus:border-indigo-500 text-sm shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-indigo-800 mb-1">Set Shift</label>
                            <select name="shift_id" class="w-full rounded-lg border-indigo-200 focus:border-indigo-500 text-sm font-bold text-indigo-700 shadow-sm">
                                <option value="">-- LIBUR / OFF --</option>
                                <?php foreach($shifts as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= $s['shift_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-6 p-3 bg-red-50 border border-red-100 rounded-xl">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="skip_weekend" value="1" checked class="rounded text-red-600 focus:ring-red-500 mr-3 h-5 w-5">
                            <span class="text-sm font-bold text-red-800">Lewati Sabtu & Minggu (Terapkan Pola 5 Hari Kerja)</span>
                        </label>
                    </div>

                    <div class="mb-2 flex justify-between items-center">
                        <label class="text-sm font-bold text-gray-700">Pilih Karyawan Target:</label>
                        <label class="text-xs flex items-center cursor-pointer bg-gray-100 px-3 py-1 rounded-full hover:bg-gray-200 transition-colors">
                            <input type="checkbox" @change="toggleAll($event)" class="mr-2 rounded text-indigo-600 focus:ring-indigo-500"> Pilih Semua
                        </label>
                    </div>

                    <div class="border rounded-xl max-h-60 overflow-y-auto divide-y divide-gray-100 custom-scrollbar">
                        <?php foreach($employees as $e): ?>
                        <label class="flex items-center px-4 py-3 hover:bg-gray-50 cursor-pointer transition">
                            <input type="checkbox" name="employee_ids[]" value="<?= $e['id'] ?>" class="emp-checkbox w-5 h-5 rounded text-indigo-600 border-gray-300 focus:ring-indigo-500 mr-4">
                            <div>
                                <div class="font-bold text-sm text-gray-800"><?= $e['first_name'] ?> <?= $e['last_name'] ?></div>
                                <div class="text-xs text-gray-500"><?= $e['department_name'] ?> &bull; <?= $e['division_name'] ?></div>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="mt-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_mod" value="1" class="rounded text-yellow-600 focus:ring-yellow-500 mr-2 w-4 h-4">
                            <span class="text-sm font-bold text-gray-600">Set sebagai MOD (Manager on Duty)</span>
                        </label>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3 rounded-b-2xl">
                    <button type="button" @click="bulkModal = false" class="px-5 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-bold text-sm hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg font-bold text-sm shadow-md hover:bg-indigo-700 transition-colors">Terapkan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function calendarApp() {
    return {
        singleModal: false,
        bulkModal: false,
        single: { employee_id: '', name: '', date: '', shift_id: '', is_mod: false },
        bulk: { start_date: '<?= "$year-$month-01" ?>', end_date: '<?= "$year-$month-" . cal_days_in_month(CAL_GREGORIAN, $month, $year) ?>' },

        formatDate(dateStr) {
            const date = new Date(dateStr);
            return date.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        },

        openSingle(empId, empName, date, currentShiftId, isMod) {
            this.single.employee_id = empId;
            this.single.name = empName;
            this.single.date = date;
            this.single.shift_id = currentShiftId;
            this.single.is_mod = (isMod == 1);
            this.singleModal = true;
        },

        // SWEETALERT2 UNTUK FUNGSI AJAX
        submitSingle() {
            Swal.fire({
                title: 'Update Jadwal?',
                text: 'Perubahan jadwal untuk karyawan ini akan disimpan.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-2xl shadow-xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    let formData = new FormData();
                    formData.append('employee_id', this.single.employee_id);
                    formData.append('assignment_date', this.single.date);
                    formData.append('shift_id', this.single.shift_id);
                    if(this.single.is_mod) formData.append('is_mod', 1);

                    fetch('<?= BASEURL ?>/Admin/Schedules/assign_store', { 
                        method: 'POST', 
                        body: formData 
                    })
                    .then(async res => {
                        if (!res.ok) throw new Error(await res.text() || 'Server Error');
                        return res.json();
                    })
                    .then(data => {
                        if(data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Jadwal shift diupdate.',
                                showConfirmButton: false,
                                timer: 1500,
                                customClass: { popup: 'rounded-2xl shadow-xl border border-slate-100' }
                            }).then(() => location.reload()); 
                        } else {
                            SwalCRUD.notify('error', 'Gagal!', 'Gagal menyimpan data ke database.');
                        }
                    })
                    .catch(err => {
                        console.error("DEBUG ERROR:", err);
                        SwalCRUD.notify('error', 'Terjadi Kesalahan!', err.message);
                    });
                }
            });
        },

        openBulk() { this.bulkModal = true; },
        toggleAll(e) {
            document.querySelectorAll('.emp-checkbox').forEach(cb => { cb.checked = e.target.checked; });
        }
    }
}
</script>