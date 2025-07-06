<?php
// File: models/User.php

class User {
    private $conn;
    private $table_name = "pengguna";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Memproses login pengguna dengan logika dan penanganan error yang lebih baik.
     */
    public function login($username, $password, $id_peran) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE (username = ? OR email = ?) AND id_peran = ?";
        $stmt = null;
        try {
            $stmt = $this->conn->prepare($query);
            if ($stmt === false) {
                throw new Exception("Prepare statement gagal: " . $this->conn->error);
            }

            $stmt->bind_param("ssi", $username, $username, $id_peran);
            
            if (!$stmt->execute()) {
                throw new Exception("Eksekusi statement gagal: " . $stmt->error);
            }
            
            $result = $this->getDynamicResult($stmt);
            
            if (count($result) === 1) {
                $user = $result[0];
                if (password_verify($password, $user['password'])) {
                    return $user; // SUKSES
                } else {
                    return 'WRONG_PASSWORD'; // GAGAL: Password salah
                }
            } else {
                return 'USER_NOT_FOUND'; // GAGAL: User/peran tidak ditemukan
            }

        } catch (Exception $e) {
            error_log("KRITIS - Error di User->login(): " . $e->getMessage());
            return 'DB_ERROR';
        } finally {
            if ($stmt !== null) {
                $stmt->close();
            }
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

    /**
     * Fungsi helper yang lebih aman untuk mengambil hasil query secara dinamis.
     */
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
