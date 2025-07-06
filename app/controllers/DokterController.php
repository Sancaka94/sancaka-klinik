<?php
// File: app/controllers/DokterController.php

// Muat model-model yang diperlukan
require_once BASE_PATH . '/app/models/JanjiTemu.php';

class DokterController {
    private $janjiTemuModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $database = new Database();
        $conn = $database->getConnection();
        $this->janjiTemuModel = new JanjiTemu($conn);
    }

    /**
     * Menampilkan halaman dashboard untuk dokter.
     */
    public function dashboard() {
        // Middleware: Cek apakah pengguna adalah Dokter
        $this->checkAuth(3); // Angka 3 adalah id_peran untuk Dokter

        $user = $_SESSION['user'];
        $id_dokter = $user['id_pengguna'];

        // Ambil data yang relevan untuk dokter
        $janji_hari_ini = $this->janjiTemuModel->getJanjiByDokter($id_dokter, date('Y-m-d'));
        // Anda bisa menambahkan data lain di sini, misal:
        // $pasien_selesai_hari_ini = ...

        // Muat file view dari folder views/dokter/
        require_once BASE_PATH . '/app/views/dokter/dashboard.php';
    }

    /**
     * Fungsi helper untuk memeriksa otentikasi dan otorisasi peran.
     */
    private function checkAuth($required_role_id) {
        if (!isset($_SESSION['user'])) {
            header("Location: /auth/login?error=Anda harus login terlebih dahulu.");
            exit;
        }
        if ($_SESSION['user']['id_peran'] != $required_role_id) {
            die("Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.");
        }
    }
}
