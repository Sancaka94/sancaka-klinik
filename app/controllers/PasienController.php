<?php
// File: app/controllers/PasienController.php

// Muat model-model yang diperlukan
require_once BASE_PATH . '/app/models/User.php';
require_once BASE_PATH . '/app/models/RekamMedis.php';
require_once BASE_PATH . '/app/models/JanjiTemu.php';
require_once BASE_PATH . '/app/models/Notifikasi.php';

class PasienController {
    private $userModel;
    private $rekamMedisModel;
    private $janjiTemuModel;
    private $notifikasiModel;

    public function __construct() {
        // Pastikan session sudah dimulai
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Buat koneksi database dan instance dari semua model
        $database = new Database();
        $conn = $database->getConnection();
        $this->userModel = new User($conn);
        $this->rekamMedisModel = new RekamMedis($conn);
        $this->janjiTemuModel = new JanjiTemu($conn);
        $this->notifikasiModel = new Notifikasi($conn);
    }

    /**
     * Menampilkan halaman dashboard untuk pasien.
     */
    public function dashboard() {
        // Middleware: Cek apakah pengguna adalah pasien
        $this->checkAuth(4); // Angka 4 adalah id_peran untuk Pasien

        $user = $_SESSION['user'];
        $id_pengguna = $user['id_pengguna'];

        // Ambil semua data yang diperlukan untuk dashboard dari model
        $jumlah_kunjungan = $this->rekamMedisModel->countKunjungan($id_pengguna);
        $janji_aktif = $this->janjiTemuModel->countJanjiAktif($id_pengguna);
        $riwayat_rekam_medis = $this->rekamMedisModel->getRiwayatByPasien($id_pengguna);
        $janji_temu = $this->janjiTemuModel->getJanjiByPasien($id_pengguna);
        $notifikasi_belum_dibaca = $this->notifikasiModel->countUnreadByUserId($id_pengguna);
        $notifikasi_list = $this->notifikasiModel->getByUserId($id_pengguna);

        // Muat file view dan kirimkan semua data ke dalamnya
        require_once BASE_PATH . '/app/views/pasien/dashboard.php';
    }

    // Anda bisa menambahkan method lain di sini, misalnya:
    // public function riwayatRekamMedis() { ... }
    // public function detailJanjiTemu($id) { ... }

    /**
     * Fungsi helper untuk memeriksa otentikasi dan otorisasi peran.
     */
    private function checkAuth($required_role_id) {
        if (!isset($_SESSION['user'])) {
            header("Location: /auth/login?error=Anda harus login terlebih dahulu.");
            exit;
        }
        if ($_SESSION['user']['id_peran'] != $required_role_id) {
            // Jika peran tidak sesuai, bisa diarahkan ke halaman error atau dashboard default
            die("Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.");
        }
    }
}
