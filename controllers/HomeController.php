<?php

class HomeController {
    
    /**
     * Menampilkan halaman utama (landing page) aplikasi.
     */
    public function index() {
        // Memuat file view untuk halaman utama
        require_once __DIR__ . '/../views/home.php';
    }
}
