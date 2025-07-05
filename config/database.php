<?php
class Database {
    private $host = 'localhost';
    private $user = 'sancakab_admin';
    private $pass = 'Salafyyin***94';
    private $dbname = 'sancakab_klinik';

    public function connect() {
        $conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }
        return $conn;
    }
}
