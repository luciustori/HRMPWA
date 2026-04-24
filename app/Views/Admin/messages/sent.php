<div class="max-w-[1600px] mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pesan Terkirim</h1>
            <p class="text-gray-500 text-sm">Riwayat pesan yang Anda kirim.</p>
        </div>
        <a href="<?= BASEURL ?>/admin/messages/create" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition flex items-center gap-2">
            <i class="fas fa-pen"></i> Tulis Pesan
        </a>
    </div>

    <div class="flex space-x-1 bg-gray-100 p-1 rounded-xl w-fit mb-6">
        <a href="<?= BASEURL ?>/admin/messages" class="px-5 py-2 rounded-lg text-sm font-bold text-gray-500 hover:text-gray-700">Inbox</a>
        <a href="<?= BASEURL ?>/admin/messages/sent" class="px-5 py-2 rounded-lg text-sm font-bold bg-white text-indigo-600 shadow-sm">Terkirim</a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <?php if(empty($messages)): ?>
            <div class="p-10 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400 text-2xl">
                    <i class="far fa-paper-plane"></i>
                </div>
                <p class="text-gray-500 font-medium">Belum ada pesan terkirim.</p>
            </div>
        <?php else: ?>
            <ul class="divide-y divide-gray-100">
                <?php foreach($messages as $row): 
                    $recipientName = $row['first_name'] ? $row['first_name'] . ' ' . $row['last_name'] : $row['recipient_username'];
                ?>
                <li>
                    <a href="<?= BASEURL ?>/admin/messages/show?id=<?= $row['id'] ?>" class="block p-5 hover:bg-gray-50 transition bg-white">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4 flex-1">
                                <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-sm font-bold shadow-sm flex-shrink-0">
                                    <i class="fas fa-arrow-right transform -rotate-45"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm text-gray-900 font-bold truncate">
                                        Kepada: <?= htmlspecialchars($recipientName) ?>
                                    </p>
                                    <p class="text-sm text-gray-600 font-normal truncate">
                                        <?= htmlspecialchars($row['subject']) ?>
                                    </p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="text-xs text-gray-400">
                                    <?= date('d M H:i', strtotime($row['created_at'])) ?>
                                </span>
                                <?php if($row['attachment']): ?>
                                    <div class="mt-1"><i class="fas fa-paperclip text-gray-300 text-xs"></i></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>