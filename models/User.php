<?php
// File: models/User.php

class User {
    private $conn;
    private $table_name = "pengguna";

    public function __construct($db) {
        $this->conn = $db;
    }
    
    // --- Metode yang sudah ada ---
    public function login($username, $password, $id_peran) { /* ... (kode dari sebelumnya) ... */ }
    public function register($data) { /* ... (kode dari sebelumnya) ... */ }
    public function registerDokter($data) { /* ... (kode dari sebelumnya) ... */ }
    public function emailExists($email) { /* ... (kode dari sebelumnya) ... */ }
    public function generateResetToken($email) { /* ... (kode dari sebelumnya) ... */ }
    public function validateResetToken($token) { /* ... (kode dari sebelumnya) ... */ }
    public function resetPassword($token, $newPassword) { /* ... (kode dari sebelumnya) ... */ }
    private function getDynamicResult($stmt) { /* ... (kode dari sebelumnya) ... */ }
    // --- Akhir metode yang sudah ada ---


    /**
     * [UPDATE] Memperbarui data profil pengguna dengan error logging yang lebih baik.
     * @param array $data Data pengguna yang akan diperbarui.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function updateProfile($data) {
        $query = "UPDATE " . $this->table_name . " SET nama_lengkap = ?, email = ? ";
        $params = [$data['nama_lengkap'], $data['email']];
        $types = "ss";

        if (!empty($data['password'])) {
            $query .= ", password = ? ";
            $params[] = password_hash($data['password'], PASSWORD_BCRYPT);
            $types .= "s";
        }

        if (!empty($data['foto_profil'])) {
             $query .= ", foto_profil = ? ";
             $params[] = $data['foto_profil'];
             $types .= "s";
        }

        if (isset($data['spesialisasi']) && isset($data['nomor_str'])) {
            $query .= ", spesialisasi = ?, nomor_str = ? ";
            $params[] = $data['spesialisasi'];
            $params[] = $data['nomor_str'];
            $types .= "ss";
        }

        $query .= " WHERE id_pengguna = ?";
        $params[] = $data['id_pengguna'];
        $types .= "i";

        try {
            $stmt = $this->conn->prepare($query);
            if ($stmt === false) {
                throw new Exception("Gagal prepare query update profil: " . $this->conn->error);
            }

            $stmt->bind_param($types, ...$params);
            
            // PERBAIKAN: Cek hasil eksekusi dan log error jika gagal
            if ($stmt->execute()) {
                // Jika berhasil dan ada baris yang terpengaruh, kembalikan true
                // Jika tidak ada baris terpengaruh (data sama), tetap anggap berhasil
                return true;
            } else {
                // Jika eksekusi gagal, catat error spesifik dari statement
                throw new Exception("Gagal eksekusi update profil: " . $stmt->error);
            }

        } catch (Exception $e) {
            // Catat pesan error yang lebih detail ke dalam error_log server
            error_log("Error di User->updateProfile(): " . $e->getMessage());
            return false;
        }
    }
}
