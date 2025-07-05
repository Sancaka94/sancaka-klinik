<?php
// File: views/layouts/header.php

// Memulai session jika belum aktif
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Menyiapkan variabel pengguna untuk navigasi
$user = $_SESSION['user'] ?? null;
$is_logged_in = ($user !== null);

// Nilai default jika pengguna tidak login
$display_name = 'Tamu';
$foto_profil_path = 'https://placehold.co/100x100/E2E8F0/4A5568?text=G';
$default_avatar = 'https://placehold.co/100x100/E2E8F0/4A5568?text=G';

if ($is_logged_in) {
    $foto_profil_path = (!empty($user['foto_profil'])) 
        ? 'uploads/profiles/' . htmlspecialchars($user['foto_profil']) 
        : $default_avatar;
    $display_name = htmlspecialchars($user['nama_lengkap'] ?? $user['username']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik Sehat</title>
    
    <!-- Memuat file Bootstrap 5 CSS dari folder lokal -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    
    <!-- Font Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Style kustom -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: 700;
        }
        .profile-img {
            width: 32px;
            height: 32px;
            object-fit: cover;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand text-primary" href="?url=dashboard/pasien">
            Klinik Sehat
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                <?php if ($is_logged_in && isset($user['id_peran']) && $user['id_peran'] == 4): // Tampilkan hanya jika pasien login ?>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="?url=notifikasi/index">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                              <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
                            </svg>
                            <span id="notification-badge" class="position-absolute top-2 start-100 translate-middle p-1 bg-danger border border-light rounded-circle visually-hidden"></span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="<?php echo $foto_profil_path; ?>" alt="Foto Profil" class="rounded-circle profile-img" onerror="this.onerror=null;this.src='<?php echo $default_avatar; ?>';">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><h6 class="dropdown-header">Masuk sebagai<br><span class="fw-normal"><?php echo $display_name; ?></span></h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="?url=profile/pengaturan">Pengaturan Profil</a></li>
                            <li><a class="dropdown-item" href="?url=auth/logout">Logout</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Konten utama akan dimulai di sini -->
<main class="container py-4">
