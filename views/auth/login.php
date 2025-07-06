<?php
// File: views/auth/login.php
// TIDAK PERLU ADA require_once untuk model di sini.
// File ini hanya untuk menampilkan HTML.

// Memanggil header
require_once __DIR__ . '/../layouts/header.php';

// Menampilkan pesan error jika ada
if (isset($_GET['error'])) {
    echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_GET['error']) . '</div>';
}
// Menampilkan pesan sukses jika ada (misal: setelah registrasi)
if (isset($_GET['status']) && $_GET['status'] === 'registrasi_sukses') {
    echo '<div class="alert alert-success" role="alert">Registrasi berhasil! Silakan login.</div>';
}
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">Login Pengguna</h4>
            </div>
            <div class="card-body p-4">
                <form action="?url=auth/authenticate" method="POST">
                    <!-- Input Username/Email -->
                    <div class="mb-3">
                        <label for="username" class="form-label">Username atau Email</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>

                   <form class="mt-8 space-y-6" action="?url=auth/authenticate" method="POST">
            <input type="hidden" name="remember" value="true">
            <div class="space-y-4 rounded-md shadow-sm">
                <div>
                    <label for="username" class="sr-only">Username atau Email</label>
                    <input id="username" name="username" type="text" required class="relative block w-full px-3 py-2 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Username atau Email">
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" required class="relative block w-full px-3 py-2 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Password">
                </div>
                 <div>
                    <label for="id_peran" class="sr-only">Login sebagai</label>
                    <select id="id_peran" name="id_peran" required class="relative block w-full px-3 py-2 text-gray-900 bg-white border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                        <option value="" disabled selected>-- Pilih Peran --</option>
                        <option value="4">Pasien</option>
                        <option value="3">Dokter</option>
                        <option value="2">Admin</option>
                        <option value="1">Super Admin</option>
                        <option value="5">Owner</option>
                    </select>
                </div>
            </div>

            <div>
                <button type="submit" class="relative flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md group hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Login
                </button>
            </div>
        </form>

        <div class="pt-4 text-sm text-center text-gray-600">
            <p>Belum punya akun?</p>
            <div class="mt-2 space-x-2">
                 <a href="?url=auth/register" class="font-medium text-indigo-600 hover:text-indigo-500">Daftar Pasien</a>
                 <span>|</span>
                 <a href="?url=auth/register_dokter" class="font-medium text-indigo-600 hover:text-indigo-500">Daftar Dokter</a>
            </div>
        </div>
    </div>
</div>

<?php
// Memanggil footer
require_once __DIR__ . '/../layouts/footer.php';
?>
