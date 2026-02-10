<?php
session_start();
include '../../config/koneksi.php';

if(isset($_POST['simpan'])){
    $nisn     = mysqli_real_escape_string($koneksi, $_POST['nisn']);
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $kelas    = mysqli_real_escape_string($koneksi, $_POST['id_kelas']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Upload Foto
    $foto = "default.jpg";
    if($_FILES['foto']['name'] != ""){
        $nama_file = $_FILES['foto']['name'];
        $tmp_file = $_FILES['foto']['tmp_name'];
        $foto = date('dmYHis')."_".$nama_file;
        move_uploaded_file($tmp_file, "../../uploads/profil/".$foto);
    }

    $simpan = mysqli_query($koneksi, "INSERT INTO tb_siswa VALUES (NULL, '$nisn', '$nama', '$kelas', '$username', '$password', '$foto')");

    if($simpan){
        $_SESSION['notif'] = true; $_SESSION['jenis'] = 'success'; $_SESSION['pesan'] = 'Siswa Berhasil Ditambahkan';
    } else {
        $_SESSION['notif'] = true; $_SESSION['jenis'] = 'error'; $_SESSION['pesan'] = 'Gagal Tambah Siswa';
    }
}
header("location:data_siswa.php");
?>