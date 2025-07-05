<?php
class HomeController {
    public function index() {
        echo "<h1>Berhasil! Ini HomeController::index()</h1>";
    }

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
