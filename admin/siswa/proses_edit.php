<?php
session_start();
include '../../config/koneksi.php';

if(isset($_POST['update'])){
    $id        = $_POST['id_siswa'];
    $nisn      = mysqli_real_escape_string($koneksi, $_POST['nisn']);
    $nama      = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $kelas     = mysqli_real_escape_string($koneksi, $_POST['id_kelas']);
    $username  = mysqli_real_escape_string($koneksi, $_POST['username']);
    $foto_lama = $_POST['foto_lama'];
    
    // Cek Password
    if(empty($_POST['password'])){
        $q = mysqli_query($koneksi, "SELECT password FROM tb_siswa WHERE id_siswa='$id'");
        $d = mysqli_fetch_array($q);
        $password = $d['password'];
    } else {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

    // Cek Foto
    if($_FILES['foto']['name'] != ""){
        $foto = date('dmYHis')."_".$_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "../../uploads/profil/".$foto);
        
        if($foto_lama != "default.jpg" && file_exists("../../uploads/profil/".$foto_lama)){
            unlink("../../uploads/profil/".$foto_lama);
        }
    } else {
        $foto = $foto_lama;
    }

    $update = mysqli_query($koneksi, "UPDATE tb_siswa SET nisn='$nisn', nama_siswa='$nama', id_kelas='$kelas', username='$username', password='$password', foto='$foto' WHERE id_siswa='$id'");

    if($update){
        $_SESSION['notif'] = true; $_SESSION['jenis'] = 'success'; $_SESSION['pesan'] = 'Data Siswa Diupdate';
    } else {
        $_SESSION['notif'] = true; $_SESSION['jenis'] = 'error'; $_SESSION['pesan'] = 'Gagal Update Siswa';
    }
}
header("location:data_siswa.php");
?>