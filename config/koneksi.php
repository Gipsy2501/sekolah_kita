<?php
// config/koneksi.php

$host = "localhost";
$user = "root";     // Default XAMPP
$pass = "";         // Default XAMPP kosong
$db   = "db_sekolah_kita"; 

// Melakukan koneksi
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi (Opsional, buat debugging aja)
if (!$koneksi) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}

// Set base url biar gampang manggil file (sesuaikan nama folder htdocs kamu)
$base_url = "http://localhost/sekolah_kita/";
?>