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
    
    <!-- Memuat file Bootstrap CSS dari folder 'css' Anda -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    
    <!-- (Opsional) Memuat file CSS custom Anda sendiri -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="?url=home">
            Klinik Sancaka
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="?url=home">Home</a>
                </li>
                <?php if (!isset($_SESSION['user'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="?url=auth/login">Login</a>
                </li>
                <li class="nav-item">
                    <a href="?url=auth/register" class="btn btn-primary ms-2">Daftar</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-5">
