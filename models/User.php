<?php
// File: models/User.php

class User {
    private $conn;
    private $table_name = "pengguna";

    public function __construct($db) {
        $this->conn = $db;
    }

    // --- Metode Login & Registrasi ---
    public function login($username, $password, $id_peran) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE (username = ? OR email = ?) AND id_peran = ?";
        $stmt = null;
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssi", $username, $username, $id_peran);
            $stmt->execute();
            $result = $this->getDynamicResult($stmt);
            if (count($result) === 1) {
                $user = $result[0];
                if (password_verify($password, $user['password'])) {
                    return $user;
                }
                return 'WRONG_PASSWORD';
            }
            return 'USER_NOT_FOUND';
        } catch (Exception $e) {
            error_log("KRITIS - Error di User->login(): " . $e->getMessage());
            return 'DB_ERROR';
        } finally {
            if ($stmt !== null) $stmt->close();
        }
    }
    public function register($data) { /* ... (kode dari sebelumnya) ... */ }
    public function registerDokter($data) { /* ... (kode dari sebelumnya) ... */ }
    public function emailExists($email) { /* ... (kode dari sebelumnya) ... */ }
    public function updateProfile($data) { /* ... (kode dari sebelumnya) ... */ }

    // --- Metode Lupa Password (LENGKAP) ---

    /**
     * Membuat token reset password untuk pengguna berdasarkan email.
     */
    public function generateResetToken($email) {
        $clean_email = strtolower(trim($email));
        $query = "UPDATE " . $this->table_name . " SET reset_token = ?, reset_token_expires_at = ? WHERE LOWER(email) = ?";
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

    /**
     * [LENGKAP] Memvalidasi token reset password.
     */
    public function validateResetToken($token) {
        $query = "SELECT id_pengguna FROM " . $this->table_name . " WHERE reset_token = ? AND reset_token_expires_at > NOW()";
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
     * [LENGKAP] Mengatur ulang password pengguna menggunakan token.
     */
    public function resetPassword($token, $newPassword) {
        $query = "UPDATE " . $this->table_name . " SET password = ?, reset_token = NULL, reset_token_expires_at = NULL WHERE reset_token = ?";
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

    // --- Fungsi Helper ---
    private function getDynamicResult($stmt) {
        $result = [];
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $meta = $stmt->result_metadata();
            if ($meta === false) {
                 throw new Exception("Gagal mendapatkan metadata hasil: " . $stmt->error);
            }
            $params = [];
            $row = [];
            while ($field = $meta->fetch_field()) {
                $params[] = &$row[$field->name];
            }
            call_user_func_array([$stmt, 'bind_result'], $params);
            while ($stmt->fetch()) {
                $c = [];
                foreach ($row as $key => $val) {
                    $c[$key] = $val;
                }
                $result[] = $c;
            }
        }
        return $result;
    }
}
