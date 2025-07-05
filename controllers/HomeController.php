<?php
// controllers/HomeController.php

// **PERBAIKAN:** Menghapus semua kode yang berhubungan dengan database.
// Class ini sekarang menjadi sangat sederhana.

class HomeController {

    /**
     * Menampilkan halaman utama (homepage) untuk pengunjung.
     */
    public function index() {
        // Fungsi ini hanya memiliki satu tugas: memuat file view untuk halaman home.
        // Tidak perlu koneksi database atau logika kompleks lainnya.
        require_once __DIR__ . '/../views/home/index.php';
    }

    // Anda bisa menambahkan method lain di sini untuk halaman publik lainnya
    // Contoh: public function tentang_kami() { require_once __DIR__ . '/../views/home/tentang.php'; }
}
