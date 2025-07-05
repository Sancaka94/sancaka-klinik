<?php
// Nama file disesuaikan menjadi 'database.php' (huruf kecil)
$database_file = __DIR__ . '/../config/database.php';

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
     * Mendaftarkan pengguna baru dengan MySQLi dan penanganan error.
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
            if ($stmt_user === false) {
                throw new Exception("Gagal prepare query pengguna: " . $this->conn->error);
            }

            $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);
            $id_peran = 4; // Default untuk pasien
            $username = $data['email']; // Default username

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
            if ($stmt_pasien === false) {
                throw new Exception("Gagal prepare query pasien: " . $this->conn->error);
            }
            
            $stmt_pasien->bind_param(
                "issssssssssssssssssssss",
                $id_pengguna_baru, $data['nama_lengkap'], $data['nik'], $data['tempat_lahir'], $data['tanggal_lahir'], $data['jenis_kelamin'], $data['status_perkawinan'], $data['pendidikan_terakhir'], $data['pekerjaan'], $data['alamat'], $data['nomor_telepon'], $data['kontak_darurat'], $data['penanggung_jawab'], $data['golongan_darah'], $data['agama'], $data['riwayat_penyakit'], $data['riwayat_alergi'], $data['status_bpjs'], $data['nomor_bpjs'], $data['file_ktp'], $data['file_kk'], $data['foto_profil'], $data['tanda_tangan']
            );

            if (!$stmt_pasien->execute()) {
                 throw new Exception("Gagal eksekusi query pasien: " . $stmt_pasien->error);
            }
            $this->log_to_file("Query ke tabel 'pasien' berhasil.");
            
            $this->conn->commit();
            $this->log_to_file("Transaksi database di-commit.");
            return true;

        } catch (Exception $exception) {
            $this->conn->rollback();
            $this->log_to_file("TRANSAKSI GAGAL! Melakukan rollback.");
            $this->log_to_file("Exception", $exception->getMessage());
            return false;
        }
    }

    /**
     * Fungsi login yang tidak menggunakan get_result() agar kompatibel.
     */
    public function login($username, $password, $id_peran) {
        // Secara eksplisit sebutkan kolom yang dibutuhkan
        $query = "SELECT id_pengguna, username, email, password, id_peran FROM " . $this->table_name . " WHERE (username = ? OR email = ?) AND id_peran = ?";
        
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            $this->log_to_file("SQL PREPARE FAILED (login): " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("ssi", $username, $username, $id_peran);
        $stmt->execute();
        
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id_pengguna, $db_username, $db_email, $db_password, $db_id_peran);
            $stmt->fetch();

            if (password_verify($password, $db_password)) {
                $user = [
                    'id_pengguna' => $id_pengguna,
                    'username' => $db_username,
                    'email' => $db_email,
                    'id_peran' => $db_id_peran
                ];
                return $user;
            }
        }
        
        return false;
    }

    public function emailExists($email) {
        $query = "SELECT id_pengguna FROM " . $this->table_name . " WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        
        if ($stmt === false) {
            $this->log_to_file("SQL PREPARE FAILED (emailExists): " . $this->conn->error);
            return true;
        }
        
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function phoneExists($phone) {
        $query = "SELECT id_pasien FROM pasien WHERE nomor_telepon = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        
        if ($stmt === false) {
            $this->log_to_file("SQL PREPARE FAILED (phoneExists): " . $this->conn->error);
            return true;
        }
        
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    /**
     * [DIPERBAIKI] Mengambil semua data dari tabel 'pasien' dan 'pengguna' berdasarkan id_pengguna.
     */
    public function getPatientProfileById($id_pengguna) {
        $query = "SELECT p.*, u.email, u.username 
                  FROM pasien p 
                  JOIN pengguna u ON p.id_pengguna = u.id_pengguna 
                  WHERE p.id_pengguna = ?";

        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            $this->log_to_file("SQL PREPARE FAILED (getPatientProfileById): " . $this->conn->error);
            return null;
        }

        $stmt->bind_param("i", $id_pengguna);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Cara dinamis untuk bind semua kolom hasil ke sebuah array
            $data = [];
            $meta = $stmt->result_metadata();
            $params = [];
            while ($field = $meta->fetch_field()) {
                $params[] = &$data[$field->name];
            }

            call_user_func_array([$stmt, 'bind_result'], $params);
            
            $stmt->fetch();
            return $data;
        }

        return null;
    }
    
    /**
     * [DIPERBARUI] Memperbarui data profil pasien di database dengan semua field.
     */
    public function updatePatientProfile($data) {
        $query = "UPDATE pasien SET 
                    nama_lengkap = ?,
                    tempat_lahir = ?,
                    tanggal_lahir = ?,
                    jenis_kelamin = ?,
                    agama = ?,
                    status_perkawinan = ?,
                    nomor_telepon = ?,
                    kontak_darurat = ?,
                    alamat = ?,
                    pendidikan_terakhir = ?,
                    pekerjaan = ?,
                    golongan_darah = ?,
                    status_bpjs = ?,
                    nomor_bpjs = ?,
                    riwayat_penyakit = ?,
                    riwayat_alergi = ?,
                    foto_profil = ? 
                  WHERE id_pasien = ?";
                  
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            $this->log_to_file("SQL PREPARE FAILED (updatePatientProfile): " . $this->conn->error);
            return false;
        }
        
        // [DIPERBARUI] Menambahkan 's' untuk foto_profil, total menjadi 17 's' dan 1 'i'
        $stmt->bind_param(
            "sssssssssssssssssi",
            $data['nama_lengkap'],
            $data['tempat_lahir'],
            $data['tanggal_lahir'],
            $data['jenis_kelamin'],
            $data['agama'],
            $data['status_perkawinan'],
            $data['nomor_telepon'],
            $data['kontak_darurat'],
            $data['alamat'],
            $data['pendidikan_terakhir'],
            $data['pekerjaan'],
            $data['golongan_darah'],
            $data['status_bpjs'],
            $data['nomor_bpjs'],
            $data['riwayat_penyakit'],
            $data['riwayat_alergi'],
            $data['foto_profil'],
            $data['id_pasien']
        );
        
        if ($stmt->execute()) {
            $this->log_to_file("Update profil berhasil untuk id_pasien: " . $data['id_pasien']);
            return true;
        } else {
            $this->log_to_file("SQL EXECUTE FAILED (updatePatientProfile): " . $stmt->error);
            return false;
        }
    }
}
