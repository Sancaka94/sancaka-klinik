<?php
// File ini dipanggil oleh DashboardController.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect jika pengguna belum login
if (!isset($_SESSION['user']) || $_SESSION['user']['id_peran'] != 4) {
    header('Location: ?url=auth/login&error=Anda harus login sebagai pasien.');
    exit;
}

$user = $_SESSION['user'];

// **PERBAIKAN:** Membuat variabel $display_name untuk sapaan
// Gunakan nama lengkap jika ada, jika tidak, gunakan username.
$display_name = !empty($user['nama_lengkap']) ? $user['nama_lengkap'] : $user['username'];

// Tentukan path foto profil, gunakan gambar default jika tidak ada
$default_avatar = 'https://placehold.co/100x100/E2E8F0/4A5568?text=Profil';
$foto_profil_path = (!empty($user['foto_profil'])) ? 'uploads/profiles/' . htmlspecialchars($user['foto_profil']) : $default_avatar;

// Data notifikasi (ini seharusnya diambil dari model Notifikasi nanti)
$unread_notifications = 1; 

// Data janji temu (ini seharusnya datang dari controller)
$riwayat_janji_temu = isset($riwayat_janji_temu) ? $riwayat_janji_temu : [];
$janji_berikutnya = isset($janji_berikutnya) ? $janji_berikutnya : null;
$rekam_medis_terakhir = isset($rekam_medis_terakhir) ? $rekam_medis_terakhir : null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pasien</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-100">

    <div class="min-h-screen flex flex-col">
        <!-- Header Navigasi -->
        <nav class="bg-white shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <a href="?url=dashboard/pasien" class="flex items-center space-x-2">
                        <svg class="h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.5v3m0 9v3m4.5-7.5h3m-15 0h3" /></svg>
                        <div class="flex-shrink-0 text-2xl font-bold text-indigo-600">Klinik Sehat</div>
                    </a>
                    <div class="flex items-center space-x-4">
                        <a href="?url=notifikasi/index" class="relative text-gray-600 hover:text-indigo-600 p-2 rounded-full">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                            <?php if (isset($unread_notifications) && $unread_notifications > 0): ?>
                            <span class="absolute top-1 right-1 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                            <?php endif; ?>
                        </a>
                        <div class="relative">
                            <button id="profile-menu-button" class="flex text-sm bg-white rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <span class="sr-only">Buka menu pengguna</span>
                                <img class="h-8 w-8 rounded-full object-cover" src="<?php echo $foto_profil_path; ?>" alt="Foto Profil" onerror="this.onerror=null;this.src='<?php echo $default_avatar; ?>';">
                            </button>
                            <div id="profile-menu" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="profile-menu-button">
                                <div class="px-4 py-2 border-b"><p class="text-sm text-gray-700">Masuk sebagai</p><p class="text-sm font-medium text-gray-900 truncate"><?php echo htmlspecialchars($display_name); ?></p></div>
                                <a href="?url=profile/pengaturan" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"><svg class="h-5 w-5 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>Pengaturan Profil</a>
                                <a href="?url=auth/logout" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"><svg class="h-5 w-5 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Konten Utama -->
        <main class="flex-grow">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                
                <div class="mb-8">
                    <!-- **PERBAIKAN:** Menggunakan variabel $display_name yang sudah didefinisikan -->
                    <h1 class="text-3xl font-bold text-gray-800">Selamat Datang, <?php echo htmlspecialchars($display_name); ?>!</h1>
                    <p class="text-gray-600 mt-1">Ini adalah pusat informasi kesehatan Anda.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <div id="janji-berikutnya-card" class="bg-white rounded-lg shadow p-6"></div>
                    <div id="rekam-medis-terakhir-card" class="bg-white rounded-lg shadow p-6"></div>
                    <a href="?url=janjitemu/buat" class="bg-indigo-600 text-white rounded-lg shadow p-6 flex flex-col justify-center items-center hover:bg-indigo-700 transition">
                        <svg class="h-12 w-12" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                        <h3 class="mt-2 text-lg font-medium">Buat Janji Temu Baru</h3>
                    </a>
                </div>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6 border-b border-gray-200"><h2 class="text-xl font-semibold text-gray-800">Riwayat dan Status Antrian</h2></div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dokter</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Antrian</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="riwayat-table-body" class="bg-white divide-y divide-gray-200">
                                <!-- Data akan diisi oleh JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        <footer class="bg-white"><div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-center text-gray-500">&copy; <?php echo date('Y'); ?> Klinik Sehat.</div></footer>
    </div>

    <script>
        // JavaScript untuk dropdown profil
        const profileMenuButton = document.getElementById('profile-menu-button');
        const profileMenu = document.getElementById('profile-menu');
        profileMenuButton.addEventListener('click', () => profileMenu.classList.toggle('hidden'));
        window.addEventListener('click', (e) => {
            if (!profileMenuButton.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.classList.add('hidden');
            }
        });

        // JavaScript untuk Realtime Update
        const janjiCard = document.getElementById('janji-berikutnya-card');
        const rekamMedisCard = document.getElementById('rekam-medis-terakhir-card');
        const riwayatTableBody = document.getElementById('riwayat-table-body');
        const notificationBadge = document.getElementById('notification-badge');

        function formatDate(dateString) {
            if (!dateString) return '-';
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('id-ID', options);
        }

        async function updateDashboard() {
            try {
                const response = await fetch('?url=dashboard/api');
                if (!response.ok) return;

                const data = await response.json();

                // 1. Update Kartu Janji Temu Berikutnya
                if (data.janji_berikutnya) {
                    janjiCard.innerHTML = `
                        <h3 class="text-lg font-medium text-gray-900">Janji Temu Berikutnya</h3>
                        <p class="text-2xl font-semibold text-indigo-600 mt-2">${data.janji_berikutnya.dokter}</p>
                        <p class="text-sm text-gray-500">${formatDate(data.janji_berikutnya.tanggal_booking)} - Antrian ${data.janji_berikutnya.nomor_antrian}</p>
                    `;
                } else {
                    janjiCard.innerHTML = `
                        <h3 class="text-lg font-medium text-gray-900">Janji Temu Berikutnya</h3>
                        <p class="text-gray-500 mt-2">Tidak ada janji temu yang direncanakan.</p>
                    `;
                }

                // 2. Update Kartu Rekam Medis Terakhir
                if (data.rekam_medis_terakhir) {
                    rekamMedisCard.innerHTML = `
                        <h3 class="text-lg font-medium text-gray-900">Rekam Medis Terakhir</h3>
                        <p class="text-2xl font-semibold text-green-600 mt-2">Pemeriksaan Selesai</p>
                        <p class="text-sm text-gray-500">${formatDate(data.rekam_medis_terakhir.tanggal_booking)} oleh ${data.rekam_medis_terakhir.dokter}</p>
                    `;
                } else {
                    rekamMedisCard.innerHTML = `
                        <h3 class="text-lg font-medium text-gray-900">Rekam Medis Terakhir</h3>
                        <p class="text-gray-500 mt-2">Belum ada rekam medis.</p>
                    `;
                }

                // 3. Update Tabel Riwayat
                riwayatTableBody.innerHTML = '';
                if (data.riwayat_janji_temu.length > 0) {
                    data.riwayat_janji_temu.forEach(janji => {
                        let statusColor = 'bg-gray-100 text-gray-800';
                        if (janji.status === 'Selesai') statusColor = 'bg-green-100 text-green-800';
                        if (janji.status === 'Direncanakan') statusColor = 'bg-blue-100 text-blue-800';
                        if (janji.status === 'Batal') statusColor = 'bg-red-100 text-red-800';
                        
                        const aksiHtml = janji.rekam_medis_tersedia
                            ? `<a href="?url=rekammedis/detail&id=${janji.id}" class="text-indigo-600 hover:text-indigo-900">Lihat Rekam Medis</a>`
                            : `<span class="text-gray-400">-</span>`;

                        const row = `
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatDate(janji.tanggal_booking)}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${janji.dokter}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700">${janji.nomor_antrian || '-'}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusColor}">${janji.status}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">${aksiHtml}</td>
                            </tr>
                        `;
                        riwayatTableBody.innerHTML += row;
                    });
                } else {
                    riwayatTableBody.innerHTML = `<tr><td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Anda belum memiliki riwayat janji temu.</td></tr>`;
                }

                // 4. Update Badge Notifikasi
                if (data.unread_notifications > 0) {
                    notificationBadge.classList.remove('hidden');
                } else {
                    notificationBadge.classList.add('hidden');
                }

            } catch (error) {
                console.error('Gagal mengambil data dashboard:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', updateDashboard);
        setInterval(updateDashboard, 1000);
    </script>
</body>
</html>
