<?php
// controllers/HomeController.php

require_once __DIR__ . '/../config/Database.php'; // pastikan path ini sesuai

class HomeController {

    /**
     * Menampilkan halaman utama (homepage) untuk pengunjung.
     */
    public function index() {
        require_once __DIR__ . '/../views/home/index.php';
    }

    /**
     * Menampilkan daftar database di server MySQL (untuk admin/developer).
     */
    public function databaseList() {
        $db = new Database();
        $conn = $db->connect();

        $result = $conn->query("SHOW DATABASES");

        echo "<h2>Daftar Database</h2><ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . $row['Database'] . "</li>";
        }
        echo "</ul>";

        $conn->close();
    }
}
