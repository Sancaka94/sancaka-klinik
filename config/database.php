<?php
// File: config.php

// Pengaturan zona waktu default
date_default_timezone_set('Asia/Jakarta');

// Detail koneksi database
$db_host = "localhost";
$db_user = "sancakab_admin";
$db_pass = "Salafyyin***94";
$db_name = "sancakab_klinik";

// Membuat koneksi ke database menggunakan MySQLi
$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Memeriksa apakah koneksi berhasil atau gagal
if(mysqli_connect_errno()){
	// Jika koneksi gagal, hentikan skrip dan tampilkan pesan error
	die("Koneksi database gagal: " . mysqli_connect_error());
}

/**
 * Fungsi untuk mengambil base URL dari aplikasi.
 * Ini membuat tautan menjadi dinamis dan tidak perlu diubah-ubah
 * saat folder proyek dipindahkan.
 */
function base_url($url = null) {
    // Diperbarui sesuai dengan path proyek Anda
    $base_url = "http://localhost/apps/klinik-app"; 
    if ($url != null) {
        return $base_url . "/" . $url;
    } else {
        return $base_url;
    }
}
?>
