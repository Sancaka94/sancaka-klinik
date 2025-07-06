<?php
// File: app/controllers/AdminController.php

// Muat model-model yang diperlukan
require_once BASE_PATH . '/app/models/User.php';
require_once BASE_PATH . '/app/models/JanjiTemu.php';

class AdminController {
    private $userModel;
    private $janjiTemuModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $database = new Database();
        $conn = $database->getConnection();
        $this->userModel = new User($conn);
        $this->janjiTemuModel = new JanjiTemu($conn);
    }

    /**
     * Menampilkan halaman dashboard untuk admin.
     */
    public function dashboard() {
        // Middleware: Cek apakah pengguna adalah Admin atau Staf
        $this->checkAuth([2, 6]); // Angka 2=Admin, 6=Staf

        // Ambil data yang relevan untuk admin
        // Anda perlu membuat method countByRole di User.php
        // $total_pasien = $this->userModel->countByRole(4);
        // $total_dokter = $this->userModel->countByRole(3);
        $janji_hari_ini = $this->janjiTemuModel->countJanjiByDate(date('Y-m-d'));

        // Muat file view dari folder views/admin/
        require_once BASE_PATH . '/app/views/admin/dashboard.php';
    }

    /**
     * Fungsi helper untuk memeriksa otentikasi dan otorisasi peran.
     */
    private function checkAuth(array $allowed_roles) {
        if (!isset($_SESSION['user'])) {
            header("Location: /auth/login?error=Anda harus login terlebih dahulu.");
            exit;
        }
        if (!in_array($_SESSION['user']['id_peran'], $allowed_roles)) {
            die("Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.");
        }
    }
}
