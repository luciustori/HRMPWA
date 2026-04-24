<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Template Slip Gaji</h1>
            <p class="text-sm text-gray-500">Kelola desain slip gaji sesuai kebutuhan.</p>
        </div>
        <a href="<?= BASEURL ?>/admin/paysliptemplates/create" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 transition">
            <i class="fas fa-plus mr-2"></i> Template Baru
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach($templates as $t): ?>
        <div class="bg-white rounded-xl shadow-sm border <?= $t['is_active'] ? 'border-indigo-500 ring-2 ring-indigo-100' : 'border-gray-200' ?> p-5 relative overflow-hidden group">
            
            <?php if($t['is_active']): ?>
                <div class="absolute top-0 right-0 bg-indigo-600 text-white text-xs font-bold px-3 py-1 rounded-bl-lg z-10">AKTIF</div>
            <?php endif; ?>

            <div class="h-32 bg-gray-100 rounded-lg mb-4 flex items-center justify-center border border-gray-100">
                <i class="fas fa-file-invoice-dollar text-4xl text-gray-300"></i>
            </div>
            
            <h3 class="font-bold text-lg text-gray-800 mb-1"><?= $t['template_name'] ?></h3>
            <p class="text-xs text-gray-500 mb-4">Last update: <?= date('d M Y', strtotime($t['updated_at'])) ?></p>

            <div class="flex items-center gap-2 pt-3 border-t border-gray-100">
                <?php if(!$t['is_active']): ?>
                    <a href="<?= BASEURL ?>/admin/paysliptemplates/set_active/<?= $t['id'] ?>" class="flex-1 py-2 text-center text-xs font-bold bg-green-50 text-green-700 rounded hover:bg-green-100">
                        <i class="fas fa-check-circle mr-1"></i> Pakai
                    </a>
                <?php else: ?>
                    <button disabled class="flex-1 py-2 text-center text-xs font-bold bg-gray-100 text-gray-400 rounded cursor-not-allowed">
                        Sedang Dipakai
                    </button>
                <?php endif; ?>
                
                <a href="<?= BASEURL ?>/admin/paysliptemplates/edit/<?= $t['id'] ?>" class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded" title="Edit HTML">
                    <i class="fas fa-code"></i>
                </a>
                
                <?php if(!$t['is_active']): ?>
                    <a href="<?= BASEURL ?>/admin/paysliptemplates/delete/<?= $t['id'] ?>" onclick="return confirm('Hapus template ini?')" class="px-3 py-2 text-red-600 hover:bg-red-50 rounded" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>