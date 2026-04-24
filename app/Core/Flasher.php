<?php

class Flasher {
    public static function setFlash($message, $type) {
        // Simpan pesan ke session biar bisa muncul di view
        $_SESSION['flash'] = [
            'message' => $message,
            'type' => $type
        ];
    }

    public static function flash() {
        if (isset($_SESSION['flash'])) {
            // Ini helper optional kalau mau echo langsung (tapi kita handle di View manual)
            echo '<div class="alert alert-' . $_SESSION['flash']['type'] . '">
                    ' . $_SESSION['flash']['message'] . '
                  </div>';
            unset($_SESSION['flash']);
        }
    }
}