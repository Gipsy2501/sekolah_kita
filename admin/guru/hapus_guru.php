<?php
session_start();
include '../../config/koneksi.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    
    // Ambil info foto dulu sebelum hapus data
    $cek = mysqli_query($koneksi, "SELECT foto FROM tb_guru WHERE id_guru='$id'");
    if($data = mysqli_fetch_array($cek)){
        $foto = $data['foto'];
        
        // Hapus file fisik foto jika bukan default
        if($foto != "default.jpg" && file_exists("../../uploads/profil/".$foto)){
            unlink("../../uploads/profil/".$foto);
        }
    }

    $hapus = mysqli_query($koneksi, "DELETE FROM tb_guru WHERE id_guru='$id'");
    
    if($hapus){
        $_SESSION['notif'] = true;
        $_SESSION['jenis'] = 'success';
        $_SESSION['pesan'] = 'Guru Berhasil Dihapus';
    } else {
        $_SESSION['notif'] = true;
        $_SESSION['jenis'] = 'error';
        $_SESSION['pesan'] = 'Gagal Menghapus Data';
    }
}
header("location:data_guru.php");
?>