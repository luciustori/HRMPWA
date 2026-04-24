<style>
    /* Toggle Switch Base */
    .switch { position: relative; display: inline-block; width: 44px; height: 24px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #CBD5E1; transition: .3s; border-radius: 34px; }
    .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    
    /* State: Active (Extra Permission - BLUE) */
    input:checked + .slider { background-color: #2563EB; }
    input:checked + .slider:before { transform: translateX(20px); }

    /* State: Locked (Role Permission - GREEN & LOCKED) */
    input.role-locked:checked + .slider { background-color: #10B981; opacity: 0.6; cursor: not-allowed; } 
    input.role-locked:checked + .slider:before { background-color: #f0fdf4; }
</style>

<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <a href="<?= BASEURL ?>/admin/useraccess" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-800 text-sm font-bold mb-4 transition-colors">
            <i class="fas fa-arrow-left"></i> Kembali ke List
        </a>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">
                    Akses Spesifik: <span class="text-blue-600"><?= $data['user']['full_name'] ?></span>
                </h1>
                <p class="text-slate-500 text-sm mt-1">
                    Role Utama: <span class="font-bold bg-slate-100 px-2 py-0.5 rounded text-slate-700"><?= $data['user']['role_name'] ?? ucfirst($data['user']['role']) ?></span>
                </p>
            </div>
            
            <div class="flex items-center gap-4 text-xs font-bold bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 opacity-60"></span>
                    <span class="text-emerald-700">Bawaan Role (Terkunci)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                    <span class="text-blue-700">Akses Tambahan</span>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['flash'])) : ?>
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-3">
            <i class="fas fa-check-circle text-lg"></i>
            <?= $_SESSION['flash']['message']; unset($_SESSION['flash']); ?>
        </div>
    <?php endif; ?>

    <form action="<?= BASEURL ?>/admin/useraccess/update" method="POST">
        <input type="hidden" name="user_id" value="<?= $data['user']['id'] ?>">

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-24">
            
            <?php foreach ($data['permissions_grouped'] as $module => $permissions) : ?>
                <div class="bg-white rounded-2xl border border-slate-200 p-6 hover:shadow-lg transition-all duration-300">
                    <div class="flex justify-between items-center mb-5 pb-4 border-b border-dashed border-slate-200">
                        <h3 class="font-bold text-slate-800 capitalize flex items-center gap-2">
                            <?php 
                                $icon = 'fa-cube';
                                if(str_contains($module, 'employee')) $icon = 'fa-users';
                                if(str_contains($module, 'attendance')) $icon = 'fa-clock';
                                if(str_contains($module, 'payroll')) $icon = 'fa-money-bill';
                                if(str_contains($module, 'report')) $icon = 'fa-chart-pie';
                            ?>
                            <i class="fas <?= $icon ?> text-slate-300"></i>
                            <?= $module ?>
                        </h3>
                    </div>
                    
                    <div class="space-y-5">
                        <?php foreach ($permissions as $perm) : ?>
                            <div class="flex justify-between items-center group">
                                <div class="pr-4">
                                    <div class="text-sm font-bold text-slate-700 group-hover:text-blue-700 transition-colors">
                                        <?= $perm['permission_name'] ?>
                                    </div>
                                    <?php if($perm['is_role_access']): ?>
                                        <div class="text-[10px] text-emerald-500 font-bold mt-0.5 flex items-center gap-1">
                                            <i class="fas fa-lock"></i> Default Role
                                        </div>
                                    <?php elseif($perm['is_extra_access']): ?>
                                        <div class="text-[10px] text-blue-500 font-bold mt-0.5">
                                            + Extra Access
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <label class="switch scale-90 flex-shrink-0">
                                    <?php if ($perm['is_role_access']) : ?>
                                        <input type="checkbox" checked disabled class="role-locked">
                                        <span class="slider"></span>
                                    <?php else : ?>
                                        <input type="checkbox" name="extra_permissions[]" value="<?= $perm['id'] ?>"
                                               <?= $perm['is_extra_access'] ? 'checked' : '' ?>>
                                        <span class="slider"></span>
                                    <?php endif; ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>

        <div class="fixed bottom-6 right-6 z-40 animate-bounce-slow">
            <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white px-8 py-4 rounded-full shadow-2xl flex items-center gap-3 font-bold transition-transform hover:scale-105 border border-slate-700">
                <i class="fas fa-save text-yellow-400"></i>
                <span>Simpan Perubahan Akses</span>
            </button>
        </div>
    </form>
</div>