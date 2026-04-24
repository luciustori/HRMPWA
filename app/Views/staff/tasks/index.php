<div class="max-w-5xl mx-auto">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Daftar Pekerjaan</h2>
            <p class="text-slate-500 text-sm">Kelola tugas harian dan target kinerja Anda.</p>
        </div>
        
        <div class="flex gap-2">
            <div class="px-4 py-2 bg-white rounded-lg border border-slate-200 shadow-sm text-center">
                <span class="block text-xs text-slate-400 font-bold uppercase">Todo</span>
                <span class="text-lg font-bold text-slate-700"><?= $data['stats']['pending'] ?></span>
            </div>
            <div class="px-4 py-2 bg-white rounded-lg border border-slate-200 shadow-sm text-center">
                <span class="block text-xs text-slate-400 font-bold uppercase">Proses</span>
                <span class="text-lg font-bold text-blue-500">
                    <?= $data['stats']['on_going'] ?>
                </span>
            </div>
            <div class="px-4 py-2 bg-white rounded-lg border border-slate-200 shadow-sm text-center">
                <span class="block text-xs text-slate-400 font-bold uppercase">Selesai</span>
                <span class="text-lg font-bold text-green-500"><?= $data['stats']['completed'] ?></span>
            </div>
        </div>
    </div>

    <div class="flex gap-2 mb-6 overflow-x-auto pb-2">
        <a href="<?= BASEURL ?>/staff/tasks?status=all" class="px-4 py-2 rounded-full text-sm font-medium transition-colors <?= $data['filter'] == 'all' ? 'bg-slate-800 text-white' : 'bg-white text-slate-600 hover:bg-slate-100' ?>">Semua</a>
        
        <a href="<?= BASEURL ?>/staff/tasks?status=pending" class="px-4 py-2 rounded-full text-sm font-medium transition-colors <?= $data['filter'] == 'pending' ? 'bg-orange-500 text-white' : 'bg-white text-slate-600 hover:bg-slate-100' ?>">Pending</a>
        
        <a href="<?= BASEURL ?>/staff/tasks?status=in_progress" class="px-4 py-2 rounded-full text-sm font-medium transition-colors <?= $data['filter'] == 'in_progress' ? 'bg-blue-500 text-white' : 'bg-white text-slate-600 hover:bg-slate-100' ?>">
            Proses & Review
        </a>
        
        <a href="<?= BASEURL ?>/staff/tasks?status=completed" class="px-4 py-2 rounded-full text-sm font-medium transition-colors <?= $data['filter'] == 'completed' ? 'bg-green-500 text-white' : 'bg-white text-slate-600 hover:bg-slate-100' ?>">Selesai</a>
    </div>

    <?php if(empty($data['tasks'])): ?>
        <div class="bg-white rounded-xl p-10 text-center border border-dashed border-slate-300">
            <i class="ri-checkbox-circle-line text-4xl text-slate-300 mb-2"></i>
            <p class="text-slate-500">Tidak ada tugas ditemukan pada filter ini.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php foreach($data['tasks'] as $task): ?>
                <a href="<?= BASEURL ?>/staff/tasks/detail/<?= $task['id'] ?>" class="block group">
                    <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:shadow-md hover:border-indigo-300 transition-all h-full flex flex-col">
                        
                        <div class="flex justify-between items-start mb-3">
                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wide bg-slate-100 text-slate-600">
                                <?= $task['category_name'] ?? 'General' ?>
                            </span>
                            
                            <?php if($task['priority'] == 'high'): ?>
                                <span class="text-red-500 text-xs font-bold flex items-center gap-1"><i class="ri-fire-fill"></i> Urgent</span>
                            <?php elseif($task['priority'] == 'medium'): ?>
                                <span class="text-orange-500 text-xs font-bold">Medium</span>
                            <?php else: ?>
                                <span class="text-blue-500 text-xs font-bold">Low</span>
                            <?php endif; ?>
                        </div>

                        <h3 class="text-lg font-bold text-slate-800 mb-2 group-hover:text-indigo-600 transition-colors">
                            <?= $task['title'] ?>
                        </h3>
                        
                        <p class="text-sm text-slate-500 line-clamp-2 mb-4 flex-1">
                            <?= strip_tags($task['description']) ?>
                        </p>

                        <div class="flex items-center justify-between pt-4 border-t border-slate-50 mt-auto">
                            <div class="flex items-center gap-2 text-xs text-slate-400">
                                <i class="ri-calendar-line"></i> Due: <?= date('d M', strtotime($task['due_date'])) ?>
                            </div>
                            
                            <?php if($task['status'] == 'completed'): ?>
                                <div class="w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center" title="Selesai"><i class="ri-check-line"></i></div>
                            <?php elseif($task['status'] == 'submitted'): ?>
                                <div class="w-6 h-6 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center" title="Menunggu Approval"><i class="ri-hourglass-fill"></i></div>
                            <?php elseif($task['status'] == 'review'): ?>
                                <div class="w-6 h-6 rounded-full bg-red-100 text-red-600 flex items-center justify-center" title="Perlu Revisi"><i class="ri-edit-circle-line"></i></div>
                            <?php elseif($task['status'] == 'in_progress'): ?>
                                <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center" title="Sedang Dikerjakan"><i class="ri-loader-4-line animate-spin"></i></div>
                            <?php else: ?>
                                <div class="w-6 h-6 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center"><i class="ri-time-line"></i></div>
                            <?php endif; ?>
                        </div>

                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>