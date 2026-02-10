<?php
include '../../config/koneksi.php';
$id = $_GET['id'];

// (Opsional) Hapus file fotonya dulu dari folder biar bersih
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT foto FROM tb_siswa WHERE id_siswa='$id'"));
if($data['foto'] != 'default.jpg'){
    unlink("../../uploads/profil/".$data['foto']);
}

// Baru hapus datanya
mysqli_query($koneksi, "DELETE FROM tb_siswa WHERE id_siswa='$id'");

header("location:data_siswa.php");
?>