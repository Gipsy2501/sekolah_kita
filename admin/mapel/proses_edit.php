<?php
session_start();
include '../../config/koneksi.php';

if(isset($_POST['update'])){
    $id   = $_POST['id_mapel'];
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_mapel']);
    $guru = mysqli_real_escape_string($koneksi, $_POST['id_guru']);

    $update = mysqli_query($koneksi, "UPDATE tb_mapel SET nama_mapel='$nama', id_guru='$guru' WHERE id_mapel='$id'");

    if($update){
        $_SESSION['notif'] = true; $_SESSION['jenis'] = 'success'; $_SESSION['pesan'] = 'Mapel Berhasil Diupdate';
    } else {
        $_SESSION['notif'] = true; $_SESSION['jenis'] = 'error'; $_SESSION['pesan'] = 'Gagal Update Mapel';
    }
}
header("location:data_mapel.php");
?>