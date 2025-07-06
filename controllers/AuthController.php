<?php
// File: app/controllers/AuthController.php

require_once __DIR__ . '/../config/database.php';

class AuthController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // Tambahkan method autentikasi di sini

    public function register_dokter($data = null) {
        // TODO: Implementasi logika pendaftaran dokter di sini
        // Contoh: simpan data dokter ke database
        return 'Method register_dokter berhasil dipanggil.';
    }
}
