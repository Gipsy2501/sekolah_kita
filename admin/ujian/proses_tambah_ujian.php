<?php
session_start();
include '../../config/koneksi.php';

if(isset($_POST['simpan'])){
    $judul      = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $id_mapel   = mysqli_real_escape_string($koneksi, $_POST['id_mapel']);
    $tgl_mulai  = $_POST['tgl_mulai'];
    $tgl_selesai= $_POST['tgl_selesai'];
    $durasi     = $_POST['durasi'];
    $token      = strtoupper(mysqli_real_escape_string($koneksi, $_POST['token'])); // Token otomatis Huruf Besar

    // Cari ID Guru berdasarkan Mapel (Karena tabel ujian butuh id_guru)
    $cari_guru = mysqli_query($koneksi, "SELECT id_guru FROM tb_mapel WHERE id_mapel='$id_mapel'");
    $dg = mysqli_fetch_array($cari_guru);
    $id_guru = isset($dg['id_guru']) ? $dg['id_guru'] : 0; // Kalau mapel gak ada gurunya, set 0

    // Simpan ke Database
    $simpan = mysqli_query($koneksi, "INSERT INTO tb_ujian 
              (id_ujian, judul_ujian, id_mapel, id_guru, tanggal_mulai, waktu_selesai, durasi_menit, token_ujian) 
              VALUES 
              (NULL, '$judul', '$id_mapel', '$id_guru', '$tgl_mulai', '$tgl_selesai', '$durasi', '$token')");

    if($simpan){
        $_SESSION['notif'] = true;
        $_SESSION['jenis'] = 'success';
        $_SESSION['pesan'] = 'Jadwal Ujian Berhasil Dibuat!';
    } else {
        $_SESSION['notif'] = true;
        $_SESSION['jenis'] = 'error';
        $_SESSION['pesan'] = 'Gagal: '.mysqli_error($koneksi);
    }
}

header("location:data_ujian.php");
?>