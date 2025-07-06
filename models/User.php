<?php
// File: models/User.php

class User {
    private $conn;
    // Menggunakan nama tabel dari kode baru Anda
    private $table_name = "pengguna"; 

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Fungsi untuk memverifikasi login pengguna.
     * Disempurnakan menggunakan PDO yang konsisten.
     */
    public function login($username, $password, $id_peran) {
        try {
            $query = "SELECT id_pengguna, nama_lengkap, username, email, id_peran, password 
                      FROM " . $this->table_name . " 
                      WHERE (username = :username OR email = :email) AND id_peran = :id_peran 
                      LIMIT 1";

            $stmt = $this->conn->prepare($query);

            // Ikat parameter dengan cara PDO
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $username); // Cek di kolom username dan email
            $stmt->bindParam(':id_peran', $id_peran, PDO::PARAM_INT);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $user['password'])) {
                    unset($user['password']); // Hapus password dari data session
                    return $user;
                }
                return 'WRONG_PASSWORD';
            }
            return 'USER_NOT_FOUND';

        } catch (PDOException $e) {
            error_log("KRITIS - Error di User->login(): " . $e->getMessage());
            return 'DB_ERROR';
        }
    }

    /**
     * Membuat pengguna baru (registrasi).
     * @return bool True jika berhasil, false jika gagal.
     */
    public function createUser($nama_lengkap, $username, $email, $password, $id_peran) {
        try {
            $checkQuery = "SELECT id_pengguna FROM " . $this->table_name . " WHERE username = :username OR email = :email LIMIT 1";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(':username', $username);
            $checkStmt->bindParam(':email', $email);
            $checkStmt->execute();
            if ($checkStmt->rowCount() > 0) {
                return false; // Pengguna sudah ada
            }

            $query = "INSERT INTO " . $this->table_name . " (nama_lengkap, username, email, password, id_peran) VALUES (:nama_lengkap, :username, :email, :password, :id_peran)";
            $stmt = $this->conn->prepare($query);

            $password_hash = password_hash($password, PASSWORD_BCRYPT);

            $stmt->bindParam(':nama_lengkap', $nama_lengkap);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password_hash);
            $stmt->bindParam(':id_peran', $id_peran, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error di User->createUser(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Membuat token reset password.
     * CATATAN: Pastikan Anda memiliki kolom `reset_token_hash` (VARCHAR 255) dan `reset_token_expires_at` (DATETIME) di tabel 'pengguna'.
     * @return string|false Token jika berhasil, false jika email tidak ditemukan.
     */
    public function generateResetToken($email) {
        try {
            $clean_email = strtolower(trim($email));
            // Cek dulu apakah email ada
            $checkQuery = "SELECT id_pengguna FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(':email', $clean_email);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                $token = bin2hex(random_bytes(32)); // Token mentah untuk dikirim ke user
                $token_hash = hash('sha256', $token); // Hash untuk disimpan di DB
                $expires_at = date('Y-m-d H:i:s', time() + 3600); // Berlaku 1 jam

                $updateQuery = "UPDATE " . $this->table_name . " SET reset_token_hash = :token_hash, reset_token_expires_at = :expires_at WHERE email = :email";
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->bindParam(':token_hash', $token_hash);
                $updateStmt->bindParam(':expires_at', $expires_at);
                $updateStmt->bindParam(':email', $clean_email);
                
                if ($updateStmt->execute()) {
                    return $token; // Berhasil, kembalikan token mentah
                }
            }
            return false; // Email tidak ditemukan atau gagal update
        } catch (PDOException $e) {
            error_log("Error di User->generateResetToken(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Memvalidasi token reset password.
     * @return bool True jika token valid, false jika tidak.
     */
    public function validateResetToken($token) {
        try {
            $token_hash = hash('sha256', $token);
            $query = "SELECT id_pengguna FROM " . $this->table_name . " WHERE reset_token_hash = :token_hash AND reset_token_expires_at > NOW() LIMIT 1";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':token_hash', $token_hash);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error di User->validateResetToken(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Mengatur ulang password pengguna menggunakan token.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function resetPassword($token, $newPassword) {
        try {
            if (!$this->validateResetToken($token)) {
                return false; // Token tidak valid atau sudah kedaluwarsa
            }
            
            $token_hash = hash('sha256', $token);
            $password_hash = password_hash($newPassword, PASSWORD_BCRYPT);

            $query = "UPDATE " . $this->table_name . " SET password = :password, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE reset_token_hash = :token_hash";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':password', $password_hash);
            $stmt->bindParam(':token_hash', $token_hash);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error di User->resetPassword(): " . $e->getMessage());
            return false;
        }
    }
}
?>
