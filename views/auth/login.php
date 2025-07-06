<?php
// File: models/User.php

class User {
    private $conn;
    private $table_name = "pengguna";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Memproses login pengguna dengan logging tambahan untuk debugging.
     */
    public function login($username, $password, $id_peran) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE (username = ? OR email = ?) AND id_peran = ?";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssi", $username, $username, $id_peran);
            $stmt->execute();
            
            $result = $this->getDynamicResult($stmt);
            
            if (count($result) === 1) {
                // Pengguna ditemukan, sekarang verifikasi password
                $user = $result[0];
                if (password_verify($password, $user['password'])) {
                    // Password cocok, login berhasil
                    $stmt->close();
                    error_log("DEBUG: Login SUKSES untuk user: " . $username);
                    return $user;
                } else {
                    // Pengguna ditemukan, tetapi password salah
                    $stmt->close();
                    error_log("DEBUG: Login GAGAL - Password salah untuk user: " . $username);
                    return false;
                }
            } else {
                // Pengguna dengan username/email dan peran tersebut tidak ditemukan
                $stmt->close();
                error_log("DEBUG: Login GAGAL - User tidak ditemukan dengan username/email: '" . $username . "' dan id_peran: '" . $id_peran . "'");
                return false;
            }

        } catch (Exception $e) {
            error_log("Error di User->login(): " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Memeriksa apakah sebuah email sudah terdaftar.
     */
    public function emailExists($email) {
        $query = "SELECT id_pengguna FROM " . $this->table_name . " WHERE email = ? LIMIT 1";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            $num_rows = $stmt->num_rows;
            $stmt->close();
            return $num_rows > 0;
        } catch (Exception $e) {
            error_log("Error di User->emailExists(): " . $e->getMessage());
            return false;
        }
    }

    // --- Metode register dan registerDokter tetap sama karena tidak mengambil data ---
    public function register($data) {
        $query = "INSERT INTO " . $this->table_name . " (username, email, password, id_peran, nama_lengkap) VALUES (?, ?, ?, 4, ?)";
        try {
            $stmt = $this->conn->prepare($query);
            $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);
            $username = $data['email'];
            $stmt->bind_param("ssss", $username, $data['email'], $password_hash, $data['nama_lengkap']);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error di User->register(): " . $e->getMessage());
            return false;
        }
    }

    public function registerDokter($data) {
        $query = "INSERT INTO " . $this->table_name . " (username, email, password, id_peran, nama_lengkap, spesialisasi, nomor_str, foto_profil) 
                  VALUES (?, ?, ?, 3, ?, ?, ?, ?)";
        try {
            $stmt = $this->conn->prepare($query);
            $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);
            $username = $data['email'];
            $stmt->bind_param(
                "sssssss",
                $username,
                $data['email'],
                $password_hash,
                $data['nama_lengkap'],
                $data['spesialisasi'],
                $data['nomor_str'],
                $data['foto_profil']
            );
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error di User->registerDokter(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Fungsi helper pribadi untuk mengambil hasil query secara dinamis
     * tanpa menggunakan get_result().
     */
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
