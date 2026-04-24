<div class="max-w-4xl mx-auto">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="inline-flex bg-slate-100 p-1 rounded-lg border border-slate-200 self-start">
            
            <button onclick="switchTab('announcement')" id="btn-announcement" class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 bg-white text-slate-800 shadow-sm">
                Pengumuman
                <?php 
                    $unreadAnnounce = count(array_filter($data['announcements'], fn($m) => $m['is_read'] == 0));
                    if($unreadAnnounce > 0): 
                ?>
                    <span class="ml-1.5 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-red-100 bg-red-500 rounded-full"><?= $unreadAnnounce ?></span>
                <?php endif; ?>
            </button>
            
            <button onclick="switchTab('personal')" id="btn-personal" class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 text-slate-500 hover:text-slate-700">
                Inbox
                <?php 
                    $unreadPersonal = count(array_filter($data['personal'], fn($m) => $m['is_read'] == 0));
                    if($unreadPersonal > 0): 
                ?>
                    <span class="ml-1.5 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-red-100 bg-red-500 rounded-full"><?= $unreadPersonal ?></span>
                <?php endif; ?>
            </button>

            <button onclick="switchTab('sent')" id="btn-sent" class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 text-slate-500 hover:text-slate-700">
                Terkirim
            </button>
        </div>

        <div id="action-area" class="hidden">
             <a href="<?= BASEURL ?>/staff/inbox/create" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <i class="ri-edit-box-line"></i>
                Tulis Pesan
            </a>
        </div>
    </div>

    <div id="tab-announcement" class="space-y-3">
        <?php if (empty($data['announcements'])): ?>
            <div class="bg-white rounded-xl border border-slate-200 p-10 text-center">
                <i class="ri-notification-off-line text-4xl text-slate-300 mb-2 block"></i>
                <p class="text-slate-500">Tidak ada pengumuman baru.</p>
            </div>
        <?php else: ?>
            <?php foreach ($data['announcements'] as $msg): ?>
                <a href="<?= BASEURL ?>/staff/inbox/read/<?= $msg['id'] ?>" class="group block bg-white rounded-xl border border-slate-200 p-4 hover:shadow-md hover:border-indigo-200 transition-all duration-200 relative overflow-hidden">
                    <?php if ($msg['is_read'] == 0): ?><div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500"></div><?php endif; ?>
                    <div class="flex items-center gap-4">
                        <div class="shrink-0">
                            <?php if($msg['target_type'] == 'all'): ?>
                                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600"><i class="ri-megaphone-line text-xl"></i></div>
                            <?php else: ?>
                                <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center text-orange-600"><i class="ri-building-4-line text-xl"></i></div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 min-w-0 py-1">
                            <div class="flex justify-between items-start">
                                <h4 class="text-sm font-bold text-slate-800 truncate pr-2 group-hover:text-indigo-600 transition-colors"><?= $msg['title'] ?></h4>
                                <span class="text-[11px] text-slate-400 whitespace-nowrap"><?= date('d M H:i', strtotime($msg['created_at'])) ?></span>
                            </div>
                            <p class="text-xs text-slate-500 line-clamp-1 mt-0.5"><?= strip_tags(html_entity_decode($msg['content'])) ?></p>
                            <div class="flex items-center mt-2 gap-2">
                                <span class="text-[10px] font-semibold tracking-wide text-slate-400 uppercase"><?= $msg['target_type'] == 'all' ? 'Global' : 'Dept. ' . ($msg['dept_name'] ?? '-') ?></span>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div id="tab-personal" class="hidden space-y-3">
        <?php if (empty($data['personal'])): ?>
            <div class="bg-white rounded-xl border border-slate-200 p-10 text-center">
                <i class="ri-mail-send-line text-4xl text-slate-300 mb-2 block"></i>
                <p class="text-slate-500 mb-4">Belum ada pesan masuk.</p>
                <a href="<?= BASEURL ?>/staff/inbox/create" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-600 text-sm font-medium rounded-lg transition-colors">
                    <i class="ri-pencil-line"></i> Mulai Tulis Pesan
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($data['personal'] as $msg): ?>
                <a href="<?= BASEURL ?>/staff/inbox/read/<?= $msg['id'] ?>" class="group block bg-white rounded-xl border border-slate-200 p-4 hover:shadow-md hover:border-purple-200 transition-all duration-200 relative overflow-hidden">
                     <?php if ($msg['is_read'] == 0): ?><div class="absolute left-0 top-0 bottom-0 w-1 bg-red-500"></div><?php endif; ?>
                    <div class="flex items-center gap-4">
                        <div class="shrink-0">
                            <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-lg">
                                <?= strtoupper(substr($msg['author_name'] ?? 'A', 0, 1)) ?>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0 py-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="text-[10px] text-slate-400 block mb-0.5">Dari: <?= $msg['author_name'] ?? 'Unknown' ?></span>
                                    <h4 class="text-sm font-bold text-slate-800 truncate group-hover:text-purple-600 transition-colors"><?= $msg['title'] ?></h4>
                                </div>
                                <div class="flex flex-col items-end gap-1">
                                    <span class="text-[11px] text-slate-400 whitespace-nowrap"><?= date('d M', strtotime($msg['created_at'])) ?></span>
                                    <?php if ($msg['is_read'] == 0): ?><span class="w-2 h-2 rounded-full bg-red-500 shadow-sm"></span><?php endif; ?>
                                </div>
                            </div>
                            <p class="text-xs text-slate-500 line-clamp-1 mt-1"><?= strip_tags(html_entity_decode($msg['content'])) ?></p>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div id="tab-sent" class="hidden space-y-3">
        <?php if (empty($data['sent'])): ?>
            <div class="bg-white rounded-xl border border-slate-200 p-10 text-center">
                <i class="ri-history-line text-4xl text-slate-300 mb-2 block"></i>
                <p class="text-slate-500">Belum ada riwayat pesan terkirim.</p>
            </div>
        <?php else: ?>
            <?php foreach ($data['sent'] as $msg): ?>
                <a href="<?= BASEURL ?>/staff/inbox/read/<?= $msg['id'] ?>" class="group block bg-white rounded-xl border border-slate-200 p-4 hover:shadow-md hover:border-emerald-200 transition-all duration-200 relative overflow-hidden">
                    <div class="flex items-center gap-4">
                        <div class="shrink-0">
                            <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 font-bold text-lg">
                                <i class="ri-arrow-right-up-line"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0 py-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="text-[10px] text-slate-400 block mb-0.5">
                                        Kepada: <?= $msg['target_name'] ?? 'Unknown' ?> 
                                        <span class="text-[9px] bg-slate-100 px-1 rounded ml-1"><?= $msg['target_position'] ?? 'Staff' ?></span>
                                    </span>
                                    <h4 class="text-sm font-bold text-slate-800 truncate group-hover:text-emerald-600 transition-colors"><?= $msg['title'] ?></h4>
                                </div>
                                <span class="text-[11px] text-slate-400 whitespace-nowrap"><?= date('d M', strtotime($msg['created_at'])) ?></span>
                            </div>
                            <p class="text-xs text-slate-500 line-clamp-1 mt-1"><?= strip_tags(html_entity_decode($msg['content'])) ?></p>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<script>
