<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {

    // Metode login, authenticate, logout, register, dan registrasi_berhasil tetap sama...
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

    private function handleFileUpload($file, $uploadDir) {
        if ($file === null || $file['error'] !== UPLOAD_ERR_OK) {
            if (isset($file['error']) && $file['error'] !== UPLOAD_ERR_NO_FILE) {
                error_log("File upload error code: " . $file['error']);
            }
            return null;
        }

        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        if (!in_array($fileExtension, $allowedExtensions)) {
            error_log("Invalid file type uploaded: " . $fileExtension);
            return null;
        }

        $maxFileSize = 5 * 1024 * 1024; // 5 MB
        if ($file['size'] > $maxFileSize) {
            error_log("File size exceeds limit: " . $file['size']);
            return null;
        }

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $uniqueFilename = uniqid('file_', true) . '.' . $fileExtension;
        $targetPath = $uploadDir . $uniqueFilename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $uniqueFilename;
        }

        return null;
    }

    public function processRegister() {
        
        
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['registration_error'] = ['message' => 'Permintaan tidak valid.'];
            header("Location: ?url=auth/register");
            exit;
        }

        $ktpFilename = $this->handleFileUpload($_FILES['file_ktp'] ?? null, 'uploads/ktp/');
        $kkFilename = $this->handleFileUpload($_FILES['file_kk'] ?? null, 'uploads/kk/');
        $profilePicFilename = $this->handleFileUpload($_FILES['foto_profil'] ?? null, 'uploads/profil/');

        if ($ktpFilename === null) {
            $ktpError = $_FILES['file_ktp']['error'] ?? UPLOAD_ERR_NO_FILE;
            $message = 'Upload File KTP Gagal.';
            $solution = 'Pastikan file tidak rusak, ukurannya tidak lebih dari 5MB, dan formatnya adalah JPG, PNG, atau PDF.';

            if ($ktpError === UPLOAD_ERR_NO_FILE) {
                $message = 'File KTP Wajib Diisi.';
                $solution = 'Mohon upload scan atau foto KTP Anda untuk melanjutkan pendaftaran.';
            }

            $_SESSION['registration_error'] = ['message' => $message, 'solution' => $solution];
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
            'tanda_tangan'      => $_POST['tanda_tangan'] ?? '',
            'file_ktp'          => $ktpFilename,
            'file_kk'           => $kkFilename,
            'foto_profil'       => $profilePicFilename,
        ];

        if ($userModel->emailExists($data['email'])) {
            $_SESSION['registration_error'] = [
                'message' => 'Email yang Anda masukkan sudah terdaftar.',
                'solution' => '<ul><li>Gunakan email lain.</li><li>Jika ini email Anda, silakan coba login.</li></ul>'
            ];
            header("Location: ?url=auth/register");
            exit;
        }
        if (!empty($data['nomor_telepon']) && $userModel->phoneExists($data['nomor_telepon'])) {
            $_SESSION['registration_error'] = [
                'message' => 'Nomor telepon yang Anda masukkan sudah digunakan.',
                'solution' => '<ul><li>Gunakan nomor telepon lain.</li><li>Jika ini nomor Anda, silakan coba login.</li></ul>'
            ];
            header("Location: ?url=auth/register");
            exit;
        }

        if ($userModel->register($data)) {
            header("Location: ?url=auth/registrasi_berhasil");
        } else {
             $_SESSION['registration_error'] = [
                'message' => 'Registrasi Gagal',
                'solution' => 'Terjadi kesalahan pada server saat menyimpan data. Silakan coba lagi beberapa saat, atau hubungi administrasi jika masalah berlanjut.'
            ];
            header("Location: ?url=auth/register");
        }
        exit;
    }
    
} // <-- INI ADALAH KURUNG KURAWAL PENUTUP YANG HILANG. SEKARANG SUDAH ADA.
