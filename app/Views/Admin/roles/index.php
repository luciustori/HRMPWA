<style>
    /* Toggle Switch Style */
    .switch { position: relative; display: inline-block; width: 44px; height: 24px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #CBD5E1; transition: .3s; border-radius: 34px; }
    .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%; }
    input:checked + .slider { background-color: #2563EB; }
    input:checked + .slider:before { transform: translateX(20px); }
</style>

<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Access Control</h1>
        <p class="text-slate-500 text-sm mt-1">Atur hak akses untuk setiap jabatan.</p>
    </div>

    <div class="bg-white p-1 rounded-xl border border-slate-200 inline-flex shadow-sm overflow-x-auto">
        <?php foreach ($data['roles'] as $role) : ?>
            <a href="<?= BASEURL ?>/admin/roles?role_id=<?= $role['id'] ?>" 
               class="px-5 py-2 rounded-lg text-sm font-bold transition-all whitespace-nowrap
               <?= ($data['selected_role_id'] == $role['id']) ? 'bg-slate-900 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50' ?>">
                <?= $role['role_name'] ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<form action="<?= BASEURL ?>/admin/roles/update" method="POST">
    <input type="hidden" name="role_id" value="<?= $data['selected_role_id'] ?>">

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-20">
        <?php foreach ($data['permissions_grouped'] as $module => $permissions) : ?>
            <div class="bg-white rounded-2xl border border-slate-200 p-6 hover:shadow-lg transition-shadow">
                <div class="flex justify-between items-center mb-5 pb-4 border-b border-dashed border-slate-200">
                    <h3 class="font-bold text-slate-700 capitalize"><?= $module ?></h3>
                </div>
                
                <div class="space-y-4">
                    <?php foreach ($permissions as $perm) : ?>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-slate-600"><?= $perm['permission_name'] ?></span>
                            
                            <label class="switch scale-75">
                                <input type="checkbox" name="permissions[]" value="<?= $perm['id'] ?>"
                                       <?= in_array($perm['id'], $data['active_permissions']) ? 'checked' : '' ?>>
                                <span class="slider"></span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="fixed bottom-6 right-6 z-40">
        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white px-6 py-4 rounded-full shadow-2xl flex items-center gap-3 font-bold transition-transform hover:scale-105">
            <i class="fas fa-save"></i>
            <span>Simpan Perubahan</span>
        </button>
    </div>
</form>