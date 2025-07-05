<?php
// controllers/NotifikasiController.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/Notifikasi.php';

class NotifikasiController {

    /**
     * Menampilkan halaman daftar notifikasi.
     */
    public function index() {
        $this->checkLogin();

        $notifikasiModel = new Notifikasi();
        
        // Ambil semua notifikasi untuk pengguna yang sedang login
        $daftar_notifikasi = $notifikasiModel->getNotifikasiByPenggunaId($_SESSION['user']['id_pengguna']);
        
        // (Nantinya, di sini Anda bisa menambahkan logika untuk menandai notifikasi sebagai sudah dibaca)

        // Muat view dan teruskan data notifikasi ke dalamnya
        require __DIR__ . '/../views/notifikasi/index.php';
    }

    /**
     * Fungsi internal untuk memeriksa apakah pengguna sudah login.
     */
    private function checkLogin() {
        if (!isset($_SESSION['user'])) {
            header('Location: ?url=auth/login&error=Anda harus login terlebih dahulu.');
            exit;
        }
    }
}
