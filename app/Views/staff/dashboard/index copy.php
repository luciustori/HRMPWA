<div class="max-w-7xl mx-auto">
    
    <!-- 1. WELCOME & STATS ROW -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- BIG PERFORMANCE CARD (Gradient Blue) -->
        <div class="lg:col-span-2 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <!-- Decorative circle -->
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white opacity-10 rounded-full"></div>
            <div class="absolute right-10 top-10 w-20 h-20 bg-white opacity-5 rounded-full"></div>

            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between h-full">
                <div>
                    <h2 class="text-blue-100 text-sm font-medium mb-1">Performance Score</h2>
                    <div class="flex items-baseline gap-2">
                        <span class="text-5xl font-bold"><?= $data['kpi_score'] ?></span>
                        <span class="text-blue-200 text-lg">/100</span>
                    </div>
                    <div class="flex gap-2 mt-4">
                        <span class="bg-white/20 px-3 py-1 rounded-full text-xs font-medium backdrop-blur-sm">Monthly</span>
                        <span class="bg-green-400/20 text-green-100 px-3 py-1 rounded-full text-xs font-medium backdrop-blur-sm border border-green-400/30">
                            <?= $data['mood'] ?>
                        </span>
                    </div>
                </div>
                <div class="mt-4 sm:mt-0 text-right hidden sm:block">
                    <p class="text-blue-100 mb-2">Selamat Bekerja,</p>
                    <p class="text-2xl font-bold"><?= explode(' ', $data['user_name'])[0] ?>!</p>
                </div>
            </div>
        </div>

        <!-- ATTENDANCE CARD -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-500 text-sm font-medium">Kehadiran Bulan Ini</p>
                    <h3 class="text-4xl font-bold text-slate-800 mt-2"><?= $data['attendance']['present'] ?> <span class="text-lg text-slate-400 font-normal">Hari</span></h3>
                </div>
                <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600">
                    <i class="ri-calendar-check-fill text-2xl"></i>
                </div>
            </div>
            
            <!-- Tombol Absen (PWA Redirect) -->
            <div class="mt-6">
                <a href="<?= BASEURL ?>/pwa/home" target="_blank" class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-lg transition-all shadow-md shadow-indigo-200">
                    <i class="ri-fingerprint-line mr-2"></i> Buka Mesin Absen
                </a>
                <p class="text-xs text-center text-slate-400 mt-2">*Absensi wajib via Mobile/PWA</p>
            </div>
        </div>
    </div>

    <!-- 2. CONTENT GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- LEFT COLUMN: ACTIVE TASKS (2/3 width) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="ri-list-check text-indigo-600"></i> Active Tasks
                </h3>
                <a href="<?= BASEURL ?>/staff/tasks" class="text-sm text-indigo-600 font-medium hover:underline">View All</a>
            </div>

            <?php if(empty($data['tasks'])): ?>
                <!-- Empty State -->
                <div class="bg-white rounded-xl p-8 text-center border border-dashed border-slate-300">
                    <i class="ri-check-double-line text-4xl text-slate-300 mb-2"></i>
                    <p class="text-slate-500">Tidak ada tugas aktif. Santai sejenak!</p>
                </div>
            <?php else: ?>
                <!-- Task List -->
                <div class="grid gap-4">
                    <?php foreach($data['tasks'] as $task): ?>
                    <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow flex items-start gap-4">
                        <!-- Priority Indicator -->
                        <div class="w-1 h-12 rounded-full 
                            <?= ($task['priority'] == 'high') ? 'bg-red-500' : (($task['priority'] == 'medium') ? 'bg-orange-500' : 'bg-blue-500') ?>">
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex justify-between mb-1">
                                <span class="text-xs font-semibold px-2 py-0.5 rounded bg-slate-100 text-slate-600 uppercase tracking-wide">
                                    <?= $task['category_name'] ?? 'General' ?>
                                </span>
                                <span class="text-xs text-slate-400"><?= date('d M', strtotime($task['due_date'])) ?></span>
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm mb-1"><?= $task['title'] ?></h4>
                            <p class="text-xs text-slate-500 line-clamp-1"><?= strip_tags($task['description']) ?></p>
                            
                            <!-- Progress Bar Mockup -->
                            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-3">
                                <div class="bg-indigo-500 h-1.5 rounded-full" style="width: 25%"></div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- QUICK ACTIONS ROW -->
            <div class="pt-4">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="#" class="bg-white p-4 rounded-xl border border-slate-100 hover:border-indigo-200 hover:shadow-md transition-all text-center group">
                        <div class="w-10 h-10 mx-auto bg-orange-100 text-orange-600 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <i class="ri-sun-line text-xl"></i>
                        </div>
                        <span class="text-sm font-medium text-slate-700">Cuti</span>
                    </a>
                    <a href="#" class="bg-white p-4 rounded-xl border border-slate-100 hover:border-indigo-200 hover:shadow-md transition-all text-center group">
                        <div class="w-10 h-10 mx-auto bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <i class="ri-timer-flash-line text-xl"></i>
                        </div>
                        <span class="text-sm font-medium text-slate-700">Lembur</span>
                    </a>
                    <a href="#" class="bg-white p-4 rounded-xl border border-slate-100 hover:border-indigo-200 hover:shadow-md transition-all text-center group">
                        <div class="w-10 h-10 mx-auto bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <i class="ri-file-text-line text-xl"></i>
                        </div>
                        <span class="text-sm font-medium text-slate-700">Izin</span>
                    </a>
                    <a href="#" class="bg-white p-4 rounded-xl border border-slate-100 hover:border-indigo-200 hover:shadow-md transition-all text-center group">
                        <div class="w-10 h-10 mx-auto bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <i class="ri-money-dollar-circle-line text-xl"></i>
                        </div>
                        <span class="text-sm font-medium text-slate-700">Payslip</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: ANNOUNCEMENTS -->
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-800">Pengumuman</h3>
                <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
            </div>

            <div class="bg-white rounded-2xl p-2 shadow-sm border border-slate-100">
                <?php if(empty($data['announcements'])): ?>
                    <div class="p-6 text-center text-slate-400 text-sm">Belum ada pengumuman.</div>
                <?php else: ?>
                    <div class="relative pl-4 border-l-2 border-slate-100 ml-4 py-4 space-y-6">
                        <?php foreach($data['announcements'] as $info): ?>
                        <div class="relative">
                            <!-- Bullet -->
                            <div class="absolute -left-[25px] top-1 w-4 h-4 rounded-full bg-white border-2 border-indigo-500"></div>
                            
                            <span class="text-xs text-slate-400 block mb-1"><?= date('d M Y', strtotime($info['created_at'])) ?></span>
                            <h4 class="text-sm font-bold text-slate-800 mb-1 leading-tight"><?= $info['title'] ?></h4>
                            <p class="text-xs text-slate-500 line-clamp-2"><?= strip_tags($info['content']) ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Widget Kalender (Opsional/Statik) -->
            <div class="bg-indigo-900 rounded-2xl p-6 text-white text-center">
                <p class="text-indigo-200 text-xs uppercase tracking-widest mb-2"><?= date('F Y') ?></p>
                <h3 class="text-5xl font-bold"><?= date('d') ?></h3>
                <p class="text-lg font-medium mt-1"><?= date('l') ?></p>
                <div class="mt-4 pt-4 border-t border-indigo-800">
                    <p class="text-xs text-indigo-300">Don't forget to submit your report today!</p>
                </div>
            </div>
        </div>

    </div>
</div>
