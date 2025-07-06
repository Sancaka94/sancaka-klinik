<?php
// File: models/User.php
// VERSI FINAL SETELAH DEBUGGING

class User {
    private $conn;
    private $table_name = "pengguna"; 

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Fungsi untuk memverifikasi login pengguna.
     * [FIXED] Kolom 'foto' dihapus dari query login awal.
     */
    public function login($username, $password, $id_peran) {
        try {
            // Query hanya mengambil data yang esensial untuk login dan session awal
            $query = "SELECT id_pengguna, nama_lengkap, username, email, id_peran, password 
                      FROM " . $this->table_name . " 
                      WHERE (username = :username OR email = :email) AND id_peran = :id_peran 
                      LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $username);
            $stmt->bindParam(':id_peran', $id_peran, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $user['password'])) {
                    unset($user['password']);
                    // Data foto akan diambil nanti di halaman profil/dashboard jika perlu
                    return $user;
                }
                return 'WRONG_PASSWORD';
            }
            return 'USER_NOT_FOUND';

        } catch (PDOException $e) {
            // Mengembalikan ke mode penanganan error yang aman
            error_log("KRITIS - Error di User->login(): " . $e->getMessage());
            return 'DB_ERROR';
        }
    }

    /**
     * Menemukan pengguna berdasarkan ID.
     */
    public function find($id_pengguna) {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id_pengguna = :id_pengguna LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_pengguna', $id_pengguna, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error di User->find(): " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Memperbarui profil pengguna dengan data lengkap.
     */
    public function updateProfile($data) {
        try {
            $checkQuery = "SELECT id_pengguna FROM " . $this->table_name . " WHERE email = :email AND id_pengguna != :id_pengguna LIMIT 1";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(':email', $data['email']);
            $checkStmt->bindParam(':id_pengguna', $data['id_pengguna'], PDO::PARAM_INT);
            $checkStmt->execute();
            if ($checkStmt->rowCount() > 0) {
                return false; // Email sudah digunakan
            }

            // [FIXED] Pastikan nama kolom 'foto' di sini sesuai dengan tabel Anda. Jika tidak ada, hapus baris 'foto = :foto'.
            $query = "UPDATE " . $this->table_name . " 
                      SET nama_lengkap = :nama_lengkap, 
                          email = :email, 
                          no_telepon = :no_telepon, 
                          alamat = :alamat, 
                          spesialisasi = :spesialisasi,
                          nomor_str = :nomor_str,
                          tanggal_lahir = :tanggal_lahir,
                          jenis_kelamin = :jenis_kelamin,
                          no_ktp = :no_ktp,
                          no_bpjs = :no_bpjs
                      WHERE id_pengguna = :id_pengguna";
            // Jika Anda punya kolom foto, tambahkan lagi baris ini di dalam SET: foto = :foto,
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':nama_lengkap', $data['nama_lengkap']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':no_telepon', $data['no_telepon']);
            $stmt->bindParam(':alamat', $data['alamat']);
            // Jika Anda punya kolom foto, aktifkan baris ini: $stmt->bindParam(':foto', $data['foto']);
            $stmt->bindParam(':spesialisasi', $data['spesialisasi']);
            $stmt->bindParam(':nomor_str', $data['nomor_str']);
            $stmt->bindParam(':tanggal_lahir', $data['tanggal_lahir']);
            $stmt->bindParam(':jenis_kelamin', $data['jenis_kelamin']);
            $stmt->bindParam(':no_ktp', $data['no_ktp']);
            $stmt->bindParam(':no_bpjs', $data['no_bpjs']);
            $stmt->bindParam(':id_pengguna', $data['id_pengguna'], PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error di User->updateProfile(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Mengubah password dari halaman profil.
     */
    public function changePassword($id_pengguna, $oldPassword, $newPassword) {
        try {
            $query = "SELECT password FROM " . $this->table_name . " WHERE id_pengguna = :id_pengguna LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_pengguna', $id_pengguna, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($oldPassword, $user['password'])) {
                    $new_password_hash = password_hash($newPassword, PASSWORD_BCRYPT);
                    $updateQuery = "UPDATE " . $this->table_name . " SET password = :password WHERE id_pengguna = :id_pengguna";
                    $updateStmt = $this->conn->prepare($updateQuery);
                    $updateStmt->bindParam(':password', $new_password_hash);
                    $updateStmt->bindParam(':id_pengguna', $id_pengguna, PDO::PARAM_INT);
                    
                    return $updateStmt->execute() ? 'SUCCESS' : 'DB_ERROR';
                }
                return 'WRONG_PASSWORD';
            }
            return 'USER_NOT_FOUND';
        } catch (PDOException $e) {
            error_log("Error di User->changePassword(): " . $e->getMessage());
            return 'DB_ERROR';
        }
    }

    /**
     * Membuat pengguna baru (registrasi).
     */
    public function createUser($data) {
        try {
            $checkQuery = "SELECT id_pengguna FROM " . $this->table_name . " WHERE username = :username OR email = :email LIMIT 1";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(':username', $data['username']);
            $checkStmt->bindParam(':email', $data['email']);
            $checkStmt->execute();
            if ($checkStmt->rowCount() > 0) {
                return false; // Pengguna sudah ada
            }

            $query = "INSERT INTO " . $this->table_name . " (nama_lengkap, username, email, password, id_peran, spesialisasi, nomor_str, tanggal_lahir, jenis_kelamin, no_ktp, no_bpjs) VALUES (:nama_lengkap, :username, :email, :password, :id_peran, :spesialisasi, :nomor_str, :tanggal_lahir, :jenis_kelamin, :no_ktp, :no_bpjs)";
            $stmt = $this->conn->prepare($query);

            $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);

            $stmt->bindParam(':nama_lengkap', $data['nama_lengkap']);
            $stmt->bindParam(':username', $data['username']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':password', $password_hash);
            $stmt->bindParam(':id_peran', $data['id_peran'], PDO::PARAM_INT);
            $stmt->bindParam(':spesialisasi', $data['spesialisasi']);
            $stmt->bindParam(':nomor_str', $data['nomor_str']);
            $stmt->bindParam(':tanggal_lahir', $data['tanggal_lahir']);
            $stmt->bindParam(':jenis_kelamin', $data['jenis_kelamin']);
            $stmt->bindParam(':no_ktp', $data['no_ktp']);
            $stmt->bindParam(':no_bpjs', $data['no_bpjs']);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error di User->createUser(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Membuat token reset password.
     */
    public function generateResetToken($email) {
        try {
            $clean_email = strtolower(trim($email));
            $checkQuery = "SELECT id_pengguna FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(':email', $clean_email);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                $token = bin2hex(random_bytes(32));
                $token_hash = hash('sha256', $token);
                $expires_at = date('Y-m-d H:i:s', time() + 3600); // Token berlaku 1 jam

                $updateQuery = "UPDATE " . $this->table_name . " SET reset_token_hash = :token_hash, reset_token_expires_at = :expires_at WHERE email = :email";
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->bindParam(':token_hash', $token_hash);
                $updateStmt->bindParam(':expires_at', $expires_at);
                $updateStmt->bindParam(':email', $clean_email);
                
                return $updateStmt->execute() ? $token : false;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error di User->generateResetToken(): " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Mengatur ulang password pengguna menggunakan token.
     */
    public function resetPassword($token, $newPassword) {
        try {
            $token_hash = hash('sha256', $token);
            $checkQuery = "SELECT id_pengguna FROM " . $this->table_name . " WHERE reset_token_hash = :token_hash AND reset_token_expires_at > NOW() LIMIT 1";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(':token_hash', $token_hash);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                $password_hash = password_hash($newPassword, PASSWORD_BCRYPT);
                $updateQuery = "UPDATE " . $this->table_name . " SET password = :password, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE reset_token_hash = :token_hash";
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->bindParam(':password', $password_hash);
                $updateStmt->bindParam(':token_hash', $token_hash);
                
                return $updateStmt->execute();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error di User->resetPassword(): " . $e->getMessage());
            return false;
        }
    }
}
?>
