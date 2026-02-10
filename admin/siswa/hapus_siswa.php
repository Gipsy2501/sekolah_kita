<?php
session_start();
include '../../config/koneksi.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    
    // Hapus foto fisik
    $cek = mysqli_query($koneksi, "SELECT foto FROM tb_siswa WHERE id_siswa='$id'");
    if($data = mysqli_fetch_array($cek)){
        if($data['foto'] != "default.jpg" && file_exists("../../uploads/profil/".$data['foto'])){
            unlink("../../uploads/profil/".$data['foto']);
        }
    }

    $hapus = mysqli_query($koneksi, "DELETE FROM tb_siswa WHERE id_siswa='$id'");
    
    if($hapus){
        $_SESSION['notif'] = true; $_SESSION['jenis'] = 'success'; $_SESSION['pesan'] = 'Siswa Berhasil Dihapus';
    } else {
        $_SESSION['notif'] = true; $_SESSION['jenis'] = 'error'; $_SESSION['pesan'] = 'Gagal Hapus Siswa';
    }
}
header("location:data_siswa.php");
?>