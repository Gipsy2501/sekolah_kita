<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika belum login ATAU role-nya bukan admin, tendang keluar
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login" || $_SESSION['role'] !== "siswa") {
    // Deteksi base url untuk redirect aman
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
    $base_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . "/sekolah_kita/";
    
    header("location:" . $base_url . "auth/login.php?pesan=belum_login");
    exit();
}
?>