<div class="space-y-4">
    <h4 class="text-sm font-bold text-gray-700 uppercase mb-4">Aktivitas Terbaru</h4>
    
    <div class="relative border-l-2 border-gray-200 ml-3 space-y-6">
        <?php foreach ($history as $log): ?>
        <div class="mb-8 ml-6 relative">
            <span class="absolute -left-[31px] bg-white border-2 border-gray-200 rounded-full w-4 h-4 mt-1.5"></span>
            <div class="flex flex-col">
                <span class="text-sm font-semibold text-gray-800"><?= $log['user_name'] ?></span>
                <span class="text-sm text-gray-600"><?= $log['description'] ?></span>
                <span class="text-xs text-gray-400 mt-1"><?= date('d M Y, H:i', strtotime($log['created_at'])) ?></span>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if (empty($history)): ?>
            <div class="ml-6 text-sm text-gray-400 italic">Belum ada riwayat aktivitas.</div>
        <?php endif; ?>
    </div>
</div>