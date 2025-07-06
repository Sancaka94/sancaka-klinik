<?php
// File: controllers/NotifikasiController.php

// Mulai session jika belum ada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Notifikasi.php';

class NotifikasiController {
    private $conn;
    private $notifikasiModel;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->notifikasiModel = new Notifikasi($this->conn);
    }

    /**
     * Menampilkan daftar notifikasi untuk pengguna yang sedang login.
     */
    public function index() {
        if (!isset($_SESSION['user']['id_pengguna'])) {
            header("Location: ?url=auth/login");
            exit;
        }

        $id_pengguna = $_SESSION['user']['id_pengguna'];
        $notifikasi = $this->notifikasiModel->getByUserId($id_pengguna);

        // Muat halaman view untuk menampilkan notifikasi
        require_once __DIR__ . '/../views/notifikasi/index.php';
    }

    /**
     * [SEMPURNA] Menandai satu notifikasi sebagai sudah dibaca dan mengarahkan ke link-nya.
     * @param int $id_notifikasi ID notifikasi yang akan ditandai.
     */
    public function read($id_notifikasi) {
        if (!isset($_SESSION['user']['id_pengguna'])) {
            // Jika tidak login, tidak melakukan apa-apa atau redirect
            exit('Akses ditolak.');
        }

        $id_pengguna = $_SESSION['user']['id_pengguna'];
        
        // Tandai sebagai sudah dibaca
        $this->notifikasiModel->markAsRead($id_notifikasi, $id_pengguna);

        // Ambil link dari notifikasi (opsional)
        // Anda perlu menambahkan method find() di Notifikasi.php jika ingin fitur ini
        // $notif = $this->notifikasiModel->find($id_notifikasi);
        // if ($notif && !empty($notif['link'])) {
        //     header("Location: " . $notif['link']);
        // } else {
        //     // Jika tidak ada link, kembali ke halaman sebelumnya
        //     header("Location: " . $_SERVER['HTTP_REFERER'] ?? '?url=dashboard');
        // }
        
        // Untuk saat ini, kita hanya kembali ke halaman sebelumnya
        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '?url=dashboard'));
        exit;
    }

    /**
     * [SEMPURNA] Menandai semua notifikasi sebagai sudah dibaca.
     */
    public function readAll() {
        if (!isset($_SESSION['user']['id_pengguna'])) {
            exit('Akses ditolak.');
        }

        $id_pengguna = $_SESSION['user']['id_pengguna'];
        $this->notifikasiModel->markAllAsReadByUserId($id_pengguna);

        // Kembali ke halaman sebelumnya
        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '?url=dashboard'));
        exit;
    }

    /**
     * [SEMPURNA] Endpoint untuk AJAX: Mengambil jumlah notifikasi yang belum dibaca.
     */
    public function getUnreadCount() {
        if (!isset($_SESSION['user']['id_pengguna'])) {
            echo json_encode(['unread_count' => 0]);
            exit;
        }
        
        $id_pengguna = $_SESSION['user']['id_pengguna'];
        $count = $this->notifikasiModel->countUnreadByUserId($id_pengguna);

        header('Content-Type: application/json');
        echo json_encode(['unread_count' => $count]);
        exit;
    }
}
?>
