<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {

    // ... (method login, register, authenticate, logout tetap sama) ...
    public function login() {
        require __DIR__ . '/../views/auth/login.php';
    }
    public function register() {
        require __DIR__ . '/../views/auth/register.php';
    }
    public function authenticate() {
        // Logika otentikasi yang sudah ada
        $userModel = new User();
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $id_peran = $_POST['id_peran'] ?? '';
        if (empty($username) || empty($password) || empty($id_peran)) {
            header("Location: ?url=auth/login&error=Semua field harus diisi.");
            exit;
        }
        $user = $userModel->login($username, $password, $id_peran);
        if ($user) {
            if (session_status() == PHP_SESSION_NONE) session_start();
            $_SESSION['user'] = $user;
            switch ($user['id_peran']) {
                case 1: header("Location: ?url=dashboard/superadmin"); break;
                case 2: header("Location: ?url=dashboard/admin"); break;
                case 3: header("Location: ?url=dashboard/dokter"); break;
                case 4: header("Location: ?url=dashboard/pasien"); break;
                case 5: header("Location: ?url=dashboard/owner"); break;
                default: echo "Role tidak dikenali.";
            }
        } else {
            header("Location: ?url=auth/login&error=Username, password, atau peran yang Anda pilih salah.");
        }
        exit;
    }
    public function logout() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        session_destroy();
        header("Location: ?url=home");
        exit;
    }


    /**
     * Memproses semua data dari form registrasi multi-langkah.
     */
    public function processRegister() {
        // Validasi dasar
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?url=auth/register&error=Invalid request.");
            exit;
        }

        $userModel = new User();
        
        // Mengumpulkan semua data dari $_POST dan $_FILES
        $data = [
            // Step 1 & 5
            'email'             => $_POST['email'] ?? '',
            'password'          => $_POST['password'] ?? '',
            // Step 2
            'nama_lengkap'      => $_POST['nama_lengkap'] ?? '',
            'nik'               => $_POST['nik'] ?? '',
            'tempat_lahir'      => $_POST['tempat_lahir'] ?? '',
            'tanggal_lahir'     => $_POST['tanggal_lahir'] ?? '',
            'jenis_kelamin'     => $_POST['jenis_kelamin'] ?? '',
            'status_perkawinan' => $_POST['status_perkawinan'] ?? '',
            'pendidikan_terakhir' => $_POST['pendidikan_terakhir'] ?? '',
            'pekerjaan'         => $_POST['pekerjaan'] ?? '',
            // Step 3
            'alamat'            => $_POST['alamat'] ?? '',
            'nomor_telepon'     => $_POST['nomor_telepon'] ?? '',
            'kontak_darurat'    => $_POST['kontak_darurat'] ?? '',
            'penanggung_jawab'  => $_POST['penanggung_jawab'] ?? '',
            // Step 4
            'golongan_darah'    => $_POST['golongan_darah'] ?? '',
            'agama'             => $_POST['agama'] ?? '',
            'riwayat_penyakit'  => $_POST['riwayat_penyakit'] ?? '',
            'riwayat_alergi'    => $_POST['riwayat_alergi'] ?? '',
            'status_bpjs'       => $_POST['status_bpjs'] ?? 'Tidak Ada',
            'nomor_bpjs'        => $_POST['nomor_bpjs'] ?? null,
            // Step 5
            'file_ktp'          => $_FILES['file_ktp'] ?? null,
            'file_kk'           => $_FILES['file_kk'] ?? null,
            'foto_profil'       => $_FILES['foto_profil'] ?? null,
            'tanda_tangan'      => $_POST['tanda_tangan'] ?? ''
        ];

        // Validasi email dan nomor telepon sebelum mencoba mendaftar
        if ($userModel->emailExists($data['email'])) {
            header("Location: ?url=auth/register&error=Email ini sudah digunakan.");
            exit;
        }
        if ($userModel->phoneExists($data['nomor_telepon'])) {
            header("Location: ?url=auth/register&error=Nomor telepon ini sudah digunakan.");
            exit;
        }

        // Panggil fungsi register di model dengan data yang sudah lengkap
        if ($userModel->register($data)) {
            header("Location: ?url=auth/login&success=Registrasi berhasil. Silakan login.");
        } else {
            header("Location: ?url=auth/register&error=Registrasi gagal karena kesalahan server.");
        }
        exit;
    }
}
