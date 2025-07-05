<?php 
// Panggil file header layout Anda
require_once __DIR__ . '/../layouts/header_public.php'; 
?>

<!-- Bagian Hero Section -->
<div class="container col-xxl-8 px-4 py-5">
    <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
        <div class="col-10 col-sm-8 col-lg-6">
            <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?q=80&w=2070&auto=format&fit=crop" class="d-block mx-lg-auto img-fluid rounded-3 shadow-lg" alt="Dokter sedang memeriksa pasien" width="700" height="500" loading="lazy">
        </div>
        <div class="col-lg-6">
            <h1 class="display-5 fw-bold lh-1 mb-3">Layanan Kesehatan Terpercaya untuk Anda dan Keluarga</h1>
            <p class="lead">Klinik Sehat menyediakan pelayanan medis profesional dengan dokter berpengalaman dan fasilitas modern. Prioritas kami adalah kesehatan dan kenyamanan Anda.</p>
            <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                <a href="?url=auth/register" type="button" class="btn btn-primary btn-lg px-4 me-md-2">Buat Janji Temu</a>
                <a href="#layanan" type="button" class="btn btn-outline-secondary btn-lg px-4">Lihat Layanan</a>
            </div>
        </div>
    </div>
</div>

<!-- Bagian Layanan -->
<div class="container px-4 py-5" id="layanan">
    <h2 class="pb-2 border-bottom text-center">Layanan Unggulan Kami</h2>
    <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
        <div class="col d-flex align-items-start">
            <div class="icon-square bg-light text-dark flex-shrink-0 me-3 p-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-heart-pulse" viewBox="0 0 16 16"><path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053.918 3.995.78 5.323 1.508 7H.43c-2.128-5.697 4.165-8.83 7.394-5.857.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17c3.23-2.974 9.522.159 7.394 5.856h-1.078c.728-1.677.59-3.005.108-3.947C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-2.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17c4.545-3.182 15.157 3.725 7.824 13.857C14.282 14.815 12.502 14.5 8 14.5s-6.282.315-7.824.508z"/></svg>
            </div>
            <div>
                <h4>Konsultasi Dokter Umum</h4>
                <p>Pelayanan medis dasar untuk berbagai keluhan kesehatan umum, dari flu hingga pemeriksaan rutin.</p>
            </div>
        </div>
        <div class="col d-flex align-items-start">
            <div class="icon-square bg-light text-dark flex-shrink-0 me-3 p-2">
                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-badge" viewBox="0 0 16 16"><path d="M6.5 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/><path d="M4.5 0A2.5 2.5 0 0 0 2 2.5V14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2.5A2.5 2.5 0 0 0 11.5 0h-7zM3 2.5A1.5 1.5 0 0 1 4.5 1h7A1.5 1.5 0 0 1 13 2.5v10.795a4.2 4.2 0 0 0-.776-.492A3.14 3.14 0 0 0 11 11.5a3.14 3.14 0 0 0-2.224.905 4.2 4.2 0 0 0-.776.492V2.5z"/></svg>
            </div>
            <div>
                <h4>Dokter Spesialis</h4>
                <p>Tersedia dokter spesialis THT, Anak, dan Penyakit Dalam dengan jadwal yang fleksibel.</p>
            </div>
        </div>
        <div class="col d-flex align-items-start">
            <div class="icon-square bg-light text-dark flex-shrink-0 me-3 p-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-pulse" viewBox="0 0 16 16"><path d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2V1.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5h3Z"/><path d="M3 3.5a.5.5 0 0 0-.5.5V14a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V4a.5.5 0 0 0-.5-.5h-1v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5V3.5h-1v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5V3.5h-1v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5V3.5H3Z"/><path d="M10.854 8.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708 0Z"/></svg>
            </div>
            <div>
                <h4>Pemeriksaan Laboratorium</h4>
                <p>Layanan laboratorium lengkap untuk pemeriksaan darah, urin, dan tes diagnostik lainnya.</p>
            </div>
        </div>
    </div>
</div>

<?php 
// Panggil file footer layout Anda
require_once __DIR__ . '/../layouts/footer_public.php'; 
?>
