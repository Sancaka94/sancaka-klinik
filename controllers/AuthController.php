<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {

    /**
     * [BARU] Fungsi helper untuk mencetak log debug ke layar.
     * @param string $step - Deskripsi langkah yang sedang di-debug.
     * @param mixed|null $data - Data (array atau variabel) untuk ditampilkan.
     */
    private function debug_log($step, $data = null) {
        echo "<div style='font-family: Consolas, monospace; border: 1px solid #007bff; padding: 10px; margin: 5px; background: #f0f8ff; border-radius: 5px;'>";
        echo "<strong style='color: #007bff;'>LANGKAH: $step</strong><br>";
        if ($data !== null) {
            echo "<pre style='background: #e9ecef; padding: 10px; border-radius: 3px; white-space: pre-wrap; word-wrap: break-word; margin-top: 5px;'>";
            print_r($data);
            echo "</pre>";
        }
        echo "</div>";
        // Memaksa server untuk mengirim output ke browser segera
        if (ob_get_level() > 0) {
            ob_flush();
        }
        flush();
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
        // Mulai output buffering untuk menangkap semua output
        ob_start();

        $this->debug_log("A: Memulai proses registrasi.");

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
            $this->debug_log("Session dimulai.");
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->debug_log("ERROR: Metode request bukan POST.", $_SERVER['REQUEST_METHOD']);
            die();
        }
        $this->debug_log("Metode request adalah POST.");

        $ktpFilename = $this->handleFileUpload($_FILES['file_ktp'] ?? null, 'uploads/ktp/');
        $kkFilename = $this->handleFileUpload($_FILES['file_kk'] ?? null, 'uploads/kk/');
        $profilePicFilename = $this->handleFileUpload($_FILES['foto_profil'] ?? null, 'uploads/profil/');
        
        $this->debug_log("B: Proses upload file selesai.", [
            'file_ktp' => $ktpFilename ?? 'TIDAK ADA / GAGAL',
            'file_kk' => $kkFilename ?? 'TIDAK ADA / GAGAL',
            'foto_profil' => $profilePicFilename ?? 'TIDAK ADA / GAGAL'
        ]);

        if ($ktpFilename === null) {
            $this->debug_log("ERROR: File KTP wajib di-upload atau gagal diproses.");
            // Logika untuk menampilkan error ke pengguna tetap ada
            // ...
            die();
        }

        $userModel = new User();
        $this->debug_log("Model 'User' berhasil dimuat.");
        
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
        $this->debug_log("C: Data dari form berhasil dikumpulkan.", $data);
        
        if ($userModel->emailExists($data['email'])) {
            $this->debug_log("ERROR: Validasi gagal, email sudah ada.", $data['email']);
            die();
        }
        if (!empty($data['nomor_telepon']) && $userModel->phoneExists($data['nomor_telepon'])) {
            $this->debug_log("ERROR: Validasi gagal, nomor telepon sudah ada.", $data['nomor_telepon']);
            die();
        }
        $this->debug_log("D: Validasi email dan nomor telepon berhasil (tidak ada duplikat).");

        $this->debug_log("E: Mencoba menyimpan data ke database melalui metode User->register().");

        if ($userModel->register($data)) {
            $this->debug_log("HASIL: SUKSES! Metode User->register() mengembalikan 'true'.");
            // header("Location: ?url=auth/registrasi_berhasil"); // Dimatikan sementara untuk melihat log
            die("<div style='background: #28a745; color: white; padding: 15px; font-family: sans-serif; text-align: center; font-size: 1.2em;'>PROSES SELESAI: Registrasi Berhasil!</div>");
        } else {
            $this->debug_log("HASIL: GAGAL! Metode User->register() mengembalikan 'false'.");
            // header("Location: ?url=auth/register"); // Dimatikan sementara untuk melihat log
            die("<div style='background: #dc3545; color: white; padding: 15px; font-family: sans-serif; text-align: center; font-size: 1.2em;'>PROSES SELESAI: Registrasi Gagal! Periksa file `models/User.php` dan log error database.</div>");
        }
        exit;
    }
    
}
