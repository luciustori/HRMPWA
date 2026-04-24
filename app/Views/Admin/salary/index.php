<div class="max-w-7xl mx-auto px-4 py-6"
     x-data="{
        /* STATE MODALS */
        modalOpen: false,      
        modalGrade: false,     
        modalDivision: false,
        modalPackage: false,   
        
        isLoading: false,
        activeTab: 'existing',
        
        /* DATA HOLDERS */
        emp: {}, gradeComps: [], customComps: [],
        gradeForm: { empId: '', empName: '', currentGrade: '' },
        divForm: { empId: '', empName: '', currentDiv: '' },
        packageData: { title: '', total: 0, list: '' },

        /* FUNCTIONS */
        async openSetting(id) {
            this.modalOpen = true; this.isLoading = true;
            this.emp = {}; this.gradeComps = []; this.customComps = []; // Reset data
            try {
                let url = '<?= BASEURL ?>/admin/salary/get_employee_data/' + id;
                let response = await fetch(url);
                if (!response.ok) throw new Error('Server Error');
                let data = await response.json();
                if(data.status === 'error') throw new Error(data.message);
                
                this.emp = data.employee; 
                this.gradeComps = data.grade_components; 
                this.customComps = data.custom_components;
            } catch (error) { 
                console.error(error);
                alert('Gagal load data: ' + error.message); 
                this.modalOpen = false;
            } finally { 
                this.isLoading = false; 
            }
        },

        openChangeGrade(id, name, gradeId) {
            this.modalGrade = true;
            this.gradeForm.empId = id; this.gradeForm.empName = name; this.gradeForm.currentGrade = gradeId;
        },

        openChangeDivision(id, name, divId) {
            this.modalDivision = true;
            this.divForm.empId = id;
            this.divForm.empName = name;
            this.divForm.currentDiv = divId;
        },

        openPackageDetail(gradeName, total, listHtml) {
            this.modalPackage = true;
            this.packageData.title = gradeName; this.packageData.total = total; this.packageData.list = listHtml;
        }
     }">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Master Gaji Karyawan</h1>
            <p class="text-sm text-gray-500">Monitoring Take Home Pay & Setting Komponen Custom.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-700 font-bold border-b border-gray-200 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">Karyawan</th>
                    <th class="px-6 py-4">Golongan (Grade)</th>
                    <th class="px-6 py-4 w-1/3">Standard Take Home Pay</th>
                    <th class="px-6 py-4 text-center">Custom</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $current_dept = ''; foreach($employees as $emp): 
                    $base = $emp['custom_base'] ? $emp['custom_base'] : ($emp['grade_base'] ?? 0);
                    $thp_grade = $base + $emp['total_grade_earning'] - $emp['total_grade_deduction'];
                    
                    if ($emp['department_name'] != $current_dept): $current_dept = $emp['department_name'];
                ?>
                    <tr class="bg-indigo-50/50">
                        <td colspan="5" class="px-6 py-3 font-bold text-indigo-700 uppercase tracking-wide text-xs border-y border-indigo-100">
                            <i class="ri-building-line mr-1"></i> Departemen: <?= $current_dept ? $current_dept : 'Tanpa Departemen' ?>
                        </td>
                    </tr>
                <?php endif; ?>

                <tr class="hover:bg-gray-50 transition group">
                    <td class="px-6 py-4 align-top">
                        <div class="font-bold text-gray-800"><?= $emp['first_name'] . ' ' . $emp['last_name'] ?></div>
                        <div class="text-xs text-gray-500 font-mono mb-1"><?= $emp['employee_number'] ?></div>
                        
                        <div class="flex items-center">
                            <?php if(!empty($emp['division_name'])): ?>
                                <button @click="openChangeDivision(<?= $emp['id'] ?>, '<?= $emp['first_name'] ?>', '<?= $emp['division_id'] ?>')" 
                                        class="text-[10px] bg-blue-50 text-blue-600 border border-blue-100 px-2 py-0.5 rounded hover:bg-blue-100 transition flex items-center gap-1 group-div">
                                    <?= $emp['division_name'] ?>
                                    <i class="ri-pencil-line opacity-0 group-div-hover:opacity-100"></i>
                                </button>
                            <?php else: ?>
                                <button @click="openChangeDivision(<?= $emp['id'] ?>, '<?= $emp['first_name'] ?>', '')" 
                                        class="text-[10px] text-blue-500 hover:text-blue-700 hover:underline flex items-center">
                                    <i class="ri-add-line mr-0.5"></i> Set Divisi
                                </button>
                            <?php endif; ?>
                        </div>
                    </td>
                    
                    <td class="px-6 py-4 align-middle">
                        <button @click="openChangeGrade(<?= $emp['id'] ?>, '<?= $emp['first_name'] ?>', '<?= $emp['grade_id'] ?? '' ?>')" 
                                class="text-left group-hover:bg-white border border-transparent group-hover:border-gray-200 rounded-lg p-2 transition w-full">
                            <?php if($emp['grade_code']): ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                    <?= $emp['grade_code'] ?>
                                </span>
                                <span class="text-xs text-gray-600 ml-2 font-medium"><?= $emp['grade_name'] ?></span>
                            <?php else: ?>
                                <span class="text-gray-400 italic text-xs">Belum set grade</span>
                            <?php endif; ?>
                            <i class="ri-pencil-fill text-gray-300 ml-2 opacity-0 group-hover:opacity-100 text-xs"></i>
                        </button>
                    </td>

                    <td class="px-6 py-4 align-middle text-right">
                        <div class="flex items-center justify-end gap-3">
                            <div>
                                <div class="text-sm font-bold font-mono text-gray-800">Rp <?= number_format($thp_grade, 0, ',', '.') ?></div>
                                <div class="text-[10px] text-gray-400 uppercase tracking-wider">Per Bulan</div>
                            </div>
                            <?php if($emp['grade_id']): ?>
                                <button @click="openPackageDetail('<?= $emp['grade_name'] ?>', '<?= number_format($thp_grade, 0, ',', '.') ?>', `<?= $emp['component_list'] ? $emp['component_list'] : '<i>Tidak ada komponen tambahan</i>' ?>`)" 
                                        class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition shadow-sm" title="Lihat Rincian Paket">
                                    <i class="ri-eye-line"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </td>

                    <td class="px-6 py-4 align-middle text-center">
                        <?php if($emp['total_custom'] > 0): ?>
                            <span class="inline-flex items-center justify-center h-6 px-2 rounded-full bg-orange-100 text-orange-700 text-xs font-bold border border-orange-200">
                                +<?= $emp['total_custom'] ?> Custom
                            </span>
                        <?php else: ?>
                            <span class="text-gray-300 text-xs">-</span>
                        <?php endif; ?>
                    </td>

                    <td class="px-6 py-4 align-middle text-center">
                        <button @click="openSetting(<?= $emp['id'] ?>)" 
                                class="text-gray-400 hover:text-indigo-600 transition p-2 rounded-full hover:bg-gray-100">
                            <i class="ri-settings-4-fill text-xl"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div x-show="modalDivision" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75" @click="modalDivision = false"></div>
            <div class="bg-white rounded-xl shadow-xl w-full max-w-sm relative z-10 overflow-hidden p-0 transform transition-all">
                <div class="bg-blue-50 px-5 py-4 border-b border-blue-100">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2"><i class="ri-briefcase-line text-blue-600"></i> Setting Divisi</h3>
                    <p class="text-xs text-gray-500">Karyawan: <span x-text="divForm.empName" class="font-bold text-blue-600"></span></p>
                </div>
                <form action="<?= BASEURL ?>/admin/salary/update_division" method="POST" class="p-5">
                    <input type="hidden" name="employee_id" :value="divForm.empId">
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-600 mb-2">Pilih Divisi</label>
                        <select name="division_id" x-model="divForm.currentDiv" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500">
                            <option value="">-- Pilih Divisi --</option>
                            <?php foreach($divisions_list as $div): ?>
                                <option value="<?= $div['id'] ?>"><?= $div['division_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" @click="modalDivision = false" class="px-4 py-2 text-xs font-bold text-gray-500 bg-gray-100 rounded hover:bg-gray-200">Batal</button>
                        <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-blue-600 rounded hover:bg-blue-700 shadow">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div x-show="modalGrade" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75" @click="modalGrade = false"></div>
            <div class="bg-white rounded-xl shadow-xl w-full max-w-sm relative z-10 p-5">
                <h3 class="font-bold text-gray-800 mb-4">Ganti Grade: <span x-text="gradeForm.empName"></span></h3>
                <form action="<?= BASEURL ?>/admin/salary/update_grade" method="POST">
                    <input type="hidden" name="employee_id" :value="gradeForm.empId">
                    <select name="grade_id" x-model="gradeForm.currentGrade" class="w-full border-gray-300 rounded mb-4 text-sm">
                        <option value="">-- Pilih --</option>
                        <?php foreach($grades_list as $g): ?>
                        <option value="<?= $g['id'] ?>"><?= $g['grade_code'] ?> - <?= $g['grade_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded font-bold hover:bg-indigo-700">Simpan</button>
                </form>
            </div>
        </div>
    </div>

    <div x-show="modalPackage" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75" @click="modalPackage = false"></div>
            <div class="bg-white rounded-xl shadow-xl w-full max-w-sm relative z-10 overflow-hidden transform transition-all p-6 text-center">
                <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4"><i class="ri-money-dollar-circle-line text-2xl"></i></div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Paket: <span x-text="packageData.title"></span></h3>
                <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left border border-gray-100 mt-4"><div class="text-xs text-gray-400 font-bold uppercase mb-2">Komponen:</div><div class="text-sm text-gray-600 space-y-1" x-html="packageData.list"></div><div class="border-t border-gray-200 mt-3 pt-3 flex justify-between items-center"><span class="font-bold text-gray-800 text-sm">Total THP</span><span class="font-bold text-indigo-600 font-mono text-base" x-text="'Rp ' + packageData.total"></span></div></div>
                <button @click="modalPackage = false" class="w-full py-2 bg-gray-100 text-gray-700 font-bold rounded-lg hover:bg-gray-200 transition">Tutup</button>
            </div>
        </div>
    </div>

    <div x-show="modalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" @click="modalOpen = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full">
                <div x-show="isLoading" class="p-10 text-center"><i class="ri-loader-4-line text-4xl text-indigo-600 animate-spin"></i><p class="mt-2 text-gray-500 font-medium">Memuat data...</p></div>
                <div x-show="!isLoading">
                   <div class="bg-indigo-600 px-6 py-4 flex justify-between items-center"><div><h3 class="text-lg font-bold text-white">Setting Gaji Spesifik</h3><p class="text-indigo-200 text-sm"><span x-text="emp.first_name"></span> (<span x-text="emp.grade_name || 'No Grade'"></span>)</p></div><button @click="modalOpen = false" class="text-white"><i class="ri-close-line text-2xl"></i></button></div>
                   
                   <div class="p-6 max-h-[70vh] overflow-y-auto">
                        <div class="mb-6">
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3 flex items-center"><i class="ri-shield-check-line mr-1"></i> Komponen Baku (Dari Grade)</h4>
                            <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                                <table class="w-full text-sm">
                                    <template x-if="emp.grade_base > 0">
                                        <tr class="border-b border-gray-100 last:border-0">
                                            <td class="px-4 py-2 text-gray-600">Gaji Pokok (Base)</td>
                                            <td class="px-4 py-2 text-right font-mono font-bold text-gray-700" x-text="'Rp ' + parseInt(emp.grade_base).toLocaleString('id-ID')"></td>
                                            <td class="px-4 py-2 text-center text-xs text-gray-400"><i class="ri-lock-line"></i></td>
                                        </tr>
                                    </template>
                                    <template x-for="gc in gradeComps" :key="gc.component_name">
                                        <tr class="border-b border-gray-100 last:border-0">
                                            <td class="px-4 py-2"><span x-text="gc.component_name" class="text-gray-600"></span><span x-show="gc.component_type == 'deduction'" class="text-[10px] text-red-500 bg-red-50 px-1 rounded ml-1">POT</span></td>
                                            <td class="px-4 py-2 text-right font-mono text-gray-600" x-text="'Rp ' + parseInt(gc.amount).toLocaleString('id-ID')"></td>
                                            <td class="px-4 py-2 text-center text-xs text-gray-400"><i class="ri-lock-line"></i></td>
                                        </tr>
                                    </template>
                                    <template x-if="gradeComps.length === 0 && (!emp.grade_base || emp.grade_base == 0)"><tr><td colspan="3" class="px-4 py-2 text-center text-gray-400 italic text-xs">Tidak ada komponen baku.</td></tr></template>
                                </table>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h4 class="text-xs font-bold text-indigo-600 uppercase tracking-wide mb-3 flex items-center"><i class="ri-edit-circle-line mr-1"></i> Komponen Spesifik (Custom)</h4>
                            <div class="bg-white rounded-lg border border-indigo-100 shadow-sm overflow-hidden">
                                <table class="w-full text-sm">
                                    <template x-for="cc in customComps" :key="cc.id">
                                        <tr class="border-b border-gray-100 last:border-0 hover:bg-indigo-50/30 transition">
                                            <td class="px-4 py-3 font-medium text-gray-800"><span x-text="cc.component_name"></span><span x-show="cc.component_type == 'deduction'" class="text-[10px] text-red-600 bg-red-100 px-1.5 py-0.5 rounded font-bold ml-1">POTONGAN</span></td>
                                            <td class="px-4 py-3 text-right font-mono font-bold text-indigo-700" x-text="'Rp ' + parseInt(cc.amount).toLocaleString('id-ID')"></td>
                                            <td class="px-4 py-3 text-right"><a :href="'<?= BASEURL ?>/admin/salary/delete_component/' + cc.id" onclick="return confirm('Hapus komponen ini?')" class="text-red-400 hover:text-red-600 p-1 rounded hover:bg-red-50 transition"><i class="ri-delete-bin-line"></i></a></td>
                                        </tr>
                                    </template>
                                    <template x-if="customComps.length === 0"><tr><td colspan="3" class="px-4 py-6 text-center text-gray-400 italic">Belum ada komponen custom untuk karyawan ini.</td></tr></template>
                                </table>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-200 mt-4">
                            <h4 class="text-sm font-bold text-gray-800 mb-4 border-b pb-2">Tambah Komponen Personal</h4>
                            <form action="<?= BASEURL ?>/admin/salary/store_component" method="POST">
                                <input type="hidden" name="employee_id" :value="emp.id">
                                <div class="flex space-x-1 bg-gray-200 p-1 rounded-lg mb-4 w-full sm:w-2/3">
                                    <button type="button" @click="activeTab = 'existing'" :class="activeTab === 'existing' ? 'bg-white shadow text-indigo-600' : 'text-gray-500'" class="flex-1 py-1.5 text-xs font-bold rounded transition">Pilih Template</button>
                                    <button type="button" @click="activeTab = 'new'" :class="activeTab === 'new' ? 'bg-white shadow text-indigo-600' : 'text-gray-500'" class="flex-1 py-1.5 text-xs font-bold rounded transition">Buat Baru</button>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="col-span-1">
                                        <div x-show="activeTab === 'existing'">
                                            <label class="block text-xs font-bold text-gray-500 mb-1">Pilih Komponen</label>
                                            <select name="component_id" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500"><option value="">-- Pilih --</option><?php foreach($component_list as $cl): ?><option value="<?= $cl['id'] ?>"><?= $cl['component_name'] ?> (<?= ucfirst($cl['component_type']) ?>)</option><?php endforeach; ?></select>
                                        </div>
                                        <div x-show="activeTab === 'new'">
                                            <label class="block text-xs font-bold text-gray-500 mb-1">Nama Baru</label>
                                            <input type="text" name="new_component_name" class="w-full text-sm border-gray-300 rounded-lg" placeholder="Contoh: Bonus Project X">
                                            <div class="mt-2 flex gap-3"><label class="inline-flex items-center"><input type="radio" name="new_component_type" value="earning" checked class="text-indigo-600"><span class="ml-1 text-xs">Penerimaan</span></label><label class="inline-flex items-center"><input type="radio" name="new_component_type" value="deduction" class="text-red-600"><span class="ml-1 text-xs">Potongan</span></label></div>
                                        </div>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="block text-xs font-bold text-gray-500 mb-1">Nominal (Rp)</label>
                                        <div class="flex gap-2"><input type="text" name="amount" class="w-full text-sm border-gray-300 rounded-lg font-mono font-bold" placeholder="0" onkeyup="this.value=this.value.replace(/\./g,'').replace(/\B(?=(\d{3})+(?!\d))/g,'.')" required><button type="submit" class="bg-indigo-600 text-white px-4 rounded-lg font-bold hover:bg-indigo-700 shadow"><i class="ri-add-line"></i></button></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>