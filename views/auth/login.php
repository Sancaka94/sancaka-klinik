<?php
// File: views/auth/login.php
// Versi ini menggunakan Tailwind CSS untuk tampilan yang modern.

// Memanggil header. Pastikan header.php memuat Tailwind CSS.
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
        
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-900">Login Akun</h1>
            <p class="mt-2 text-sm text-gray-600">Silakan masuk untuk melanjutkan.</p>
        </div>

        <?php
        // Menampilkan pesan error jika ada
        if (isset($_GET['error'])) {
            echo '<div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">' . htmlspecialchars($_GET['error']) . '</div>';
        }
        // Menampilkan pesan sukses jika ada
        if (isset($_GET['status']) && $_GET['status'] === 'registrasi_sukses') {
            echo '<div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">Registrasi berhasil! Silakan login.</div>';
        }
        ?>

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
