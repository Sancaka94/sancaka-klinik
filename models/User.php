<?php
// File: models/User.php

class User {
    // Properti untuk menyimpan koneksi database dan nama tabel
    private $conn;
    private $table_name = "pengguna";

    /**
     * Constructor untuk class User.
     * @param object $db Objek koneksi database.
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * [FUNGSI YANG HILANG] Memproses login pengguna.
     * @param string $username Username atau email pengguna.
     * @param string $password Password yang dimasukkan pengguna.
     * @param int $id_peran Peran yang dipilih pengguna saat login.
     * @return array|false Data pengguna jika berhasil, false jika gagal.
     */
    public function login($username, $password, $id_peran) {
        // Cari pengguna berdasarkan username atau email
        $query = "SELECT * FROM " . $this->table_name . " WHERE (username = ? OR email = ?) AND id_peran = ?";
        
        try {
            $stmt = $this->conn->prepare($query);
            if ($stmt === false) {
                throw new Exception("Gagal prepare query login: " . $this->conn->error);
            }

            $stmt->bind_param("ssi", $username, $username, $id_peran);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();
                // Verifikasi password
                if (password_verify($password, $user['password'])) {
                    return $user; // Login berhasil
                }
            }
            return false; // Login gagal (user tidak ditemukan atau password salah)

        } catch (Exception $e) {
            error_log("Error di User->login(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Mendaftarkan pengguna baru dengan peran sebagai Pasien.
     * @param array $data Data dari form registrasi pasien.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function register($data) {
        $query = "INSERT INTO " . $this->table_name . " (username, email, password, id_peran, nama_lengkap) VALUES (?, ?, ?, 4, ?)";

        try {
            $stmt = $this->conn->prepare($query);
            $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);
            $username = $data['email']; // Gunakan email sebagai username default

            $stmt->bind_param("ssss", $username, $data['email'], $password_hash, $data['nama_lengkap']);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error di User->register(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Mendaftarkan pengguna baru dengan peran sebagai Dokter.
     * @param array $data Data dari form pendaftaran dokter.
     * @return bool True jika berhasil, false jika gagal.
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
     * Memeriksa apakah sebuah email sudah terdaftar di database.
     * @param string $email Email yang akan diperiksa.
     * @return bool True jika email sudah ada, false jika belum.
     */
    public function emailExists($email) {
        $query = "SELECT id_pengguna FROM " . $this->table_name . " WHERE email = ? LIMIT 1";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            
            return $stmt->num_rows > 0;
        } catch (Exception $e) {
            error_log("Error di User->emailExists(): " . $e->getMessage());
            return false; // Asumsikan tidak ada jika terjadi error
        }
    }
}
