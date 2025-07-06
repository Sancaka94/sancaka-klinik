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

// Rute utama untuk Dashboard (akan mengarahkan berdasarkan peran)
Router::add('GET', 'dashboard', 'DashboardController@index');

// Rute spesifik untuk setiap peran dashboard
Router::add('GET', 'pasien/dashboard', 'PasienController@dashboard');
Router::add('GET', 'dokter/dashboard', 'DokterController@dashboard');
Router::add('GET', 'admin/dashboard', 'AdminController@dashboard');
Router::add('GET', 'staf/dashboard', 'AdminController@dashboard'); // Staf menggunakan dashboard Admin
Router::add('GET', 'superadmin/dashboard', 'SuperadminController@dashboard');
Router::add('GET', 'owner/dashboard', 'SuperadminController@dashboard'); // Owner menggunakan dashboard Superadmin

// Rute untuk Profil Pengguna
Router::add('GET', 'profile/pengaturan', 'ProfileController@pengaturan');
Router::add('POST', 'profile/update', 'ProfileController@update');
Router::add('POST', 'profile/updatePassword', 'ProfileController@updatePassword');

// Rute untuk Notifikasi
Router::add('GET', 'notifikasi', 'NotifikasiController@index');
Router::add('GET', 'notifikasi/read/{id}', 'NotifikasiController@read'); // Contoh rute dengan parameter

// Tambahkan rute lain di sini...
// Contoh:
// Router::add('GET', 'janji/create', 'JanjiTemuController@create');
// Router::add('POST', 'janji/store', 'JanjiTemuController@store');
