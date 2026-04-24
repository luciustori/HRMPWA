<div class="flex flex-col h-[500px]">
    <div class="flex-1 overflow-y-auto space-y-4 pr-2 mb-4">
        <?php foreach ($comments as $comment): ?>
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center font-bold text-xs"><?= substr($comment['user_name'],0,1) ?></div>
                <div class="bg-gray-50 p-3 rounded-lg flex-1">
                    <div class="flex justify-between mb-1"><span class="font-bold text-sm"><?= $comment['user_name'] ?></span><span class="text-xs text-gray-400"><?= date('d M H:i', strtotime($comment['created_at'])) ?></span></div>
                    <p class="text-sm"><?= htmlspecialchars($comment['comment']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="border-t pt-4">
        <form onsubmit="submitComment(event)" class="relative">
            <textarea rows="3" class="w-full border border-gray-300 rounded-lg p-3 text-sm pr-12" placeholder="Tulis komentar..." required></textarea>
            <button type="submit" class="absolute bottom-3 right-3 text-blue-600 hover:text-blue-800 p-2"><i class="fas fa-paper-plane"></i></button>
        </form>
    </div>
</div>