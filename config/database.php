<?php
// File: config/database.php

class Database {
    // Properti untuk menyimpan detail koneksi database Anda
    // PERBAIKAN: Menggunakan kredensial yang Anda berikan.
    private $host = "localhost";
    private $db_name = "sancakab_klinik";
    private $username = "sancakab_admin";
    private $password = "Salafyyin***94";

    // Properti PUBLIK untuk menyimpan objek koneksi
    public $conn;

    /**
     * Constructor akan otomatis dijalankan saat 'new Database()' dipanggil.
     * Fungsinya adalah membuat koneksi dan menyimpannya ke dalam $this->conn.
     */
    public function __construct() {
        $this->conn = null; // Set ke null dulu untuk awal

        try {
            // Membuat objek koneksi mysqli baru
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            
            // Mengatur set karakter untuk mencegah masalah encoding
            $this->conn->set_charset("utf8mb4");

        } catch (mysqli_sql_exception $exception) {
            // Jika koneksi gagal, tampilkan pesan error dan hentikan script
            // Di lingkungan produksi, sebaiknya ini dicatat ke log, bukan ditampilkan ke pengguna
            die("Koneksi Database Gagal: " . $exception->getMessage());
        }
    }
}
