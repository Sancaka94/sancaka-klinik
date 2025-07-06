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
    public function updateProfile($data) { /* ... (kode dari sebelumnya) ... */ }
    private function getDynamicResult($stmt) { /* ... (kode dari sebelumnya) ... */ }
    // --- Akhir metode yang sudah ada ---

    /**
     * [BARU] Membuat token reset password untuk pengguna berdasarkan email.
     * @param string $email Email pengguna yang meminta reset.
     * @return string|false Token yang dihasilkan jika email ditemukan, atau false jika tidak.
     */
    public function generateResetToken($email) {
        // Query untuk memperbarui token dan waktu kedaluwarsanya
        $query = "UPDATE " . $this->table_name . " 
                  SET reset_token = ?, reset_token_expires_at = ? 
                  WHERE email = ?";
        
        try {
            // Membuat token acak yang aman
            $token = bin2hex(random_bytes(32));
            // Mengatur waktu kedaluwarsa (misalnya, 1 jam dari sekarang)
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sss", $token, $expiry, $email);
            $stmt->execute();

            // Cek apakah ada baris yang terpengaruh (artinya email ditemukan dan di-update)
            if ($stmt->affected_rows > 0) {
                return $token;
            }
            // Jika tidak ada baris yang terpengaruh, berarti email tidak ada di database
            return false;

        } catch (Exception $e) {
            error_log("Error di User->generateResetToken(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * [BARU] Memvalidasi token reset password.
     * Memeriksa apakah token ada dan belum kedaluwarsa.
     * @param string $token Token yang akan divalidasi.
     * @return bool True jika token valid, false jika tidak.
     */
    public function validateResetToken($token) {
        $query = "SELECT id_pengguna FROM " . $this->table_name . " 
                  WHERE reset_token = ? AND reset_token_expires_at > NOW()";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $stmt->store_result();

            return $stmt->num_rows > 0;

        } catch (Exception $e) {
            error_log("Error di User->validateResetToken(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * [BARU] Mengatur ulang password pengguna menggunakan token yang valid.
     * @param string $token Token reset yang valid.
     * @param string $newPassword Password baru yang dimasukkan pengguna.
     * @return bool True jika password berhasil di-reset, false jika gagal.
     */
    public function resetPassword($token, $newPassword) {
        // Query untuk memperbarui password dan menghapus token
        $query = "UPDATE " . $this->table_name . " 
                  SET password = ?, reset_token = NULL, reset_token_expires_at = NULL 
                  WHERE reset_token = ?";
        
        try {
            $password_hash = password_hash($newPassword, PASSWORD_BCRYPT);
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ss", $password_hash, $token);
            
            return $stmt->execute();

        } catch (Exception $e) {
            error_log("Error di User->resetPassword(): " . $e->getMessage());
            return false;
        }
    }
}
