<?php
// File: views/layouts/header_public.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik Sancaka - Selamat Datang</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="?url=home">
            <i class="bi bi-heart-pulse-fill me-2"></i>Klinik Sancaka
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="?url=home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?url=home#layanan">Layanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?url=home#about">Tentang Kami</a>
                </li>
            </ul>
            <div class="d-flex align-items-center ms-lg-3">
                <a href="?url=janjitemu/buat" class="btn btn-outline-primary me-2">Janji Temu</a>
                <a href="?url=auth/register" class="btn btn-primary">Daftar Pasien</a>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle ms-2" type="button" id="loginDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Login
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="loginDropdown">
                        <li><a class="dropdown-item" href="?url=auth/login&peran=pasien">Login Pasien</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="?url=auth/login&peran=staf">Login Dokter</a></li>
                        <li><a class="dropdown-item" href="?url=auth/login&peran=staf">Login Admin</a></li>
                        <li><a class="dropdown-item" href="?url=auth/login&peran=staf">Login Owner</a></li>
                        <li><a class="dropdown-item" href="?url=auth/login&peran=staf">Login Super Admin</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<main>
