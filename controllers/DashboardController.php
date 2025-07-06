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

class DashboardController {

    private $conn;

    public function __construct() {
        // [SEMPURNA] Mengaktifkan koneksi database untuk controller ini
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Method default yang akan menampilkan halaman dashboard utama.
     * Logika ini akan mengarahkan pengguna ke view yang sesuai dengan perannya.
     */
    public function index() {
        // Langkah 1: Periksa apakah pengguna sudah login
        if (!isset($_SESSION['user']['id_pengguna'])) {
            // Jika belum, arahkan ke halaman login dengan pesan error
            header("Location: ?url=auth/login&error=Anda harus login terlebih dahulu.");
            exit;
        }

        // Langkah 2: Ambil data pengguna dari session
        $user = $_SESSION['user'];
        $id_peran = $user['id_peran'];

        // Langkah 3: [SEMPURNA] Muat halaman view yang sesuai dengan peran pengguna
        // Ini memastikan setiap peran mendapatkan tampilan dashboard yang benar.
        switch ($id_peran) {
            case 1: // Superadmin
                require_once __DIR__ . '/../views/dashboard/superadmin.php';
                break;
            case 2: // Admin
                require_once __DIR__ . '/../views/dashboard/admin.php';
                break;
            case 3: // Dokter
                require_once __DIR__ . '/../views/dashboard/dokter.php';
                break;
            case 4: // Pasien
                require_once __DIR__ . '/../views/dashboard/pasien.php';
                break;
            case 5: // Owner
                require_once __DIR__ . '/../views/dashboard/owner.php';
                break;
            default:
                // Jika peran tidak dikenali, logout dan kembali ke halaman utama
                session_destroy();
                header("Location: ?url=home&error=Peran tidak valid.");
                exit;
        }
    }
}
?>
