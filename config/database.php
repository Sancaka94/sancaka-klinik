<?php
// File: config/database.php
// Ganti isi file lama Anda dengan kode class ini.

class Database {
    // Properti untuk koneksi database
    private $host = 'localhost';
    private $db_name = 'sancakab_klinik';
    private $username = 'sancakab_admin';
    private $password = 'Salafyyin***94';
    private $conn; // Properti untuk menampung koneksi PDO

    // Method untuk mendapatkan koneksi ke database
    public function getConnection() {
        $this->conn = null; // Set koneksi ke null di awal

        try {
            // Membuat instance PDO baru
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            
            // Mengatur mode error PDO ke exception untuk penanganan error yang lebih baik
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Mengatur agar hasil fetch default adalah associative array
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch(PDOException $exception) {
            // Jika koneksi gagal, hentikan eksekusi dan tampilkan pesan error
            die('Connection error: ' . $exception->getMessage());
        }

        return $this->conn;
    }
}
?>
