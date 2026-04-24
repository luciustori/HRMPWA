<?php
// Helper Data
$e = $data['employee'];
$u = $data['user'];
$photo = !empty($e['profile_photo_path']) ? BASEURL . '/' . $e['profile_photo_path'] : 'https://ui-avatars.com/api/?name='.urlencode($e['first_name']).'&background=0D8ABC&color=fff';
?>

<div class="max-w-6xl mx-auto px-4 py-8 font-sans">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Pengaturan Akun</h1>
        <p class="text-slate-500 text-sm">Kelola informasi pribadi dan keamanan akun Anda.</p>
    </div>

    <div class="mb-6">
        <?php Flasher::flash(); ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 text-center relative overflow-hidden">
                <div class="bg-slate-50 h-24 absolute top-0 left-0 w-full z-0"></div>
                
                <div class="relative z-10">
                    <div class="relative w-24 h-24 mx-auto mb-4 group">
                        <img src="<?= $photo ?>" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md">
                        
                        <form action="<?= BASEURL ?>/staff/profile/upload_photo" method="POST" enctype="multipart/form-data" id="formPhoto">
                            <label for="uploadPhoto" class="absolute inset-0 bg-black/50 rounded-full flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition cursor-pointer">
                                <i class="fas fa-camera"></i>
                            </label>
                            <input type="file" name="photo" id="uploadPhoto" class="hidden" onchange="document.getElementById('formPhoto').submit()">
                        </form>
                    </div>

                    <h2 class="text-lg font-bold text-slate-800"><?= $e['first_name'] . ' ' . $e['last_name'] ?></h2>
                    <p class="text-slate-500 text-xs uppercase tracking-wider font-semibold mb-4"><?= $e['position'] ?? $e['position_name'] ?? 'Staff' ?></p>
                    
                    <div class="flex justify-center gap-2">
                        <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-bold border border-blue-100">
                            <?= $e['employee_number'] ?>
                        </span>
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-xs font-bold border border-emerald-100">
                            Active
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                <h3 class="text-xs font-bold text-slate-400 uppercase mb-4">Informasi Pekerjaan</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <span class="text-slate-500">Departemen</span>
                        <span class="font-medium text-slate-700"><?= $e['department_name'] ?? '-' ?></span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <span class="text-slate-500">Tanggal Gabung</span>
                        <span class="font-medium text-slate-700"><?= $e['hire_date'] ? date('d M Y', strtotime($e['hire_date'])) : '-' ?></span>
                    </div>
                    <div class="flex justify-between pb-2">
                        <span class="text-slate-500">Sisa Cuti</span>
                        <span class="font-medium text-slate-700"><?= $e['annual_leave_balance'] ?> Hari</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="lg:col-span-2 space-y-8">
            
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Informasi Pribadi</h3>
                        <p class="text-xs text-slate-500">Update kontak dan alamat domisili Anda.</p>
                    </div>
                    <button form="formInfo" type="submit" class="bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-bold shadow hover:bg-slate-800 transition">
                        Simpan Perubahan
                    </button>
                </div>

                <form id="formInfo" action="<?= BASEURL ?>/staff/profile/update_info" method="POST">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Email Pribadi</label>
                            <input type="email" name="email" value="<?= $e['email'] ?>" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">No. WhatsApp / HP</label>
                            <input type="text" name="phone" value="<?= $e['phone'] ?>" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Alamat Lengkap</label>
                        <textarea name="address" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition"><?= $e['address'] ?></textarea>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-lg border border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="block text-xs text-slate-400 font-bold uppercase mb-1">Bank Account</span>
                            <div class="text-sm font-mono text-slate-700 font-bold">
                                <?= $e['bank_name'] ?? '-' ?> - <?= $e['bank_account_number'] ?? 'Not Set' ?>
                            </div>
                            <div class="text-xs text-slate-500"><?= $e['bank_account_name'] ?></div>
                        </div>
                        <div>
                            <span class="block text-xs text-slate-400 font-bold uppercase mb-1">NPWP</span>
                            <div class="text-sm font-mono text-slate-700 font-bold">
                                <?= $e['npwp'] ?? 'Not Set' ?>
                            </div>
                        </div>
                        <div class="col-span-1 md:col-span-2 text-[10px] text-rose-500 italic mt-1">
                            *Untuk perubahan data rekening & NPWP, harap hubungi HRD/Finance.
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="mb-6 pb-4 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">Keamanan Akun</h3>
                    <p class="text-xs text-slate-500">Ganti password secara berkala untuk keamanan.</p>
                </div>

                <form action="<?= BASEURL ?>/staff/profile/update_password" method="POST">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Password Lama</label>
                            <input type="password" name="old_password" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-indigo-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Password Baru</label>
                            <input type="password" name="new_password" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-indigo-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Konfirmasi Baru</label>
                            <input type="password" name="confirm_password" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-indigo-500 transition">
                        </div>
                    </div>
                    <div class="mt-6 text-right">
                        <button type="submit" class="bg-white border border-slate-300 text-slate-700 px-4 py-2 rounded-lg text-sm font-bold shadow-sm hover:bg-slate-50 transition">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

</div>