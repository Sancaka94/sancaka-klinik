<?php
require_once __DIR__ . '/../models/User.php';

class ProfileController {

    public function __construct() {
        // Pastikan session sudah dimulai
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Metode default, bisa diarahkan ke pengaturan.
     */
    public function index() {
        $this->pengaturan();
    }

    /**
     * Menampilkan halaman pengaturan profil pasien.
     */
    public function pengaturan() {
        // 1. Cek apakah pengguna sudah login
        if (!isset($_SESSION['user'])) {
            // Jika belum, alihkan ke halaman login
            header("Location: ?url=auth/login&error=Anda harus login untuk mengakses halaman ini.");
            exit;
        }

        // 2. Buat instance dari model User
        $userModel = new User();

        // 3. Ambil ID pengguna dari session
        $id_pengguna = $_SESSION['user']['id_pengguna'];

        // 4. Panggil fungsi baru untuk mendapatkan data profil lengkap
        $data_pasien = $userModel->getPatientProfileById($id_pengguna);

        // 5. Cek apakah data pasien ditemukan
        if ($data_pasien) {
            // Jika ditemukan, muat halaman view dan kirimkan datanya
            // Pastikan Anda memiliki file view di: /views/profile/pengaturan.php
            require __DIR__ . '/../views/profile/pengaturan.php';
        } else {
            // Jika tidak ditemukan, tampilkan pesan error
            // Ini adalah pesan yang Anda lihat di screenshot
            die("Error: Data pasien tidak ditemukan.");
        }
    }
}
