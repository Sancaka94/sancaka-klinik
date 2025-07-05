<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Variabel $pasien_data disiapkan oleh ProfileController
$default_avatar = 'https://placehold.co/150x150/E2E8F0/4A5568?text=Profil';
$foto_profil_path = (!empty($pasien_data['foto_profil'])) ? 'uploads/profiles/' . htmlspecialchars($pasien_data['foto_profil']) : $default_avatar;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Profil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-100">

    <div class="min-h-screen flex flex-col">
        <!-- Header Navigasi -->
        <nav class="bg-white shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <a href="?url=dashboard/pasien" class="flex items-center"><div class="flex-shrink-0 text-2xl font-bold text-indigo-600">Klinik Sehat</div></a>
                    <a href="?url=auth/logout" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium transition">Logout</a>
                </div>
            </div>
        </nav>

        <!-- Konten Utama -->
        <main class="flex-grow">
            <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="bg-white p-8 rounded-lg shadow">
                    <div class="mb-6">
                        <h1 class="text-3xl font-bold text-gray-800">Pengaturan Profil</h1>
                        <p class="text-gray-600 mt-1">Perbarui informasi pribadi dan kontak Anda.</p>
                    </div>

                    <!-- Notifikasi Sukses atau Error -->
                    <?php if (isset($_GET['success'])): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline"><?php echo htmlspecialchars($_GET['success']); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_GET['error'])): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline"><?php echo htmlspecialchars($_GET['error']); ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- **PERBAIKAN:** Tambahkan enctype untuk upload file -->
                    <form action="?url=profile/update" method="POST" enctype="multipart/form-data">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Kolom Foto Profil -->
                            <div class="md:col-span-1 flex flex-col items-center">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                                <img class="h-32 w-32 rounded-full object-cover mb-4" src="<?php echo $foto_profil_path; ?>" alt="Foto Profil Saat Ini" onerror="this.onerror=null;this.src='<?php echo $default_avatar; ?>';">
                                <input type="file" name="foto_profil" id="foto_profil" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF (MAX. 1MB)</p>
                            </div>

                            <!-- Kolom Form Lainnya -->
                            <div class="md:col-span-2 grid grid-cols-1 gap-6">
                                <div>
                                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                    <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?php echo htmlspecialchars($pasien_data['nama_lengkap'] ?? ''); ?>" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($pasien_data['email'] ?? ''); ?>" readonly class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-100 cursor-not-allowed">
                                </div>
                                <div>
                                    <label for="nomor_telepon" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                                    <input type="tel" id="nomor_telepon" name="nomor_telepon" value="<?php echo htmlspecialchars($pasien_data['nomor_telepon'] ?? ''); ?>" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo htmlspecialchars($pasien_data['tanggal_lahir'] ?? ''); ?>" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="mt-8 pt-5 border-t border-gray-200">
                            <div class="flex justify-end">
                                <a href="?url=dashboard/pasien" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">Kembali</a>
                                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
