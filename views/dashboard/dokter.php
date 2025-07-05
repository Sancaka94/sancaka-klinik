<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasbor Dokter - Klinik Sehat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" xintegrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        :root {
            --primary-color: #007bff; --secondary-color: #6c757d; --success-color: #28a745;
            --warning-color: #ffc107; --light-color: #f8f9fa; --dark-color: #343a40;
            --bg-color: #f4f7f9; --text-color: #495057; --border-color: #dee2e6;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-color); color: var(--text-color); display: flex; }
        .sidebar { width: 260px; background-color: #ffffff; height: 100vh; position: fixed; left: 0; top: 0; padding: 20px; display: flex; flex-direction: column; border-right: 1px solid var(--border-color); z-index: 100; }
        .sidebar-header { display: flex; align-items: center; gap: 10px; padding-bottom: 20px; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); }
        .sidebar-header i { font-size: 24px; color: var(--primary-color); }
        .sidebar-header h2 { font-size: 22px; color: var(--dark-color); }
        .nav-menu a { display: flex; align-items: center; padding: 12px 15px; margin-bottom: 8px; border-radius: 8px; text-decoration: none; color: var(--secondary-color); font-weight: 500; transition: background-color 0.3s, color 0.3s; }
        .nav-menu a i { margin-right: 15px; width: 20px; text-align: center; }
        .nav-menu a:hover, .nav-menu a.active { background-color: var(--primary-color); color: white; }
        .sidebar-footer { margin-top: auto; }
        .main-content { margin-left: 260px; flex-grow: 1; padding: 30px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header-title h1 { font-size: 28px; color: var(--dark-color); }
        .header-title p { color: var(--secondary-color); }
        .user-profile { display: flex; align-items: center; gap: 15px; }
        .user-profile img { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; background-color: var(--border-color); }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background-color: white; padding: 25px; border-radius: 12px; box-shadow: var(--shadow); display: flex; align-items: center; gap: 20px; }
        .stat-card .icon-container { font-size: 28px; padding: 15px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .stat-card.pasien .icon-container { background-color: #e7f3ff; color: #007bff; }
        .stat-card.selesai .icon-container { background-color: #eaf7f0; color: #28a745; }
        .stat-card.menunggu .icon-container { background-color: #fff8e6; color: #ffc107; }
        .stat-card .info h3 { font-size: 24px; font-weight: 700; color: var(--dark-color); }
        .stat-card .info p { color: var(--secondary-color); font-size: 14px; }
        .content-card { background-color: white; padding: 30px; border-radius: 12px; box-shadow: var(--shadow); }
        .content-card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .content-card-header h2 { font-size: 20px; color: var(--dark-color); }
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid var(--border-color); }
        th { font-weight: 600; color: var(--secondary-color); font-size: 14px; }
        td { color: var(--dark-color); }
        .patient-info { display: flex; align-items: center; gap: 10px; }
        .patient-info img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; background-color: var(--border-color); }
        .btn { padding: 8px 15px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; transition: opacity 0.3s; }
        .btn:hover { opacity: 0.8; }
        .btn-primary { background-color: var(--primary-color); color: white; }
        .btn-outline { background-color: transparent; border: 1px solid var(--border-color); color: var(--secondary-color); }
        .no-data { text-align: center; padding: 40px; color: var(--secondary-color); }
        .skeleton { animation: pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite; background-color: #e9ecef; border-radius: 6px; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .5; } }
        /* [BARU] Styling untuk Notifikasi */
        .notification-bell { position: relative; cursor: pointer; }
        .notification-bell .badge {
            position: absolute;
            top: -5px; right: -8px;
            background-color: #dc3545; color: white;
            width: 18px; height: 18px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 10px; font-weight: bold;
            border: 2px solid white;
        }
        .notification-dropdown {
            display: none;
            position: absolute;
            top: 120%; right: 0;
            width: 320px;
            background-color: white;
            border-radius: 8px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            z-index: 100;
        }
        .notification-dropdown.show { display: block; }
        .notification-header { padding: 15px; font-weight: 600; border-bottom: 1px solid var(--border-color); }
        .notification-list { max-height: 300px; overflow-y: auto; }
        .notification-item { display: flex; gap: 10px; padding: 15px; border-bottom: 1px solid var(--border-color); }
        .notification-item:last-child { border-bottom: none; }
        .notification-item .icon { font-size: 18px; color: var(--primary-color); padding-top: 2px; }
        .notification-item .message { font-size: 14px; }
        .notification-footer { padding: 10px; text-align: center; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-clinic-medical"></i>
            <h2>Klinik Sehat</h2>
        </div>
        <nav class="nav-menu">
            <a href="?url=dashboard/dokter" class="active"><i class="fas fa-tachometer-alt"></i> Dasbor</a>
            <a href="#"><i class="fas fa-calendar-check"></i> Janji Temu</a>
            <a href="#"><i class="fas fa-users"></i> Daftar Pasien</a>
            <a href="#"><i class="fas fa-history"></i> Riwayat Medis</a>
            <a href="?url=profile/pengaturan"><i class="fas fa-cog"></i> Pengaturan</a>
        </nav>
        <div class="sidebar-footer">
            <a href="?url=auth/logout"><i class="fas fa-sign-out-alt"></i> Keluar</a>
        </div>
    </aside>

    <main class="main-content">
        <header class="header">
            <div class="header-title">
                <h1 id="doctor-greeting">Selamat Datang...</h1>
                <p><?php echo date('l, j F Y'); ?></p>
            </div>
            <div class="user-profile">
                <div class="notification-bell" id="notification-bell">
                    <i class="fas fa-bell" style="font-size: 20px; color: var(--secondary-color);"></i>
                    <span class="badge" id="notification-badge" style="display: none;">0</span>
                    <div class="notification-dropdown" id="notification-dropdown">
                        <div class="notification-header">Notifikasi</div>
                        <div class="notification-list" id="notification-list">
                            <!-- Skeleton loader untuk notifikasi -->
                            <div class="notification-item"><div class="skeleton" style="width: 100%; height: 20px;"></div></div>
                        </div>
                        <div class="notification-footer"><a href="#">Lihat semua</a></div>
                    </div>
                </div>
                <img id="doctor-profile-img" src="" alt="Foto Profil Dokter">
                <div>
                    <span id="doctor-name-profile" class="skeleton" style="width: 120px; height: 20px; display: inline-block;"></span><br>
                    <span style="font-size: 14px; color: var(--secondary-color);">Dokter Umum</span>
                </div>
            </div>
        </header>

        <section class="stats-grid">
            <div class="stat-card pasien">
                <div class="icon-container"><i class="fas fa-users"></i></div>
                <div class="info">
                    <h3 id="stat-total-today" class="skeleton" style="width: 40px;">&nbsp;</h3>
                    <p>Pasien Hari Ini</p>
                </div>
            </div>
            <div class="stat-card selesai">
                <div class="icon-container"><i class="fas fa-check-circle"></i></div>
                <div class="info">
                    <h3 id="stat-total-selesai" class="skeleton" style="width: 40px;">&nbsp;</h3>
                    <p>Janji Temu Selesai</p>
                </div>
            </div>
            <div class="stat-card menunggu">
                <div class="icon-container"><i class="fas fa-clock"></i></div>
                <div class="info">
                    <h3 id="stat-total-menunggu" class="skeleton" style="width: 40px;">&nbsp;</h3>
                    <p>Menunggu Konsultasi</p>
                </div>
            </div>
        </section>

        <section class="content-card">
            <div class="content-card-header">
                <h2>Janji Temu Hari Ini</h2>
                <a href="#" class="btn btn-outline">Lihat Semua</a>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Pasien</th>
                            <th>Keluhan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="appointments-tbody">
                        <tr>
                            <td><div class="skeleton" style="height: 20px; width: 50px;"></div></td>
                            <td><div class="skeleton" style="height: 20px; width: 150px;"></div></td>
                            <td><div class="skeleton" style="height: 20px; width: 200px;"></div></td>
                            <td><div class="skeleton" style="height: 35px; width: 120px;"></div></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            
            function updateHeader(doctorInfo) {
                const greetingEl = document.getElementById('doctor-greeting');
                const profileNameEl = document.getElementById('doctor-name-profile');
                const profileImgEl = document.getElementById('doctor-profile-img');

                if (doctorInfo && doctorInfo.nama_lengkap) {
                    greetingEl.textContent = `Selamat Datang, ${doctorInfo.nama_lengkap}!`;
                    profileNameEl.textContent = doctorInfo.nama_lengkap;
                    profileNameEl.classList.remove('skeleton');
                    profileImgEl.src = doctorInfo.foto_profil 
                        ? `uploads/profil/${doctorInfo.foto_profil}` 
                        : 'https://placehold.co/100x100/007BFF/FFFFFF/png?text=Dr';
                } else {
                    greetingEl.textContent = 'Selamat Datang, Dokter!';
                    profileNameEl.textContent = 'Dokter';
                }
            }

            function updateStats(stats) {
                const totalTodayEl = document.getElementById('stat-total-today');
                const totalSelesaiEl = document.getElementById('stat-total-selesai');
                const totalMenungguEl = document.getElementById('stat-total-menunggu');

                if (stats) {
                    totalTodayEl.textContent = stats.total_today || 0;
                    totalSelesaiEl.textContent = stats.total_selesai || 0;
                    totalMenungguEl.textContent = stats.total_menunggu || 0;
                    [totalTodayEl, totalSelesaiEl, totalMenungguEl].forEach(el => el.classList.remove('skeleton'));
                }
            }

            function updateAppointmentsTable(appointments) {
                const tableBody = document.getElementById('appointments-tbody');
                tableBody.innerHTML = ''; 

                if (!appointments || appointments.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="4" class="no-data">Tidak ada janji temu hari ini.</td></tr>';
                    return;
                }

                appointments.forEach(janji => {
                    const photoSrc = janji.foto_profil ? `uploads/profil/${janji.foto_profil}` : 'https://placehold.co/80x80/EFEFEF/AAAAAA/png?text=P';
                    const time = new Date(`1970-01-01T${janji.waktu_temu}`).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false });
                    const row = `
                        <tr>
                            <td>${time}</td>
                            <td><div class="patient-info"><img src="${photoSrc}" alt="Foto Pasien"> ${janji.nama_lengkap}</div></td>
                            <td>${janji.keluhan || '-'}</td>
                            <td><button class="btn btn-primary">Mulai Konsultasi</button></td>
                        </tr>`;
                    tableBody.innerHTML += row;
                });
            }

            function updateNotifications(notifications) {
                const badge = document.getElementById('notification-badge');
                const list = document.getElementById('notification-list');
                list.innerHTML = '';

                if (notifications && notifications.length > 0) {
                    badge.textContent = notifications.length;
                    badge.style.display = 'flex';
                    notifications.forEach(notif => {
                        const item = `
                            <a href="${notif.link || '#'}" style="text-decoration:none; color:inherit;">
                                <div class="notification-item">
                                    <i class="fas fa-bell icon"></i>
                                    <div class="message">${notif.pesan}</div>
                                </div>
                            </a>`;
                        list.innerHTML += item;
                    });
                } else {
                    badge.style.display = 'none';
                    list.innerHTML = '<div class="no-data" style="padding: 20px;">Tidak ada notifikasi baru.</div>';
                }
            }

            const bell = document.getElementById('notification-bell');
            const dropdown = document.getElementById('notification-dropdown');
            bell.addEventListener('click', (event) => {
                event.stopPropagation();
                dropdown.classList.toggle('show');
            });
            document.addEventListener('click', () => {
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            });

            async function loadDashboardData() {
                try {
                    const response = await fetch('?url=dashboard/api_dokter');
                    if (!response.ok) throw new Error(`Gagal mengambil data: ${response.statusText}`);
                    const data = await response.json();
                    
                    updateHeader(data.info_dokter);
                    updateStats(data.statistik);
                    updateAppointmentsTable(data.janji_temu_hari_ini);
                    updateNotifications(data.notifikasi);
                } catch (error) {
                    console.error('Gagal memuat data dasbor:', error);
                    const tableBody = document.getElementById('appointments-tbody');
                    tableBody.innerHTML = '<tr><td colspan="4" class="no-data">Gagal memuat data. Silakan coba lagi.</td></tr>';
                }
            }
            loadDashboardData();
        });
    </script>
</body>
</html>
