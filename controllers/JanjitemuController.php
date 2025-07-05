<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Dokter.php'; // Kita akan butuh model Dokter
require_once __DIR__ . '/../models/JanjiTemu.php'; // Kita juga butuh model JanjiTemu

class JanjiTemuController {

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Menampilkan form untuk membuat janji temu baru.
     */
    public function buat() {
        // 1. Cek apakah pengguna sudah login
        if (!isset($_SESSION['user'])) {
            header("Location: ?url=auth/login&error=Anda harus login untuk membuat janji temu.");
            exit;
        }

        // 2. Dapatkan data pasien yang sedang login
        $userModel = new User();
        $id_pengguna = $_SESSION['user']['id_pengguna'];
        $data_pasien = $userModel->getPatientProfileById($id_pengguna);

        // 3. Cek apakah data pasien ditemukan
        if (!$data_pasien) {
            // Ini adalah error yang Anda lihat
            die("Error: Tidak dapat menemukan data pasien yang terkait dengan akun Anda.");
        }
        
        // 4. Dapatkan daftar dokter yang tersedia
        $dokterModel = new Dokter();
        $daftar_dokter = $dokterModel->getAllDokter();

        // 5. Muat halaman view dan kirimkan data yang dibutuhkan
        require __DIR__ . '/../views/janjitemu/buat.php';
    }

    /**
     * Menyimpan janji temu baru ke database.
     */
    public function simpan() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?url=janjitemu/buat");
            exit;
        }

        if (!isset($_SESSION['user'])) {
            header("Location: ?url=auth/login&error=Sesi Anda telah berakhir.");
            exit;
        }

        // Kumpulkan data dari form
        $data = [
            'id_pasien' => $_POST['id_pasien'],
            'id_dokter' => $_POST['id_dokter'],
            'tanggal_temu' => $_POST['tanggal_temu'],
            'waktu_temu' => $_POST['waktu_temu'],
            'keluhan' => $_POST['keluhan'],
            'status' => 'Dijadwalkan' // Status awal
        ];

        $janjiTemuModel = new JanjiTemu();

        if ($janjiTemuModel->create($data)) {
            // Jika berhasil, alihkan ke halaman daftar janji temu
            header("Location: ?url=janjitemu/riwayat&status=sukses");
        } else {
            // Jika gagal, kembali ke form dengan pesan error
            header("Location: ?url=janjitemu/buat&error=Gagal membuat janji temu.");
        }
        exit;
    }
    
    /**
     * Menampilkan riwayat janji temu pasien.
     */
    public function riwayat() {
        // Logika untuk menampilkan riwayat janji temu bisa ditambahkan di sini
        echo "Halaman riwayat janji temu.";
    }
}
