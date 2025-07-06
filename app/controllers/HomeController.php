<?php

class HomeController {
    
    /**
     * Menampilkan halaman utama (landing page) aplikasi.
     */
    public function index() {
        // [DIPERBAIKI] Path disesuaikan dengan struktur folder Anda.
        $view_file = __DIR__ . '/../views/home.php';

        if (file_exists($view_file)) {
            // Jika file ditemukan, muat halamannya.
            require_once $view_file;
        } else {
            // Jika tidak, tampilkan pesan error yang lebih jelas.
            die("Error: File view tidak ditemukan. Pastikan Anda memiliki file di path: " . $view_file);
        }
    }
}
