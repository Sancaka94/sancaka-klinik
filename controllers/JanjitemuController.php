<?php
// File: controllers/JanjiTemuController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/JanjiTemu.php'; // Model untuk operasi janji temu
require_once __DIR__ . '/../models/Dokter.php';   // Model untuk mendapatkan daftar dokter

class JanjiTemuController {
    
    private $conn;

    /**
     * Constructor untuk membuat koneksi database sekali.
     */
    public function __construct() {
        $database = new Database();
        $this->conn = $database->conn;
        
        // Memulai session di awal agar tersedia untuk semua metode
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * [READ] Menampilkan daftar semua janji temu (untuk admin) 
     * atau riwayat janji temu (untuk pasien).
     */
    public function index() {
        // Logika untuk menentukan data mana yang akan diambil berdasarkan peran pengguna
        $janjiTemuModel = new JanjiTemu($this->conn);
        $user_id = $_SESSION['user']['id_pengguna'] ?? null;
        $user_role = $_SESSION['user']['id_peran'] ?? null;

        if ($user_role == 4 && $user_id) { // Jika peran adalah Pasien
            $list_janji = $janjiTemuModel->getByPatientId($user_id);
        } else if ($user_role == 2 || $user_role == 3) { // Jika peran adalah Admin atau Dokter
             $list_janji = $janjiTemuModel->getAll();
        } else {
            // Jika tidak ada peran yang cocok atau tidak login, tampilkan daftar kosong atau redirect
            $list_janji = [];
        }
        
        // Memuat view yang menampilkan daftar janji temu
        require_once __DIR__ . '/../views/janjitemu/index.php';
    }

    /**
     * [CREATE - Form] Menampilkan halaman form untuk membuat janji temu baru.
     */
    public function buat() {
        // Mengambil daftar dokter untuk ditampilkan di dropdown form
        $dokterModel = new Dokter($this->conn);
        $list_dokter = $dokterModel->getAll();

        // Mengirim data daftar dokter ke view
        require_once __DIR__ . '/../views/janjitemu/buat.php';
    }

    /**
     * [CREATE - Process] Menyimpan data janji temu baru dari form ke database.
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?url=janjitemu/buat");
            exit;
        }

        // Pastikan pasien sudah login
        if (!isset($_SESSION['user']) || $_SESSION['user']['id_peran'] != 4) {
            header("Location: ?url=auth/login&error=Anda harus login sebagai pasien untuk membuat janji temu.");
            exit;
        }

        $janjiTemuModel = new JanjiTemu($this->conn);

        $data = [
            'id_pasien' => $_SESSION['user']['id_pengguna'],
            'id_dokter' => $_POST['id_dokter'] ?? null,
            'tanggal_booking' => $_POST['tanggal_booking'] ?? null,
            'keluhan' => $_POST['keluhan'] ?? ''
        ];

        if (empty($data['id_dokter']) || empty($data['tanggal_booking'])) {
            header("Location: ?url=janjitemu/buat&error=Dokter dan tanggal janji harus diisi.");
            exit;
        }

        if ($janjiTemuModel->create($data)) {
            header("Location: ?url=dashboard/pasien&status=janjitemu_sukses");
        } else {
            header("Location: ?url=janjitemu/buat&error=Gagal membuat janji temu.");
        }
        exit;
    }

    /**
     * [UPDATE - Form] Menampilkan halaman form untuk mengedit janji temu.
     * @param int $id ID dari janji temu yang akan diedit.
     */
    public function edit($id) {
        $janjiTemuModel = new JanjiTemu($this->conn);
        $janji = $janjiTemuModel->getById($id);

        // Jika janji temu tidak ditemukan, redirect atau tampilkan error
        if (!$janji) {
            die('Janji temu tidak ditemukan.');
        }

        // Mengambil daftar dokter untuk dropdown
        $dokterModel = new Dokter($this->conn);
        $list_dokter = $dokterModel->getAll();

        // Memuat view form edit dan mengirim data janji temu serta daftar dokter
        require_once __DIR__ . '/../views/janjitemu/edit.php';
    }

    /**
     * [UPDATE - Process] Memperbarui data janji temu di database.
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?url=janjitemu/index"); // Arahkan ke daftar janji
            exit;
        }

        $janjiTemuModel = new JanjiTemu($this->conn);
        
        $data = [
            'id' => $_POST['id_janji_temu'] ?? 0,
            'id_dokter' => $_POST['id_dokter'] ?? null,
            'tanggal_booking' => $_POST['tanggal_booking'] ?? null,
            'keluhan' => $_POST['keluhan'] ?? '',
            'status' => $_POST['status'] ?? 'Direncanakan' // Admin mungkin bisa mengubah status
        ];

        if ($janjiTemuModel->update($data)) {
            header("Location: ?url=janjitemu/index&status=update_sukses");
        } else {
            header("Location: ?url=janjitemu/edit&id={$data['id']}&error=Gagal memperbarui janji temu.");
        }
        exit;
    }

    /**
     * [DELETE] Membatalkan atau menghapus janji temu.
     */
    public function destroy() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?url=janjitemu/index");
            exit;
        }

        $id = $_POST['id_janji_temu'] ?? 0;
        $janjiTemuModel = new JanjiTemu($this->conn);

        if ($janjiTemuModel->delete($id)) {
            header("Location: ?url=janjitemu/index&status=hapus_sukses");
        } else {
            header("Location: ?url=janjitemu/index&error=Gagal menghapus janji temu.");
        }
        exit;
    }
}
