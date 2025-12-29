<!-- app/Views/PWA/layouts/main.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#1f2937">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="HRM PWA">
    
    <title><?= $page_title ?? 'HRM PWA' ?> - Human Resource Management</title>
    
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/assets/img/icon-192.png">
    <link rel="stylesheet" href="/assets/css/tailwind.css">
    <link rel="stylesheet" href="/assets/css/pwa-theme.css">
    <link rel="icon" href="/assets/img/favicon.ico">
</head>
<body class="bg-gray-900 text-gray-100 antialiased">
    <!-- Top Nav -->
    <?php require '../app/Views/PWA/components/topnav.php'; ?>

    <!-- Main Content -->
    <main class="pb-20 pt-16">
        <div class="max-w-lg mx-auto px-3">
            <?php 
            if (isset($_SESSION['success'])): ?>
                <div class="mb-4 p-3 bg-green-500/20 border border-green-500 rounded-lg text-green-400 text-sm">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php 
            if (isset($_SESSION['error'])): ?>
                <div class="mb-4 p-3 bg-red-500/20 border border-red-500 rounded-lg text-red-400 text-sm">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php require $page_content; ?>
        </div>
    </main>

    <!-- Bottom Nav -->
    <?php require '../app/Views/PWA/components/bottom-nav.php'; ?>

    <!-- Scripts -->
    <script src="/assets/js/pwa/geo-location.js"></script>
    <script src="/assets/js/pwa/checkin.js"></script>
    <script src="/assets/js/pwa/requests.js"></script>
    <script src="/assets/js/pwa/dashboard.js"></script>

    <script>
    // Register Service Worker
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/service-worker.js')
                .then(reg => console.log('SW registered'))
                .catch(err => console.log('SW registration failed:', err));
        });
    }

    // Handle PWA install prompt
    let deferredPrompt;
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
    });
    </script>
</body>
</html>
