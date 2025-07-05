<?php
// [PERIKSA DI SINI] Pastikan path ini benar-benar menunjuk ke file koneksi database Anda.
// Path ini mengasumsikan ada folder 'config' di direktori utama aplikasi Anda.
$database_file = __DIR__ . '/../config/Database.php';

// Cek apakah file database ada sebelum mencoba memuatnya.
if (!file_exists($database_file)) {
    // Hentikan eksekusi dan berikan pesan error yang jelas jika file tidak ditemukan.
    die("FATAL ERROR: File konfigurasi database tidak ditemukan. Harap periksa apakah file di path berikut ini ada: " . $database_file);
}
require_once $database_file;

class User {
    private $conn;
    private $table_name = "pengguna"; // Ganti jika nama tabel Anda berbeda

    public function __construct() {
        $database = new Database();
        // Menggunakan metode 'connect()' dari kelas Database Anda
        $this->conn = $database->connect();
    }
    
    /**
     * Helper untuk mencatat log dari dalam Model.
     */
    private function log_to_file($message, $data = null) {
        $log_file = __DIR__ . '/../debug.log';
        $time = date('Y-m-d H:i:s');
        $log_entry = "[$time] [MODEL] $message";
        if ($data !== null) {
            $log_entry .= ": " . print_r($data, true);
        }
        $log_entry .= PHP_EOL;
        file_put_contents($log_file, $log_entry, FILE_APPEND);
    }

    /**
     * [DIPERBAIKI] Mendaftarkan pengguna baru dengan MySQLi dan penanganan error.
     */
    public function register($data) {
        // Query untuk tabel pengguna
        $query_user = "INSERT INTO pengguna (username, email, password, id_peran) 
                       VALUES (?, ?, ?, ?)";
                       
        // Query untuk tabel pasien
        $query_pasien = "INSERT INTO pasien (id_pengguna, nama_lengkap, nik, tempat_lahir, tanggal_lahir, jenis_kelamin, status_perkawinan, pendidikan_terakhir, pekerjaan, alamat, nomor_telepon, kontak_darurat, penanggung_jawab, golongan_darah, agama, riwayat_penyakit, riwayat_alergi, status_bpjs, nomor_bpjs, file_ktp, file_kk, foto_profil, tanda_tangan)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Memulai transaksi dengan MySQLi
        $this->conn->begin_transaction();

        try {
            // 1. Eksekusi query untuk tabel pengguna
            $stmt_user = $this->conn->prepare($query_user);

            $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);
            $id_peran = 4; // Default untuk pasien
            $username = $data['email']; // Default username

            // Binding parameter untuk MySQLi (tipe data: s=string, i=integer)
            $stmt_user->bind_param("sssi", $username, $data['email'], $password_hash, $id_peran);
            
            if (!$stmt_user->execute()) {
                throw new Exception("Gagal eksekusi query pengguna: " . $stmt_user->error);
            }
            $this->log_to_file("Query ke tabel 'pengguna' berhasil.");

            // 2. Dapatkan ID pengguna yang baru saja dibuat
            $id_pengguna_baru = $this->conn->insert_id;
            $this->log_to_file("ID Pengguna baru didapatkan: $id_pengguna_baru");

            // 3. Eksekusi query untuk tabel pasien
            $stmt_pasien = $this->conn->prepare($query_pasien);
            
            // Binding parameter untuk MySQLi (i=integer, s=string)
            $stmt_pasien->bind_param(
                "issssssssssssssssssssss",
                $id_pengguna_baru,
                $data['nama_lengkap'],
                $data['nik'],
                $data['tempat_lahir'],
                $data['tanggal_lahir'],
                $data['jenis_kelamin'],
                $data['status_perkawinan'],
                $data['pendidikan_terakhir'],
                $data['pekerjaan'],
                $data['alamat'],
                $data['nomor_telepon'],
                $data['kontak_darurat'],
                $data['penanggung_jawab'],
                $data['golongan_darah'],
                $data['agama'],
                $data['riwayat_penyakit'],
                $data['riwayat_alergi'],
                $data['status_bpjs'],
                $data['nomor_bpjs'],
                $data['file_ktp'],
                $data['file_kk'],
                $data['foto_profil'],
                $data['tanda_tangan']
            );

            if (!$stmt_pasien->execute()) {
                 throw new Exception("Gagal eksekusi query pasien: " . $stmt_pasien->error);
            }
            $this->log_to_file("Query ke tabel 'pasien' berhasil.");
            
            // Jika semua berhasil, simpan perubahan
            $this->conn->commit();
            $this->log_to_file("Transaksi database di-commit.");
            return true;

        } catch (Exception $exception) {
            // Jika terjadi error, batalkan semua perubahan
            $this->conn->rollback();
            $this->log_to_file("TRANSAKSI GAGAL! Melakukan rollback.");
            // Catat pesan error SQL yang sebenarnya ke dalam log
            $this->log_to_file("Exception", $exception->getMessage());
            return false;
        }
    }

    // Fungsi lain seperti login, emailExists, phoneExists, dll.
    // ...
    public function login($username, $password, $id_peran) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE (username = ? OR email = ?) AND id_peran = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssi", $username, $username, $id_peran);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }

    public function emailExists($email) {
        $query = "SELECT id_pengguna FROM " . $this->table_name . " WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function phoneExists($phone) {
        $query = "SELECT id_pasien FROM pasien WHERE nomor_telepon = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
}
