<?php
// File: views/layouts/header.php

// Memulai session jika belum aktif, penting untuk mengakses data login
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Variabel ini dibutuhkan oleh navigasi, jadi kita siapkan di sini.
$user = $_SESSION['user'] ?? null;
$is_logged_in = ($user !== null);

// Inisialisasi variabel dengan nilai default untuk mencegah error jika user tidak login
$display_name = 'Tamu';
$foto_profil_path = 'https://placehold.co/100x100/E2E8F0/4A5568?text=G';
$default_avatar = 'https://placehold.co/100x100/E2E8F0/4A5568?text=G';

if ($is_logged_in) {
    // Menentukan path foto profil, menggunakan default jika tidak ada.
    $foto_profil_path = (!empty($user['foto_profil'])) 
        ? 'uploads/profiles/' . htmlspecialchars($user['foto_profil']) 
        : $default_avatar;

    // Nama lengkap untuk ditampilkan, dengan fallback ke username jika nama lengkap kosong.
    $display_name = htmlspecialchars($user['nama_lengkap'] ?? $user['username']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik Sehat</title>
    
    <!-- Memuat Tailwind CSS melalui CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- (Opsional) Font Inter seperti di dashboard Anda -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Menerapkan font Inter ke seluruh halaman */
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">

<div class="min-h-screen flex flex-col">
    <!-- Header Navigasi yang konsisten dengan Dashboard -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="?url=dashboard/pasien" class="flex items-center space-x-2">
                    <svg class="h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.5v3m0 9v3m4.5-7.5h3m-15 0h3" /></svg>
                    <div class="flex-shrink-0 text-2xl font-bold text-indigo-600">Klinik Sehat</div>
                </a>
                
                <?php if ($is_logged_in && $user['id_peran'] == 4): // Tampilkan hanya jika pasien sudah login ?>
                <div class="flex items-center space-x-4">
                    <a href="?url=notifikasi/index" class="relative text-gray-600 hover:text-indigo-600 p-2 rounded-full">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        <span id="notification-badge" class="absolute top-1 right-1 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white hidden"></span>
                    </a>
                    <div class="relative">
                        <button id="profile-menu-button" class="flex text-sm bg-white rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <img class="h-8 w-8 rounded-full object-cover" src="<?php echo $foto_profil_path; ?>" alt="Foto Profil" onerror="this.onerror=null;this.src='<?php echo $default_avatar; ?>';">
                        </button>
                        <div id="profile-menu" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden" role="menu">
                            <div class="px-4 py-2 border-b">
                                <p class="text-sm text-gray-700">Masuk sebagai</p>
                                <p class="text-sm font-medium text-gray-900 truncate"><?php echo $display_name; ?></p>
                            </div>
                            <a href="?url=profile/pengaturan" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                <svg class="h-5 w-5 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                Pengaturan Profil
                            </a>
                            <a href="?url=auth/logout" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                <svg class="h-5 w-5 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Membuka container utama untuk konten halaman -->
    <main class="flex-grow">
