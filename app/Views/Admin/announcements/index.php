<div class="max-w-[1600px] mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pusat Informasi</h1>
            <p class="text-gray-500 text-sm">Kelola pengumuman untuk seluruh karyawan atau departemen tertentu.</p>
        </div>
        <a href="<?= BASEURL ?>/admin/announcements/create" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition transform hover:-translate-y-0.5 flex items-center gap-2">
            <i class="fas fa-plus"></i> Buat Pengumuman
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-4">
            <?php if(empty($announcements)): ?>
                <div class="p-10 text-center bg-white rounded-2xl border border-dashed border-gray-300">
                    <p class="text-gray-500 font-medium">Belum ada pengumuman yang diterbitkan.</p>
                </div>
            <?php else: ?>
                <?php foreach($announcements as $row): 
                    $type = $row['type'] ?? 'info';
                    $style = match($type) {
                        'danger'  => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'border' => 'border-red-200', 'icon' => 'fa-exclamation-circle'],
                        'warning' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-600', 'border' => 'border-orange-200', 'icon' => 'fa-bell'],
                        'success' => ['bg' => 'bg-green-50', 'text' => 'text-green-600', 'border' => 'border-green-200', 'icon' => 'fa-check-circle'],
                        default   => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-200', 'icon' => 'fa-info-circle']
                    };

                    $targetLabel = '';
                    // FIX: Ganti target_audience menjadi target_type sesuai struktur Database
                    if ($row['target_type'] == 'department') $targetLabel = '<i class="fas fa-building mr-1"></i> ' . ($row['department_name'] ?? 'Departemen');
                    elseif ($row['target_type'] == 'specific') $targetLabel = '<i class="fas fa-user-tag mr-1"></i> Personal';
                    else $targetLabel = '<i class="fas fa-users mr-1"></i> Semua Staff';
                ?>
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition relative group">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl <?= $style['bg'] ?> <?= $style['text'] ?> flex items-center justify-center text-xl flex-shrink-0">
                            <i class="fas <?= $style['icon'] ?>"></i>
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded border bg-gray-50 text-gray-500 border-gray-200">
                                            <?= $targetLabel ?>
                                        </span>
                                        <span class="text-[10px] text-gray-400">
                                            <?= date('d M Y • H:i', strtotime($row['created_at'])) ?>
                                        </span>
                                    </div>
                                    <h3 class="font-bold text-gray-800 text-lg leading-tight mb-2"><?= htmlspecialchars($row['title']) ?></h3>
                                </div>
                                <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition">
                                    <a href="<?= BASEURL ?>/admin/announcements/edit?id=<?= $row['id'] ?>" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 flex items-center justify-center transition"><i class="fas fa-pencil-alt text-xs"></i></a>
                                    <button onclick="if(confirm('Hapus?')) location.href='<?= BASEURL ?>/admin/announcements/delete?id=<?= $row['id'] ?>'" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-red-50 hover:text-red-600 flex items-center justify-center transition"><i class="fas fa-trash-alt text-xs"></i></button>
                                </div>
                            </div>
                            
                            <?php if(!empty($row['event_date'])): ?>
                                <div class="flex flex-wrap gap-2 mb-3">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-lg border border-indigo-100">
                                        <i class="far fa-calendar-alt"></i> 
                                        <?= date('d M Y', strtotime($row['event_date'])) ?>
                                        <?php if(!empty($row['event_time'])) echo ' • ' . date('H:i', strtotime($row['event_time'])); ?>
                                    </span>
                                    <?php if(!empty($row['event_location'])): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-50 text-gray-600 text-xs font-bold rounded-lg border border-gray-200">
                                            <i class="fas fa-map-marker-alt text-red-400"></i> <?= htmlspecialchars($row['event_location']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <p class="text-gray-600 text-sm leading-relaxed whitespace-pre-line mb-3"><?= htmlspecialchars($row['content']) ?></p>
                            
                            <div class="flex items-center gap-3 mt-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center text-[9px] font-bold text-gray-500"><?= substr($row['creator_name'] ?? 'A', 0, 1) ?></div>
                                    <span class="text-xs text-gray-400">By <?= $row['creator_name'] ?? 'Admin' ?></span>
                                </div>
                                <?php if(!empty($row['attachment'])): ?>
                                    <a href="<?= BASEURL ?>/uploads/announcements/<?= $row['attachment'] ?>" target="_blank" class="text-xs font-bold text-indigo-600 hover:underline flex items-center gap-1"><i class="fas fa-paperclip"></i> Lampiran</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-gray-800 mb-4 text-sm uppercase">Statistik</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-blue-50 rounded-xl border border-blue-100"><span class="block text-2xl font-bold text-blue-600"><?= $stats['info'] ?></span><span class="text-xs text-blue-400 font-bold uppercase">Info</span></div>
                    <div class="p-3 bg-orange-50 rounded-xl border border-orange-100"><span class="block text-2xl font-bold text-orange-600"><?= $stats['warning'] ?></span><span class="text-xs text-orange-400 font-bold uppercase">Warning</span></div>
                </div>
            </div>
        </div>
    </div>
</div>