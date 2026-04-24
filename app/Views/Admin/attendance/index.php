<style>
    @media print {
        nav, .no-print, header, form, .fixed, .stats-card-container { display: none !important; }
        .tab-content { display: block !important; }
        body { background: white; }
        .shadow-md { box-shadow: none !important; border: 1px solid #ddd; }
        .container { max-width: 100% !important; padding: 0 !important; }
        .print-header { display: block !important; text-align: center; margin-bottom: 20px; }
    }
    .print-header { display: none; }
</style>

<div class="container mx-auto px-4 py-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4 no-print">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Absensi & Approval</h1>
            <p class="text-gray-600 text-sm">Monitoring kehadiran karyawan.</p>
        </div>
        
        <div class="flex gap-2">
            <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-lg text-xs font-bold border border-emerald-200">
                H: <?= $stats['total_hadir'] ?>
            </span>
            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-lg text-xs font-bold border border-red-200">
                T: <?= $stats['total_telat'] ?>
            </span>
            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-lg text-xs font-bold border border-yellow-200">
                P: <?= $stats['total_pulang_awal'] ?>
            </span>
        </div>
    </div>

    <div class="border-b border-gray-200 mb-6 no-print">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button onclick="switchTab('monitoring')" id="tab-btn-monitoring" class="border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center transition-colors">
                <i class="ri-file-list-line mr-2"></i> Log Monitoring
            </button>
            <button onclick="switchTab('approval')" id="tab-btn-approval" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center transition-colors">
                <i class="ri-check-double-line mr-2"></i> Butuh Approval
                <?php if (count($pending_approvals) > 0): ?>
                    <span class="ml-2 bg-red-100 text-red-600 py-0.5 px-2 rounded-full text-xs font-bold animate-pulse"><?= count($pending_approvals) ?></span>
                <?php endif; ?>
            </button>
        </nav>
    </div>

    <div id="content-monitoring" class="tab-content block">
        
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-4 no-print">
            <form method="GET" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                
                <div class="flex flex-wrap items-center gap-3">
                    <?php if($mode == 'history'): ?>
                        <div class="flex items-center gap-2">
                            <a href="<?= BASEURL ?>/admin/attendance?mode=monthly" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-2 rounded-lg text-sm font-bold transition">
                                <i class="ri-arrow-left-line"></i> Kembali
                            </a>
                            <div class="px-3 py-2 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-bold border border-indigo-100">
                                Detail: <?= $employee_detail['first_name'] ?? 'Pegawai' ?>
                            </div>
                        </div>
                        <input type="hidden" name="mode" value="history">
                        <input type="hidden" name="start" value="<?= $raw_start ?>">
                        <input type="hidden" name="end" value="<?= $raw_end ?>">
                        <input type="hidden" name="employee_id" value="<?= $filter_emp ?>">
                    <?php else: ?>
                        <select name="mode" id="modeSelector" class="pl-3 pr-8 py-2 border rounded-lg text-sm font-bold text-gray-700 bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500" onchange="updateInputType()">
                            <option value="daily" <?= $mode == 'daily' ? 'selected' : '' ?>>Harian</option>
                            <option value="weekly" <?= $mode == 'weekly' ? 'selected' : '' ?>>Mingguan</option>
                            <option value="monthly" <?= $mode == 'monthly' ? 'selected' : '' ?>>Bulanan (Cut-Off)</option>
                        </select>

                        <div id="inputContainer"></div>

                        <select name="employee_id" class="pl-3 pr-8 py-2 border rounded-lg text-sm text-gray-600 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua Pegawai</option>
                            <?php foreach ($all_employees as $emp): ?>
                                <option value="<?= $emp['id'] ?>" <?= ($filter_emp == $emp['id']) ? 'selected' : '' ?>>
                                    <?= $emp['first_name'] . ' ' . $emp['last_name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition">Filter</button>
                    <?php endif; ?>
                </div>

                <div class="flex gap-2">
                    <div class="relative group">
                        <button type="button" class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition">
                            <i class="ri-download-line"></i> Export
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block border border-gray-100">
                            <a href="<?= BASEURL ?>/admin/attendance/export?type=excel&mode=<?= $mode ?>&start=<?= $raw_start ?>&end=<?= $raw_end ?>&employee_id=<?= $filter_emp ?>" target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="ri-file-excel-2-line text-green-600 mr-2"></i> Excel (.xls)
                            </a>
                            <a href="<?= BASEURL ?>/admin/attendance/export?type=print&mode=<?= $mode ?>&start=<?= $raw_start ?>&end=<?= $raw_end ?>&employee_id=<?= $filter_emp ?>" target="_blank" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="ri-printer-line text-gray-600 mr-2"></i> Print Preview
                            </a>
                        </div>
                    </div>

                    <?php if($mode == 'daily'): ?>
                    <button type="button" onclick="openModal('modalCheckIn')" class="flex items-center gap-2 bg-white border border-indigo-200 text-indigo-600 px-4 py-2 rounded-lg text-sm font-bold hover:bg-indigo-50 transition">
                        <i class="ri-add-line"></i> Manual
                    </button>
                    <?php endif; ?>
                </div>
            </form>
            
            <div class="mt-2 text-xs text-gray-500">
                Periode Data: <span class="font-bold text-gray-700"><?= $period_info ?></span>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <?php if ($mode == 'history'): ?>
                                <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-widest">Tanggal</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-widest">Shift</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-widest">Masuk</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-widest">Pulang</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-widest">Ket</th>
                                <th class="px-6 py-4 text-right text-[11px] font-bold text-gray-500 uppercase tracking-widest no-print">Aksi</th>

                            <?php elseif ($mode == 'daily'): ?>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Pegawai</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Shift</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Masuk</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Pulang</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase no-print">Aksi</th>
                            
                            <?php else: ?>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Pegawai</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Total Hadir</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-red-500 uppercase">Telat</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-yellow-600 uppercase">Plg Awal</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Total Jam</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase no-print">Detail</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <?php if (empty($logs)): ?>
                            <tr><td colspan="7" class="px-6 py-10 text-center text-sm text-gray-500 italic">Belum ada data.</td></tr>
                        <?php else: ?>
                            <?php foreach ($logs as $log): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                
                                <?php if ($mode == 'history'): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-800"><?= date('d M Y', strtotime($log['date'])) ?></div>
                                        <div class="text-xs <?= $log['is_weekend'] ? 'text-red-500 font-medium' : 'text-gray-500' ?>"><?= date('l', strtotime($log['date'])) ?></div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-700 font-medium">
                                        <?= $log['shift_name'] ?>
                                        <?php if ($log['is_mod']): ?>
                                            <span class="ml-1 px-1.5 py-0.5 text-[9px] font-bold rounded bg-indigo-100 text-indigo-700">MOD</span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold <?= $log['in'] !== '-' ? 'text-indigo-700' : 'text-gray-400' ?>"><?= $log['in'] ?></td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold <?= $log['out'] !== '-' ? 'text-indigo-700' : 'text-gray-400' ?>"><?= $log['out'] ?></div>
                                        <?php if ($log['overtime_mins'] > 0): ?>
                                            <div class="text-[10px] text-orange-600 font-bold mt-0.5">+<?= number_format($log['overtime_mins']/60, 1) ?>h OT</div>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap"><?= $log['badge'] ?></td>
                                    
                                    <td class="px-6 py-4 text-xs italic text-gray-500 max-w-[200px] truncate" title="<?= $log['note'] ?>"><?= $log['note'] ?: '-' ?></td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-right no-print">
                                        <?php if ($log['attendance_id']): ?>
                                            <div class="flex items-center justify-end gap-1.5">
                                                <?php if ($log['out'] === '-'): ?>
                                                    <button onclick="openManualOut(<?= $log['attendance_id'] ?>, '<?= addslashes($employee_detail['first_name']) ?>')" class="text-[10px] uppercase bg-indigo-50 text-indigo-600 px-2 py-1.5 rounded hover:bg-indigo-100 font-bold border border-indigo-200 transition" title="Check-Out">Out</button>
                                                <?php endif; ?>
                                                
                                                <button onclick="openViewRecord('<?= addslashes($employee_detail['first_name'].' '.$employee_detail['last_name']) ?>', '<?= $log['shift_name'] ?>', '<?= $log['in'] ?>', '<?= $log['out'] ?>', '<?= addslashes($log['note']) ?>')" class="text-blue-500 hover:text-blue-700 bg-blue-50 p-1.5 rounded transition" title="Detail"><i class="ri-eye-line text-lg"></i></button>
                                                
                                                <a href="<?= BASEURL ?>/admin/attendance/delete_record/<?= $log['attendance_id'] ?>" class="swal-link text-red-500 hover:text-red-700 bg-red-50 p-1.5 rounded transition" data-action="delete" data-msg="Hapus log absensi tanggal <?= date('d M Y', strtotime($log['date'])) ?> permanen?"><i class="ri-delete-bin-line text-lg"></i></a>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-gray-300">-</span>
                                        <?php endif; ?>
                                    </td>

                                <?php elseif ($mode == 'daily'): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900"><?= $log['first_name'] . ' ' . $log['last_name'] ?></div>
                                        <div class="text-xs text-gray-500 flex flex-col">
                                            <span><?= $log['employee_number'] ?> • <?= $log['department_name'] ?></span>
                                            <span class="text-indigo-500 font-medium"><?= $log['position'] ?? 'Staff' ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500"><?= $log['shift_name'] ?? '-' ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900"><?= substr($log['check_in_time'], 11, 5) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900"><?= $log['check_out_time'] ? substr($log['check_out_time'], 11, 5) : '-' ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($log['is_late']): ?><span class="px-2 py-1 text-xs font-bold rounded bg-red-100 text-red-800">Telat</span><?php endif; ?>
                                        <?php if (!$log['is_late']): ?><span class="px-2 py-1 text-xs font-bold rounded bg-green-100 text-green-800">Normal</span><?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium no-print">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <?php if (empty($log['check_out_time'])): ?>
                                                <button onclick="openManualOut(<?= $log['id'] ?>, '<?= addslashes($log['first_name']) ?>')" class="text-xs bg-indigo-50 text-indigo-600 px-2 py-1 rounded hover:bg-indigo-100 font-bold border border-indigo-200 shadow-sm" title="Check-Out"><i class="ri-logout-box-r-line"></i> Out</button>
                                            <?php endif; ?>
                                            <button onclick="openViewRecord('<?= addslashes($log['first_name'].' '.$log['last_name']) ?>', '<?= $log['shift_name'] ?>', '<?= substr($log['check_in_time'], 11, 5) ?>', '<?= $log['check_out_time'] ? substr($log['check_out_time'], 11, 5) : '-' ?>', '<?= addslashes($log['check_in_notes']) ?>')" class="text-blue-500 hover:text-blue-700 bg-blue-50 p-1.5 rounded transition" title="Detail"><i class="ri-eye-line text-lg"></i></button>
                                            
                                            <button onclick="openEditRecord(<?= $log['id'] ?>, '<?= addslashes($log['first_name']) ?>', '<?= $log['applied_shift_id'] ?? 1 ?>', '<?= substr($log['check_in_time'], 11, 5) ?>', '<?= $log['check_out_time'] ? substr($log['check_out_time'], 11, 5) : '' ?>', '<?= addslashes($log['check_in_notes']) ?>')" class="text-yellow-500 hover:text-yellow-700 bg-yellow-50 p-1.5 rounded transition" title="Edit"><i class="ri-edit-line text-lg"></i></button>
                                            
                                            <a href="<?= BASEURL ?>/admin/attendance/delete_record/<?= $log['id'] ?>" class="swal-link text-red-500 hover:text-red-700 bg-red-50 p-1.5 rounded transition" data-action="delete" data-msg="Hapus log absensi ini permanen?" title="Hapus"><i class="ri-delete-bin-line text-lg"></i></a>
                                        </div>
                                    </td>

                                <?php else: ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900"><?= $log['first_name'] . ' ' . $log['last_name'] ?></div>
                                        <div class="text-xs text-gray-500 flex flex-col gap-0.5">
                                            <span><?= $log['employee_number'] ?> • <?= $log['department_name'] ?></span>
                                            <span class="text-indigo-600 font-semibold"><?= $log['position'] ?? 'Staff' ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center font-bold text-gray-700"><?= $log['total_present'] ?> Hari</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-red-600 font-bold"><?= $log['total_late'] > 0 ? $log['total_late'].'x' : '-' ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-yellow-600 font-bold"><?= $log['total_early_out'] > 0 ? $log['total_early_out'].'x' : '-' ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-xs font-mono"><?= number_format($log['total_minutes'] / 60, 1) ?> Jam</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right no-print">
                                        <a href="<?= BASEURL ?>/admin/attendance?mode=history&employee_id=<?= $log['employee_id'] ?>&start=<?= $raw_start ?>&end=<?= $raw_end ?>" 
                                           class="text-indigo-600 hover:bg-indigo-50 px-2 py-1 rounded text-xs font-bold transition">
                                            Log
                                        </a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="content-approval" class="tab-content hidden">
       <?php include 'approvals.php'; ?>
    </div>
<div id="modalCheckIn" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="flex justify-between items-center p-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-800">Manual Check-In</h3>
                <button onclick="closeModal('modalCheckIn')" class="text-gray-400 hover:text-red-500 transition">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>
            <form action="<?= BASEURL ?>/admin/attendance/manual_checkin" method="POST" class="p-5">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Pegawai</label>
                        <select name="employee_id" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Pilih Pegawai --</option>
                            <?php foreach ($all_employees as $emp): ?>
                                <option value="<?= $emp['id'] ?>"><?= $emp['first_name'] . ' ' . $emp['last_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Tanggal</label>
                            <input type="date" name="attendance_date" value="<?= date('Y-m-d') ?>" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Jam Masuk</label>
                            <input type="time" name="check_in_time" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Catatan Admin</label>
                        <textarea name="notes" rows="2" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Alasan input manual..."></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('modalCheckIn')" class="px-4 py-2 text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-md transition">Simpan Absen</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalManualOut" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden">
            <div class="flex justify-between items-center p-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-800">Check-Out Manual</h3>
                <button onclick="closeModal('modalManualOut')" class="text-gray-400 hover:text-red-500 transition">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>
            <form action="<?= BASEURL ?>/admin/attendance/manual_checkout" method="POST" class="p-5">
                <input type="hidden" name="attendance_id" id="out_record_id">
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Pegawai: <span id="out_emp_name" class="font-bold text-gray-800"></span></p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Waktu Pulang</label>
                    <input type="time" name="check_out_time" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('modalManualOut')" class="px-4 py-2 text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-lg shadow-md transition">Proses Pulang</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalEditRecord" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="flex justify-between items-center p-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-800">Edit Data Absensi</h3>
                <button onclick="closeModal('modalEditRecord')" class="text-gray-400 hover:text-red-500 transition">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>
            <form action="<?= BASEURL ?>/admin/attendance/edit_record" method="POST" class="p-5">
                <input type="hidden" name="attendance_id" id="edit_record_id">
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Pegawai: <span id="edit_emp_name" class="font-bold text-gray-800"></span></p>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Jam Masuk</label>
                        <input type="time" name="check_in_time" id="edit_check_in" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Jam Pulang</label>
                        <input type="time" name="check_out_time" id="edit_check_out" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Catatan</label>
                    <textarea name="notes" id="edit_notes" rows="2" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('modalEditRecord')" class="px-4 py-2 text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg shadow-md transition">Update Data</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalViewRecord" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden">
            <div class="flex justify-between items-center p-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-800">Detail Absensi</h3>
                <button onclick="closeModal('modalViewRecord')" class="text-gray-400 hover:text-red-500 transition">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>
            <div class="p-5 space-y-4">
                <div><span class="block text-xs font-bold text-gray-400 uppercase">Nama Pegawai</span><div id="view_emp_name" class="text-sm font-semibold text-gray-800"></div></div>
                <div><span class="block text-xs font-bold text-gray-400 uppercase">Shift</span><div id="view_shift" class="text-sm font-semibold text-gray-800"></div></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><span class="block text-xs font-bold text-gray-400 uppercase">Masuk</span><div id="view_in" class="text-sm font-semibold text-indigo-600"></div></div>
                    <div><span class="block text-xs font-bold text-gray-400 uppercase">Pulang</span><div id="view_out" class="text-sm font-semibold text-indigo-600"></div></div>
                </div>
                <div><span class="block text-xs font-bold text-gray-400 uppercase">Catatan</span><div id="view_notes" class="text-sm font-semibold text-gray-800 bg-gray-50 p-2 rounded"></div></div>
            </div>
            <div class="p-4 border-t border-gray-100 flex justify-end">
                <button onclick="closeModal('modalViewRecord')" class="px-4 py-2 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    const currentMode = "<?= $mode ?>";
    const currentValue = "<?= $filter_value ?>";

    function updateInputType() {
        const modeElem = document.getElementById('modeSelector');
        if(!modeElem) return; 
        
        const mode = modeElem.value;
        const container = document.getElementById('inputContainer');
        let html = '';
        
        if (mode === 'daily') {
            html = `<input type="date" name="date" value="${currentMode === 'daily' ? currentValue : '<?= date('Y-m-d') ?>'}" class="pl-3 pr-2 py-2 border rounded-lg text-sm text-gray-600 focus:ring-indigo-500 focus:border-indigo-500">`;
        } else if (mode === 'weekly') {
            html = `<input type="week" name="week" value="${currentMode === 'weekly' ? currentValue : '<?= date('Y-\WW') ?>'}" class="pl-3 pr-2 py-2 border rounded-lg text-sm text-gray-600 focus:ring-indigo-500 focus:border-indigo-500">`;
        } else if (mode === 'monthly') {
            html = `<input type="month" name="month" value="${currentMode === 'monthly' ? currentValue : '<?= date('Y-m') ?>'}" class="pl-3 pr-2 py-2 border rounded-lg text-sm text-gray-600 focus:ring-indigo-500 focus:border-indigo-500">`;
        }
        container.innerHTML = html;
    }
    
    if(document.getElementById('modeSelector')) updateInputType();

    function switchTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('block'));
        
        document.getElementById('tab-btn-monitoring').className = "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center transition-colors";
        document.getElementById('tab-btn-approval').className = "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center transition-colors";

        document.getElementById('content-' + tabName).classList.remove('hidden');
        document.getElementById('content-' + tabName).classList.add('block');
        
        document.getElementById('tab-btn-' + tabName).className = "border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center transition-colors";
    }

    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.get('tab') === 'approval') switchTab('approval');

    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
    
    function openManualOut(id, name) { 
        document.getElementById('out_record_id').value = id; 
        document.getElementById('out_emp_name').textContent = name; 
        openModal('modalManualOut'); 
    }

    function openViewRecord(name, shift, timeIn, timeOut, notes) {
        document.getElementById('view_emp_name').textContent = name;
        document.getElementById('view_shift').textContent = shift;
        document.getElementById('view_in').textContent = timeIn;
        document.getElementById('view_out').textContent = timeOut;
        document.getElementById('view_notes').textContent = (notes == '' ? '-' : notes);
        openModal('modalViewRecord');
    }
    function openEditRecord(id, name, shiftId, timeIn, timeOut, notes) {
        document.getElementById('edit_record_id').value = id;
        document.getElementById('edit_emp_name').textContent = name;
        document.getElementById('edit_check_in').value = timeIn;
        document.getElementById('edit_check_out').value = timeOut;
        document.getElementById('edit_notes').value = notes;
        openModal('modalEditRecord');
    }
</script>