<?php
require_once __DIR__ . '/../config/Database.php';

class User {
    private $conn;
    private $table_name = "pengguna"; // Ganti jika nama tabel Anda berbeda

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
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
     * [DIPERBAIKI] Mendaftarkan pengguna baru dengan penanganan error.
     */
    public function register($data) {
        // Asumsi: Tabel 'pasien' dan 'pengguna'
        // Ganti nama tabel dan kolom sesuai dengan database Anda.
        
        // Query untuk tabel pengguna
        $query_user = "INSERT INTO pengguna (username, email, password, id_peran) 
                       VALUES (:username, :email, :password, :id_peran)";
                       
        // Query untuk tabel pasien
        $query_pasien = "INSERT INTO pasien (id_pengguna, nama_lengkap, nik, tempat_lahir, tanggal_lahir, jenis_kelamin, status_perkawinan, pendidikan_terakhir, pekerjaan, alamat, nomor_telepon, kontak_darurat, penanggung_jawab, golongan_darah, agama, riwayat_penyakit, riwayat_alergi, status_bpjs, nomor_bpjs, file_ktp, file_kk, foto_profil, tanda_tangan)
                         VALUES (:id_pengguna, :nama_lengkap, :nik, :tempat_lahir, :tanggal_lahir, :jenis_kelamin, :status_perkawinan, :pendidikan_terakhir, :pekerjaan, :alamat, :nomor_telepon, :kontak_darurat, :penanggung_jawab, :golongan_darah, :agama, :riwayat_penyakit, :riwayat_alergi, :status_bpjs, :nomor_bpjs, :file_ktp, :file_kk, :foto_profil, :tanda_tangan)";

        // Gunakan transaksi untuk memastikan kedua query berhasil atau tidak sama sekali.
        $this->conn->beginTransaction();

        try {
            // 1. Eksekusi query untuk tabel pengguna
            $stmt_user = $this->conn->prepare($query_user);

            // Hash password sebelum disimpan
            $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);
            
            // Untuk pasien, id_peran biasanya 4 (sesuaikan jika berbeda)
            $id_peran = 4;
            
            // Gunakan email sebagai username default
            $username = $data['email'];

            $stmt_user->bindParam(':username', $username);
            $stmt_user->bindParam(':email', $data['email']);
            $stmt_user->bindParam(':password', $password_hash);
            $stmt_user->bindParam(':id_peran', $id_peran);
            
            $stmt_user->execute();
            $this->log_to_file("Query ke tabel 'pengguna' berhasil.");

            // 2. Dapatkan ID pengguna yang baru saja dibuat
            $id_pengguna_baru = $this->conn->lastInsertId();
            $this->log_to_file("ID Pengguna baru didapatkan: $id_pengguna_baru");

            // 3. Eksekusi query untuk tabel pasien
            $stmt_pasien = $this->conn->prepare($query_pasien);

            $stmt_pasien->bindParam(':id_pengguna', $id_pengguna_baru);
            $stmt_pasien->bindParam(':nama_lengkap', $data['nama_lengkap']);
            $stmt_pasien->bindParam(':nik', $data['nik']);
            $stmt_pasien->bindParam(':tempat_lahir', $data['tempat_lahir']);
            $stmt_pasien->bindParam(':tanggal_lahir', $data['tanggal_lahir']);
            $stmt_pasien->bindParam(':jenis_kelamin', $data['jenis_kelamin']);
            $stmt_pasien->bindParam(':status_perkawinan', $data['status_perkawinan']);
            $stmt_pasien->bindParam(':pendidikan_terakhir', $data['pendidikan_terakhir']);
            $stmt_pasien->bindParam(':pekerjaan', $data['pekerjaan']);
            $stmt_pasien->bindParam(':alamat', $data['alamat']);
            $stmt_pasien->bindParam(':nomor_telepon', $data['nomor_telepon']);
            $stmt_pasien->bindParam(':kontak_darurat', $data['kontak_darurat']);
            $stmt_pasien->bindParam(':penanggung_jawab', $data['penanggung_jawab']);
            $stmt_pasien->bindParam(':golongan_darah', $data['golongan_darah']);
            $stmt_pasien->bindParam(':agama', $data['agama']);
            $stmt_pasien->bindParam(':riwayat_penyakit', $data['riwayat_penyakit']);
            $stmt_pasien->bindParam(':riwayat_alergi', $data['riwayat_alergi']);
            $stmt_pasien->bindParam(':status_bpjs', $data['status_bpjs']);
            $stmt_pasien->bindParam(':nomor_bpjs', $data['nomor_bpjs']);
            $stmt_pasien->bindParam(':file_ktp', $data['file_ktp']);
            $stmt_pasien->bindParam(':file_kk', $data['file_kk']);
            $stmt_pasien->bindParam(':foto_profil', $data['foto_profil']);
            $stmt_pasien->bindParam(':tanda_tangan', $data['tanda_tangan']);

            $stmt_pasien->execute();
            $this->log_to_file("Query ke tabel 'pasien' berhasil.");
            
            // Jika semua berhasil, simpan perubahan
            $this->conn->commit();
            $this->log_to_file("Transaksi database di-commit.");
            return true;

        } catch (PDOException $exception) {
            // Jika terjadi error, batalkan semua perubahan
            $this->conn->rollBack();
            $this->log_to_file("TRANSAKSI GAGAL! Melakukan rollback.");
            // Catat pesan error SQL yang sebenarnya ke dalam log
            $this->log_to_file("PDOException", $exception->getMessage());
            return false;
        }
    }

    // Fungsi lain seperti login, emailExists, phoneExists, dll.
    // ...
    public function login($username, $password, $id_peran) {
        // ... (kode login Anda)
    }

    public function emailExists($email) {
        $query = "SELECT id_pengguna FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function phoneExists($phone) {
        // Asumsi nomor telepon ada di tabel pasien
        $query = "SELECT id_pasien FROM pasien WHERE nomor_telepon = :phone LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
