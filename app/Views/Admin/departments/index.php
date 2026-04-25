<div x-data="window.departmentsApp()" class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600"><i class="ri-building-4-line text-2xl"></i></div>
            <div><p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Departemen</p><h3 class="text-2xl font-bold text-slate-800"><?= $data['stats']['total_dept'] ?></h3></div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600"><i class="ri-node-tree text-2xl"></i></div>
            <div><p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Total Divisi</p><h3 class="text-2xl font-bold text-slate-800"><?= $data['stats']['total_div'] ?></h3></div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600"><i class="ri-team-line text-2xl"></i></div>
            <div><p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Total Karyawan</p><h3 class="text-2xl font-bold text-slate-800"><?= $data['stats']['total_emp'] ?></h3></div>
        </div>
    </div>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Struktur Organisasi</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola hierarki departemen dan divisi perusahaan.</p>
        </div>
        <button @click="modalCreateDept = true" class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-medium shadow-lg shadow-indigo-200 transition-all active:scale-95">
            <i class="ri-add-line text-lg"></i>
            <span>Tambah Dept</span>
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[11px] border-b border-slate-100">
                <tr>
                    <th class="p-4 text-center w-12">#</th>
                    <th class="p-4">Departemen</th>
                    <th class="p-4">Manager (HOD)</th>
                    <th class="p-4">Direktur (BOD)</th>
                    <th class="p-4">Kapasitas</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($data['departments'] as $dept): ?>
                    
                    <tr class="hover:bg-slate-50/80 transition-colors group cursor-pointer" @click="toggleRow(<?= $dept['id'] ?>)">
                        
                        <td class="px-6 py-4 text-center">
                            <div class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-400 group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-all">
                                <i class="ri-arrow-right-s-line text-lg transition-transform duration-300"
                                   :class="isExpanded(<?= $dept['id'] ?>) ? 'rotate-90' : ''"></i>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center font-bold text-sm text-indigo-600 shadow-sm">
                                    <?= substr($dept['department_name'], 0, 2) ?>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm"><?= $dept['department_name'] ?></h4>
                                    <span class="text-[10px] font-mono text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200"><?= $dept['department_code'] ?></span>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs shadow-sm">
                                    <?= substr($dept['manager_name'] ?? '?', 0, 1) ?>
                                </div>
                                <div class="font-bold text-slate-800 text-xs"><?= $dept['manager_name'] ?? '<span class="text-slate-400 font-normal">Belum Set</span>' ?></div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center justify-between group bg-slate-50/50 p-2 rounded-xl border border-transparent hover:border-emerald-100 hover:bg-white transition-all cursor-default">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold text-[10px]">
                                        <i class="ri-shield-user-line"></i>
                                    </div>
                                    <div class="text-[11px] font-bold text-slate-700">
                                        <?= $dept['director_name'] ?? '<span class="text-slate-400 font-normal italic">Belum Ditentukan</span>' ?>
                                    </div>
                                </div>
                                
                                <button onclick="openDirectorModal(<?= $dept['id'] ?>, '<?= $dept['department_name'] ?>', '<?= $dept['director_id'] ?>')" 
                                        class="w-7 h-7 flex items-center justify-center rounded-lg text-emerald-400 hover:bg-emerald-50 hover:text-emerald-600 transition-all opacity-0 group-hover:opacity-100" 
                                        title="Atur Direksi">
                                    <i class="ri-edit-2-line text-sm"></i>
                                </button>
                            </div>
                        </td>

                        <td class="px-6 py-4 w-48">
                            <div class="w-full bg-slate-100 rounded-full h-1.5 mb-2 overflow-hidden">
                                <div class="bg-indigo-500 h-1.5 rounded-full" style="width: <?= min(100, ($dept['total_employees']/20)*100) ?>%"></div>
                            </div>
                            <div class="flex justify-between text-[10px] font-medium text-slate-500">
                                <span><?= $dept['total_employees'] ?> Karyawan</span>
                                <span><?= $dept['total_divisions'] ?> Divisi</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="w-2.5 h-2.5 rounded-full inline-block <?= $dept['is_active'] ? 'bg-emerald-500 shadow-lg shadow-emerald-200' : 'bg-rose-500' ?>"></span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1" @click.stop> <button type="button" @click='openEditDept(<?= htmlspecialchars(json_encode($dept), ENT_QUOTES, 'UTF-8') ?>)' 
                                        class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">
                                    <i class="ri-pencil-line text-lg"></i>
                                </button>
                                <a href="<?= BASEURL ?>/admin/departments/delete/<?= $dept['id'] ?>" 
                                   onclick="return confirm('Hapus Departemen beserta isinya?')"
                                   class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
                                    <i class="ri-delete-bin-line text-lg"></i>
                                </a>
                            </div>
                        </td>
                    </tr>

                    <tr x-show="isExpanded(<?= $dept['id'] ?>)" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                        <td colspan="6" class="p-0 border-b border-indigo-50 bg-slate-50/50">
                            <div class="px-6 py-4 pl-20"> <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                                    <div class="bg-slate-50 px-4 py-2 border-b border-slate-100 flex justify-between items-center">
                                        <h5 class="text-xs font-bold text-slate-500 uppercase tracking-wider flex items-center gap-2">
                                            <i class="ri-git-branch-line"></i> Daftar Divisi
                                        </h5>
                                        <button @click="openCreateDiv(<?= $dept['id'] ?>, '<?= htmlspecialchars($dept['department_name'], ENT_QUOTES) ?>')" 
                                                class="text-xs bg-white border border-indigo-200 text-indigo-600 px-3 py-1.5 rounded-lg hover:bg-indigo-50 hover:border-indigo-300 transition-all font-medium flex items-center gap-1 shadow-sm">
                                            <i class="ri-add-circle-fill"></i> Divisi Baru
                                        </button>
                                    </div>

                                    <table class="w-full text-sm">
                                        <thead class="bg-white border-b border-slate-100 text-xs text-slate-400 uppercase">
                                            <tr>
                                                <th class="px-4 py-2 text-left font-semibold w-24">Kode</th>
                                                <th class="px-4 py-2 text-left font-semibold">Nama Divisi</th>
                                                <th class="px-4 py-2 text-left font-semibold">Koordinator</th>
                                                <th class="px-4 py-2 text-center font-semibold w-24">Status</th>
                                                <th class="px-4 py-2 text-right font-semibold w-24">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-50">
                                            <?php if(empty($dept['divisions_list'])): ?>
                                                <tr>
                                                    <td colspan="5" class="px-4 py-6 text-center text-slate-400 italic">
                                                        <div class="flex flex-col items-center">
                                                            <i class="ri-inbox-line text-2xl mb-1 text-slate-300"></i>
                                                            <span>Belum ada divisi di departemen ini.</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($dept['divisions_list'] as $div): ?>
                                                <tr class="hover:bg-indigo-50/30 transition-colors group/child">
                                                    <td class="px-4 py-3 font-mono text-xs text-slate-500 bg-slate-50/50">
                                                        <?= $div['division_code'] ?>
                                                    </td>
                                                    <td class="px-4 py-3 font-medium text-slate-700">
                                                        <?= $div['division_name'] ?>
                                                        <?php if($div['description']): ?>
                                                            <p class="text-[10px] text-slate-400 font-normal truncate max-w-xs"><?= $div['description'] ?></p>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <?php if($div['coordinator_name']): ?>
                                                            <div class="flex items-center gap-2">
                                                                <div class="w-6 h-6 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-[10px] font-bold ring-1 ring-purple-200">
                                                                    <?= substr($div['coordinator_name'], 0, 1) ?>
                                                                </div>
                                                                <span class="text-xs text-slate-600"><?= $div['coordinator_name'] ?></span>
                                                            </div>
                                                        <?php else: ?>
                                                            <span class="text-[10px] text-slate-400 border border-dashed border-slate-300 px-2 py-0.5 rounded-full">Belum diset</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="px-4 py-3 text-center">
                                                        <?php if($div['is_active']): ?>
                                                            <span class="text-[10px] px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-full font-medium border border-emerald-200">Aktif</span>
                                                        <?php else: ?>
                                                            <span class="text-[10px] px-2 py-0.5 bg-slate-100 text-slate-500 rounded-full border border-slate-200">Nonaktif</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="px-4 py-3 text-right">
                                                        <div class="flex justify-end gap-1 opacity-0 group-hover/child:opacity-100 transition-opacity">
                                                            <button type="button" @click='openEditDiv(<?= htmlspecialchars(json_encode($div), ENT_QUOTES, 'UTF-8') ?>)' 
                                                                    class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-white rounded border border-transparent hover:border-slate-200 shadow-sm transition-all">
                                                                <i class="ri-pencil-fill"></i>
                                                            </button>
                                                            <a href="<?= BASEURL ?>/admin/departments/delete_division/<?= $div['id'] ?>" 
                                                               onclick="return confirm('Hapus Divisi permanen?')" 
                                                               class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-white rounded border border-transparent hover:border-slate-200 shadow-sm transition-all">
                                                                <i class="ri-delete-bin-fill"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </td>
                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div x-show="modalCreateDept" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900 bg-opacity-75" @click="modalCreateDept = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block w-full overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg">
                <form action="<?= BASEURL ?>/admin/departments/store" method="POST">
                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                        <h3 class="mb-4 text-lg font-bold text-slate-900">Departemen Baru</h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-1">
                                    <label class="block mb-1 text-xs font-bold uppercase text-slate-500">Kode</label>
                                    <input type="text" name="department_code" required class="w-full px-3 py-2 uppercase border rounded-lg border-slate-300 focus:ring-2 focus:ring-indigo-500" placeholder="IT">
                                </div>
                                <div class="col-span-2">
                                    <label class="block mb-1 text-xs font-bold uppercase text-slate-500">Nama</label>
                                    <input type="text" name="department_name" required class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-bold uppercase text-slate-500">Manager</label>
                                <select name="manager_id" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-indigo-500">
                                    <option value="">-- Pilih Manager --</option>
                                    <?php foreach ($data['employees'] as $emp): ?>
                                        <option value="<?= $emp['id'] ?>"><?= $emp['first_name'] . ' ' . $emp['last_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-bold uppercase text-slate-500">Deskripsi</label>
                                <textarea name="description" rows="2" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-indigo-500"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-slate-50 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-xl shadow-sm hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                        <button type="button" @click="modalCreateDept = false" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium bg-white border rounded-xl border-slate-300 text-slate-700 shadow-sm hover:bg-slate-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div x-show="modalEditDept" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900 bg-opacity-75" @click="modalEditDept = false"></div>
            <div class="inline-block w-full overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg">
                <form action="<?= BASEURL ?>/admin/departments/update" method="POST">
                    <input type="hidden" name="id" :value="editDeptId">
                    <input type="hidden" name="is_active" value="1">
                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6">
                        <h3 class="mb-4 text-lg font-bold text-slate-900">Edit Departemen</h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-1">
                                    <label class="block mb-1 text-xs font-bold uppercase text-slate-500">Kode</label>
                                    <input type="text" name="department_code" x-model="editDeptCode" required class="w-full px-3 py-2 uppercase border rounded-lg border-slate-300 focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div class="col-span-2">
                                    <label class="block mb-1 text-xs font-bold uppercase text-slate-500">Nama</label>
                                    <input type="text" name="department_name" x-model="editDeptName" required class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-bold uppercase text-slate-500">Manager</label>
                                <select name="manager_id" x-model="editDeptManager" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-indigo-500">
                                    <option value="">-- Tidak Ada --</option>
                                    <?php foreach ($data['employees'] as $emp): ?>
                                        <option value="<?= $emp['id'] ?>"><?= $emp['first_name'] . ' ' . $emp['last_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-bold uppercase text-slate-500">Deskripsi</label>
                                <textarea name="description" x-model="editDeptDesc" rows="2" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-indigo-500"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-slate-50 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-xl shadow-sm hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">Update</button>
                        <button type="button" @click="modalEditDept = false" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium bg-white border rounded-xl border-slate-300 text-slate-700 shadow-sm hover:bg-slate-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div x-show="modalCreateDiv" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900 bg-opacity-75" @click="modalCreateDiv = false"></div>
            <div class="inline-block w-full overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg">
                <form :action="'<?= BASEURL ?>/admin/departments/create_division/' + parentDeptId" method="POST">
                    <input type="hidden" name="department_id" :value="parentDeptId">
                    
                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6">
                        <div class="flex items-center gap-3 p-3 mb-4 border rounded-lg bg-indigo-50 border-indigo-100">
                            <i class="text-indigo-500 ri-building-4-line"></i>
                            <div>
                                <p class="text-xs font-bold uppercase text-slate-500">Parent Dept</p>
                                <p class="font-bold text-indigo-700" x-text="parentDeptName"></p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-1">
                                    <label class="block mb-1 text-xs font-bold uppercase text-slate-500">Kode Div</label>
                                    <input type="text" name="division_code" required class="w-full px-3 py-2 uppercase border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500">
                                </div>
                                <div class="col-span-2">
                                    <label class="block mb-1 text-xs font-bold uppercase text-slate-500">Nama Divisi</label>
                                    <input type="text" name="division_name" required class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500">
                                </div>
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-bold uppercase text-slate-500">Koordinator</label>
                                <select name="coordinator_id" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500">
                                    <option value="">-- Pilih Koordinator --</option>
                                    <?php foreach ($data['employees'] as $emp): ?>
                                        <option value="<?= $emp['id'] ?>"><?= $emp['first_name'] . ' ' . $emp['last_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                             <div>
                                <label class="block mb-1 text-xs font-bold uppercase text-slate-500">Deskripsi</label>
                                <textarea name="description" rows="2" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-slate-50 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-purple-600 border border-transparent rounded-xl shadow-sm hover:bg-purple-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan Divisi</button>
                        <button type="button" @click="modalCreateDiv = false" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium bg-white border rounded-xl border-slate-300 text-slate-700 shadow-sm hover:bg-slate-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div x-show="modalEditDiv" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900 bg-opacity-75" @click="modalEditDiv = false"></div>
            <div class="inline-block w-full overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg">
                <form :action="'<?= BASEURL ?>/admin/departments/edit_division/' + editDivId" method="POST">
                    <input type="hidden" name="department_id" :value="editDivDeptId">
                    <input type="hidden" name="is_active" value="1">

                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6">
                        <h3 class="mb-4 text-lg font-bold text-slate-900">Edit Divisi</h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-1">
                                    <label class="block mb-1 text-xs font-bold uppercase text-slate-500">Kode</label>
                                    <input type="text" name="division_code" x-model="editDivCode" required class="w-full px-3 py-2 uppercase border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500">
                                </div>
                                <div class="col-span-2">
                                    <label class="block mb-1 text-xs font-bold uppercase text-slate-500">Nama Divisi</label>
                                    <input type="text" name="division_name" x-model="editDivName" required class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500">
                                </div>
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-bold uppercase text-slate-500">Koordinator</label>
                                <select name="coordinator_id" x-model="editDivCoord" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500">
                                    <option value="">-- Pilih Koordinator --</option>
                                    <?php foreach ($data['employees'] as $emp): ?>
                                        <option value="<?= $emp['id'] ?>"><?= $emp['first_name'] . ' ' . $emp['last_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-bold uppercase text-slate-500">Deskripsi</label>
                                <textarea name="description" x-model="editDivDesc" rows="2" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-slate-50 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-purple-600 border border-transparent rounded-xl shadow-sm hover:bg-purple-700 sm:ml-3 sm:w-auto sm:text-sm">Update Divisi</button>
                        <button type="button" @click="modalEditDiv = false" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium bg-white border rounded-xl border-slate-300 text-slate-700 shadow-sm hover:bg-slate-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="modalDirector" class="fixed inset-0 z-[999] hidden">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm shadow-2xl transition-opacity"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-indigo-600 text-white">
                    <div>
                        <h3 class="font-bold text-lg">Set Direktur Pembina</h3>
                        <p class="text-indigo-100 text-xs" id="display_dept_name"></p>
                    </div>
                    <button onclick="closeDirectorModal()" class="text-white/50 hover:text-white transition">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>
                
                <form action="<?= BASEURL ?>/admin/departments/update_director" method="POST" class="p-6">
                    <input type="hidden" name="id" id="modal_dept_id">
                    
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Pilih Direktur Pembina (BOD)</label>
                        <select name="director_id" id="modal_director_select" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition">
                        <option value="">-- Tidak Ada / Lepas Jabatan --</option>
                        <?php if(isset($data['employees'])): ?>
                            <?php foreach ($data['employees'] as $emp): ?>
                                <?php 
                                    // Ambil levelnya, jadikan huruf kecil semua biar aman pas dicek
                                    $level = strtolower($emp['employee_level'] ?? ''); 
                                    
                                    // Jika di dalam level ada kata 'direktur' atau 'direksi', tampilkan!
                                    if(str_contains($level, 'direktur') || str_contains($level, 'direksi')): 
                                ?>
                                    <option value="<?= $emp['id'] ?>">
                                        <?= $emp['first_name'] . ' ' . $emp['last_name'] ?> (<?= $emp['employee_number'] ?? '' ?>)
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                        <p class="mt-2 text-[10px] text-slate-400 italic leading-relaxed">
                            *Direktur yang dipilih akan memiliki wewenang approval akhir (Tupoksi) untuk seluruh divisi di departemen ini.
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="closeDirectorModal()" class="flex-1 px-4 py-3 border border-slate-200 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-50 transition">Batal</button>
                        <button type="submit" class="flex-1 px-4 py-3 bg-indigo-600 text-white rounded-xl font-bold text-sm shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    // ======================================================
    // 1. ALPINE.JS / APP COMPONENT (Untuk Dept & Divisi)
    // ======================================================
    window.departmentsApp = function() {
        return {
            // --- MODAL STATE ---
            modalCreateDept: false,
            modalEditDept: false,
            modalCreateDiv: false,
            modalEditDiv: false,

            // --- EXPANDED ROWS STATE ---
            expandedRows: [], 

            // --- DEPARTEMEN FORM DATA ---
            editDeptId: '',
            editDeptName: '',
            editDeptCode: '',
            editDeptManager: '',
            editDeptDesc: '',

            // --- DIVISI FORM DATA ---
            parentDeptId: '',
            parentDeptName: '',
            editDivId: '',
            editDivName: '',
            editDivCode: '',
            editDivCoord: '',
            editDivDesc: '',
            editDivDeptId: '', 

            // --- ACTIONS ---
            toggleRow(id) {
                if (this.expandedRows.includes(id)) {
                    this.expandedRows = this.expandedRows.filter(rowId => rowId !== id);
                } else {
                    this.expandedRows.push(id);
                }
            },

            isExpanded(id) {
                return this.expandedRows.includes(id);
            },

            openEditDept(dept) {
                this.editDeptId = dept.id;
                this.editDeptName = dept.department_name;
                this.editDeptCode = dept.department_code;
                this.editDeptManager = dept.manager_id ? dept.manager_id : '';
                this.editDeptDesc = dept.description;
                this.modalEditDept = true;
            },

            openCreateDiv(deptId, deptName) {
                this.parentDeptId = deptId;
                this.parentDeptName = deptName;
                this.modalCreateDiv = true;
            },

            openEditDiv(div) {
                this.editDivId = div.id;
                this.editDivName = div.division_name;
                this.editDivCode = div.division_code;
                this.editDivCoord = div.coordinator_id ? div.coordinator_id : '';
                this.editDivDesc = div.description;
                this.editDivDeptId = div.department_id;
                this.modalEditDiv = true;
            }
        };
    }; // <--- KURUNG TUTUP departmentsApp HARUS DI SINI!

    // ======================================================
    // 2. VANILLA JS (Untuk Modal Direktur - Harus Global!)
    // ======================================================
    
    function openDirectorModal(deptId, deptName, currentDirectorId) {
        const modal = document.getElementById('modalDirector');
        document.getElementById('modal_dept_id').value = deptId;
        document.getElementById('display_dept_name').innerText = "Departemen: " + deptName;
        
        // Set value dropdown jika sudah ada direkturnya
        const select = document.getElementById('modal_director_select');
        select.value = currentDirectorId || "";
        
        modal.classList.remove('hidden');
    }

    function closeDirectorModal() {
        document.getElementById('modalDirector').classList.add('hidden');
    }

    // Close modal saat klik area luar
    window.onclick = function(event) {
        const modal = document.getElementById('modalDirector');
        if (modal && event.target == modal.firstElementChild) {
            closeDirectorModal();
        }
    }
</script>