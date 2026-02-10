<?php
session_start();
include '../../config/koneksi.php';

if(isset($_POST['simpan'])){
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_mapel']);
    $guru = mysqli_real_escape_string($koneksi, $_POST['id_guru']);
    
    // Insert Data
    $simpan = mysqli_query($koneksi, "INSERT INTO tb_mapel VALUES (NULL, '$nama', '$guru')");

    if($simpan){
        $_SESSION['notif'] = true; 
        $_SESSION['jenis'] = 'success'; 
        $_SESSION['pesan'] = 'Mapel Berhasil Ditambahkan';
    } else {
        $_SESSION['notif'] = true; 
        $_SESSION['jenis'] = 'error'; 
        $_SESSION['pesan'] = 'Gagal Tambah Mapel';
    }
}
header("location:data_mapel.php");
exit();
?>