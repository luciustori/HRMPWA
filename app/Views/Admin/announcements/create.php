<div class="max-w-[1600px] mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Buat Pengumuman Baru</h1>
            <p class="text-gray-500 text-sm">Informasi akan disebarkan melalui notifikasi dan dashboard.</p>
        </div>
        <a href="<?= BASEURL ?>/admin/announcements" class="text-gray-500 hover:text-gray-700 text-sm font-bold">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <div class="xl:col-span-2">
            <form action="<?= BASEURL ?>/admin/announcements/store" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Judul Pengumuman <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition" placeholder="Contoh: Upacara Hari Pahlawan">
                </div>

                <div class="mb-6 flex items-center gap-4 p-4 bg-gray-50 border border-gray-200 rounded-xl">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_event" id="toggleEvent" value="1" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        <span class="ml-3 text-sm font-bold text-gray-700">Jadikan Agenda / Event?</span>
                    </label>
                    <p class="text-xs text-gray-500">Aktifkan jika ini adalah undangan rapat atau kegiatan yang memiliki jadwal.</p>
                </div>

                <div id="eventDetails" class="hidden bg-indigo-50 border border-indigo-100 rounded-xl p-5 mb-6 transition-all duration-300">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="far fa-calendar-alt text-indigo-600"></i>
                        <h3 class="font-bold text-gray-800 text-sm uppercase">Detail Acara</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tanggal Pelaksanaan</label>
                            <input type="date" name="event_date" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-100 bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Waktu / Jam</label>
                            <input type="time" name="event_time" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-100 bg-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Lokasi Kegiatan</label>
                        <input type="text" name="event_location" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-100 bg-white" placeholder="Contoh: Halaman Kantor Walikota, Ruang Rapat Lt. 2">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Lampiran / Attachment</label>
                    <input type="file" name="attachment" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tipe & Warna Badge</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="info" checked class="peer sr-only">
                            <div class="text-center py-2 border rounded-lg peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:text-blue-600 text-sm font-medium text-gray-500 hover:bg-gray-50">Info (Biru)</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="success" class="peer sr-only">
                            <div class="text-center py-2 border rounded-lg peer-checked:bg-green-50 peer-checked:border-green-500 peer-checked:text-green-600 text-sm font-medium text-gray-500 hover:bg-gray-50">Sukses (Hijau)</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="warning" class="peer sr-only">
                            <div class="text-center py-2 border rounded-lg peer-checked:bg-orange-50 peer-checked:border-orange-500 peer-checked:text-orange-600 text-sm font-medium text-gray-500 hover:bg-gray-50">Penting (Kuning)</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="danger" class="peer sr-only">
                            <div class="text-center py-2 border rounded-lg peer-checked:bg-red-50 peer-checked:border-red-500 peer-checked:text-red-600 text-sm font-medium text-gray-500 hover:bg-gray-50">Darurat (Merah)</div>
                        </label>
                    </div>
                </div>

                <hr class="border-gray-100 my-6">

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Target Penerima</label>
                    <select name="target_scope" id="targetScope" onchange="handleTargetChange()" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-100 bg-white">
                        <option value="all_company">Seluruh Perusahaan</option>
                        <option value="department_all">Seluruh Anggota Departemen...</option>
                        <option value="department_specific">Karyawan Tertentu (Multiple)...</option>
                    </select>
                </div>

                <div id="dynamicTargetArea" class="hidden space-y-6 p-5 bg-gray-50 rounded-xl border border-gray-100 mb-6">
                    
                    <div id="deptArea" class="hidden">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Pilih Departemen</label>
                        <select name="department_id" id="deptSelect" class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white">
                            <option value="">- Pilih Departemen -</option>
                            <?php foreach($departments as $d): ?>
                                <option value="<?= $d['id'] ?>"><?= $d['department_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div id="empArea" class="hidden">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Pilih Karyawan</label>
                        <div class="text-[10px] text-gray-500 mb-2">Centang satu atau beberapa karyawan yang akan menerima pengumuman ini.</div>
                        <div class="max-h-60 overflow-y-auto bg-white border border-gray-300 rounded-xl p-4 grid grid-cols-1 md:grid-cols-2 gap-3 shadow-inner">
                            <?php foreach($employees as $emp): ?>
                                <label class="flex items-center gap-3 cursor-pointer hover:bg-slate-50 p-2 rounded-lg transition-colors border border-transparent hover:border-slate-100">
                                    <input type="checkbox" name="employee_ids[]" value="<?= $emp['id'] ?>" class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                                    <span class="text-sm font-medium text-gray-700"><?= $emp['first_name'] . ' ' . $emp['last_name'] ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                </div>

                <div class="mb-8">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Isi Pesan <span class="text-red-500">*</span></label>
                    <textarea name="content" rows="6" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-100 resize-none" placeholder="Tulis detail informasi selengkapnya..."></textarea>
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-100">
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition flex items-center gap-2">
                        <i class="fas fa-paper-plane"></i> Terbitkan Sekarang
                    </button>
                </div>
            </form>
        </div>

        <div class="xl:col-span-1 space-y-6">
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-6 -mr-6 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
                <h3 class="font-bold text-lg mb-4 flex items-center gap-2"><i class="far fa-calendar-check text-yellow-300"></i> Event & Acara</h3>
                <p class="text-sm text-indigo-50 mb-3">Jika pengumuman ini adalah undangan rapat atau kegiatan:</p>
                <ul class="text-sm space-y-2 list-disc list-inside text-indigo-100">
                    <li>Aktifkan toggle "Jadikan Agenda".</li>
                    <li>Isi tanggal, jam, dan lokasi.</li>
                    <li>Otomatis muncul di Jadwal Staff.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
const BASE_URL = '<?= BASEURL ?>';

// 1. Logic Toggle Event
const toggleEvent = document.getElementById('toggleEvent');
const eventDetails = document.getElementById('eventDetails');

toggleEvent.addEventListener('change', function() {
    if(this.checked) {
        eventDetails.classList.remove('hidden');
    } else {
        eventDetails.classList.add('hidden');
    }
});

// 2. Logic Target Selector (FIXED)
function handleTargetChange() {
    const scope = document.getElementById('targetScope').value;
    const dynamicArea = document.getElementById('dynamicTargetArea');
    const deptArea = document.getElementById('deptArea');
    const empArea = document.getElementById('empArea');

    if (scope === 'all_company') {
        dynamicArea.classList.add('hidden');
        deptArea.classList.add('hidden');
        empArea.classList.add('hidden');
    } else {
        dynamicArea.classList.remove('hidden');
        
        if (scope === 'department_all') {
            deptArea.classList.remove('hidden');
            empArea.classList.add('hidden');
        } else if (scope === 'department_specific') {
            deptArea.classList.add('hidden');
            empArea.classList.remove('hidden');
        }
    }
}
</script>