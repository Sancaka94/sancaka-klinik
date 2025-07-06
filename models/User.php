<?php
// File: models/User.php

class User {
    private $conn;
    private $table_name = "pengguna";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Memproses login pengguna dengan return value yang lebih spesifik.
     * @return array|string Data pengguna jika berhasil, atau string error jika gagal.
     */
    public function login($username, $password, $id_peran) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE (username = ? OR email = ?) AND id_peran = ?";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssi", $username, $username, $id_peran);
            $stmt->execute();
            
            $result = $this->getDynamicResult($stmt);
            $stmt->close();
            
            if (count($result) === 1) {
                // Pengguna dan peran cocok, sekarang verifikasi password
                $user = $result[0];
                if (password_verify($password, $user['password'])) {
                    return $user; // SUKSES: Kembalikan data pengguna
                } else {
                    return 'WRONG_PASSWORD'; // GAGAL: Password salah
                }
            } else {
                return 'USER_NOT_FOUND'; // GAGAL: Kombinasi user & peran tidak ditemukan
            }

        } catch (Exception $e) {
            error_log("Error di User->login(): " . $e->getMessage());
            return 'DB_ERROR'; // GAGAL: Error database
        }
    }
    
    // ... (Metode lain seperti register, updateProfile, dll. tetap sama) ...
    
    public function emailExists($email) { /* ... */ }
    public function register($data) { /* ... */ }
    public function registerDokter($data) { /* ... */ }
    public function updateProfile($data) { /* ... */ }
    public function generateResetToken($email) { /* ... */ }
    public function validateResetToken($token) { /* ... */ }
    public function resetPassword($token, $newPassword) { /* ... */ }

    private function getDynamicResult($stmt) {
        $result = [];
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $meta = $stmt->result_metadata();
            $params = [];
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
