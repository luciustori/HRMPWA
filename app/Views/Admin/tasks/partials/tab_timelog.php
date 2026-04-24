<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h4 class="text-sm font-bold text-gray-700 uppercase">Riwayat Pengerjaan</h4>
        <button onclick="openLogModal()" class="text-xs bg-blue-50 text-blue-600 px-3 py-1 rounded-full font-bold hover:bg-blue-100 transition">
            <i class="fas fa-clock mr-1"></i> Log Time
        </button>
    </div>
    <?php include 'tab_timelog_table_content.php'; // Atau paste tabel lama di sini ?> 
</div>