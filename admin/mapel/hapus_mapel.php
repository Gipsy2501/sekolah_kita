<?php
session_start();
include '../../config/koneksi.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    
    // Proses Hapus dari Database
    $hapus = mysqli_query($koneksi, "DELETE FROM tb_mapel WHERE id_mapel='$id'");
    
    if($hapus){
        $_SESSION['notif'] = true;
        $_SESSION['jenis'] = 'success';
        $_SESSION['pesan'] = 'Data Mapel Berhasil Dihapus';
    } else {
        $_SESSION['notif'] = true;
        $_SESSION['jenis'] = 'error';
        $_SESSION['pesan'] = 'Gagal Menghapus Data';
    }
}

// Kembalikan user ke halaman tabel
header("location:data_mapel.php");
exit();
?><?php
session_start();
include '../../config/koneksi.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    
    // Proses Hapus dari Database
    $hapus = mysqli_query($koneksi, "DELETE FROM tb_mapel WHERE id_mapel='$id'");
    
    if($hapus){
        $_SESSION['notif'] = true;
        $_SESSION['jenis'] = 'success';
        $_SESSION['pesan'] = 'Data Mapel Berhasil Dihapus';
    } else {
        $_SESSION['notif'] = true;
        $_SESSION['jenis'] = 'error';
        $_SESSION['pesan'] = 'Gagal Menghapus Data';
    }
}

// Kembalikan user ke halaman tabel
header("location:data_mapel.php");
exit();
?>