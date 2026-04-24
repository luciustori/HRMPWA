<div class="max-w-3xl mx-auto">
    <a href="<?= BASEURL ?>/staff/inbox" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-indigo-600 mb-6 font-medium transition-colors">
        <div class="w-8 h-8 rounded-full bg-white shadow-sm border border-slate-200 flex items-center justify-center hover:border-indigo-200">
            <i class="ri-arrow-left-line"></i>
        </div>
        Kembali ke Inbox
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
        
        <div class="border-b border-slate-100 pb-6 mb-6">
            <div class="flex flex-wrap items-center gap-3 mb-4">
                <?php if($data['message']['target_type'] == 'all'): ?>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-600 border border-blue-100 uppercase tracking-wide">Announcement</span>
                <?php elseif($data['message']['target_type'] == 'department'): ?>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-orange-50 text-orange-600 border border-orange-100 uppercase tracking-wide">Department Info</span>
                <?php else: ?>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-600 border border-purple-100 uppercase tracking-wide">Personal Message</span>
                <?php endif; ?>

                <span class="flex items-center text-slate-400 text-sm font-medium">
                    <i class="ri-time-line mr-1.5"></i>
                    <?= date('d M Y • H:i', strtotime($data['message']['created_at'])) ?>
                </span>
            </div>
            
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800 leading-tight">
                <?= $data['message']['title'] ?>
            </h1>
            
            <div class="mt-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 border border-slate-200">
                    <i class="ri-user-3-fill text-sm"></i>
                </div>
                <div>
                    <span class="text-xs text-slate-400 uppercase tracking-wider font-bold">Dikirim Oleh</span>
                    <span class="block text-sm font-semibold text-slate-700"><?= $data['message']['sender_name'] ?? 'Admin System' ?></span>
                </div>
            </div>
        </div>

        <?php if(!empty($data['message']['event_date']) || !empty($data['message']['event_location'])): ?>
        <div class="bg-indigo-50/50 rounded-xl p-5 border border-indigo-100 mb-8 grid grid-cols-1 sm:grid-cols-2 gap-6 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-24 h-24 bg-indigo-100 rounded-bl-full -mr-4 -mt-4 opacity-30 pointer-events-none"></div>

            <?php if(!empty($data['message']['event_date'])): ?>
            <div class="flex items-start gap-3 relative z-10">
                <div class="w-10 h-10 rounded-lg bg-white text-indigo-600 flex items-center justify-center shadow-sm border border-indigo-50 shrink-0">
                    <i class="ri-calendar-event-fill text-xl"></i>
                </div>
                <div>
                    <h4 class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider mb-1">Waktu Pelaksanaan</h4>
                    <p class="font-bold text-slate-800 text-sm">
                        <?= date('l, d F Y', strtotime($data['message']['event_date'])) ?>
                    </p>
                    <?php if(!empty($data['message']['event_time'])): ?>
                        <p class="text-sm text-slate-500 font-medium">
                            Pukul <?= date('H:i', strtotime($data['message']['event_time'])) ?> WIB
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if(!empty($data['message']['event_location'])): ?>
            <div class="flex items-start gap-3 relative z-10">
                <div class="w-10 h-10 rounded-lg bg-white text-orange-500 flex items-center justify-center shadow-sm border border-orange-50 shrink-0">
                    <i class="ri-map-pin-fill text-xl"></i>
                </div>
                <div>
                    <h4 class="text-[10px] font-bold text-orange-400 uppercase tracking-wider mb-1">Lokasi Kegiatan</h4>
                    <p class="font-bold text-slate-800 text-sm leading-snug">
                        <?= $data['message']['event_location'] ?>
                    </p>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed mb-8">
            <?= html_entity_decode($data['message']['content']) ?>
        </div>

        <?php if(!empty($data['message']['attachment'])): ?>
        <div class="pt-6 border-t border-slate-100">
            <h4 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                <i class="ri-attachment-2"></i> Lampiran Berkas
            </h4>
            
            <a href="<?= BASEURL ?>/uploads/announcements/<?= $data['message']['attachment'] ?>" target="_blank" class="group flex items-start gap-4 p-4 bg-slate-50 border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50 rounded-xl transition-all duration-200 w-full md:w-fit">
                <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center text-indigo-500 shadow-sm border border-slate-100 shrink-0">
                    <?php 
                        $ext = pathinfo($data['message']['attachment'], PATHINFO_EXTENSION);
                        if(in_array(strtolower($ext), ['jpg','jpeg','png','gif'])) echo '<i class="ri-image-fill text-2xl"></i>';
                        elseif(in_array(strtolower($ext), ['pdf'])) echo '<i class="ri-file-pdf-fill text-2xl text-red-500"></i>';
                        elseif(in_array(strtolower($ext), ['doc','docx'])) echo '<i class="ri-file-word-fill text-2xl text-blue-500"></i>';
                        elseif(in_array(strtolower($ext), ['xls','xlsx'])) echo '<i class="ri-file-excel-fill text-2xl text-green-500"></i>';
                        else echo '<i class="ri-file-text-fill text-2xl"></i>';
                    ?>
                </div>
                <div class="min-w-0">
                    <div class="text-sm font-bold text-slate-700 group-hover:text-indigo-700 transition-colors break-all">
                        <?= $data['message']['attachment'] ?>
                    </div>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs text-indigo-600 font-medium group-hover:underline">Klik untuk download</span>
                    </div>
                </div>
            </a>
        </div>
        <?php endif; ?>

        <div class="mt-8 pt-6 border-t border-slate-100 flex flex-wrap gap-3">
            <?php 
                $isPersonal = ($data['message']['target_type'] == 'employee');
                $isMe = ($data['message']['created_by'] == $_SESSION['user_id']);
            ?>
            
            <?php if($isPersonal && !$isMe): ?>
                <a href="<?= BASEURL ?>/staff/inbox/reply/<?= $data['message']['id'] ?>" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors flex items-center gap-2 shadow-sm shadow-indigo-200">
                    <i class="ri-reply-line"></i> Balas Pesan
                </a>
            <?php endif; ?>

            <?php if($isMe): ?>
                <div class="px-4 py-2 bg-slate-50 text-slate-500 rounded-lg text-xs font-medium border border-slate-200 flex items-center gap-2">
                    <i class="ri-check-double-line text-emerald-500 text-base"></i> 
                    Pesan ini Anda kirim.
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>