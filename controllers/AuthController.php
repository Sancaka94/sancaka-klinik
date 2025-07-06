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
        if (empty($data) || !is_array($data)) {
            return 'Data dokter tidak valid.';
        }
        // Contoh field: nama, email, spesialis
        $nama = $data['nama'] ?? null;
        $email = $data['email'] ?? null;
        $spesialis = $data['spesialis'] ?? null;
        if (!$nama || !$email || !$spesialis) {
            return 'Field wajib tidak boleh kosong.';
        }
        $stmt = $this->conn->prepare("INSERT INTO dokter (nama, email, spesialis) VALUES (?, ?, ?)");
        if (!$stmt) {
            return 'Gagal menyiapkan statement: ' . $this->conn->error;
        }
        $stmt->bind_param("sss", $nama, $email, $spesialis);
        if ($stmt->execute()) {
            $stmt->close();
            return 'Dokter berhasil didaftarkan.';
        } else {
            $error = $stmt->error;
            $stmt->close();
            return 'Gagal mendaftar dokter: ' . $error;
        }
    }
}
