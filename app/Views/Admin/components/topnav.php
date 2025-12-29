<!-- File: app/Views/Admin/components/topnav.php -->
<?php
// Top Navigation Component
$current_user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
$unread_notifications = isset($unread_count) ? $unread_count : 0;
?>

<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
        <!-- Left Side: Menu Toggle -->
        <button id="sidebar-toggle" class="lg:hidden text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <!-- Right Side: Notifications & Profile -->
        <div class="flex items-center space-x-6">
            <!-- Notification Dropdown -->
            <div class="relative">
                <button id="notification-btn" class="relative text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <?php if($unread_notifications > 0): ?>
                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full"><?= $unread_notifications ?></span>
                    <?php endif; ?>
                </button>
                
                <!-- Notification Dropdown Panel -->
                <div id="notification-panel" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl z-50">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-900">Notifikasi</h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <!-- Notifications akan dimuat via JS -->
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="relative">
                <button id="profile-btn" class="flex items-center space-x-2 text-gray-600 hover:text-gray-900">
                    <img src="<?= $current_user['photo_path'] ?? '/assets/images/default-avatar.png' ?>" alt="Profile" class="w-8 h-8 rounded-full">
                    <span><?= substr($current_user['full_name'] ?? 'User', 0, 20) ?></span>
                </button>
                
                <!-- Profile Dropdown Menu -->
                <div id="profile-panel" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl z-50">
                    <a href="/admin/profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Lihat Profil</a>
                    <a href="/admin/change-password" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Ubah Password</a>
                    <hr class="my-2">
                    <a href="/logout" class="block px-4 py-2 text-red-700 hover:bg-red-50">Log-out</a>
                </div>
            </div>
        </div>
    </div>
</nav>
