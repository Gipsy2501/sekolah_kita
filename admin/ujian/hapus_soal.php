<?php
session_start();
include '../../config/koneksi.php';

if(isset($_GET['id']) && isset($_GET['ujian'])){
    $id_soal = $_GET['id'];
    $id_ujian = $_GET['ujian']; // Biar bisa balik ke halaman input soal yang benar
    
    $hapus = mysqli_query($koneksi, "DELETE FROM tb_soal_ujian WHERE id_soal='$id_soal'");
    
    // Gak perlu notif sesi biar cepet inputnya, langsung redirect aja
    header("location:input_soal.php?id=$id_ujian");
}
?>