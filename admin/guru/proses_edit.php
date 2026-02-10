<?php
session_start();
include '../../config/koneksi.php';

if(isset($_POST['update'])){
    $id         = $_POST['id_guru'];
    $nip        = mysqli_real_escape_string($koneksi, $_POST['nip']);
    $nama       = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username   = mysqli_real_escape_string($koneksi, $_POST['username']);
    $foto_lama  = $_POST['foto_lama'];
    
    // 1. Cek Password
    if(empty($_POST['password'])){
        // Ambil password lama kalau user gak ngisi kolom password
        $q = mysqli_query($koneksi, "SELECT password FROM tb_guru WHERE id_guru='$id'");
        $d = mysqli_fetch_array($q);
        $password = $d['password'];
    } else {
        // Hash password baru
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

    // 2. Cek Upload Foto
    if($_FILES['foto']['name'] != ""){
        $nama_file = $_FILES['foto']['name'];
        $tmp_file = $_FILES['foto']['tmp_name'];
        $foto = date('dmYHis')."_".$nama_file;
        
        // Upload file baru
        move_uploaded_file($tmp_file, "../../uploads/profil/".$foto);
        
        // Hapus file lama jika bukan default
        if($foto_lama != "default.jpg" && file_exists("../../uploads/profil/".$foto_lama)){
            unlink("../../uploads/profil/".$foto_lama);
        }
    } else {
        $foto = $foto_lama;
    }

    $update = mysqli_query($koneksi, "UPDATE tb_guru SET 
                            nip='$nip', 
                            nama_guru='$nama', 
                            username='$username', 
                            password='$password', 
                            foto='$foto' 
                            WHERE id_guru='$id'");

    if($update){
        $_SESSION['notif'] = true;
        $_SESSION['jenis'] = 'success';
        $_SESSION['pesan'] = 'Data Guru Diperbarui';
    } else {
        $_SESSION['notif'] = true;
        $_SESSION['jenis'] = 'error';
        $_SESSION['pesan'] = 'Gagal Update Data';
    }
}
header("location:data_guru.php");
?>