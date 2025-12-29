<!-- File: app/Views/Admin/layouts/main.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="HRM PWA - Human Resource Management System">
    <meta name="theme-color" content="#1F2937">
    
    <title><?= $page_title ?? 'HRM PWA' ?> | Admin Panel</title>
    
    <!-- Tailwind CSS (Local) -->
    <link rel="stylesheet" href="/assets/css/tailwind.css">
    <link rel="stylesheet" href="/assets/css/admin-theme.css">
    <link rel="stylesheet" href="/assets/css/components.css">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/png" href="/assets/images/favicon.png">
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <?php require_once '../app/Views/Admin/components/sidebar.php'; ?>

    <!-- Main Content -->
    <div id="main-content" class="lg:ml-64">
        <!-- Header -->
        <?php require_once '../app/Views/Admin/components/header.php'; ?>

        <!-- Top Navigation -->
        <?php require_once '../app/Views/Admin/components/topnav.php'; ?>

        <!-- Page Content -->
        <main class="max-w-7xl mx-auto px-4 py-8">
            <?php require_once $page_content; ?>
        </main>

        <!-- Footer -->
        <?php require_once '../app/Views/Admin/components/footer.php'; ?>
    </div>

    <!-- Scripts -->
    <script src="/assets/js/shared/utils.js"></script>
    <script src="/assets/js/admin/dashboard.js"></script>
    <script>
        // Sidebar toggle
        document.getElementById('sidebar-toggle')?.addEventListener('click', () => {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        });

        // Notification dropdown
        document.getElementById('notification-btn')?.addEventListener('click', () => {
            document.getElementById('notification-panel').classList.toggle('hidden');
        });

        // Profile dropdown
        document.getElementById('profile-btn')?.addEventListener('click', () => {
            document.getElementById('profile-panel').classList.toggle('hidden');
        });
    </script>
</body>
</html>
