<?php
// File: controllers/DashboardController.php

// Aktifkan error reporting untuk debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Mulai session jika belum ada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Memuat file-file yang diperlukan
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
// [SEMPURNA] Memuat model lain yang diperlukan untuk dashboard
require_once __DIR__ . '/../models/RekamMedis.php';
require_once __DIR__ . '/../models/JanjiTemu.php';

class DashboardController {

    private $conn;
    private $userModel;
    private $rekamMedisModel;
    private $janjiTemuModel;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        // [SEMPURNA] Membuat instance dari semua model yang diperlukan
        $this->userModel = new User($this->conn);
        $this->rekamMedisModel = new RekamMedis($this->conn);
        $this->janjiTemuModel = new JanjiTemu($this->conn);
    }

    /**
     * Method default yang akan menampilkan dashboard utama berdasarkan peran.
     */
    public function index() {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
        }

        $id_peran = $_SESSION['user']['id_peran'];

        switch ($id_peran) {
            case 1: $this->superadmin(); break;
            case 2: $this->admin(); break;
            case 3: $this->dokter(); break;
            case 4: $this->pasien(); break;
            case 5: $this->owner(); break;
            // [SEMPURNA] Menambahkan case untuk peran staf
            case 6: $this->staf(); break;
            default:
                session_destroy();
                header("Location: ?url=home&error=Peran tidak valid.");
                exit;
        }
    }

    /**
     * [SEMPURNA] Menampilkan dashboard untuk Pasien dengan data dinamis.
     */
    public function pasien() {
        if (!$this->isLoggedIn() || $_SESSION['user']['id_peran'] != 4) {
            $this->redirectToLogin("Akses ditolak. Silakan login sebagai Pasien.");
        }
        
        $id_pengguna = $_SESSION['user']['id_pengguna'];
        
        // Mengambil data spesifik untuk pasien dari model
        $jumlah_kunjungan = $this->rekamMedisModel->countKunjungan($id_pengguna);
        $janji_aktif = $this->janjiTemuModel->countJanjiAktif($id_pengguna);
        $riwayat_rekam_medis = $this->rekamMedisModel->getRiwayatByPasien($id_pengguna);
        $janji_temu = $this->janjiTemuModel->getJanjiByPasien($id_pengguna);
        
        require_once __DIR__ . '/../views/dashboard/pasien.php';
    }

    /**
     * [SEMPURNA] Menampilkan dashboard untuk Dokter dengan data dinamis.
     */
    public function dokter() {
        if (!$this->isLoggedIn() || $_SESSION['user']['id_peran'] != 3) {
            $this->redirectToLogin("Akses ditolak. Silakan login sebagai Dokter.");
        }
        
        $id_dokter = $_SESSION['user']['id_pengguna'];
        $janji_hari_ini = $this->janjiTemuModel->getJanjiByDokter($id_dokter, date('Y-m-d'));
        
        require_once __DIR__ . '/../views/dashboard/dokter.php';
    }

    /**
     * [SEMPURNA] Menampilkan dashboard untuk Admin dengan data dinamis.
     */
    public function admin() {
        if (!$this->isLoggedIn() || $_SESSION['user']['id_peran'] != 2) {
            $this->redirectToLogin("Akses ditolak. Silakan login sebagai Admin.");
        }
        
        $total_pasien = $this->userModel->countByRole(4);
        $total_dokter = $this->userModel->countByRole(3);
        $janji_hari_ini = $this->janjiTemuModel->countJanjiByDate(date('Y-m-d'));
        
        require_once __DIR__ . '/../views/dashboard/admin.php';
    }

    /**
     * [SEMPURNA] Menampilkan dashboard untuk Superadmin.
     */
    public function superadmin() {
        if (!$this->isLoggedIn() || $_SESSION['user']['id_peran'] != 1) {
            $this->redirectToLogin("Akses ditolak. Silakan login sebagai Superadmin.");
        }
        require_once __DIR__ . '/../views/dashboard/superadmin.php';
    }
    
    /**
     * [SEMPURNA] Menampilkan dashboard untuk Owner.
     */
    public function owner() {
        if (!$this->isLoggedIn() || $_SESSION['user']['id_peran'] != 5) {
            $this->redirectToLogin("Akses ditolak. Silakan login sebagai Owner.");
        }
        // Owner bisa melihat dashboard yang sama dengan Superadmin
        require_once __DIR__ . '/../views/dashboard/superadmin.php';
    }

    /**
     * [BARU & SEMPURNA] Menampilkan dashboard untuk Staf.
     */
    public function staf() {
        if (!$this->isLoggedIn() || $_SESSION['user']['id_peran'] != 6) {
            $this->redirectToLogin("Akses ditolak. Silakan login sebagai Staf.");
        }
        // Staf bisa melihat dashboard yang sama dengan Admin, atau buat view khusus
        require_once __DIR__ . '/../views/dashboard/admin.php';
    }

    /**
     * Fungsi helper untuk memeriksa status login.
     */
    private function isLoggedIn() {
        return isset($_SESSION['user']['id_pengguna']);
    }

    /**
     * Fungsi helper untuk mengarahkan ke halaman login.
     */
    private function redirectToLogin($message = "Anda harus login terlebih dahulu.") {
        header("Location: ?url=auth/login&error=" . urlencode($message));
        exit;
    }
}
?>
