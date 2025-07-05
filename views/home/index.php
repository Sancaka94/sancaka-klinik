<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik Sancaka - Pusat Rehabilitasi Medik Modern</title>

    <!-- **PENYEMPURNAAN:** Menggunakan file Bootstrap lokal dari folder css/ -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons (tetap menggunakan CDN karena lebih praktis) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Animate On Scroll (AOS) Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- **PENYEMPURNAAN:** CSS Kustom bisa diletakkan di file terpisah -->
    <link rel="stylesheet" href="css/style.css"> <!-- Pastikan Anda membuat file ini jika perlu style tambahan -->

    <style>
        /* Anda bisa memindahkan semua style ini ke dalam file css/style.css */
        :root {
            --primary-color: #00796B; /* Teal */
            --secondary-color: #4DB6AC; /* Light Teal */
            --dark-color: #263238; /* Dark Grey */
            --light-color: #F5F5F5; /* Off-white */
        }
        body {
            font-family: 'Poppins', sans-serif;
            color: var(--dark-color);
        }
        .navbar {
            transition: background-color 0.4s ease-out, padding 0.4s ease-out;
        }
        .navbar.scrolled {
            background-color: #ffffff !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }
        .nav-link {
            font-weight: 600;
            color: #555;
        }
        .nav-link.active, .nav-link:hover {
            color: var(--primary-color);
        }
        .btn-cta {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            font-weight: 600;
            border-radius: 50px;
            padding: 10px 25px;
            transition: all 0.3s;
        }
        .btn-cta:hover {
            background-color: #004D40;
            border-color: #004D40;
            transform: translateY(-2px);
        }
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1584432810601-6c7f27d2362b?q=80&w=2070&auto=format&fit=crop') no-repeat center center;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        section {
            padding: 6rem 0;
        }
        .section-title {
            text-align: center;
            margin-bottom: 4rem;
        }
        .section-title h2 {
            font-weight: 700;
            position: relative;
            padding-bottom: 1rem;
        }
        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background-color: var(--primary-color);
            border-radius: 2px;
        }
        .service-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 1rem 3rem rgba(38,50,56,.15)!important;
        }
        .service-icon {
            font-size: 3.5rem;
            color: var(--primary-color);
        }
        .testimonial-card {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 2rem;
        }
        .testimonial-card img {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }
        .cta-section {
            background-color: var(--primary-color);
            color: white;
            padding: 5rem 0;
            border-radius: 20px;
        }
        .footer {
            background-color: var(--dark-color);
            color: #ccc;
            padding: 4rem 0 1rem 0;
        }
        .footer a {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s;
        }
        .footer a:hover {
            color: white;
        }
        .footer .social-icons a {
            font-size: 1.5rem;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <!-- 1. Navbar / Menu Utama -->
    <nav class="navbar navbar-expand-lg bg-transparent fixed-top py-3">
        <div class="container">
            <!-- **PENYEMPURNAAN:** Link disesuaikan dengan router -->
            <a class="navbar-brand fs-4" href="?url=home">
                <i class="bi bi-heart-pulse-fill"></i> Klinik Sancaka
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <!-- **PENYEMPURNAAN:** Link disesuaikan dengan router -->
                    <li class="nav-item"><a class="nav-link active" href="?url=home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">Tentang Kami</a></li>
                    <li class="nav-item"><a class="nav-link" href="#layanan">Layanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#kontak">Hubungi Kami</a></li>
                </ul>
                <!-- **PENYEMPURNAAN:** Link disesuaikan dengan router -->
                <a href="?url=auth/register" class="btn btn-cta">Daftar / Login</a>
            </div>
        </div>
    </nav>

    <!-- 2. Hero Section / Bagian Utama -->
    <header class="hero-section text-center">
        <div class="container" data-aos="fade-up">
            <h1 class="display-3 fw-bold">Solusi Modern untuk Pemulihan Anda</h1>
            <p class="lead my-4 col-md-8 mx-auto">Kami menggabungkan teknologi terkini dengan sentuhan personal untuk memberikan layanan rehabilitasi medik yang efektif dan nyaman.</p>
            <!-- **PENYEMPURNAAN:** Link disesuaikan dengan router -->
            <a href="?url=janjitemu/buat" class="btn btn-cta btn-lg mt-3">Buat Janji Temu</a>
        </div>
    </header>

    <!-- 3. Layanan Section -->
    <section id="layanan" class="py-5">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Layanan Unggulan</h2>
                <p class="text-muted">Didesain khusus untuk mempercepat proses pemulihan Anda.</p>
            </div>
            <div class="row text-center">
                <div class="col-md-6 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 shadow-sm service-card p-3">
                        <div class="card-body">
                            <div class="service-icon mb-3"><i class="bi bi-person-arms-up"></i></div>
                            <h5 class="card-title fw-bold">Fisioterapi</h5>
                            <p class="card-text text-muted">Program terstruktur untuk mengembalikan fungsi gerak dan mengurangi nyeri.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card h-100 shadow-sm service-card p-3">
                        <div class="card-body">
                            <div class="service-icon mb-3"><i class="bi bi-puzzle"></i></div>
                            <h5 class="card-title fw-bold">Terapi Okupasi</h5>
                            <p class="card-text text-muted">Membantu Anda kembali mandiri dalam aktivitas penting sehari-hari.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card h-100 shadow-sm service-card p-3">
                        <div class="card-body">
                            <div class="service-icon mb-3"><i class="bi bi-mic-fill"></i></div>
                            <h5 class="card-title fw-bold">Terapi Wicara</h5>
                            <p class="card-text text-muted">Solusi untuk gangguan komunikasi, bahasa, dan kemampuan menelan.</p>
                        </div>
                    </div>
                </div>
                 <div class="col-md-6 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="card h-100 shadow-sm service-card p-3">
                        <div class="card-body">
                            <div class="service-icon mb-3"><i class="bi bi-clipboard2-pulse"></i></div>
                            <h5 class="card-title fw-bold">Konsultasi Dokter</h5>
                            <p class="card-text text-muted">Penilaian komprehensif oleh Dokter Spesialis Rehabilitasi Medik (Sp.KFR).</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. About Section -->
    <section id="about" class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?q=80&w=2070&auto=format&fit=crop" class="img-fluid rounded-3 shadow-lg" alt="Dokter di Klinik Sancaka">
                </div>
                <div class="col-lg-6 ps-lg-5 mt-4 mt-lg-0" data-aos="fade-left">
                    <h2 class="fw-bold display-6">Mengapa Memilih Klinik Sancaka?</h2>
                    <p class="text-muted my-4">Kami bukan hanya penyedia layanan kesehatan, kami adalah partner dalam perjalanan pemulihan Anda.</p>
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex"><i class="bi bi-check-circle-fill text-primary me-2 fs-5"></i> <span><strong>Tim Profesional:</strong> Dokter dan terapis kami bersertifikat dan berpengalaman.</span></li>
                        <li class="mb-3 d-flex"><i class="bi bi-check-circle-fill text-primary me-2 fs-5"></i> <span><strong>Fasilitas Modern:</strong> Peralatan terapi terkini untuk hasil yang optimal.</span></li>
                        <li class="mb-3 d-flex"><i class="bi bi-check-circle-fill text-primary me-2 fs-5"></i> <span><strong>Pendekatan Personal:</strong> Program rehabilitasi dirancang khusus untuk setiap individu.</span></li>
                    </ul>
                    <!-- **PENYEMPURNAAN:** Link disesuaikan dengan router (contoh) -->
                    <a href="?url=page/about" class="btn btn-outline-primary rounded-pill px-4 mt-3">Pelajari Lebih Lanjut</a>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Testimonial Section -->
    <section id="testimonials" class="py-5">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Apa Kata Pasien Kami</h2>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card testimonial-card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <img src="https://i.pravatar.cc/150?img=1" class="rounded-circle mb-3" alt="Pasien 1">
                            <p class="text-muted fst-italic">"Layanan fisioterapi di sini luar biasa. Terapisnya sangat sabar dan profesional. Saya merasa jauh lebih baik sekarang."</p>
                            <h6 class="fw-bold mt-3 mb-0">Budi Santoso</h6>
                            <small class="text-muted">Pasien Fisioterapi</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card testimonial-card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <img src="https://i.pravatar.cc/150?img=5" class="rounded-circle mb-3" alt="Pasien 2">
                            <p class="text-muted fst-italic">"Terima kasih Klinik Sancaka, berkat terapi okupasi di sini, saya bisa kembali melakukan aktivitas harian saya dengan percaya diri."</p>
                            <h6 class="fw-bold mt-3 mb-0">Siti Aminah</h6>
                            <small class="text-muted">Pasien Terapi Okupasi</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card testimonial-card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <img src="https://i.pravatar.cc/150?img=8" class="rounded-circle mb-3" alt="Pasien 3">
                            <p class="text-muted fst-italic">"Dokternya sangat informatif dan ramah. Penjelasannya mudah dimengerti dan program terapinya sangat membantu."</p>
                            <h6 class="fw-bold mt-3 mb-0">Agus Setiawan</h6>
                            <small class="text-muted">Pasien Konsultasi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. Call to Action (CTA) Section -->
    <section class="py-5 bg-light">
        <div class="container" data-aos="fade-up">
            <div class="cta-section text-center p-5">
                <h2 class="display-6 fw-bold">Siap Memulai Perjalanan Pemulihan Anda?</h2>
                <p class="lead my-4">Jangan tunda kesehatan Anda. Jadwalkan konsultasi pertama Anda dengan tim ahli kami hari ini.</p>
                <!-- **PENYEMPURNAAN:** Link disesuaikan dengan router -->
                <a href="?url=auth/register" class="btn btn-light btn-lg rounded-pill px-5 py-3">Daftar Sekarang</a>
            </div>
        </div>
    </section>

    <!-- 7. Footer -->
    <footer class="footer" id="kontak">
        <div class="container">
            <div class="row py-5">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="fw-bold text-white mb-3">Klinik Sancaka</h5>
                    <p>Pusat rehabilitasi medik terpercaya yang berdedikasi untuk meningkatkan kualitas hidup pasien.</p>
                    <div class="social-icons mt-4">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-twitter"></i></a>
                        <a href="#"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                    <h5 class="fw-bold text-white mb-3">Navigasi</h5>
                    <ul class="list-unstyled">
                        <!-- **PENYEMPURNAAN:** Link disesuaikan dengan router -->
                        <li><a href="?url=home">Home</a></li>
                        <li><a href="#about">Tentang Kami</a></li>
                        <li><a href="#layanan">Layanan</a></li>
                        <li><a href="#kontak">Kontak</a></li>
                        <li><a href="?url=auth/login" target="_blank">Login Staf</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <h5 class="fw-bold text-white mb-3">Layanan Kami</h5>
                    <ul class="list-unstyled">
                        <li><a href="#layanan">Fisioterapi</a></li>
                        <li><a href="#layanan">Terapi Okupasi</a></li>
                        <li><a href="#layanan">Terapi Wicara</a></li>
                        <li><a href="#layanan">Konsultasi Dokter</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h5 class="fw-bold text-white mb-3">Hubungi Kami</h5>
                    <ul class="list-unstyled">
                        <li class="d-flex mb-2"><i class="bi bi-geo-alt-fill me-2"></i> <span>Jl. Kesehatan No. 123, Ngawi, Jawa Timur</span></li>
                        <li class="d-flex mb-2"><i class="bi bi-telephone-fill me-2"></i> <span>(0351) 123-456</span></li>
                        <li class="d-flex"><i class="bi bi-envelope-fill me-2"></i> <span>info@kliniksancaka.com</span></li>
                    </ul>
                </div>
            </div>
            <div class="text-center border-top border-secondary pt-3">
                <p class="mb-0">&copy; 2025 Klinik Sancaka. Didesain dengan <i class="bi bi-heart-fill text-danger"></i>.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <!-- **PENYEMPURNAAN:** Menggunakan file Bootstrap lokal dari folder js/ -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Inisialisasi AOS (Animate On Scroll)
        AOS.init({
            duration: 1000,
            once: true,
        });

        // Efek navbar berubah warna saat di-scroll
        const navbar = document.querySelector('.navbar');
        window.onscroll = () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
                navbar.classList.remove('bg-transparent');
                navbar.classList.add('bg-light');
            } else {
                navbar.classList.remove('scrolled');
                navbar.classList.remove('bg-light');
                navbar.classList.add('bg-transparent');
            }
        };
    </script>
</body>
</html>
