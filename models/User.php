<?php
// File: models/User.php

class User {
    // Properti untuk menyimpan koneksi database
    private $conn;

    /**
     * Constructor untuk class User.
     * Menerima koneksi database sebagai parameter dan menyimpannya.
     * @param object $db_connection Objek koneksi database (misalnya, dari mysqli).
     */
    public function __construct($db_connection) {
        // PERBAIKAN: Menyimpan koneksi database yang diberikan ke dalam properti class
        $this->conn = $db_connection;
    }

    /**
     * Mendaftarkan pengguna baru dengan peran sebagai Dokter.
     * @param array $data Data dari form pendaftaran dokter.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function registerDokter($data) {
        // Query untuk menyimpan data dokter ke tabel 'pengguna'
        $query = "INSERT INTO pengguna (username, email, password, id_peran, nama_lengkap, spesialisasi, nomor_str, foto_profil) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        try {
            // PERBAIKAN: Sekarang $this->conn sudah berisi koneksi database yang valid
            $stmt = $this->conn->prepare($query);
            
            // Pengecekan jika prepare gagal
            if ($stmt === false) {
                // Sebaiknya log error yang lebih detail
                throw new Exception("Gagal prepare query dokter: " . $this->conn->error);
            }

            // Hash password untuk keamanan
            $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);
            
            // Menggunakan email sebagai username default
            $username = $data['email'];

            // Mengikat parameter ke statement (s=string, i=integer)
            // Pastikan tipe data dan urutannya sesuai dengan kolom di query
            $stmt->bind_param(
                "sssissss",
                $username,
                $data['email'],
                $password_hash,
                $data['id_peran'], // Pastikan id_peran untuk dokter sudah benar (misal: 2)
                $data['nama_lengkap'],
                $data['spesialisasi'],
                $data['nomor_str'],
                $data['foto_profil'] // Pastikan ini adalah nama file, bukan data file
            );

            // Eksekusi statement
            if ($stmt->execute()) {
                // Berhasil
                return true;
            } else {
                // Gagal eksekusi
                throw new Exception("Gagal eksekusi query dokter: " . $stmt->error);
            }

        } catch (Exception $exception) {
            // Tangani dan log error jika terjadi
            // Anda bisa membuat fungsi log sendiri atau menggunakan error_log()
            error_log("REGISTRASI DOKTER GAGAL: " . $exception->getMessage());
            return false;
        }
    }
    
    // Pastikan Anda juga memiliki fungsi log_to_file dan emailExists
    private function log_to_file($message, $data = null) { /* ... */ }
    public function emailExists($email) { /* ... */ }
}
