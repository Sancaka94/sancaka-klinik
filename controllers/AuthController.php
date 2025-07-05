<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {

    /**
     * [BARU] Fungsi helper untuk mencatat log ke dalam file debug.log.
     * @param string $message - Pesan log yang akan ditulis.
     * @param mixed|null $data - Data (array atau variabel) untuk detail tambahan.
     */
    private function log_to_file($message, $data = null) {
        // Tentukan path ke file log di direktori root aplikasi
        $log_file = __DIR__ . '/../debug.log';
        // Format waktu saat ini
        $time = date('Y-m-d H:i:s');
        // Format entri log
        $log_entry = "[$time] $message";
        
        // Jika ada data tambahan, format sebagai string
        if ($data !== null) {
            // print_r dengan parameter kedua 'true' akan mengembalikan string, bukan mencetaknya
            $log_entry .= ": " . print_r($data, true);
        }
        
        // Tambahkan baris baru di akhir entri
        $log_entry .= PHP_EOL;
        
        // Tulis entri ke file log (FILE_APPEND agar tidak menimpa log lama)
        file_put_contents($log_file, $log_entry, FILE_APPEND);
    }

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
                $this->log_to_file("File upload error code", $file['error']);
            }
            return null;
        }

        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        if (!in_array($fileExtension, $allowedExtensions)) {
            $this->log_to_file("Invalid file type uploaded", $fileExtension);
            return null;
        }

        $maxFileSize = 5 * 1024 * 1024; // 5 MB
        if ($file['size'] > $maxFileSize) {
            $this->log_to_file("File size exceeds limit", $file['size']);
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
        $this->log_to_file("A: Memulai proses registrasi.");

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->log_to_file("ERROR: Metode request bukan POST.", $_SERVER['REQUEST_METHOD']);
            return; // Keluar dari fungsi jika metode salah
        }
        $this->log_to_file("Metode request adalah POST.");

        $ktpFilename = $this->handleFileUpload($_FILES['file_ktp'] ?? null, 'uploads/ktp/');
        $kkFilename = $this->handleFileUpload($_FILES['file_kk'] ?? null, 'uploads/kk/');
        $profilePicFilename = $this->handleFileUpload($_FILES['foto_profil'] ?? null, 'uploads/profil/');
        
        $this->log_to_file("B: Proses upload file selesai.", [
            'file_ktp' => $ktpFilename ?? 'TIDAK ADA / GAGAL',
            'file_kk' => $kkFilename ?? 'TIDAK ADA / GAGAL',
            'foto_profil' => $profilePicFilename ?? 'TIDAK ADA / GAGAL'
        ]);

        if ($ktpFilename === null) {
            $this->log_to_file("ERROR: File KTP wajib di-upload atau gagal diproses.");
            $_SESSION['registration_error'] = ['message' => 'File KTP Wajib Diisi atau Gagal Diproses', 'solution' => 'Pastikan file KTP valid dan coba lagi.'];
            header("Location: ?url=auth/register");
            exit;
        }

        $userModel = new User();
        $this->log_to_file("Model 'User' berhasil dimuat.");
        
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
        $this->log_to_file("C: Data dari form berhasil dikumpulkan.");
        
        if ($userModel->emailExists($data['email'])) {
            $this->log_to_file("ERROR: Validasi gagal, email sudah ada.", $data['email']);
            $_SESSION['registration_error'] = ['message' => 'Email sudah terdaftar.', 'solution' => 'Gunakan email lain atau coba login.'];
            header("Location: ?url=auth/register");
            exit;
        }
        if (!empty($data['nomor_telepon']) && $userModel->phoneExists($data['nomor_telepon'])) {
            $this->log_to_file("ERROR: Validasi gagal, nomor telepon sudah ada.", $data['nomor_telepon']);
            $_SESSION['registration_error'] = ['message' => 'Nomor telepon sudah terdaftar.', 'solution' => 'Gunakan nomor lain atau coba login.'];
            header("Location: ?url=auth/register");
            exit;
        }
        $this->log_to_file("D: Validasi email dan nomor telepon berhasil.");

        $this->log_to_file("E: Mencoba menyimpan data ke database melalui metode User->register().");

        if ($userModel->register($data)) {
            $this->log_to_file("HASIL: SUKSES! Metode User->register() mengembalikan 'true'. Mengalihkan ke halaman berhasil.");
            header("Location: ?url=auth/registrasi_berhasil");
        } else {
            $this->log_to_file("HASIL: GAGAL! Metode User->register() mengembalikan 'false'. Mengalihkan kembali ke form registrasi.");
            $_SESSION['registration_error'] = [
                'message' => 'Registrasi Gagal Disimpan',
                'solution' => 'Terjadi kesalahan pada server saat menyimpan data. Periksa log error database atau hubungi administrasi.'
            ];
            header("Location: ?url=auth/register");
        }
        exit;
    }
    
}
