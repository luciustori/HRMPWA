<?php
// File: config/config.php

// Cek dulu apakah BASEURL sudah didefinisikan sebelumnya
if (!defined('BASEURL')) {
    // URL Bersih (Tanpa /public) -> Pastikan sesuai setting Laragon Anda
    define('BASEURL', 'https://absenpwa.test');
}

if (!defined('APP_NAME')) {
    define('APP_NAME', 'AbsenPWA - HRM System');
}

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__) . '/app');
}