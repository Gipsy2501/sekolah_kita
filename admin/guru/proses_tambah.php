<?php
session_start();
include '../../config/koneksi.php';

if(isset($_POST['simpan'])){
    $nip        = mysqli_real_escape_string($koneksi, $_POST['nip']);
    $nama       = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username   = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Upload Foto
    $foto = "default.jpg";
    if($_FILES['foto']['name'] != ""){
        $ekstensi_diperbolehkan = array('png','jpg','jpeg');
        $nama_file = $_FILES['foto']['name'];
        $x = explode('.', $nama_file);
        $ekstensi = strtolower(end($x));
        $tmp_file = $_FILES['foto']['tmp_name'];
        
        if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
            $foto = date('dmYHis')."_".$nama_file;
            move_uploaded_file($tmp_file, "../../uploads/profil/".$foto);
        }
    }

    $simpan = mysqli_query($koneksi, "INSERT INTO tb_guru VALUES (NULL, '$nip', '$nama', '$username', '$password', '$foto')");

    if($simpan){
        $_SESSION['notif'] = true;
        $_SESSION['jenis'] = 'success';
        $_SESSION['pesan'] = 'Guru Berhasil Ditambahkan';
    } else {
        $_SESSION['notif'] = true;
        $_SESSION['jenis'] = 'error';
        $_SESSION['pesan'] = 'Gagal Menambahkan Data';
    }
}
header("location:data_guru.php");
?>