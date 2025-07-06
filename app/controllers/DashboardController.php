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
require_once BASE_PATH . '/../config/database.php';
require_once BASE_PATH . '/../models/User.php';
require_once BASE_PATH . '/../models/RekamMedis.php';
require_once BASE_PATH . '/../models/JanjiTemu.php';
// [BARU] Memuat model Notifikasi
require_once BASE_PATH . '/../models/Notifikasi.php';

class DashboardController {

    private $conn;
    private $userModel;
    private $rekamMedisModel;
    private $janjiTemuModel;
    // [BARU] Properti untuk model Notifikasi
    private $notifikasiModel;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->userModel = new User($this->conn);
        $this->rekamMedisModel = new RekamMedis($this->conn);
        $this->janjiTemuModel = new JanjiTemu($this->conn);
        // [BARU] Membuat instance dari NotifikasiModel
        $this->notifikasiModel = new Notifikasi($this->conn);
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
            case 6: $this->staf(); break;
            default:
                session_destroy();
                header("Location: ?url=home&error=Peran tidak valid.");
                exit;
        }
    }

    /**
     * Menampilkan dashboard untuk Pasien dengan data dinamis.
     */
    public function pasien() {
        if (!$this->isLoggedIn() || $_SESSION['user']['id_peran'] != 4) {
            $this->redirectToLogin("Akses ditolak. Silakan login sebagai Pasien.");
        }
        
        $user = $_SESSION['user'];
        $id_pengguna = $user['id_pengguna'];
        
        // Mengambil data spesifik untuk pasien dari model
        $jumlah_kunjungan = $this->rekamMedisModel->countKunjungan($id_pengguna);
        $janji_aktif = $this->janjiTemuModel->countJanjiAktif($id_pengguna);
        $riwayat_rekam_medis = $this->rekamMedisModel->getRiwayatByPasien($id_pengguna);
        $janji_temu = $this->janjiTemuModel->getJanjiByPasien($id_pengguna);
        
        // [BARU] Mengambil data notifikasi dari database
        $notifikasi_belum_dibaca = $this->notifikasiModel->countUnreadByUserId($id_pengguna);
        $notifikasi_list = $this->notifikasiModel->getByUserId($id_pengguna);
        
        // Mengirim semua data ke view
        require_once BASE_PATH . '/../views/dashboard/pasien.php';
    }

    /**
     * [LENGKAP] Menampilkan dashboard untuk Dokter.
     */
    public function dokter() {
        if (!$this->isLoggedIn() || $_SESSION['user']['id_peran'] != 3) {
            $this->redirectToLogin("Akses ditolak. Silakan login sebagai Dokter.");
        }
        
        $id_dokter = $_SESSION['user']['id_pengguna'];
        // Anda bisa menambahkan pengambilan data lain di sini
        // Contoh:
        // $pasien_hari_ini = $this->janjiTemuModel->countPasienByDokter($id_dokter, date('Y-m-d'));
        // $janji_selesai = $this->janjiTemuModel->countJanjiSelesaiByDokter($id_dokter, date('Y-m-d'));
        
        require_once BASE_PATH . '/../views/dashboard/dokter.php';
    }

    /**
     * [LENGKAP] Menampilkan dashboard untuk Admin.
     */
    public function admin() {
        if (!$this->isLoggedIn() || $_SESSION['user']['id_peran'] != 2) {
            $this->redirectToLogin("Akses ditolak. Silakan login sebagai Admin.");
        }
        
        // Anda perlu membuat method countByRole di User.php
        // $total_pasien = $this->userModel->countByRole(4); 
        // $janji_hari_ini = $this->janjiTemuModel->countJanjiByDate(date('Y-m-d'));

        require_once BASE_PATH . '/../views/dashboard/admin.php';
    }

    /**
     * [LENGKAP] Menampilkan dashboard untuk Superadmin.
     */
    public function superadmin() {
        if (!$this->isLoggedIn() || $_SESSION['user']['id_peran'] != 1) {
            $this->redirectToLogin("Akses ditolak. Silakan login sebagai Superadmin.");
        }
        require_once BASE_PATH . '/../views/dashboard/superadmin.php';
    }
    
    /**
     * [LENGKAP] Menampilkan dashboard untuk Owner.
     */
    public function owner() {
        if (!$this->isLoggedIn() || $_SESSION['user']['id_peran'] != 5) {
            $this->redirectToLogin("Akses ditolak. Silakan login sebagai Owner.");
        }
        // Owner bisa melihat dashboard yang sama dengan Superadmin
        require_once BASE_PATH . '/../views/dashboard/superadmin.php';
    }

    /**
     * [LENGKAP] Menampilkan dashboard untuk Staf.
     */
    public function staf() {
        if (!$this->isLoggedIn() || $_SESSION['user']['id_peran'] != 6) {
            $this->redirectToLogin("Akses ditolak. Silakan login sebagai Staf.");
        }
        // Staf bisa melihat dashboard yang sama dengan Admin
        require_once BASE_PATH . '/../views/dashboard/admin.php';
    }

    private function isLoggedIn() {
        return isset($_SESSION['user']['id_pengguna']);
    }

    private function redirectToLogin($message = "Anda harus login terlebih dahulu.") {
        header("Location: ?url=auth/login&error=" . urlencode($message));
        exit;
    }
}
?>
