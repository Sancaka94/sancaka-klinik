<?php 
// File: views/auth/registrasi_berhasil.php
// Memastikan layout publik yang benar dipanggil
require_once __DIR__ . '/../layouts/header_public.php'; 
?>

<div class="container my-5 text-center">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-lg border-0 rounded-4 p-5">
                <div class="card-body">
                    <!-- Ikon Ceklis Besar -->
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    
                    <h1 class="display-5 fw-bold mt-4">Pendaftaran Berhasil!</h1>
                    
                    <p class="lead text-muted my-4">
                        Terima kasih telah mendaftar. Akun Anda telah berhasil dibuat. Silakan login untuk melanjutkan ke dashboard pasien dan membuat janji temu pertama Anda.
                    </p>
                    
                    <!-- Tombol untuk ke halaman login -->
                    <a href="?url=auth/login" class="btn btn-primary btn-lg px-5">
                        Lanjut ke Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// Memanggil footer layout publik
require_once __DIR__ . '/../layouts/footer_public.php'; 
?>
