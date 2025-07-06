<?php
// File: views/auth/login.php
// Versi ini menggunakan Bootstrap 5 dengan desain dua panel dan link lupa password.

// Memanggil header. Pastikan header.php memuat file bootstrap.min.css.
require_once __DIR__ . '/../layouts/header.php';
?>

<style>
    /* CSS Kustom untuk layout split-screen */
    .login-container {
        min-height: 85vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-card {
        border-radius: 1rem;
        overflow: hidden; /* Penting agar border-radius terlihat di panel gradien */
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
    }

    .login-overlay {
        background: linear-gradient(to right, #1dd1a1, #10ac84);
        color: white;
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .login-form-panel {
        padding: 3rem;
    }

    /* Membuat input form lebih minimalis */
    .form-control-minimal {
        background-color: #f8f9fa;
        border: none;
        padding: 0.75rem 1rem;
    }
    .form-control-minimal:focus {
        background-color: #e9ecef;
        box-shadow: none;
    }
    
    .btn-custom-green {
        background-color: #10ac84;
        border-color: #10ac84;
        color: white;
        padding: 0.75rem;
        font-weight: 600;
        transition: background-color 0.2s;
    }
    .btn-custom-green:hover {
        background-color: #0e9975;
        border-color: #0e9975;
        color: white;
    }
</style>

<div class="container login-container">
    <div class="card login-card w-100" style="max-width: 900px;">
        <div class="row g-0">

            <!-- Panel Kiri (Overlay Gradien) -->
            <div class="col-lg-6 d-none d-lg-flex login-overlay">
                <h1 class="fw-bold mb-3">Selamat Datang Kembali!</h1>
                <p class="mb-4">Untuk tetap terhubung dengan kami, silakan masuk dengan akun Anda.</p>
                <a href="?url=auth/register" class="btn btn-outline-light rounded-pill px-4 py-2">Daftar Akun Baru</a>
            </div>

            <!-- Panel Kanan (Form Login) -->
            <div class="col-lg-6 login-form-panel">
                <div class="text-center mb-4">
                    <h2 class="fw-bold">Login Akun</h2>
                </div>

                <?php
                // Menampilkan pesan error atau sukses
                if (isset($_GET['error'])) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . htmlspecialchars($_GET['error']) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                }
                if (isset($_GET['status']) && $_GET['status'] === 'registrasi_sukses') {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Registrasi berhasil! Silakan login.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                }
                ?>

                <form action="?url=auth/authenticate" method="POST">
                    <!-- Input Username/Email -->
                    <div class="mb-3">
                        <input type="text" class="form-control form-control-minimal" id="username" name="username" placeholder="Username atau Email" required>
                    </div>

                    <!-- Input Password -->
                    <div class="mb-3">
                        <input type="password" class="form-control form-control-minimal" id="password" name="password" placeholder="Password" required>
                    </div>

                    <!-- Pilihan Peran/Role -->
                    <div class="mb-3">
                        <select class="form-select form-control-minimal" id="id_peran" name="id_peran" required>
                            <option value="" disabled selected>-- Login sebagai --</option>
                            <option value="4">Pasien</option>
                            <option value="3">Dokter</option>
                            <option value="2">Admin</option>
                            <option value="1">Super Admin</option>
                            <option value="5">Owner</option>
                        </select>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-custom-green rounded-pill">Login</button>
                    </div>
                </form>
                
                <!-- PERUBAHAN: Menambahkan link Lupa Password -->
                <div class="text-center mt-3">
                    <a href="?url=auth/forgot_password" class="text-muted small text-decoration-none">Lupa Password?</a>
                </div>

                <div class="text-center mt-4 d-lg-none">
                    <hr>
                    <p class="text-muted small">Belum punya akun?</p>
                    <a href="?url=auth/register" class="link-secondary">Daftar di sini</a>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
// Memanggil footer. Pastikan footer.php memuat file bootstrap.bundle.min.js.
require_once __DIR__ . '/../layouts/footer.php';
?>
