<?php
session_start();
include '../../config/koneksi.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    
    // 1. Hapus Soal-soal ujian ini dulu
    mysqli_query($koneksi, "DELETE FROM tb_soal_ujian WHERE id_ujian='$id'");

    // 2. Hapus Nilai-nilai siswa di ujian ini
    mysqli_query($koneksi, "DELETE FROM tb_nilai_ujian WHERE id_ujian='$id'");

    // 3. Baru hapus Ujiannya
    $hapus = mysqli_query($koneksi, "DELETE FROM tb_ujian WHERE id_ujian='$id'");
    
    if($hapus){
        $_SESSION['notif'] = true;
        $_SESSION['jenis'] = 'success';
        $_SESSION['pesan'] = 'Data Ujian & Soal Berhasil Dihapus';
    } else {
        $_SESSION['notif'] = true;
        $_SESSION['jenis'] = 'error';
        $_SESSION['pesan'] = 'Gagal Menghapus Data';
    }
}

header("location:data_ujian.php");
?>