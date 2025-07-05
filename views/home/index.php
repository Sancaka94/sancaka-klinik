<?php 
// Panggil file header layout baru Anda
require_once __DIR__ . '/../layouts/header_public.php'; 
?>

<!-- Hero Section -->
<div class="container col-xxl-8 px-4 py-5">
    <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
        <div class="col-10 col-sm-8 col-lg-6">
            <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?q=80&w=2070&auto=format&fit=crop" class="d-block mx-lg-auto img-fluid rounded-3 shadow-lg" alt="Dokter sedang memeriksa pasien" width="700" height="500" loading="lazy">
        </div>
        <div class="col-lg-6">
            <h1 class="display-5 fw-bold lh-1 mb-3">Layanan Kesehatan Modern untuk Pemulihan Anda</h1>
            <p class="lead">Selamat datang di Klinik Sancaka. Kami menyediakan pelayanan medis profesional dengan dokter berpengalaman dan fasilitas modern untuk Anda dan keluarga.</p>
            <div class="d-grid gap-2 d-md-flex justify-content-md-start mt-4">
                <!-- Di bagian mana pun di halaman Anda -->
                <a href="?url=auth/register_dokter" class="tombol-pendaftaran-dokter">Bergabung Sebagai Dokter</a>
                <a href="?url=janjitemu/buat" type="button" class="btn btn-primary btn-lg px-4 me-md-2">Buat Janji Temu Sekarang</a>
                <a href="?url=auth/register" type="button" class="btn btn-outline-secondary btn-lg px-4">Daftar Sebagai Pasien Baru</a>
            </div>
        </div>
    </div>
</div>

<!-- Bagian Layanan (Tidak ada perubahan, tetap sama) -->
<div class="container px-4 py-5" id="layanan">
    <h2 class="pb-2 border-bottom text-center">Layanan Unggulan Kami</h2>
    <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
        <div class="col d-flex align-items-start">
            <div class="icon-square bg-light text-dark flex-shrink-0 me-3 p-2">
                <i class="bi bi-person-arms-up fs-2 text-primary"></i>
            </div>
            <div>
                <h4>Fisioterapi</h4>
                <p>Program terstruktur untuk mengembalikan fungsi gerak dan mengurangi nyeri.</p>
            </div>
        </div>
        <div class="col d-flex align-items-start">
            <div class="icon-square bg-light text-dark flex-shrink-0 me-3 p-2">
                 <i class="bi bi-puzzle fs-2 text-primary"></i>
            </div>
            <div>
                <h4>Terapi Okupasi</h4>
                <p>Membantu Anda kembali mandiri dalam aktivitas penting sehari-hari.</p>
            </div>
        </div>
        <div class="col d-flex align-items-start">
            <div class="icon-square bg-light text-dark flex-shrink-0 me-3 p-2">
                <i class="bi bi-clipboard2-pulse fs-2 text-primary"></i>
            </div>
            <div>
                <h4>Pemeriksaan Rutin</h4>
                <p>Layanan laboratorium lengkap untuk pemeriksaan darah, urin, dan tes diagnostik lainnya.</p>
            </div>
        </div>
    </div>
</div>


<?php 
// Panggil file footer layout Anda
require_once __DIR__ . '/../layouts/footer_public.php'; 
?>