function switchTab(tabName) {
    // Hide All
    document.getElementById('tab-announcement').classList.add('hidden');
    document.getElementById('tab-personal').classList.add('hidden');
    document.getElementById('tab-sent').classList.add('hidden');
    
    // Show Selected
    document.getElementById('tab-' + tabName).classList.remove('hidden');
    
    // Buttons
    const btnAnnounce = document.getElementById('btn-announcement');
    const btnPersonal = document.getElementById('btn-personal');
    const btnSent     = document.getElementById('btn-sent');
    const actionArea  = document.getElementById('action-area');
    
    // Reset Style
    const allBtns = [btnAnnounce, btnPersonal, btnSent];
    const activeClasses = ['bg-white', 'text-slate-800', 'shadow-sm'];
    const inactiveClasses = ['text-slate-500', 'hover:text-slate-700'];
    
    allBtns.forEach(btn => {
        btn.classList.remove(...activeClasses);
        btn.classList.add(...inactiveClasses);
    });

    // Set Active
    if(tabName === 'announcement') {
        btnAnnounce.classList.add(...activeClasses);
        btnAnnounce.classList.remove(...inactiveClasses);
        actionArea.classList.add('hidden');
    } else if (tabName === 'personal') {
        btnPersonal.classList.add(...activeClasses);
        btnPersonal.classList.remove(...inactiveClasses);
        actionArea.classList.remove('hidden');
    } else {
        // Tab Sent
        btnSent.classList.add(...activeClasses);
        btnSent.classList.remove(...inactiveClasses);
        actionArea.classList.remove('hidden'); // Tetap boleh kirim pesan dari tab terkirim
    }
}
</script>