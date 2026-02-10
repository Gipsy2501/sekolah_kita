<?php 
session_start();
include 'config/koneksi.php';

// Tangkap data dari form
$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = $_POST['password'];

// --------------------------------------------------------
// 1. CEK DATA ADMIN
// --------------------------------------------------------
$login_admin = mysqli_query($koneksi, "SELECT * FROM tb_admin WHERE username='$username'");
$cek_admin = mysqli_num_rows($login_admin);

if($cek_admin > 0){
    $data = mysqli_fetch_assoc($login_admin);
    
    // Verifikasi Password (Hash)
    // Catatan: Karena di awal kita insert manual, nanti passwordnya harus di-hash dulu.
    // Untuk sementara kita pakai password_verify
    if(password_verify($password, $data['password'])){
        
        // Buat Session Admin
        $_SESSION['username'] = $username;
        $_SESSION['nama']     = $data['nama_admin'];
        $_SESSION['role']     = "admin";
        $_SESSION['id_user']  = $data['id_admin'];
        $_SESSION['status']   = "login";
        
        // Lempar ke Dashboard Admin
        header("location:admin/index.php");
        exit(); // Penting biar script bawahnya gak jalan
    }
}

// --------------------------------------------------------
// 2. CEK DATA GURU
// --------------------------------------------------------
$login_guru = mysqli_query($koneksi, "SELECT * FROM tb_guru WHERE username='$username'");
$cek_guru = mysqli_num_rows($login_guru);

if($cek_guru > 0){
    $data = mysqli_fetch_assoc($login_guru);
    
    if(password_verify($password, $data['password'])){
        $_SESSION['username'] = $username;
        $_SESSION['nama']     = $data['nama_guru'];
        $_SESSION['role']     = "guru";
        $_SESSION['id_user']  = $data['id_guru'];
        $_SESSION['status']   = "login";
        
        header("location:guru/index.php");
        exit();
    }
}

// --------------------------------------------------------
// 3. CEK DATA SISWA
// --------------------------------------------------------
// Siswa bisa login pakai Username ATAU NISN
$login_siswa = mysqli_query($koneksi, "SELECT * FROM tb_siswa WHERE username='$username' OR nisn='$username'");
$cek_siswa = mysqli_num_rows($login_siswa);

if($cek_siswa > 0){
    $data = mysqli_fetch_assoc($login_siswa);
    
    if(password_verify($password, $data['password'])){
        $_SESSION['username'] = $data['username']; // Pakai username asli dari DB
        $_SESSION['nama']     = $data['nama_siswa'];
        $_SESSION['role']     = "siswa";
        $_SESSION['id_user']  = $data['id_siswa'];
        $_SESSION['id_kelas'] = $data['id_kelas']; // Penting buat filter tugas nanti
        $_SESSION['status']   = "login";
        
        header("location:siswa/index.php");
        exit();
    }
}

// --------------------------------------------------------
// JIKA GAGAL SEMUA
// --------------------------------------------------------
header("location:login.php?pesan=gagal");
?>