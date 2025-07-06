<?php
// File: models/User.php

class User {
    private $conn;
    private $table_name = "pengguna";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * [BARU & LEBIH TANGGUH] Membuat token reset password untuk pengguna.
     */
    public function generateResetToken($email) {
        // PERBAIKAN: Bersihkan dan normalkan email
        $clean_email = strtolower(trim($email));

        // PERBAIKAN: Gunakan LOWER() dalam query untuk pencarian case-insensitive
        $query = "UPDATE " . $this->table_name . " 
                  SET reset_token = ?, reset_token_expires_at = ? 
                  WHERE LOWER(email) = ?";
        
        try {
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sss", $token, $expiry, $clean_email);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                return $token;
            }
            return false;

        } catch (Exception $e) {
            error_log("Error di User->generateResetToken(): " . $e->getMessage());
            return false;
        }
    }
    
    // ... (Semua metode lain seperti login, register, dll. tetap sama) ...
    public function login($username, $password, $id_peran) { /* ... */ }
    public function register($data) { /* ... */ }
    public function registerDokter($data) { /* ... */ }
    public function emailExists($email) { /* ... */ }
    public function updateProfile($data) { /* ... */ }
    public function validateResetToken($token) { /* ... */ }
    public function resetPassword($token, $newPassword) { /* ... */ }
    private function getDynamicResult($stmt) { /* ... */ }
}
