<?php 
session_start();

// Hapus semua session
session_destroy();

// Alihkan ke halaman login
header("location:login.php?pesan=logout");
?>