<?php
// File: routes/web.php

// Muat class Router
require_once BASE_PATH . '/core/Router.php';

// Daftarkan semua rute (URL) aplikasi Anda di sini

// Rute untuk Halaman Utama & Otentikasi
Router::add('GET', '', 'HomeController@index'); // Halaman home
Router::add('GET', 'auth/login', 'AuthController@login'); // Menampilkan form login
Router::add('POST', 'auth/authenticate', 'AuthController@authenticate'); // Memproses login
Router::add('GET', 'auth/logout', 'AuthController@logout'); // Proses logout

// Rute untuk Pasien
Router::add('GET', 'pasien/dashboard', 'PasienController@dashboard');

// Rute untuk Dokter
Router::add('GET', 'dokter/dashboard', 'DokterController@dashboard');

// Rute untuk Profil Pengguna
Router::add('GET', 'profile/pengaturan', 'ProfileController@pengaturan');
Router::add('POST', 'profile/update', 'ProfileController@update');
Router::add('POST', 'profile/updatePassword', 'ProfileController@updatePassword');

// Tambahkan rute lain di sini...
// Contoh:
// Router::add('GET', 'janji/create', 'JanjiTemuController@create');
// Router::add('POST', 'janji/store', 'JanjiTemuController@store');
