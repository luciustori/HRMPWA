<!-- app/Views/PWA/pages/dashboard.php -->
<div class="space-y-4">
    <!-- Hero Card -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-6 text-white">
        <p class="text-sm opacity-90">Selamat pagi,</p>
        <h1 class="text-2xl font-bold"><?= htmlspecialchars($employee['full_name']) ?></h1>
        <p class="text-sm opacity-90 mt-1"><?= htmlspecialchars($employee['department_name']) ?></p>
    </div>

    <!-- Check-In Card -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-4">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-xs text-gray-400">Shift Hari Ini</p>
                <p class="text-lg font-bold">
                    <?php 
                    if ($employee['start_time']): 
                        echo date('H:i', strtotime($employee['start_time'])) . ' - ' . date('H:i', strtotime($employee['end_time']));
                    else:
                        echo 'Tidak ada shift';
                    endif;
                    ?>
                </p>
            </div>
            <span class="px-3 py-1 bg-blue-500/20 text-blue-400 text-xs rounded-full">
                <?php echo $today_attendance ? ($today_attendance['check_out'] ? 'Selesai' : 'Masuk') : 'Belum'; ?>
            </span>
        </div>

        <div class="flex gap-2">
            <?php if (!$today_attendance): ?>
            <button id="btn-checkin" class="flex-1 px-4 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium text-sm transition">
                📍 Check-In
            </button>
            <?php elseif (!$today_attendance['check_out']): ?>
            <button id="btn-checkout" class="flex-1 px-4 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium text-sm transition">
                🚪 Check-Out
            </button>
            <?php else: ?>
            <div class="flex-1 px-4 py-3 bg-gray-700 text-gray-300 rounded-lg font-medium text-sm text-center">
                ✅ Selesai
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Month Stats Card -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-4">
        <h3 class="text-sm font-semibold mb-4">Statistik Bulan Ini</h3>
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-gray-700 p-3 rounded-lg">
                <p class="text-xs text-gray-400">Hadir</p>
                <p class="text-2xl font-bold text-green-400"><?= $month_stats['present_days'] ?? 0 ?></p>
            </div>
            <div class="bg-gray-700 p-3 rounded-lg">
                <p class="text-xs text-gray-400">Terlambat</p>
                <p class="text-2xl font-bold text-yellow-400"><?= $month_stats['late_days'] ?? 0 ?></p>
            </div>
            <div class="bg-gray-700 p-3 rounded-lg">
                <p class="text-xs text-gray-400">Absen</p>
                <p class="text-2xl font-bold text-red-400"><?= $month_stats['absent_days'] ?? 0 ?></p>
            </div>
            <div class="bg-gray-700 p-3 rounded-lg">
                <p class="text-xs text-gray-400">Total Lambat</p>
                <p class="text-2xl font-bold text-orange-400"><?= $month_stats['total_lateness'] ?? 0 ?>m</p>
            </div>
        </div>
    </div>

    <!-- Pending Requests Card -->
    <?php if ($pending_requests > 0): ?>
    <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-4">
        <p class="text-sm text-blue-400">📋 <?= $pending_requests ?> pengajuan menunggu persetujuan</p>
        <a href="/pwa/requests" class="text-xs text-blue-400 hover:text-blue-300 mt-2 inline-block">
            Lihat detail →
        </a>
    </div>
    <?php endif; ?>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 gap-3">
        <a href="/pwa/checkin" class="bg-gray-800 border border-gray-700 rounded-lg p-4 text-center hover:border-gray-600 transition">
            <div class="text-2xl mb-2">📍</div>
            <p class="text-xs font-medium">Riwayat Kehadiran</p>
        </a>
        <a href="/pwa/requests" class="bg-gray-800 border border-gray-700 rounded-lg p-4 text-center hover:border-gray-600 transition">
            <div class="text-2xl mb-2">📋</div>
            <p class="text-xs font-medium">Pengajuan</p>
        </a>
        <a href="/pwa/profile" class="bg-gray-800 border border-gray-700 rounded-lg p-4 text-center hover:border-gray-600 transition">
            <div class="text-2xl mb-2">👤</div>
            <p class="text-xs font-medium">Profil</p>
        </a>
        <a href="/pwa/updates" class="bg-gray-800 border border-gray-700 rounded-lg p-4 text-center hover:border-gray-600 transition">
            <div class="text-2xl mb-2">🔔</div>
            <p class="text-xs font-medium">Updates</p>
        </a>
    </div>
</div>
