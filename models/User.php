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

    /**
     * Mendaftarkan pengguna baru (misal: Pasien).
     */
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

    /**
     * Mendaftarkan pengguna baru sebagai Dokter.
     */
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
     * [UPDATE - FUNGSI YANG HILANG] Memperbarui data profil pengguna di database.
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
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error di User->updateProfile(): " . $e->getMessage());
            return false;
        }
    }

    // --- Metode Lupa Password ---
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
