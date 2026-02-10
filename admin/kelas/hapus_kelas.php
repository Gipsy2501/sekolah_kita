<?php
session_start();
include '../../config/koneksi.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $hapus = mysqli_query($koneksi, "DELETE FROM tb_kelas WHERE id_kelas='$id'");
    
    if($hapus){
        $_SESSION['notif'] = true;
        $_SESSION['jenis'] = 'success';
        $_SESSION['pesan'] = 'Kelas Berhasil Dihapus';
    } else {
        $_SESSION['notif'] = true;
        $_SESSION['jenis'] = 'error';
        $_SESSION['pesan'] = 'Gagal Menghapus Data';
    }
}
header("location:data_kelas.php");
?>