<?php
// File: app/controllers/AuthController.php

require_once __DIR__ . '/../config/database.php';

class AuthController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // Tambahkan method autentikasi di sini
}
