<div class="max-w-7xl mx-auto px-4 py-6" 
     x-data="{ 
        /* STATE MODAL */
        modalGrade: false, 
        modalAssign: false, 
        modalComponent: false,
        
        /* STATE DATA FORM */
        editMode: false,
        formData: { id: '', code: '', name: '', base: '', desc: '' }, 
        assignData: { gradeId: '', gradeName: '' }, 
        compData: { gradeId: '', gradeName: '' },
        
        /* STATE TAB COMPONENT */
        tab: 'existing',

        /* STATE ACCORDION (Perbaikan Disini) */
        activeAccordion: null,
        toggle(id) {
            this.activeAccordion = (this.activeAccordion === id) ? null : id;
        }
     }">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Master Salary Grade</h1>
            <p class="text-sm text-gray-500">Atur standar gaji dan komponen per golongan.</p>
        </div>
        <button @click="modalGrade = true; editMode = false; formData = {id:'', code:'', name:'', base:'', desc:''}" 
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 font-bold text-sm transition flex items-center">
            <i class="ri-add-line mr-1"></i> Buat Grade Baru
        </button>
    </div>

    <div class="space-y-4">
        <?php foreach($grades as $g): ?>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300">
            
            <div class="w-full flex flex-col md:flex-row md:items-center justify-between p-5 bg-gray-50 border-b border-gray-100 gap-4">
                
                <div class="flex items-center gap-4 cursor-pointer flex-1" @click="toggle('grade_<?= $g['id'] ?>')">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center font-bold text-xl shadow-md">
                        <?= $g['grade_code'] ?>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2">
                            <?= $g['grade_name'] ?>
                            <span class="text-[10px] bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full font-normal">
                                <?= $g['employee_count'] ?> Staff
                            </span>
                        </h3>
                        <p class="text-xs text-gray-500"><?= $g['description'] ?? '-' ?></p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Take Home Pay</p>
                        <p class="font-mono font-bold text-emerald-600 text-lg">Rp <?= number_format($g['total_take_home'], 0, ',', '.') ?></p>
                    </div>
                    
                    <div class="flex gap-1">
                        <button @click="modalGrade = true; editMode = true; formData = {
                                    id: '<?= $g['id'] ?>', 
                                    code: '<?= htmlspecialchars($g['grade_code']) ?>', 
                                    name: '<?= htmlspecialchars($g['grade_name']) ?>', 
                                    base: '<?= number_format($g['base_salary'],0,',','.') ?>', 
                                    desc: '<?= htmlspecialchars($g['description'] ?? '') ?>'
                                }" 
                                class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded transition" title="Edit Grade">
                            <i class="ri-edit-2-line text-lg"></i>
                        </button>

                        <button @click="modalAssign = true; assignData = { gradeId: '<?= $g['id'] ?>', gradeName: '<?= htmlspecialchars($g['grade_name']) ?>' }"
                                class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded transition" title="Assign Karyawan">
                            <i class="ri-user-add-line text-lg"></i>
                        </button>
                    </div>

                    <button @click="toggle('grade_<?= $g['id'] ?>')" class="p-2 text-gray-400 hover:text-gray-600 transition">
                        <i class="ri-arrow-down-s-line text-2xl transition-transform duration-300" 
                           :class="activeAccordion === 'grade_<?= $g['id'] ?>' ? 'rotate-180' : ''"></i>
                    </button>
                </div>
            </div>

            <div x-show="activeAccordion === 'grade_<?= $g['id'] ?>'" style="display: none;" x-transition>
                <div class="p-6 bg-white">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wide border-l-4 border-indigo-500 pl-3">Struktur Komponen</h4>
                        
                        <button @click="modalComponent = true; compData = { gradeId: '<?= $g['id'] ?>', gradeName: '<?= htmlspecialchars($g['grade_name']) ?>' }" 
                                class="text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-2 rounded shadow transition flex items-center">
                            <i class="ri-add-circle-line mr-1 text-sm"></i> Tambah Komponen
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <div class="border border-green-100 rounded-xl bg-green-50/30 overflow-hidden">
                            <div class="px-4 py-3 bg-green-50 border-b border-green-100 flex justify-between items-center">
                                <span class="text-xs font-bold text-green-700 uppercase">Penerimaan (Earnings)</span>
                                <i class="ri-wallet-3-line text-green-600"></i>
                            </div>
                            <div class="p-4 space-y-3">
                                <div class="flex justify-between items-center border-b border-dashed border-green-200 pb-2">
                                    <div>
                                        <div class="text-sm font-bold text-gray-700">Gaji Pokok</div>
                                        <div class="text-[10px] text-gray-400">Basic Salary</div>
                                    </div>
                                    <div class="font-mono font-bold text-gray-700">Rp <?= number_format($g['base_salary'], 0, ',', '.') ?></div>
                                </div>

                                <?php foreach($g['earnings'] as $earn): ?>
                                <div class="flex justify-between items-center border-b border-dashed border-green-200 pb-2 last:border-0">
                                    <div class="text-sm font-medium text-gray-600"><?= $earn['component_name'] ?></div>
                                    <div class="flex items-center gap-3">
                                        <div class="font-mono text-gray-600">Rp <?= number_format($earn['amount'], 0, ',', '.') ?></div>
                                        <a href="<?= BASEURL ?>/admin/salary_grade/delete_component/<?= $earn['id'] ?>" onclick="return confirm('Hapus?')" class="text-red-300 hover:text-red-500"><i class="ri-close-circle-fill"></i></a>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="border border-red-100 rounded-xl bg-red-50/30 overflow-hidden">
                            <div class="px-4 py-3 bg-red-50 border-b border-red-100 flex justify-between items-center">
                                <span class="text-xs font-bold text-red-700 uppercase">Potongan (Deductions)</span>
                                <i class="ri-hand-coin-line text-red-600"></i>
                            </div>
                            <div class="p-4 space-y-3">
                                <?php if(empty($g['deductions'])): ?>
                                    <p class="text-xs text-gray-400 italic text-center py-2">Tidak ada potongan baku.</p>
                                <?php else: ?>
                                    <?php foreach($g['deductions'] as $deduct): ?>
                                    <div class="flex justify-between items-center border-b border-dashed border-red-200 pb-2 last:border-0">
                                        <div class="text-sm font-medium text-gray-600"><?= $deduct['component_name'] ?></div>
                                        <div class="flex items-center gap-3">
                                            <div class="font-mono text-red-600">(Rp <?= number_format($deduct['amount'], 0, ',', '.') ?>)</div>
                                            <a href="<?= BASEURL ?>/admin/salary_grade/delete_component/<?= $deduct['id'] ?>" onclick="return confirm('Hapus?')" class="text-red-300 hover:text-red-500"><i class="ri-close-circle-fill"></i></a>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <?php endforeach; ?>
    </div>

    <div x-show="modalGrade" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="modalGrade = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="<?= BASEURL ?>/admin/salary_grade/store" method="POST">
                    <input type="hidden" name="id" :value="formData.id">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" x-text="editMode ? 'Edit Salary Grade' : 'Tambah Grade Baru'"></h3>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Kode</label>
                                <input type="text" name="grade_code" x-model="formData.code" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Nama Grade</label>
                                <input type="text" name="grade_name" x-model="formData.name" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Gaji Pokok</label>
                            <input type="text" name="base_salary" x-model="formData.base" class="w-full border-gray-300 rounded-md shadow-sm font-mono" onkeyup="this.value=this.value.replace(/\./g,'').replace(/\B(?=(\d{3})+(?!\d))/g,'.')" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="description" x-model="formData.desc" rows="2" class="w-full border-gray-300 rounded-md shadow-sm"></textarea>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                        <button type="button" @click="modalGrade = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div x-show="modalAssign" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="modalAssign = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
                <form action="<?= BASEURL ?>/admin/salary_grade/assign_employee" method="POST">
                    <input type="hidden" name="grade_id" :value="assignData.gradeId">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            Assign Karyawan ke <span x-text="assignData.gradeName" class="text-indigo-600 font-bold"></span>
                        </h3>
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Karyawan</label>
                            <select name="employee_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500" required>
                                <option value="">-- Pilih --</option>
                                <?php foreach($employees_list as $emp): ?>
                                    <option value="<?= $emp['id'] ?>">
                                        <?= $emp['first_name'] . ' ' . $emp['last_name'] ?> (<?= $emp['employee_number'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                        <button type="button" @click="modalAssign = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div x-show="modalComponent" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="modalComponent = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="<?= BASEURL ?>/admin/salary_grade/store_component" method="POST">
                    <input type="hidden" name="grade_id" :value="compData.gradeId">
                    
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            Tambah Komponen ke <span x-text="compData.gradeName" class="text-indigo-600 font-bold"></span>
                        </h3>

                        <div class="flex space-x-1 bg-gray-100 p-1 rounded-lg mb-4">
                            <button type="button" @click="tab = 'existing'" :class="tab === 'existing' ? 'bg-white shadow text-indigo-600' : 'text-gray-500'" class="flex-1 py-1.5 text-sm font-bold rounded-md transition">Pilih Template</button>
                            <button type="button" @click="tab = 'new'" :class="tab === 'new' ? 'bg-white shadow text-indigo-600' : 'text-gray-500'" class="flex-1 py-1.5 text-sm font-bold rounded-md transition">Buat Baru</button>
                        </div>
                        
                        <div x-show="tab === 'existing'">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Komponen</label>
                            <select name="component_id" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">-- Pilih --</option>
                                <?php foreach($components_list as $cl): ?>
                                    <option value="<?= $cl['id'] ?>"><?= $cl['component_name'] ?> (<?= ucfirst($cl['component_type']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div x-show="tab === 'new'">
                            <div class="mb-3">
                                <label class="block text-sm font-bold text-gray-700 mb-1">Nama Komponen</label>
                                <input type="text" name="new_component_name" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Contoh: Tunjangan Shift">
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm font-bold text-gray-700 mb-1">Jenis</label>
                                <div class="flex gap-4">
                                    <label class="inline-flex items-center"><input type="radio" name="new_component_type" value="earning" class="text-indigo-600" checked><span class="ml-2 text-sm">Penerimaan</span></label>
                                    <label class="inline-flex items-center"><input type="radio" name="new_component_type" value="deduction" class="text-red-600"><span class="ml-2 text-sm">Potongan</span></label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nominal (Rp)</label>
                            <input type="text" name="amount" class="w-full border-gray-300 rounded-md shadow-sm font-mono text-lg font-bold" placeholder="0" onkeyup="this.value=this.value.replace(/\./g,'').replace(/\B(?=(\d{3})+(?!\d))/g,'.')" required>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                        <button type="button" @click="modalComponent = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>