<div class="space-y-6" x-data="{ activeTab: 'company' }">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Konfigurasi Sistem</h1>
            <p class="text-sm text-slate-500">Kelola identitas perusahaan dan pengaturan global aplikasi.</p>
        </div>
        <button form="settingsForm" type="submit" class="inline-flex justify-center items-center px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl shadow-lg hover:bg-indigo-700 hover:shadow-indigo-200 transition-all transform active:scale-95">
            <i class="ri-save-3-line mr-2 text-lg"></i> Simpan Semua
        </button>
    </div>

    <div class="flex p-1 space-x-1 bg-slate-100 rounded-xl max-w-lg">
        <button @click="activeTab = 'company'" 
                :class="activeTab === 'company' ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-black/5' : 'text-slate-500 hover:text-slate-700'" 
                class="flex-1 py-2.5 px-4 rounded-lg text-sm font-bold transition-all flex items-center justify-center gap-2">
            <i class="ri-building-4-line"></i> Identitas Perusahaan
        </button>
        <button @click="activeTab = 'app'" 
                :class="activeTab === 'app' ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-black/5' : 'text-slate-500 hover:text-slate-700'" 
                class="flex-1 py-2.5 px-4 rounded-lg text-sm font-bold transition-all flex items-center justify-center gap-2">
            <i class="ri-settings-4-line"></i> Aplikasi & Sistem
        </button>
    </div>

    <form id="settingsForm" action="<?= BASEURL ?>/admin/settings/update" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
        
        <div x-show="activeTab === 'company'" class="space-y-8" style="display: none;" x-show.important="activeTab === 'company'">
            
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex gap-3 items-start">
                <i class="ri-information-fill text-blue-600 text-xl mt-0.5"></i>
                <div class="text-sm text-blue-800">
                    <p class="font-bold">Data Laporan</p>
                    <p>Informasi di bawah ini digunakan khusus untuk <strong>Kop Surat, Kontrak Kerja, dan Laporan PDF</strong>.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Resmi Perusahaan</label>
                    <input type="text" name="company_name" value="<?= $s['company_name'] ?? '' ?>" class="w-full border-slate-300 rounded-xl focus:ring-indigo-500" placeholder="PT. Mencari Cinta Sejati">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Email Resmi (Surat Menyurat)</label>
                    <input type="email" name="company_email" value="<?= $s['company_email'] ?? '' ?>" class="w-full border-slate-300 rounded-xl focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Website</label>
                    <input type="text" name="company_website" value="<?= $s['company_website'] ?? '' ?>" class="w-full border-slate-300 rounded-xl focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nomor Telepon</label>
                    <input type="text" name="company_phone" value="<?= $s['company_phone'] ?? '' ?>" class="w-full border-slate-300 rounded-xl focus:ring-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Lengkap (Kop Surat)</label>
                <textarea name="company_address" rows="3" class="w-full border-slate-300 rounded-xl focus:ring-indigo-500"><?= $s['company_address'] ?? '' ?></textarea>
            </div>

            <hr class="border-slate-100">

            <div class="flex items-start gap-6">
                <div class="w-24 h-24 bg-white border border-slate-200 rounded-xl flex items-center justify-center p-2 shadow-sm shrink-0">
                    <?php if(!empty($s['company_logo'])): ?>
                        <img src="<?= BASEURL . '/' . $s['company_logo'] ?>" class="max-w-full max-h-full object-contain">
                    <?php else: ?>
                        <span class="text-xs text-slate-400 text-center">Belum ada Logo PT</span>
                    <?php endif; ?>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-bold text-slate-700 mb-1">Logo Perusahaan (Resmi)</label>
                    <p class="text-xs text-slate-500 mb-3">Logo ini akan muncul di Header Laporan PDF & Slip Gaji.</p>
                    <input type="file" name="company_logo" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                </div>
            </div>
        </div>


        <div x-show="activeTab === 'app'" class="space-y-8" style="display: none;" x-show.important="activeTab === 'app'">
            
            <div>
                <h3 class="font-bold text-lg text-slate-800 mb-4 flex items-center gap-2">
                    <i class="ri-brush-line text-indigo-600"></i> Branding Aplikasi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-5 bg-slate-50 rounded-2xl border border-slate-100">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Aplikasi (Sidebar)</label>
                        <input type="text" name="app_name" value="<?= $s['app_name'] ?? 'AbsenPWA' ?>" class="w-full border-slate-300 rounded-xl focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tagline (Login Page)</label>
                        <input type="text" name="app_tagline" value="<?= $s['app_tagline'] ?? '' ?>" class="w-full border-slate-300 rounded-xl focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Warna Tema</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="primary_color" value="<?= $s['primary_color'] ?? '#4f46e5' ?>" class="h-10 w-12 border-0 bg-transparent cursor-pointer rounded">
                            <input type="text" value="<?= $s['primary_color'] ?? '#4f46e5' ?>" class="w-24 border-slate-300 rounded-xl text-sm uppercase" readonly>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white rounded-lg border flex items-center justify-center shrink-0">
                            <?php if(!empty($s['app_logo'])): ?>
                                <img src="<?= BASEURL . '/' . $s['app_logo'] ?>" class="w-8 h-8 object-contain">
                            <?php else: ?>
                                <i class="ri-smartphone-line text-slate-400"></i>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-bold text-slate-700 mb-1">Icon Aplikasi</label>
                            <input type="file" name="app_logo" accept="image/*" class="text-xs w-full text-slate-500">
                            <p class="text-[10px] text-slate-400 mt-1">Muncul di Sidebar & Favicon.</p>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            <div>
                <h3 class="font-bold text-lg text-slate-800 mb-4 flex items-center gap-2">
                    <i class="ri-settings-gear-line text-indigo-600"></i> Pengaturan Sistem
                </h3>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Toleransi Keterlambatan</label>
                        <div class="flex items-center gap-3">
                            <input type="number" name="late_tolerance" value="<?= $s['late_tolerance'] ?? 15 ?>" class="w-24 text-center font-bold text-lg border-slate-300 rounded-xl focus:ring-indigo-500">
                            <span class="text-slate-500 font-medium">Menit</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-2">Karyawan dianggap telat jika check-in melebihi batas ini.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3">Format Slip Gaji</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="cursor-pointer group relative">
                                <input type="radio" name="payslip_format" value="modern" class="peer sr-only" <?= ($s['payslip_format'] ?? 'modern') == 'modern' ? 'checked' : '' ?>>
                                <div class="border-2 border-slate-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 rounded-xl p-4 transition-all">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="font-bold text-slate-700 peer-checked:text-indigo-700">Modern Style</span>
                                        <i class="ri-checkbox-circle-fill text-xl text-slate-200 peer-checked:text-indigo-600"></i>
                                    </div>
                                    <p class="text-xs text-slate-500">Minimalis & Bersih</p>
                                </div>
                            </label>

                            <label class="cursor-pointer group relative">
                                <input type="radio" name="payslip_format" value="classic" class="peer sr-only" <?= ($s['payslip_format'] ?? '') == 'classic' ? 'checked' : '' ?>>
                                <div class="border-2 border-slate-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 rounded-xl p-4 transition-all">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="font-bold text-slate-700 peer-checked:text-indigo-700">Classic Table</span>
                                        <i class="ri-checkbox-circle-fill text-xl text-slate-200 peer-checked:text-indigo-600"></i>
                                    </div>
                                    <p class="text-xs text-slate-500">Tabel Formal</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </form>
</div>