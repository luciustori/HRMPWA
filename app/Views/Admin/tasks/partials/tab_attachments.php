<div class="space-y-4">
    <div class="flex justify-between items-center mb-4">
        <h4 class="text-sm font-bold text-gray-700 uppercase">Lampiran File</h4>
        <?php if ($can_edit): ?>
        <button class="text-xs bg-gray-100 text-gray-700 px-3 py-1 rounded-full font-bold hover:bg-gray-200 transition">
            <i class="fas fa-upload mr-1"></i> Upload
        </button>
        <?php endif; ?>
    </div>

    <?php if (empty($attachments)): ?>
        <div class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center">
            <i class="fas fa-cloud-upload-alt text-gray-300 text-3xl mb-2"></i>
            <p class="text-sm text-gray-500">Tidak ada file dilampirkan.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 gap-3">
            <?php foreach ($attachments as $file): ?>
            <div class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                <div class="w-10 h-10 bg-red-100 text-red-500 rounded flex items-center justify-center text-lg">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate"><?= $file['file_name'] ?></p>
                    <p class="text-xs text-gray-500"><?= $file['uploaded_by_name'] ?> • <?= date('d M Y', strtotime($file['uploaded_at'])) ?></p>
                </div>
                <a href="#" class="text-gray-400 hover:text-blue-600 px-2"><i class="fas fa-download"></i></a>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>