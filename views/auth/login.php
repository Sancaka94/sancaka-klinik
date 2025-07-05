<?php 
// File: views/auth/login.php
// Menggunakan layout untuk halaman publik
require_once __DIR__ . '/../layouts/header_public.php'; 
?>

<style>
    .login-container {
        display: flex;
        min-height: 100vh;
        width: 100%;
    }
    .login-form-section {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
    }
    .login-branding-section {
        flex: 1;
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.85), rgba(0, 86, 179, 0.9)), url('https://images.unsplash.com/photo-1576091160550-2173dba999ef?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
        background-size: cover;
        background-position: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: white;
        text-align: center;
        padding: 40px;
    }
    .login-branding-section h1 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 15px;
    }
    .login-branding-section p {
        font-size: 1.1rem;
        max-width: 400px;
        opacity: 0.9;
    }
    .form-wrapper {
        width: 100%;
        max-width: 450px;
    }
    .form-wrapper h2 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: #333;
    }
    .form-wrapper .text-muted {
        margin-bottom: 30px;
    }
    .form-floating label {
        color: #6c757d;
    }
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        padding: 12px;
        font-weight: 600;
    }
    .registration-links {
        margin-top: 25px;
        text-align: center;
        font-size: 0.9em;
    }
    .registration-links a {
        color: #007bff;
        text-decoration: none;
        font-weight: 500;
    }
    .registration-links a:hover {
        text-decoration: underline;
    }
    .or-divider {
        display: flex;
        align-items: center;
        text-align: center;
        color: #6c757d;
        margin: 15px 0;
    }
    .or-divider::before, .or-divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #dee2e6;
    }
    .or-divider:not(:empty)::before {
        margin-right: .25em;
    }
    .or-divider:not(:empty)::after {
        margin-left: .25em;
    }

    @media (max-width: 992px) {
        .login-branding-section {
            display: none;
        }
        .login-form-section {
            background-color: #f4f7f9;
        }
    }
</style>

<div class="login-container">
    <div class="login-branding-section">
        <i class="bi bi-heart-pulse-fill" style="font-size: 4rem; margin-bottom: 20px;"></i>
        <h1>Selamat Datang Kembali</h1>
        <p>Akses sistem manajemen klinik terpadu untuk memberikan pelayanan terbaik bagi pasien Anda.</p>
    </div>

    <div class="login-form-section">
        <div class="form-wrapper">
            <h2>Login Akun</h2>
            <p class="text-muted">Silakan masukkan kredensial Anda untuk melanjutkan.</p>

            <form action="?url=auth/authenticate" method="POST">
                <div class="form-floating mb-3">
                    <select class="form-select rounded-3" id="id_peran" name="id_peran" required>
                        <option value="" disabled selected>-- Pilih peran Anda --</option>
                        <option value="4">Saya adalah Pasien</option>
                        <option value="3">Saya adalah Dokter</option>
                        <option value="2">Saya adalah Admin</option>
                        <option value="5">Saya adalah Owner Klinik</option>
                        <option value="1">Saya adalah Super Admin</option>
                    </select>
                    <label for="id_peran">Anda login sebagai...</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control rounded-3" id="username" name="username" placeholder="Email atau Username" required>
                    <label for="username">Email atau Username</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control rounded-3" id="password" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                </div>

                <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Login</button>
            </form>

            <div class="registration-links">
                <p class="mb-1">Belum punya akun pasien? <a href="?url=auth/register">Daftar di sini</a></p>
                <div class="or-divider">atau</div>
                <p class="mb-0">Mitra dokter? <a href="?url=auth/register_dokter">Daftar sebagai Dokter</a></p>
            </div>
        </div>
    </div>
</div>

<?php 
// Memanggil footer layout publik
require_once __DIR__ . '/../layouts/footer_public.php'; 
?>
