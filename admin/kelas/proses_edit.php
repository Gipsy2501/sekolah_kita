<?php
session_start();
include '../../config/koneksi.php';

if(isset($_POST['update'])){
    $id_kelas = $_POST['id_kelas'];
    $nama_kelas = mysqli_real_escape_string($koneksi, $_POST['nama_kelas']);

    $update = mysqli_query($koneksi, "UPDATE tb_kelas SET nama_kelas='$nama_kelas' WHERE id_kelas='$id_kelas'");
    
    if($update){
        $_SESSION['notif'] = true;
        $_SESSION['jenis'] = 'success';
        $_SESSION['pesan'] = 'Data Kelas Diperbarui';
    } else {
        $_SESSION['notif'] = true;
        $_SESSION['jenis'] = 'error';
        $_SESSION['pesan'] = 'Gagal Update Data';
    }
}
// Kembalikan ke halaman data_kelas
header("location:data_kelas.php");
?>