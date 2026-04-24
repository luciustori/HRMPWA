<div class="max-w-[1600px] mx-auto p-6">
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kotak Masuk</h1>
            <p class="text-gray-500 text-sm">Pesan internal dari rekan kerja.</p>
        </div>
        <a href="<?= BASEURL ?>/admin/messages/create" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition transform hover:-translate-y-0.5 flex items-center gap-2">
            <i class="fas fa-pen"></i> Tulis Pesan
        </a>
    </div>

    <div class="flex space-x-1 bg-gray-100 p-1 rounded-xl w-fit mb-6">
        <a href="<?= BASEURL ?>/admin/messages" class="px-5 py-2 rounded-lg text-sm font-bold bg-white text-indigo-600 shadow-sm transition">
            Inbox
        </a>
        <a href="<?= BASEURL ?>/admin/messages/sent" class="px-5 py-2 rounded-lg text-sm font-bold text-gray-500 hover:text-gray-700 transition">
            Terkirim
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        
        <?php if(empty($messages)): ?>
            <div class="p-10 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400 text-2xl">
                    <i class="far fa-envelope-open"></i>
                </div>
                <h3 class="text-gray-800 font-bold">Inbox Kosong</h3>
                <p class="text-gray-500 text-sm mt-1">Belum ada pesan masuk saat ini.</p>
            </div>
        <?php else: ?>
            <ul class="divide-y divide-gray-100">
                <?php foreach($messages as $row): 
                    // Logic Visual Read/Unread
                    $isRead = $row['is_read'] == 1;
                    $bgClass = $isRead ? 'bg-white hover:bg-gray-50' : 'bg-indigo-50/40 hover:bg-indigo-50';
                    $fontSubject = $isRead ? 'font-normal text-gray-600' : 'font-bold text-gray-900';
                    $fontName = $isRead ? 'font-semibold text-gray-700' : 'font-bold text-gray-900';
                    
                    // Logic Nama Pengirim (Prioritas: Nama Asli > Username)
                    $senderName = !empty($row['first_name']) ? $row['first_name'] . ' ' . $row['last_name'] : $row['sender_username'];
                    $initial = strtoupper(substr($senderName, 0, 1));
                ?>
                <li>
                    <a href="<?= BASEURL ?>/admin/messages/show?id=<?= $row['id'] ?>" class="block p-5 transition duration-200 <?= $bgClass ?>">
                        <div class="flex items-center justify-between gap-4">
                            
                            <div class="flex items-center gap-4 flex-1 min-w-0">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center text-sm font-bold shadow-sm flex-shrink-0 border border-white">
                                    <?= $initial ?>
                                </div>
                                
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-baseline justify-between mb-0.5">
                                        <p class="text-sm <?= $fontName ?> truncate pr-2">
                                            <?= htmlspecialchars($senderName) ?>
                                        </p>
                                    </div>
                                    <p class="text-sm <?= $fontSubject ?> truncate">
                                        <?= htmlspecialchars($row['subject']) ?>
                                    </p>
                                    <p class="text-xs text-gray-400 truncate mt-0.5 font-normal">
                                        <?= substr(strip_tags($row['body']), 0, 80) ?>...
                                    </p>
                                </div>
                            </div>

                            <div class="text-right flex-shrink-0 flex flex-col items-end gap-1 pl-2">
                                <span class="text-[11px] font-medium text-gray-400">
                                    <?= date('d M H:i', strtotime($row['created_at'])) ?>
                                </span>
                                
                                <div class="flex items-center gap-2 h-5">
                                    <?php if(!empty($row['attachment'])): ?>
                                        <i class="fas fa-paperclip text-gray-300 text-xs" title="Ada Lampiran"></i>
                                    <?php endif; ?>
                                    
                                    <?php if(!$isRead): ?>
                                        <span class="w-2.5 h-2.5 rounded-full bg-red-500 border-2 border-white shadow-sm" title="Belum Dibaca"></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>