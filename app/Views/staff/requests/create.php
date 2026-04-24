<div class="max-w-4xl mx-auto font-sans text-slate-600">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Buat Pengajuan</h1>
            <p class="text-sm text-slate-500">Pilih kategori pengajuan di bawah ini.</p>
        </div>
        <a href="<?= BASEURL ?>/staff/requests" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 hover:text-indigo-600 hover:bg-slate-50 transition-colors shadow-sm">
            <i class="ri-arrow-left-line mr-1"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-2 mb-6 grid grid-cols-5 gap-2">
        <button onclick="switchForm('ijin')" id="btn-ijin" class="tab-btn active px-4 py-3 rounded-xl text-sm font-bold flex flex-col items-center gap-1 transition-all bg-slate-900 text-white shadow-lg shadow-slate-200">
            <i class="ri-user-voice-line text-lg"></i> Ijin
        </button>
        <button onclick="switchForm('dinas_luar')" id="btn-dinas_luar" class="tab-btn px-4 py-3 rounded-xl text-sm font-bold flex flex-col items-center gap-1 transition-all text-slate-500 hover:bg-slate-50">
            <i class="ri-briefcase-line text-lg"></i> Dinas Luar
        </button>
        <button onclick="switchForm('cuti')" id="btn-cuti" class="tab-btn px-4 py-3 rounded-xl text-sm font-bold flex flex-col items-center gap-1 transition-all text-slate-500 hover:bg-slate-50">
            <i class="ri-sun-line text-lg"></i> Cuti
        </button>
        <button onclick="switchForm('sppd')" id="btn-sppd" class="tab-btn px-4 py-3 rounded-xl text-sm font-bold flex flex-col items-center gap-1 transition-all text-slate-500 hover:bg-slate-50">
            <i class="ri-plane-line text-lg"></i> SPPD
        </button>
        <button onclick="switchForm('lembur')" id="btn-lembur" class="tab-btn px-4 py-3 rounded-xl text-sm font-bold flex flex-col items-center gap-1 transition-all text-slate-500 hover:bg-slate-50">
            <i class="ri-time-line text-lg"></i> Lembur
        </button>
    </div>

    <form action="<?= BASEURL ?>/staff/requests/store" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
        
        <input type="hidden" name="category" id="category_input" value="ijin">
        <input type="hidden" name="annual_id_hidden" value="<?= $data['annual_leave']['id'] ?? '' ?>">
        <input type="hidden" name="duty_id_hidden" value="<?= $data['duty_leave']['id'] ?? '' ?>">
        
        <div class="mb-8 p-4 bg-indigo-50 border border-indigo-100 rounded-xl flex gap-4 items-start">
            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 shrink-0">
                <i class="ri-information-fill text-xl"></i>
            </div>
            <div>
                <h4 class="text-sm font-bold text-indigo-800 mb-1" id="info-title">Form Ijin</h4>
                <p class="text-xs text-indigo-600 leading-relaxed" id="info-desc">Gunakan form ini untuk Sakit, Keperluan Keluarga, atau Ijin Sosial lainnya yang tidak memotong cuti tahunan.</p>
            </div>
        </div>

        <div id="form-leave">
            
            <div id="annual-leave-info" class="hidden mb-8 bg-gradient-to-r from-blue-500 to-indigo-600 p-5 rounded-xl text-white shadow-lg shadow-indigo-200 relative overflow-hidden">
                <div class="absolute right-0 top-0 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl -mr-10 -mt-10"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center border border-white/30">
                            <i class="ri-pie-chart-2-fill text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-blue-100 uppercase tracking-wider mb-0.5">Sisa Cuti Tahunan</p>
                            <h3 class="text-2xl font-black text-white"><?= $data['remaining_quota'] ?> <span class="text-sm font-medium opacity-80">Hari</span></h3>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-blue-100 opacity-80">Periode</p>
                        <p class="text-lg font-bold text-white"><?= date('Y') ?></p>
                    </div>
                </div>
            </div>

            <div class="mb-6" id="permit-type-wrapper">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Jenis Ijin</label>
                <div class="relative">
                    <select name="leave_type_id" id="leave_type_select" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 bg-slate-50 appearance-none font-medium text-slate-700">
                        <?php foreach($data['permit_types'] as $type): ?>
                            <option value="<?= $type['id'] ?>"><?= $type['leave_type_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <i class="ri-arrow-down-s-line absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Mulai Tanggal</label>
                    <input type="date" name="start_date" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 bg-white font-medium text-slate-700">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 bg-white font-medium text-slate-700">
                </div>
            </div>

            <div id="dl-time-wrapper" class="hidden mb-6 p-5 bg-slate-50 rounded-xl border border-slate-200 border-dashed">
                <div class="mb-4">
                    <span class="text-xs font-bold text-slate-600 flex items-center gap-2 uppercase tracking-wider">
                        <i class="ri-time-line text-lg text-indigo-500"></i> Waktu Dinas / Survey (Opsional)
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1 pl-6">Isi jika dinas luar tidak seharian penuh (misal hanya survey pagi).</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">Jam Mulai</label>
                        <input type="time" name="dl_start_time" class="w-full px-4 py-2 rounded-lg border border-slate-300 bg-white focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">Jam Selesai</label>
                        <input type="time" name="dl_end_time" class="w-full px-4 py-2 rounded-lg border border-slate-300 bg-white focus:ring-indigo-500">
                    </div>
                </div>
            </div>
        </div>

        <div id="form-sppd" class="hidden space-y-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tujuan Dinas</label>
                    <input type="text" name="destination" placeholder="Kota / Lokasi Tujuan" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-100 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Estimasi Budget (Rp)</label>
                    <input type="number" name="estimated_budget" placeholder="0" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-100 bg-white">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Keperluan</label>
                <input type="text" name="trip_purpose" placeholder="Contoh: Meeting Client A" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-100 bg-white">
            </div>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Berangkat</label>
                    <input type="date" name="start_date_trip" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-100 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Pulang</label>
                    <input type="date" name="end_date_trip" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-100 bg-white">
                </div>
            </div>
        </div>

        <div id="form-ot" class="hidden space-y-6 mb-6">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal Lembur</label>
                <input type="date" name="overtime_date" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-100 bg-white">
            </div>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Jam Mulai</label>
                    <input type="time" name="start_time" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-100 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Jam Selesai</label>
                    <input type="time" name="end_time" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-100 bg-white">
                </div>
            </div>
        </div>

        <div class="space-y-6 border-t border-slate-100 pt-6">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Alasan / Keterangan Lengkap</label>
                <textarea name="reason" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-100 bg-white resize-none" placeholder="Jelaskan detail pengajuan Anda..."></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Upload Bukti / Dokumen</label>
                <div class="flex items-center gap-4">
                    <label for="doc_upload" class="cursor-pointer group flex items-center gap-2 bg-slate-50 hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 px-5 py-3 rounded-xl border border-slate-200 hover:border-indigo-200 transition-all shadow-sm">
                        <i class="ri-upload-cloud-2-line text-lg"></i> 
                        <span class="text-sm font-bold">Pilih File</span>
                    </label>
                    <input type="file" name="document" id="doc_upload" class="hidden">
                    
                    <div class="flex flex-col">
                        <span id="file-name" class="text-sm font-medium text-slate-400 italic">Belum ada file dipilih</span>
                        <span class="text-[10px] text-slate-400">Max: 2MB (JPG, PNG, PDF)</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-slate-100">
            <a href="<?= BASEURL ?>/staff/requests" class="px-6 py-3 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-50 transition-colors">Batal</a>
            <button type="submit" class="px-8 py-3 bg-slate-900 text-white rounded-xl text-sm font-bold hover:bg-slate-800 transition-all shadow-lg shadow-slate-300 hover:shadow-slate-400 flex items-center gap-2">
                <i class="ri-send-plane-fill"></i> Ajukan Permohonan
            </button>
        </div>
    </form>
</div>

<script>
    // 1. Logic Upload File (FIXED)
    document.getElementById('doc_upload').addEventListener('change', function() {
        const fileLabel = document.getElementById('file-name');
        
        if (this.files && this.files.length > 0) {
            const file = this.files[0];
            
            // Validasi Ukuran Client Side (Max 5MB biar aman)
            if(file.size > 5 * 1024 * 1024) {
                alert("File terlalu besar! Maksimal 5MB.");
                this.value = ""; // Reset input
                fileLabel.textContent = "Belum ada file dipilih";
                fileLabel.className = "text-sm font-medium text-slate-400 italic";
                return;
            }

            fileLabel.textContent = file.name;
            fileLabel.className = "text-sm font-bold text-indigo-600 flex items-center gap-1"; 
            fileLabel.innerHTML = `<i class="ri-file-check-line"></i> ${file.name}`;
        } else {
            fileLabel.textContent = "Belum ada file dipilih";
            fileLabel.className = "text-sm font-medium text-slate-400 italic";
        }
    });

    // 2. Logic Switch Form
    function switchForm(type) {
        // Reset Styles Buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.className = 'tab-btn px-4 py-3 rounded-xl text-sm font-bold flex flex-col items-center gap-1 transition-all text-slate-500 hover:bg-slate-50';
        });
        
        // Active Style Button
        const activeBtn = document.getElementById('btn-' + type);
        activeBtn.className = 'tab-btn active px-4 py-3 rounded-xl text-sm font-bold flex flex-col items-center gap-1 transition-all bg-slate-900 text-white shadow-lg shadow-slate-200';

        // Update Input Hidden
        document.getElementById('category_input').value = type;

        // Hide All Sections
        const sections = ['form-leave', 'form-sppd', 'form-ot', 'permit-type-wrapper', 'annual-leave-info', 'dl-time-wrapper'];
        sections.forEach(id => document.getElementById(id).classList.add('hidden'));

        // Reset Header Info
        const title = document.getElementById('info-title');
        const desc = document.getElementById('info-desc');

        // Logic Per Type
        if(type === 'ijin') {
            document.getElementById('form-leave').classList.remove('hidden');
            document.getElementById('permit-type-wrapper').classList.remove('hidden');
            title.textContent = "Form Ijin";
            desc.textContent = "Gunakan untuk Sakit (Surat Dokter), Ijin Menikah, atau Keperluan Mendesak lainnya.";
        } 
        else if(type === 'dinas_luar') {
            document.getElementById('form-leave').classList.remove('hidden');
            document.getElementById('dl-time-wrapper').classList.remove('hidden');
            title.textContent = "Form Dinas Luar";
            desc.textContent = "Tugas kerja di luar kantor dalam kota (Survey, Meeting Luar, dll).";
        }
        else if(type === 'cuti') {
            document.getElementById('form-leave').classList.remove('hidden');
            document.getElementById('annual-leave-info').classList.remove('hidden');
            title.textContent = "Form Cuti Tahunan";
            desc.textContent = "Pengajuan penggunaan jatah cuti tahunan (Annual Leave).";
        }
        else if(type === 'sppd') {
            document.getElementById('form-sppd').classList.remove('hidden');
            title.textContent = "Form SPPD";
            desc.textContent = "Surat Perintah Perjalanan Dinas untuk tugas ke Luar Kota.";
        }
        else if(type === 'lembur') {
            document.getElementById('form-ot').classList.remove('hidden');
            title.textContent = "Form Lembur";
            desc.textContent = "Pengajuan kerja tambahan di luar jam operasional (Overtime).";
        }
    }
</script>