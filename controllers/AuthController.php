<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {

    // ... (method login, register, authenticate, logout tetap sama) ...
    public function login() {
        require __DIR__ . '/../views/auth/login.php';
    }
    public function authenticate() {
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
    public function register() {
        require __DIR__ . '/../views/auth/register.php';
    }
    public function registrasi_berhasil() {
        require __DIR__ . '/../views/auth/registrasi_berhasil.php';
    }


    /**
     * Memproses semua data dari form registrasi multi-langkah.
     */
    public function processRegister() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['registration_error'] = ['message' => 'Permintaan tidak valid.'];
            header("Location: ?url=auth/register");
            exit;
        }

        $userModel = new User();
        
        $data = [
            'email'             => $_POST['email'] ?? '',
            'password'          => $_POST['password'] ?? '',
            'nama_lengkap'      => $_POST['nama_lengkap'] ?? '',
            'nik'               => $_POST['nik'] ?? '',
            'tempat_lahir'      => $_POST['tempat_lahir'] ?? '',
            'tanggal_lahir'     => $_POST['tanggal_lahir'] ?? '',
            'jenis_kelamin'     => $_POST['jenis_kelamin'] ?? '',
            'status_perkawinan' => $_POST['status_perkawinan'] ?? '',
            'pendidikan_terakhir' => $_POST['pendidikan_terakhir'] ?? '',
            'pekerjaan'         => $_POST['pekerjaan'] ?? '',
            'alamat'            => $_POST['alamat'] ?? '',
            'nomor_telepon'     => $_POST['nomor_telepon'] ?? '',
            'kontak_darurat'    => $_POST['kontak_darurat'] ?? '',
            'penanggung_jawab'  => $_POST['penanggung_jawab'] ?? '',
            'golongan_darah'    => $_POST['golongan_darah'] ?? '',
            'agama'             => $_POST['agama'] ?? '',
            'riwayat_penyakit'  => $_POST['riwayat_penyakit'] ?? '',
            'riwayat_alergi'    => $_POST['riwayat_alergi'] ?? '',
            'status_bpjs'       => $_POST['status_bpjs'] ?? 'Tidak Ada',
            'nomor_bpjs'        => $_POST['nomor_bpjs'] ?? null,
            'file_ktp'          => $_FILES['file_ktp'] ?? null,
            'file_kk'           => $_FILES['file_kk'] ?? null,
            'foto_profil'       => $_FILES['foto_profil'] ?? null,
            'tanda_tangan'      => $_POST['tanda_tangan'] ?? ''
        ];

        // **PERBAIKAN:** Pesan solusi yang lebih spesifik untuk duplikasi data
        if ($userModel->emailExists($data['email'])) {
            $_SESSION['registration_error'] = [
                'message' => 'Email yang Anda masukkan sudah terdaftar.',
                'solution' => '<ul><li><b>Gunakan Email Lain:</b> Silakan coba mendaftar dengan alamat email yang berbeda.</li><li><b>Login dengan Akun yang Ada:</b> Jika ini adalah email Anda, kemungkinan Anda sudah memiliki akun. Silakan coba login.</li></ul>'
            ];
            header("Location: ?url=auth/register");
            exit;
        }
        if (!empty($data['nomor_telepon']) && $userModel->phoneExists($data['nomor_telepon'])) {
            $_SESSION['registration_error'] = [
                'message' => 'Nomor telepon yang Anda masukkan sudah digunakan.',
                'solution' => '<ul><li><b>Gunakan Nomor Telepon Lain:</b> Coba daftarkan akun dengan nomor telepon yang berbeda.</li><li><b>Login dengan Akun yang Ada:</b> Jika Anda merasa ini adalah nomor Anda, kemungkinan Anda sudah terdaftar. Silakan coba login.</li></ul>'
            ];
            header("Location: ?url=auth/register");
            exit;
        }

        if ($userModel->register($data)) {
            header("Location: ?url=auth/registrasi_berhasil");
        } else {
             $_SESSION['registration_error'] = [
                'message' => 'Registrasi Gagal',
                'solution' => 'Terjadi kesalahan pada server. Silakan coba lagi beberapa saat, atau hubungi administrasi jika masalah berlanjut.'
            ];
            header("Location: ?url=auth/register");
        }
        exit;
    }
}
