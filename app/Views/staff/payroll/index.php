<style>
    .payroll-card {
        background: white; border: 1px solid #e2e8f0; border-radius: 1rem;
        transition: all 0.2s ease; position: relative; overflow: hidden;
    }
    .payroll-card:hover {
        transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); border-color: #cbd5e1;
    }
    .status-line { position: absolute; left: 0; top: 0; bottom: 0; width: 4px; }
    .status-paid { background: #10b981; } .status-draft { background: #94a3b8; }
    .font-mono-tech { font-family: 'Courier New', Courier, monospace; letter-spacing: -0.5px; }
</style>

<div class="max-w-5xl mx-auto px-4 py-8 font-sans">

    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Slip Gaji</h1>
            <p class="text-sm text-slate-500 mt-1">Riwayat pendapatan & potongan Anda.</p>
        </div>
        <form class="flex items-center gap-2 bg-white border border-slate-300 rounded-lg px-3 py-1.5 shadow-sm">
            <i class="far fa-calendar-alt text-slate-400"></i>
            <select name="year" onchange="this.form.submit()" class="bg-transparent text-sm font-bold text-slate-700 outline-none cursor-pointer">
                <?php for($y=date('Y'); $y>=2024; $y--): ?>
                    <option value="<?= $y ?>" <?= $data['filter_year'] == $y ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </form>
    </div>

    <?php if(!empty($data['estimate'])): ?>
    <div class="mb-8">
        <div class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
            <div class="absolute right-0 top-0 opacity-10 p-6 transform rotate-12 scale-150"><i class="fas fa-wallet text-9xl"></i></div>
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                        <span class="text-amber-400 text-[10px] font-bold uppercase tracking-widest">ESTIMASI SEMENTARA</span>
                        <span class="text-slate-400 text-xs font-mono border-l border-slate-600 pl-2 ml-1"><?= date('F Y') ?></span>
                    </div>
                    <h2 class="text-4xl font-bold font-mono-tech tracking-tight">
                        Rp <?= number_format($data['estimate']['net_salary'], 0, ',', '.') ?>
                    </h2>
                    <p class="text-slate-400 text-xs mt-2 flex items-center gap-1"><i class="fas fa-info-circle"></i> Nominal dapat berubah sesuai absensi.</p>
                </div>
                <div class="bg-white/5 rounded-xl p-4 min-w-[220px] backdrop-blur-md border border-white/10">
                    <div class="flex justify-between text-xs mb-2">
                        <span class="text-slate-400">Gaji Pokok</span>
                        <span class="font-mono text-slate-200"><?= number_format($data['estimate']['basic_salary'] / 1000, 0) ?>k</span>
                    </div>
                    <div class="flex justify-between text-xs mb-2">
                        <span class="text-slate-400">Potongan</span>
                        <span class="font-mono text-rose-400">-<?= number_format($data['estimate']['total_deduction'] / 1000, 0) ?>k</span>
                    </div>
                    <div class="h-px bg-white/10 my-2"></div>
                    <div class="text-center text-[10px] text-amber-400 font-bold uppercase tracking-wider">Belum Final</div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="space-y-4">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Riwayat Pembayaran</h3>
        <?php if(empty($data['history'])): ?>
            <div class="text-center py-12 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50">
                <i class="fas fa-file-invoice-dollar text-4xl text-slate-300 mb-3"></i>
                <p class="text-slate-500 font-medium text-sm">Belum ada riwayat gaji tahun ini.</p>
            </div>
        <?php else: ?>
            <?php foreach($data['history'] as $pay): ?>
                <a href="<?= BASEURL ?>/staff/payroll/detail/<?= $pay['id'] ?>" class="flex flex-col md:flex-row items-center justify-between p-5 payroll-card group">
                    <div class="status-line <?= $pay['status'] == 'paid' ? 'status-paid' : 'status-draft' ?>"></div>
                    <div class="flex items-center gap-4 w-full md:w-auto">
                        <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-slate-500 border border-slate-100 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition">
                            <i class="fas fa-file-invoice text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-base group-hover:text-indigo-700 transition">
                                <?= $pay['period_name'] ?? 'Gaji Bulan '.date('F', mktime(0,0,0,$pay['period_month'],10)) ?>
                            </h4>
                            <p class="text-xs text-slate-400 font-mono">ID: #TRX-<?= str_pad($pay['id'], 6, '0', STR_PAD_LEFT) ?></p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between w-full md:w-auto gap-8 mt-4 md:mt-0">
                        <div class="text-right">
                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Take Home Pay</p>
                            <p class="text-lg font-bold text-emerald-600 font-mono-tech">Rp <?= number_format($pay['net_salary'], 0, ',', '.') ?></p>
                        </div>
                        <div class="text-right min-w-[80px]">
                            <?php if($pay['status'] == 'paid'): ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-100"><i class="fas fa-check-circle"></i> PAID</span>
                                <p class="text-[10px] text-slate-400 mt-1"><?= $pay['payment_date'] ? date('d/m/y', strtotime($pay['payment_date'])) : '-' ?></p>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 text-[10px] font-bold border border-slate-200">PROCESS</span>
                            <?php endif; ?>
                        </div>
                        <i class="fas fa-chevron-right text-slate-300 text-xs group-hover:translate-x-1 transition"></i>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>