<?php
require_once __DIR__ . '/../models/User.php';

class ProfileController {

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function index() {
        $this->pengaturan();
    }

    public function pengaturan() {
        if (!isset($_SESSION['user'])) {
            header("Location: ?url=auth/login&error=Anda harus login untuk mengakses halaman ini.");
            exit;
        }

        $userModel = new User();
        $id_pengguna = $_SESSION['user']['id_pengguna'];
        $data_pasien = $userModel->getPatientProfileById($id_pengguna);

        if ($data_pasien) {
            // Muat halaman view dan kirimkan datanya
            require __DIR__ . '/../views/profile/pengaturan.php';
        } else {
            die("Error: Data pasien tidak ditemukan.");
        }
    }
    
    /**
     * [DIPERBARUI] Memproses pembaruan data profil dengan semua field.
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?url=profile/pengaturan");
            exit;
        }
        
        if (!isset($_SESSION['user'])) {
            header("Location: ?url=auth/login&error=Sesi Anda telah berakhir.");
            exit;
        }
        
        // Kumpulkan semua data dari form yang bisa diubah
        $data = [
            'id_pasien'             => $_POST['id_pasien'],
            'nama_lengkap'          => $_POST['nama_lengkap'] ?? null,
            'tempat_lahir'          => $_POST['tempat_lahir'] ?? null,
            'tanggal_lahir'         => $_POST['tanggal_lahir'] ?? null,
            'jenis_kelamin'         => $_POST['jenis_kelamin'] ?? null,
            'agama'                 => $_POST['agama'] ?? null,
            'status_perkawinan'     => $_POST['status_perkawinan'] ?? null,
            'nomor_telepon'         => $_POST['nomor_telepon'] ?? null,
            'kontak_darurat'        => $_POST['kontak_darurat'] ?? null,
            'alamat'                => $_POST['alamat'] ?? null,
            'pendidikan_terakhir'   => $_POST['pendidikan_terakhir'] ?? null,
            'pekerjaan'             => $_POST['pekerjaan'] ?? null,
            'golongan_darah'        => $_POST['golongan_darah'] ?? null,
            'status_bpjs'           => $_POST['status_bpjs'] ?? null,
            'nomor_bpjs'            => $_POST['nomor_bpjs'] ?? null,
            'riwayat_penyakit'      => $_POST['riwayat_penyakit'] ?? null,
            'riwayat_alergi'        => $_POST['riwayat_alergi'] ?? null
            // NIK dan Email sengaja tidak disertakan karena tidak boleh diubah
        ];
        
        $userModel = new User();
        
        // Panggil method di model untuk update database
        if ($userModel->updatePatientProfile($data)) {
            // Jika berhasil, kembali ke halaman profil dengan pesan sukses
            header("Location: ?url=profile/pengaturan&status=sukses");
        } else {
            // Jika gagal, kembali dengan pesan error
            header("Location: ?url=profile/pengaturan&status=gagal");
        }
        exit;
    }
}
