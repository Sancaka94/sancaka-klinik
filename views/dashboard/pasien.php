<?php
// File: views/dashboard/pasien.php

// =================================================================
// BAGIAN 1: MEMANGGIL HEADER
// Header sudah berisi semua logika session dan tag <head>
// =================================================================
require_once __DIR__ . '/../layouts/header.php';
// Variabel $display_name sudah disiapkan di dalam header.php
?>

<!-- 
=================================================================
BAGIAN 2: KONTEN UTAMA HALAMAN (Bootstrap 5)
Struktur HTML telah diubah untuk menggunakan kelas-kelas Bootstrap.
=================================================================
-->
<div class="mb-4">
    <h1 class="h3 mb-1 text-dark">Selamat Datang, <?php echo $display_name; ?>!</h1>
    <p class="text-muted">Ini adalah pusat informasi kesehatan Anda.</p>
</div>

<!-- Kartu Informasi Cepat -->
<div class="row g-4 mb-4">
    <!-- Kartu Janji Temu Berikutnya -->
    <div class="col-lg-4 col-md-6">
        <div class="card h-100 shadow-sm">
            <div class="card-body" id="janji-berikutnya-card">
                <!-- Konten diisi oleh JavaScript -->
                <h5 class="card-title">Janji Temu Berikutnya</h5>
                <p class="card-text text-muted">Memuat data...</p>
            </div>
        </div>
    </div>
    <!-- Kartu Rekam Medis Terakhir -->
    <div class="col-lg-4 col-md-6">
        <div class="card h-100 shadow-sm">
            <div class="card-body" id="rekam-medis-terakhir-card">
                <!-- Konten diisi oleh JavaScript -->
                <h5 class="card-title">Rekam Medis Terakhir</h5>
                <p class="card-text text-muted">Memuat data...</p>
            </div>
        </div>
    </div>
    <!-- Kartu Buat Janji Temu Baru -->
    <div class="col-lg-4 col-md-12">
        <a href="?url=janjitemu/buat" class="card h-100 bg-primary text-white shadow-sm text-decoration-none d-flex flex-column justify-content-center align-items-center">
            <div class="card-body text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-plus-circle-fill mb-2" viewBox="0 0 16 16">
                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                </svg>
                <h5 class="card-title mt-2">Buat Janji Temu Baru</h5>
            </div>
        </a>
    </div>
</div>

<!-- Tabel Riwayat -->
<div class="card shadow-sm">
    <div class="card-header">
        <h2 class="h5 mb-0">Riwayat dan Status Antrian</h2>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Dokter</th>
                    <th scope="col">No. Antrian</th>
                    <th scope="col">Status</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody id="riwayat-table-body">
                <!-- Data akan diisi oleh JavaScript -->
                <tr><td colspan="5" class="text-center p-4 text-muted">Memuat riwayat...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- 
=================================================================
BAGIAN 3: JAVASCRIPT
Diletakkan di sini atau di footer.php.
=================================================================
-->
<script>
    // Elemen-elemen DOM
    const janjiCard = document.getElementById('janji-berikutnya-card');
    const rekamMedisCard = document.getElementById('rekam-medis-terakhir-card');
    const riwayatTableBody = document.getElementById('riwayat-table-body');
    // Badge notifikasi ada di header, jadi tidak perlu dideklarasikan ulang di sini.
    const notificationBadge = document.getElementById('notification-badge');

    function formatDate(dateString) {
        if (!dateString) return '-';
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    }

    async function updateDashboard() {
        try {
            const response = await fetch('?url=dashboard/api');
            if (!response.ok) {
                console.error('Gagal mengambil data dari API, status:', response.status);
                return;
            }
            const data = await response.json();

            // 1. Update Kartu Janji Temu Berikutnya
            if (janjiCard) {
                if (data.janji_berikutnya) {
                    janjiCard.innerHTML = `
                        <h5 class="card-title">Janji Temu Berikutnya</h5>
                        <h4 class="card-subtitle mb-2 text-primary">${data.janji_berikutnya.dokter}</h4>
                        <p class="card-text text-muted">${formatDate(data.janji_berikutnya.tanggal_booking)} - Antrian ${data.janji_berikutnya.nomor_antrian}</p>
                    `;
                } else {
                    janjiCard.innerHTML = `
                        <h5 class="card-title">Janji Temu Berikutnya</h5>
                        <p class="card-text text-muted mt-3">Tidak ada janji temu yang direncanakan.</p>
                    `;
                }
            }

            // 2. Update Kartu Rekam Medis Terakhir
            if (rekamMedisCard) {
                if (data.rekam_medis_terakhir) {
                    rekamMedisCard.innerHTML = `
                        <h5 class="card-title">Rekam Medis Terakhir</h5>
                        <h4 class="card-subtitle mb-2 text-success">Pemeriksaan Selesai</h4>
                        <p class="card-text text-muted">${formatDate(data.rekam_medis_terakhir.tanggal_booking)} oleh ${data.rekam_medis_terakhir.dokter}</p>
                    `;
                } else {
                    rekamMedisCard.innerHTML = `
                        <h5 class="card-title">Rekam Medis Terakhir</h5>
                        <p class="card-text text-muted mt-3">Belum ada rekam medis.</p>
                    `;
                }
            }

            // 3. Update Tabel Riwayat
            if (riwayatTableBody) {
                riwayatTableBody.innerHTML = '';
                if (data.riwayat_janji_temu && data.riwayat_janji_temu.length > 0) {
                    data.riwayat_janji_temu.forEach(janji => {
                        let statusColor = 'bg-light text-dark';
                        if (janji.status === 'Selesai') statusColor = 'bg-success text-white';
                        if (janji.status === 'Direncanakan') statusColor = 'bg-primary text-white';
                        if (janji.status === 'Batal') statusColor = 'bg-danger text-white';
                        
                        const aksiHtml = janji.rekam_medis_tersedia
                            ? `<a href="?url=rekammedis/detail&id=${janji.id}" class="btn btn-sm btn-outline-primary">Lihat Detail</a>`
                            : `<span class="text-muted">-</span>`;

                        const row = `
                            <tr>
                                <td>${formatDate(janji.tanggal_booking)}</td>
                                <td class="text-muted">${janji.dokter}</td>
                                <td><span class="fw-bold">${janji.nomor_antrian || '-'}</span></td>
                                <td><span class="badge rounded-pill ${statusColor}">${janji.status}</span></td>
                                <td>${aksiHtml}</td>
                            </tr>
                        `;
                        riwayatTableBody.innerHTML += row;
                    });
                } else {
                    riwayatTableBody.innerHTML = `<tr><td colspan="5" class="text-center p-4 text-muted">Anda belum memiliki riwayat janji temu.</td></tr>`;
                }
            }

            // 4. Update Badge Notifikasi
            if (notificationBadge) {
                if (data.unread_notifications > 0) {
                    notificationBadge.classList.remove('visually-hidden');
                } else {
                    notificationBadge.classList.add('visually-hidden');
                }
            }

        } catch (error) {
            console.error('Gagal memperbarui data dashboard:', error);
        }
    }

    // Panggil fungsi sekali saat halaman dimuat
    document.addEventListener('DOMContentLoaded', updateDashboard);
    
    // Mengatur interval update menjadi 30 detik
    setInterval(updateDashboard, 30000);
</script>

<?php
// =================================================================
// BAGIAN 4: MEMANGGIL FOOTER
// =================================================================
require_once __DIR__ . '/../layouts/footer.php';
?>
 