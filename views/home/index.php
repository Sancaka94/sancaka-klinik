<?php 
// File: views/home.php
// Menggunakan layout untuk halaman publik
require_once __DIR__ . '/layouts/header_public.php'; 
?>

<style>
    /* Hero Section */
    .hero-section {
        background: linear-gradient(rgba(0, 123, 255, 0.1), rgba(0, 123, 255, 0.1)), url('https://images.unsplash.com/photo-1551192422-a89470dd4207?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1932&q=80') no-repeat center center;
        background-size: cover;
        padding: 100px 0;
        text-align: center;
        color: #333;
    }
    .hero-section h1 {
        font-size: 3.5rem;
        font-weight: 700;
        color: var(--dark-color);
    }
    .hero-section p {
        font-size: 1.25rem;
        color: var(--secondary-color);
        max-width: 700px;
        margin: 20px auto;
    }
    .hero-section .btn {
        font-size: 1.1rem;
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 600;
    }

    /* Features Section */
    .features-section {
        padding: 80px 0;
    }
    .feature-item {
        text-align: center;
        padding: 20px;
    }
    .feature-item .icon {
        font-size: 3rem;
        color: var(--primary-color);
        margin-bottom: 20px;
        display: inline-block;
        background-color: #e7f3ff;
        width: 80px;
        height: 80px;
        line-height: 80px;
        border-radius: 50%;
    }
    .feature-item h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 10px;
    }

    /* Roles Section */
    .roles-section {
        background-color: #f8f9fa;
        padding: 80px 0;
    }
    .role-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.07);
        padding: 40px;
        text-align: center;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .role-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.1);
    }
    .role-card h2 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 15px;
    }
    .role-card p {
        margin-bottom: 30px;
        min-height: 50px;
    }
    .role-card .btn {
        margin: 5px;
    }
    
    /* Staff Login Section */
    .staff-login-section {
        padding: 60px 0;
        text-align: center;
    }
</style>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <h1 class="display-4">Pelayanan Kesehatan Terpadu di Ujung Jari Anda</h1>
        <p class="lead">Atur janji temu dengan dokter pilihan, akses riwayat medis, dan dapatkan pelayanan terbaik dengan mudah dan cepat melalui platform digital kami.</p>
        <a href="?url=janjitemu/buat" class="btn btn-primary btn-lg">Buat Janji Temu Sekarang</a>
    </div>
</div>

<!-- Features Section -->
<div class="features-section">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4">
                <div class="feature-item">
                    <span class="icon"><i class="bi bi-calendar2-check-fill"></i></span>
                    <h3>Jadwal Fleksibel</h3>
                    <p class="text-muted">Pilih dokter dan waktu konsultasi yang paling sesuai dengan jadwal Anda tanpa harus mengantre.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-item">
                    <span class="icon"><i class="bi bi-file-earmark-medical-fill"></i></span>
                    <h3>Rekam Medis Digital</h3>
                    <p class="text-muted">Akses seluruh riwayat kesehatan dan hasil konsultasi Anda kapan saja dan di mana saja dengan aman.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-item">
                    <span class="icon"><i class="bi bi-people-fill"></i></span>
                    <h3>Dokter Profesional</h3>
                    <p class="text-muted">Terhubung dengan dokter-dokter berpengalaman dan tersertifikasi di berbagai bidang spesialisasi.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Roles Section -->
<div class="roles-section">
    <div class="container">
        <div class="row">
            <!-- Card untuk Pasien -->
            <div class="col-lg-6 mb-4">
                <div class="role-card h-100">
                    <h2>Untuk Pasien</h2>
                    <p class="text-muted">Daftar untuk membuat janji temu, melihat riwayat medis, dan mengelola profil kesehatan Anda.</p>
                    <a href="?url=auth/register" class="btn btn-success btn-lg">Daftar Sekarang</a>
                    <a href="?url=auth/login" class="btn btn-outline-primary btn-lg">Login</a>
                </div>
            </div>
            <!-- Card untuk Dokter -->
            <div class="col-lg-6 mb-4">
                <div class="role-card h-100">
                    <h2>Untuk Dokter</h2>
                    <p class="text-muted">Bergabunglah dengan tim medis kami untuk memberikan pelayanan dan mengelola jadwal praktik Anda secara online.</p>
                    <a href="?url=auth/register_dokter" class="btn btn-info btn-lg text-white">Bergabunglah dengan Kami</a>
                    <a href="?url=auth/login" class="btn btn-outline-primary btn-lg">Login</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Staff Login Section -->
<div class="staff-login-section">
    <div class="container">
        <p class="text-muted">Apakah Anda bagian dari staf atau manajemen klinik?</p>
        <a href="?url=auth/login">Login sebagai Staf/Admin/Owner</a>
    </div>
</div>


<?php 
// Memanggil footer layout publik
require_once __DIR__ . '/../layouts/footer_public.php'; 
?>
