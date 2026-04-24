<?php
// File: app/init.php

date_default_timezone_set('Asia/Jakarta');

require_once 'config/config.php'; // Panggil config utama duluan
require_once 'Core/Database.php'; // Database sekarang otomatis baca config
require_once 'Core/App.php';
require_once 'Core/Controller.php';
require_once 'Helpers/Flasher.php';