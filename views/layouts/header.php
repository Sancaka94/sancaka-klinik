<?php
// File: views/layouts/header.php

// Memulai session jika belum aktif, penting untuk mengakses data login
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah pengguna sudah login
$isLoggedIn = isset($_SESSION['user']);
if ($isLoggedIn) {
    $user = $_SESSION['user'];
    // Tentukan path foto profil, gunakan gambar default jika tidak ada
    $default_avatar = 'https://placehold.co/100x100/E2E8F0/4A5568?text=Profil';
    $foto_profil_path = (!empty($user['foto_profil'])) ? 'uploads/profiles/' . htmlspecialchars($user['foto_profil']) : $default_avatar;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik Sehat</title>
    
    <!-- Memuat file Bootstrap CSS dari folder 'css' Anda -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    
    <!-- (Opsional) Memuat file CSS custom Anda sendiri -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Font (jika Anda ingin menggunakan font kustom) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar .dropdown-toggle::after {
            display: none; /* Menyembunyikan panah default dropdown bootstrap */
        }
    </style>
</head>
<body>

<!-- Navigasi Header yang Dinamis -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-primary" href="?url=dashboard/pasien">
            Klinik Sehat
        </a>
        
        <div class="d-flex align-items-center">
            <?php if ($isLoggedIn): ?>
                <!-- Menu untuk pengguna yang sudah login -->
                <a href="?url=notifikasi/index" class="nav-link position-relative me-3">
                    <!-- Ikon Notifikasi (Anda bisa ganti dengan ikon Bootstrap) -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16"><path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/></svg>
                    <!-- Tanda notifikasi, bisa diatur dengan PHP -->
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                        <span class="visually-hidden">New alerts</span>
                    </span>
                </a>

                <div class="dropdown">
                    <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo $foto_profil_path; ?>" alt="foto profil" width="32" height="32" class="rounded-circle" onerror="this.onerror=null;this.src='<?php echo $default_avatar; ?>';">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end text-small" aria-labelledby="dropdownUser">
                        <li><a class="dropdown-item" href="?url=profile/pengaturan">Pengaturan Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="?url=auth/logout">Logout</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <!-- Menu untuk pengguna yang belum login -->
                <a href="?url=auth/login" class="btn btn-outline-primary me-2">Login</a>
                <a href="?url=auth/register" class="btn btn-primary">Daftar</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<main class="container py-4">
