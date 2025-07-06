<?php
// File: app/controllers/SuperadminController.php

// Muat model-model yang diperlukan
require_once BASE_PATH . '/app/models/User.php';

class SuperadminController {
    private $userModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $database = new Database();
        $conn = $database->getConnection();
        $this->userModel = new User($conn);
    }

    /**
     * Menampilkan halaman dashboard untuk superadmin.
     */
    public function dashboard() {
        // Middleware: Cek apakah pengguna adalah Superadmin atau Owner
        $this->checkAuth([1, 5]); // Angka 1=Superadmin, 5=Owner

        // Ambil data yang relevan untuk superadmin
        // Anda perlu membuat method countAll di User.php
        // $total_pengguna = $this->userModel->countAll();
        // $pendapatan_bulan_ini = ...

        // Muat file view dari folder views/superadmin/
        require_once BASE_PATH . '/app/views/superadmin/dashboard.php';
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
