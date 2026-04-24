<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HRIS PRO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F8FAFC; }
    </style>
</head>
<body class="h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
        
        <div class="bg-slate-900 p-8 text-center">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-white/10 rounded-xl mb-4 text-white font-bold text-2xl">H</div>
            <h1 class="text-white text-2xl font-bold tracking-tight">HRIS <span class="text-blue-500">PRO</span></h1>
            <p class="text-slate-400 text-sm mt-2">Masuk untuk mengelola perusahaan.</p>
        </div>

        <div class="p-8">
            
            <?php if (isset($_SESSION['flash'])) : ?>
                <div class="bg-red-50 text-red-600 px-4 py-3 rounded-lg text-sm font-medium mb-6 flex items-center gap-2">
                    <?= $_SESSION['flash']['message']; unset($_SESSION['flash']); ?>
                </div>
            <?php endif; ?>

            <form action="<?= BASEURL; ?>/Admin/LoginController/login" method="post">
                <div class="mb-5">
                    <label class="block text-slate-600 text-sm font-bold mb-2">Username</label>
                    <input type="text" name="username" autocomplete="username" class="w-full px-4 py-3 rounded-lg bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white focus:outline-none transition-all font-medium text-slate-800" placeholder="X-10.001" required>
                </div>

                <div class="mb-8">
                    <label class="block text-slate-600 text-sm font-bold mb-2">Password</label>
                    <input type="password" name="password" autocomplete="current-password" class="w-full px-4 py-3 rounded-lg bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white focus:outline-none transition-all font-medium text-slate-800" placeholder="••••••••" required>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition-all transform active:scale-95 shadow-lg shadow-blue-600/30">
                    Masuk Sekarang
                </button>
            </form>
        </div>
        
        <div class="bg-slate-50 p-4 text-center border-t border-slate-100">
            <p class="text-xs text-slate-400 font-medium">&copy; <?= date('Y') ?> PT Jogjatama Vishesha - XT Square</p>
        </div>

    </div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tangkap elemen input username
    const usernameInput = document.querySelector('input[name="username"]');

    if (usernameInput) {
        // Set batas maksimal karakter ke 8 (contoh: A-10.001)
        usernameInput.setAttribute('maxlength', '8');

        usernameInput.addEventListener('input', function(e) {
            // Hapus semua karakter aneh, sisakan huruf dan angka saja
            let val = this.value.replace(/[^a-zA-Z0-9]/g, '');

            if (val.length > 0) {
                // 1. Karakter pertama wajib huruf kapital
                let formatted = val.charAt(0).toUpperCase();

                // 2. Tambahkan strip (-) setelah huruf pertama
                if (val.length > 1) {
                    // Ambil maksimal 2 digit angka setelah huruf
                    let numbers1 = val.substring(1, 3).replace(/[^0-9]/g, '');
                    formatted += '-' + numbers1;

                    // 3. Tambahkan titik (.) setelah 2 angka pertama
                    if (val.length > 3) {
                        // Ambil sisa angkanya (maksimal 3 digit)
                        let numbers2 = val.substring(3, 6).replace(/[^0-9]/g, '');
                        if (numbers2.length > 0) {
                            formatted += '.' + numbers2;
                        }
                    }
                }
                
                // Terapkan format yang udah dirapikan ke dalam input
                this.value = formatted;
            } else {
                this.value = '';
            }
        });
    }
});
</script>
</body>
</html>