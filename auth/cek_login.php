<?php 
session_start();
include '../config/koneksi.php'; // Mundur satu folder ke config

// Pasang Base URL dinamis agar lemparan halaman tidak salah alamat
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$host = $_SERVER['HTTP_HOST'];
$base_url = $protocol . "://" . $host . "/sekolah_kita/";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("location:login.php");
    exit();
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    header("location:login.php?pesan=gagal");
    exit();
}

// 1. CEK DATA ADMIN
$query_admin = "SELECT id_admin, password, nama_admin FROM tb_admin WHERE username = ?";
$stmt_admin  = $koneksi->prepare($query_admin);
$stmt_admin->bind_param("s", $username);
$stmt_admin->execute();
$result_admin = $stmt_admin->get_result();

if ($result_admin->num_rows > 0) {
    $data = $result_admin->fetch_assoc();
    if (password_verify($password, $data['password'])) {
        session_regenerate_id(true);
        $_SESSION['username'] = $username;
        $_SESSION['nama']     = $data['nama_admin'];
        $_SESSION['role']     = "admin";
        $_SESSION['id_user']  = $data['id_admin'];
        $_SESSION['status']   = "login";
        
        $stmt_admin->close();
        header("location:" . $base_url . "admin/index.php"); // FIX JALUR ADMIN
        exit();
    }
}
$stmt_admin->close();

// 2. CEK DATA GURU
$query_guru = "SELECT id_guru, password, nama_guru FROM tb_guru WHERE username = ?";
$stmt_guru  = $koneksi->prepare($query_guru);
$stmt_guru->bind_param("s", $username);
$stmt_guru->execute();
$result_guru = $stmt_guru->get_result();

if ($result_guru->num_rows > 0) {
    $data = $result_guru->fetch_assoc();
    if (password_verify($password, $data['password'])) {
        session_regenerate_id(true);
        $_SESSION['username'] = $username;
        $_SESSION['nama']     = $data['nama_guru'];
        $_SESSION['role']     = "guru";
        $_SESSION['id_user']  = $data['id_guru'];
        $_SESSION['status']   = "login";
        
        $stmt_guru->close();
        header("location:" . $base_url . "guru/index.php"); // FIX JALUR GURU
        exit();
    }
}
$stmt_guru->close();

// 3. CEK DATA SISWA
$query_siswa = "SELECT id_siswa, password, nama_siswa, id_kelas, username FROM tb_siswa WHERE username = ? OR nisn = ?";
$stmt_siswa  = $koneksi->prepare($query_siswa);
$stmt_siswa->bind_param("ss", $username, $username);
$stmt_siswa->execute();
$result_siswa = $stmt_siswa->get_result();

if ($result_siswa->num_rows > 0) {
    $data = $result_siswa->fetch_assoc();
    if (password_verify($password, $data['password'])) {
        session_regenerate_id(true);
        $_SESSION['username'] = $data['username'];
        $_SESSION['nama']     = $data['nama_siswa'];
        $_SESSION['role']     = "siswa";
        $_SESSION['id_user']  = $data['id_siswa'];
        $_SESSION['id_kelas'] = $data['id_kelas'];
        $_SESSION['status']   = "login";
        
        $stmt_siswa->close();
        header("location:" . $base_url . "siswa/index.php"); // FIX JALUR SISWA
        exit();
    }
}
$stmt_siswa->close();

// JIKA GAGAL (Tetap di folder auth)
header("location:login.php?pesan=gagal");
exit();
?>