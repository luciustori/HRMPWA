<div class="max-w-7xl mx-auto pb-12">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                <a href="<?= BASEURL ?>/staff/tasks" class="hover:text-indigo-600">Tasks</a>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-slate-800 font-medium"><?= $data['task']['task_code'] ?></span>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800"><?= htmlspecialchars($data['task']['title']) ?></h1>
        </div>
        <div class="flex gap-3">
            <a href="<?= BASEURL ?>/staff/tasks" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-50 font-medium transition-colors">
                <i class="ri-arrow-left-line mr-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="flex justify-between items-center mb-4 border-b border-slate-50 pb-4">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="ri-align-left text-slate-400"></i> Deskripsi Tugas
                    </h3>
                    
                    <?php 
                    $statusColor = match($data['task']['status']) {
                        'completed' => 'bg-green-100 text-green-700 border-green-200',
                        'in_progress' => 'bg-blue-100 text-blue-700 border-blue-200',
                        'review' => 'bg-red-100 text-red-700 border-red-200',
                        'submitted' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                        default => 'bg-slate-100 text-slate-600 border-slate-200'
                    };
                    $statusLabel = match($data['task']['status']) {
                        'in_progress' => 'Sedang Dikerjakan',
                        'completed' => 'Selesai (Approved)',
                        'review' => 'Perlu Revisi',
                        'submitted' => 'Menunggu Approval',
                        default => ucfirst($data['task']['status'])
                    };
                    ?>
                    <span class="px-3 py-1 rounded-full text-xs font-bold border <?= $statusColor ?> uppercase tracking-wide">
                        <?= $statusLabel ?>
                    </span>
                </div>
                
                <div class="prose prose-sm max-w-none text-slate-600 leading-relaxed">
                    <?= nl2br(htmlspecialchars($data['task']['description'])) ?>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="ri-checkbox-multiple-line text-indigo-500"></i> Checklist Item
                    </h3>
                    
                    <?php 
                    $totalCheck = count($data['checklist']);
                    $doneCheck = 0;
                    foreach($data['checklist'] as $c) { if($c['is_completed']) $doneCheck++; }
                    $percent = $totalCheck > 0 ? round(($doneCheck/$totalCheck)*100) : 0;
                    ?>
                    <span class="text-xs font-bold text-slate-500"><?= $percent ?>% Selesai</span>
                </div>

                <div class="w-full bg-slate-100 rounded-full h-2 mb-6">
                    <div class="bg-green-500 h-2 rounded-full transition-all duration-500" style="width: <?= $percent ?>%"></div>
                </div>

                <?php if(empty($data['checklist'])): ?>
                    <p class="text-sm text-slate-400 italic text-center py-4 bg-slate-50 rounded-lg border border-dashed border-slate-200">
                        Tidak ada sub-item checklist untuk tugas ini.
                    </p>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach($data['checklist'] as $item): ?>
                        <?php $isDisabled = in_array($data['task']['status'], ['submitted', 'completed']); ?>
                        
                        <a href="<?= $isDisabled ? '#' : BASEURL . '/staff/tasks/toggle_checklist/' . $item['id'] . '/' . $data['task']['id'] ?>" 
                           class="flex items-start gap-3 p-3 rounded-lg border transition-all group <?= $item['is_completed'] ? 'bg-slate-50 border-slate-200' : 'bg-white border-slate-200 hover:border-indigo-300' ?> <?= $isDisabled ? 'cursor-not-allowed opacity-70' : '' ?>">
                            
                            <div class="mt-0.5 w-5 h-5 rounded border flex items-center justify-center transition-colors shadow-sm 
                                <?= $item['is_completed'] ? 'bg-green-500 border-green-500 text-white' : 'border-slate-300 bg-white group-hover:border-indigo-400' ?>">
                                <i class="ri-check-line text-sm <?= $item['is_completed'] ? '' : 'opacity-0' ?>"></i>
                            </div>
                            
                            <span class="text-sm font-medium <?= $item['is_completed'] ? 'text-slate-400 line-through' : 'text-slate-700' ?>">
                                <?= htmlspecialchars($item['item_text']) ?>
                            </span>
                        </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                 <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="ri-history-line text-orange-500"></i> Log Pengerjaan
                    </h3>
                    
                    <?php if(!in_array($data['task']['status'], ['submitted', 'completed'])): ?>
                    <button onclick="document.getElementById('logFormWrapper').classList.toggle('hidden')" class="px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg text-xs font-bold hover:bg-indigo-100 transition-colors">
                        + Catat Log
                    </button>
                    <?php endif; ?>
                </div>
                
                <div id="logFormWrapper" class="hidden mb-8 bg-slate-50 border border-slate-200 rounded-xl p-5">
                    <form action="<?= BASEURL ?>/staff/tasks/add_time_log" method="POST">
                        <input type="hidden" name="task_id" value="<?= $data['task']['id'] ?>">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
                            <div class="md:col-span-3">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal</label>
                                <div class="relative">
                                    <input type="date" name="log_date" value="<?= date('Y-m-d') ?>" class="w-full pl-3 pr-2 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400">
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Jam Kerja</label>
                                <div class="relative">
                                    <input type="number" step="0.5" name="hours" placeholder="1" class="w-full pl-3 pr-8 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400">
                                    <span class="absolute right-3 top-2 text-xs text-slate-400 font-bold">Jam</span>
                                </div>
                            </div>
                            <div class="md:col-span-7">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Keterangan Aktivitas</label>
                                <input type="text" name="description" placeholder="Contoh: Riset kompetitor, coding fitur login..." class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400" required>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-bold transition-all shadow-lg shadow-slate-200 flex items-center gap-2 ml-auto">
                                <i class="ri-save-3-fill"></i> Simpan Log
                            </button>
                        </div>
                    </form>
                </div>

                <?php if(empty($data['time_logs'])): ?>
                    <div class="text-center py-6 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        <p class="text-sm text-slate-400 italic">Belum ada catatan waktu.</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-6 relative before:absolute before:left-[19px] before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-100">
                        <?php foreach($data['time_logs'] as $log): ?>
                        <div class="relative pl-10 group">
                            <div class="absolute left-[15px] top-2 w-2.5 h-2.5 rounded-full bg-orange-400 border-2 border-white ring-1 ring-orange-100 group-hover:ring-orange-200 transition-all"></div>
                            <div class="bg-white p-3 rounded-lg border border-transparent hover:border-slate-100 hover:bg-slate-50 transition-all">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-xs font-bold border border-slate-200">
                                        <?= date('d M Y', strtotime($log['created_at'])) ?>
                                    </span>
                                    <?php if($log['hours_spent'] > 0): ?>
                                    <span class="bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded text-xs font-bold border border-indigo-100 flex items-center gap-1">
                                        <i class="ri-time-fill"></i> <?= floatval($log['hours_spent']) ?> Jam
                                    </span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-slate-700 text-sm leading-relaxed"><?= htmlspecialchars($log['description']) ?></p>
                                <p class="text-[10px] text-slate-300 mt-1 text-right">By <?= $log['username'] ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="ri-chat-1-line text-blue-500"></i> Diskusi Tim
                </h3>

                <div class="space-y-4 mb-6 max-h-60 overflow-y-auto pr-2">
                    <?php if(empty($data['comments'])): ?>
                        <div class="text-center py-4">
                            <p class="text-sm text-slate-400">Belum ada diskusi.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach($data['comments'] as $chat): 
                            $isMe = ($chat['user_id'] == $_SESSION['user_id']);
                        ?>
                        <div class="flex gap-3 <?= $isMe ? 'flex-row-reverse' : '' ?>">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0 <?= $isMe ? 'bg-indigo-100 text-indigo-600' : 'bg-slate-100 text-slate-500' ?>">
                                <?= substr($chat['username'], 0, 1) ?>
                            </div>
                            <div class="max-w-[80%]">
                                <div class="flex items-center gap-2 mb-1 <?= $isMe ? 'flex-row-reverse' : '' ?>">
                                    <span class="text-xs font-bold text-slate-700"><?= $chat['first_name'] ?? $chat['username'] ?></span>
                                    <span class="text-[10px] text-slate-400"><?= date('d M H:i', strtotime($chat['created_at'])) ?></span>
                                </div>
                                <div class="px-3 py-2 rounded-lg text-sm <?= $isMe ? 'bg-indigo-50 text-indigo-900 rounded-tr-none' : 'bg-slate-50 text-slate-700 rounded-tl-none' ?>">
                                    <?= nl2br(htmlspecialchars($chat['comment'])) ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <form action="<?= BASEURL ?>/staff/tasks/add_comment" method="POST" class="relative">
                    <input type="hidden" name="task_id" value="<?= $data['task']['id'] ?>">
                    <textarea name="comment" rows="2" placeholder="Tulis update atau pertanyaan..." class="w-full pl-4 pr-12 py-3 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-100 resize-none"></textarea>
                    <button type="submit" class="absolute right-2 bottom-2 bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                        Kirim
                    </button>
                </form>
            </div>
        </div>

        <div class="space-y-6">
            
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Update Status</h3>
                
                <?php if($data['task']['status'] == 'submitted'): ?>
                    <div class="text-center py-4 bg-yellow-50 rounded-xl border border-yellow-100 border-dashed">
                        <i class="ri-hourglass-fill text-3xl text-yellow-400 mb-2 block"></i>
                        <span class="text-sm font-bold text-yellow-700">Menunggu Approval</span>
                        <p class="text-xs text-yellow-600 mt-1">Tugas sedang direview oleh atasan.</p>
                    </div>
                
                <?php elseif($data['task']['status'] == 'completed'): ?>
                    <div class="text-center py-4 bg-green-50 rounded-xl border border-green-100 border-dashed">
                        <i class="ri-checkbox-circle-fill text-3xl text-green-500 mb-2 block"></i>
                        <span class="text-sm font-bold text-green-700">Tugas Selesai</span>
                        <p class="text-xs text-green-600 mt-1">Pekerjaan telah disetujui.</p>
                    </div>

                <?php else: ?>
                    <form action="<?= BASEURL ?>/staff/tasks/update_status" method="POST" class="grid grid-cols-2 gap-2">
                        <input type="hidden" name="task_id" value="<?= $data['task']['id'] ?>">
                        
                        <?php if($data['task']['status'] != 'review'): ?>
                        <button type="submit" name="status" value="pending" class="px-3 py-2 rounded-lg text-xs font-bold border transition-all <?= $data['task']['status'] == 'pending' ? 'bg-slate-800 text-white border-slate-800' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-400' ?>">
                            Pending
                        </button>
                        <?php endif; ?>
                        
                        <button type="submit" name="status" value="in_progress" class="px-3 py-2 rounded-lg text-xs font-bold border transition-all <?= $data['task']['status'] == 'in_progress' ? 'bg-blue-600 text-white border-blue-600 shadow-md shadow-blue-200' : 'bg-white text-blue-600 border-blue-200 hover:bg-blue-50' ?>">
                            Kerjakan
                        </button>
                        
                        <button type="submit" name="status" value="completed" class="col-span-2 px-3 py-3 rounded-lg text-xs font-bold border transition-all bg-green-600 text-white border-green-600 hover:bg-green-700 shadow-md shadow-green-200 flex items-center justify-center gap-2">
                            <i class="ri-send-plane-fill"></i> <?= $data['task']['status'] == 'review' ? 'Kirim Revisi' : 'Selesai & Ajukan' ?>
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-5">
                <div class="space-y-3 pb-5 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-500">
                            <i class="ri-calendar-event-line"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase">Tanggal Mulai</p>
                            <p class="text-sm font-semibold text-slate-700">
                                <?= date('d M Y', strtotime($data['task']['start_date'] ?? 'now')) ?>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-500">
                            <i class="ri-timer-flash-line"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase">Tenggat Waktu</p>
                            <p class="text-sm font-semibold text-red-600">
                                <?= date('d M Y', strtotime($data['task']['due_date'])) ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">Prioritas</p>
                        <?php 
                        $prioClass = match($data['task']['priority']) {
                            'high' => 'bg-red-50 text-red-600 border-red-100',
                            'medium' => 'bg-orange-50 text-orange-600 border-orange-100',
                            default => 'bg-blue-50 text-blue-600 border-blue-100'
                        };
                        ?>
                        <span class="inline-block px-2 py-1 rounded text-xs font-bold border <?= $prioClass ?>">
                            <?= ucfirst($data['task']['priority']) ?>
                        </span>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">Kategori</p>
                        <span class="text-sm font-medium text-slate-700">
                            <?= $data['task']['category_name'] ?? 'General' ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Tim Bertugas</h3>
                
                <div class="flex items-center gap-3 mb-4">
                    <?php if(!empty($data['task']['pic_photo'])): ?>
                        <img src="<?= BASEURL ?>/uploads/profile/<?= $data['task']['pic_photo'] ?>" class="w-10 h-10 rounded-full object-cover border border-slate-200">
                    <?php else: ?>
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">
                            <?= substr($data['task']['pic_first'] ?? 'U', 0, 1) ?>
                        </div>
                    <?php endif; ?>
                    
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase">PIC (Penanggung Jawab)</p>
                        <p class="text-sm font-bold text-slate-800">
                            <?= ($data['task']['pic_first'] ?? 'You') . ' ' . ($data['task']['pic_last'] ?? '') ?>
                        </p>
                        <p class="text-xs text-slate-500"><?= $data['task']['pic_position'] ?? 'Employee' ?></p>
                    </div>
                </div>

                <div class="border-t border-slate-100 my-3"></div>

                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-xs">
                        <i class="ri-user-star-line"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase">Dibuat Oleh</p>
                        <p class="text-sm font-medium text-slate-700">
                            <?= ($data['task']['creator_first'] ?? $data['task']['creator_name'] ?? 'System') ?>
                        </p>
                    </div>
                </div>
            </div>

            <?php if($data['task']['is_recurring']): ?>
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl p-5 text-white shadow-lg shadow-indigo-200">
                <div class="flex items-center gap-3 mb-2">
                    <i class="ri-loop-right-line text-xl opacity-80"></i>
                    <h3 class="font-bold">Tugas Rutin</h3>
                </div>
                <p class="text-xs text-indigo-100 leading-relaxed">
                    Diulang setiap <strong class="text-white"><?= ucfirst($data['task']['recurrence_type']) ?></strong>
                    <br>pada tanggal/hari ke- <strong class="text-white"><?= $data['task']['recurrence_day'] ?></strong>.
                </p>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>