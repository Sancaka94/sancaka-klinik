<?php
// File ini dipanggil oleh NotifikasiController.
// Variabel $daftar_notifikasi sudah disiapkan di sana.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Anda</title>
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
                    <a href="?url=dashboard/pasien" class="flex items-center">
                        <div class="flex-shrink-0 text-2xl font-bold text-indigo-600">Klinik Sehat</div>
                    </a>
                    <a href="?url=auth/logout" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium transition">Logout</a>
                </div>
            </div>
        </nav>

        <!-- Konten Utama -->
        <main class="flex-grow">
            <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-800">Pemberitahuan</h1>
                    <a href="?url=dashboard/pasien" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">&larr; Kembali ke Dashboard</a>
                </div>

                <div class="bg-white rounded-lg shadow">
                    <ul class="divide-y divide-gray-200">
                        <?php if (isset($daftar_notifikasi) && !empty($daftar_notifikasi)): ?>
                            <?php foreach ($daftar_notifikasi as $notif): ?>
                                <li class="p-4 hover:bg-gray-50 <?php echo ($notif['sudah_dibaca'] == 0) ? 'bg-indigo-50' : ''; ?>">
                                    <a href="<?php echo htmlspecialchars($notif['link'] ?? '#'); ?>" class="block">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-semibold text-indigo-600"><?php echo htmlspecialchars($notif['judul']); ?></p>
                                            <p class="text-xs text-gray-500"><?php echo date('d M Y, H:i', strtotime($notif['tanggal_dibuat'])); ?></p>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-600">
                                            <?php echo htmlspecialchars($notif['pesan']); ?>
                                        </p>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="p-6 text-center">
                                <p class="text-sm text-gray-500">Anda tidak memiliki pemberitahuan saat ini.</p>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>

            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white mt-8">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-center text-gray-500">
                &copy; <?php echo date('Y'); ?> Klinik Sehat. Semua Hak Cipta Dilindungi.
            </div>
        </footer>
    </div>

</body>
</html>
