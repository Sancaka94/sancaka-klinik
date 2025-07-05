<?php
require_once __DIR__ . '/../config/database.php';

class User {
    // ... (semua properti dan fungsi lain tetap sama) ...
    private $conn;
    public function __construct() { /* ... */ }
    public function register($data) { /* ... */ }
    public function login($username, $password, $id_peran) { /* ... */ }
    // ... dll ...

    /**
     * [FUNGSI BARU] Mendaftarkan pengguna baru dengan peran sebagai Dokter.
     * Data disimpan ke tabel 'pengguna'.
     * @param array $data Data dari form pendaftaran dokter.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function registerDokter($data) {
        // Query hanya ke tabel pengguna, karena dokter tidak punya data di tabel pasien
        $query = "INSERT INTO pengguna (username, email, password, id_peran, nama_lengkap, spesialisasi, nomor_str, foto_profil) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        try {
            $stmt = $this->conn->prepare($query);
            if ($stmt === false) {
                throw new Exception("Gagal prepare query dokter: " . $this->conn->error);
            }

            $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);
            
            // Username default bisa diambil dari email
            $username = $data['email'];

            // Binding parameter (s=string, i=integer)
            $stmt->bind_param(
                "sssissss",
                $username,
                $data['email'],
                $password_hash,
                $data['id_peran'],
                $data['nama_lengkap'],
                $data['spesialisasi'],
                $data['nomor_str'],
                $data['foto_profil']
            );

            if ($stmt->execute()) {
                $this->log_to_file("Registrasi dokter berhasil untuk email: " . $data['email']);
                return true;
            } else {
                throw new Exception("Gagal eksekusi query dokter: " . $stmt->error);
            }

        } catch (Exception $exception) {
            $this->log_to_file("REGISTRASI DOKTER GAGAL", $exception->getMessage());
            return false;
        }
    }
    
    // Pastikan Anda juga memiliki fungsi log_to_file dan emailExists
    private function log_to_file($message, $data = null) { /* ... */ }
    public function emailExists($email) { /* ... */ }
}
