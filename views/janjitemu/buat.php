<?php
// File ini dipanggil oleh JanjitemuController.
// Variabel $daftar_dokter sudah disiapkan di sana.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Janji Temu Baru</title>
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
            <div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                
                <div class="bg-white p-8 rounded-lg shadow">
                    <div class="mb-6">
                        <h1 class="text-3xl font-bold text-gray-800">Buat Janji Temu Baru</h1>
                        <p class="text-gray-600 mt-1">Silakan isi form di bawah ini untuk menjadwalkan konsultasi Anda.</p>
                    </div>

                    <form action="?url=janjitemu/simpan" method="POST">
                        <div class="space-y-6">
                            <!-- Pilihan Dokter -->
                            <div>
                                <label for="dokter" class="block text-sm font-medium text-gray-700">Pilih Dokter</label>
                                <select id="dokter" name="id_staf_dokter" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="" disabled selected>-- Pilih salah satu dokter --</option>
                                    <?php if (isset($daftar_dokter) && !empty($daftar_dokter)): ?>
                                        <?php foreach ($daftar_dokter as $dokter): ?>
                                            <option value="<?php echo $dokter['id_staf']; ?>">
                                                <?php echo htmlspecialchars($dokter['nama_lengkap']) . ' (' . htmlspecialchars($dokter['spesialisasi']) . ')'; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" disabled>Tidak ada dokter yang tersedia saat ini.</option>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <!-- **PERBAIKAN:** Input Tanggal dan Waktu dipisah -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <!-- Tanggal Janji Temu -->
                                <div>
                                    <label for="tanggal" class="block text-sm font-medium text-gray-700">Pilih Tanggal</label>
                                    <input type="date" id="tanggal" name="tanggal" required class="mt-1 block w-full pl-3 pr-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" min="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <!-- Waktu Janji Temu -->
                                <div>
                                    <label for="waktu" class="block text-sm font-medium text-gray-700">Pilih Waktu</label>
                                    <input type="time" id="waktu" name="waktu" required class="mt-1 block w-full pl-3 pr-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                </div>
                            </div>

                             <!-- Keluhan -->
                            <div>
                                <label for="keluhan" class="block text-sm font-medium text-gray-700">Keluhan</label>
                                <textarea id="keluhan" name="keluhan" rows="4" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="Jelaskan keluhan utama Anda..."></textarea>
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="mt-8 pt-5 border-t border-gray-200">
                            <div class="flex justify-end">
                                <a href="?url=dashboard/pasien" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Batal
                                </a>
                                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Buat Janji Temu
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

</body>
</html>
