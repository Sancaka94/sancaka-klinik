<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasbor Pasien - Klinik Sehat</title>
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
        .header h1 { font-size: 28px; color: var(--dark-color); }
        .user-profile { display: flex; align-items: center; gap: 15px; }
        .user-profile img { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; background-color: var(--border-color); }
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: var(--shadow); margin-bottom: 25px; }
        .card-header { font-size: 1.2em; font-weight: 600; margin-bottom: 15px; color: var(--dark-color); border-bottom: 1px solid var(--border-color); padding-bottom: 15px; }
        .appointment-card { background: linear-gradient(135deg, #007bff, #0056b3); color: white; }
        .appointment-card .card-header { color: white; border-bottom-color: rgba(255,255,255,0.2); }
        .appointment-card .date { font-size: 1.5em; font-weight: 700; }
        .appointment-card .doctor { margin-top: 10px; opacity: 0.9; }
        .history-table { width: 100%; border-collapse: collapse; }
        .history-table th, .history-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid var(--border-color); }
        .history-table th { font-size: 14px; color: var(--secondary-color); }
        .status-badge { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .status-dijadwalkan { background-color: #e7f3ff; color: #007bff; }
        .status-selesai { background-color: #eaf7f0; color: #28a745; }
        .status-dibatalkan { background-color: #fbe9eb; color: #dc3545; }
        .skeleton { animation: pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite; background-color: #e9ecef; border-radius: 6px; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .5; } }
        .notification-bell { position: relative; cursor: pointer; }
        .notification-bell .badge { position: absolute; top: -5px; right: -8px; background-color: #dc3545; color: white; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold; border: 2px solid white; }
        .notification-dropdown { display: none; position: absolute; top: 120%; right: 0; width: 320px; background-color: white; border-radius: 8px; box-shadow: var(--shadow); border: 1px solid var(--border-color); z-index: 100; }
        .notification-dropdown.show { display: block; }
        .notification-header { padding: 15px; font-weight: 600; border-bottom: 1px solid var(--border-color); }
        .notification-list { max-height: 300px; overflow-y: auto; }
        .notification-item { display: flex; gap: 10px; padding: 15px; border-bottom: 1px solid var(--border-color); }
        .notification-item .icon { font-size: 18px; color: var(--primary-color); }
        .no-data { text-align: center; padding: 40px; color: var(--secondary-color); }
    </style>
</head>
<body>
    <div class="main-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-clinic-medical"></i>
                <h2>Klinik Sehat</h2>
            </div>
            <nav class="nav-menu">
                <a href="?url=dashboard/pasien" class="active"><i class="fas fa-tachometer-alt"></i> Dasbor</a>
                <a href="?url=janjitemu/buat"><i class="fas fa-calendar-plus"></i> Buat Janji Temu</a>
                <a href="#"><i class="fas fa-history"></i> Riwayat Medis</a>
                <a href="?url=profile/pengaturan"><i class="fas fa-user-cog"></i> Pengaturan Profil</a>
            </nav>
            <div class="sidebar-footer">
                <a href="?url=auth/logout"><i class="fas fa-sign-out-alt"></i> Keluar</a>
            </div>
        </aside>
        <main class="main-content">
            <header class="header">
                <h1 id="patient-greeting" class="skeleton" style="width: 300px; height: 36px;"></h1>
                <div class="user-profile">
                    <div class="notification-bell" id="notification-bell">
                        <i class="fas fa-bell" style="font-size: 20px; color: var(--secondary-color);"></i>
                        <span class="badge" id="notification-badge" style="display: none;">0</span>
                        <div class="notification-dropdown" id="notification-dropdown">
                            <div class="notification-header">Notifikasi</div>
                            <div class="notification-list" id="notification-list"></div>
                        </div>
                    </div>
                    <img id="patient-profile-img" src="" alt="Foto Profil Pasien">
                </div>
            </header>
            
            <div class="card appointment-card" id="next-appointment-card">
                <div class="card-header">Janji Temu Berikutnya</div>
                <div id="next-appointment-details">
                    <div class="skeleton" style="width: 80%; height: 28px; margin-bottom: 10px;"></div>
                    <div class="skeleton" style="width: 60%; height: 20px;"></div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Riwayat Janji Temu</div>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Dokter</th>
                            <th>Keluhan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="history-tbody">
                        <tr>
                            <td><div class="skeleton" style="height: 20px; width: 80%;"></div></td>
                            <td><div class="skeleton" style="height: 20px; width: 90%;"></div></td>
                            <td><div class="skeleton" style="height: 20px; width: 70%;"></div></td>
                            <td><div class="skeleton" style="height: 20px; width: 60%;"></div></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            
            function updateHeader(patientInfo) {
                const greetingEl = document.getElementById('patient-greeting');
                const profileImgEl = document.getElementById('patient-profile-img');

                if (patientInfo && patientInfo.nama_lengkap) {
                    greetingEl.textContent = `Selamat Datang, ${patientInfo.nama_lengkap}!`;
                    greetingEl.classList.remove('skeleton');
                    profileImgEl.src = patientInfo.foto_profil 
                        ? `uploads/profil/${patientInfo.foto_profil}` 
                        : 'https://placehold.co/100x100/EFEFEF/AAAAAA/png?text=P';
                }
            }

            function updateNextAppointment(appointment) {
                const nextAppointmentEl = document.getElementById('next-appointment-details');
                if (appointment) {
                    const tgl = new Date(appointment.tanggal_temu).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                    const waktu = new Date(`1970-01-01T${appointment.waktu_temu}`).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                    nextAppointmentEl.innerHTML = `
                        <div class="date">${tgl} pukul ${waktu}</div>
                        <div class="doctor">dengan <strong>${appointment.nama_dokter}</strong></div>
                    `;
                } else {
                    nextAppointmentEl.innerHTML = '<p>Anda tidak memiliki janji temu yang akan datang.</p>';
                }
            }

            function updateHistoryTable(history) {
                const historyBody = document.getElementById('history-tbody');
                historyBody.innerHTML = '';
                if (history && history.length > 0) {
                    history.forEach(janji => {
                        const tglRiwayat = new Date(janji.tanggal_temu).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                        const statusClass = `status-${janji.status.toLowerCase().replace(' ', '-')}`;
                        const row = `
                            <tr>
                                <td>${tglRiwayat}</td>
                                <td>${janji.nama_dokter}</td>
                                <td>${janji.keluhan || '-'}</td>
                                <td><span class="status-badge ${statusClass}">${janji.status}</span></td>
                            </tr>
                        `;
                        historyBody.innerHTML += row;
                    });
                } else {
                    historyBody.innerHTML = '<tr><td colspan="4" class="no-data">Belum ada riwayat janji temu.</td></tr>';
                }
            }

            function updateNotifications(notifications) {
                const badge = document.getElementById('notification-badge');
                const list = document.getElementById('notification-list');
                list.innerHTML = '';

                if (notifications && notifications.length > 0) {
                    badge.textContent = notifications.length;
                    badge.style.display = 'flex';
                    notifications.forEach(notif => {
                        const item = `<a href="${notif.link || '#'}" style="text-decoration:none; color:inherit;"><div class="notification-item"><i class="fas fa-bell icon"></i><div class="message">${notif.pesan}</div></div></a>`;
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
                    const response = await fetch('?url=dashboard/api_pasien');
                    if (!response.ok) throw new Error(`Gagal mengambil data: ${response.statusText}`);
                    const data = await response.json();

                    updateHeader(data.info_pasien);
                    updateNextAppointment(data.janji_berikutnya);
                    updateHistoryTable(data.riwayat_janji);
                    updateNotifications(data.notifikasi);

                } catch (error) {
                    console.error('Gagal memuat data dasbor pasien:', error);
                    document.getElementById('patient-greeting').textContent = 'Gagal memuat data.';
                }
            }

            loadDashboardData();
        });
    </script>
</body>
</html>
