<div class="space-y-4">
    <h4 class="text-sm font-bold text-gray-700 uppercase mb-4">Riwayat Approval</h4>

    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
        <h5 class="text-sm font-bold text-yellow-800 mb-1">Status Saat Ini: <?= ucfirst($task['approval_status'] ?? 'None') ?></h5>
        <p class="text-xs text-yellow-700">
            <?= $task['approval_notes'] ?? 'Menunggu review manager...' ?>
        </p>
    </div>

    <?php if (!empty($approval_history)): ?>
        <div class="space-y-3">
            <?php foreach ($approval_history as $hist): ?>
            <div class="flex items-start gap-3 p-3 bg-white border border-gray-100 rounded-lg shadow-sm">
                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold">
                    <?= substr($hist['reviewer_name'] ?? 'S', 0, 1) ?>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-800">
                        <?= $hist['action'] == 'approved' ? '<span class="text-green-600">Approved</span>' : '<span class="text-red-600">Rejected</span>' ?>
                        by <?= $hist['reviewer_name'] ?>
                    </p>
                    <p class="text-xs text-gray-500 mb-1"><?= date('d M Y H:i', strtotime($hist['created_at'])) ?></p>
                    <p class="text-sm text-gray-600 italic">"<?= $hist['notes'] ?>"</p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>