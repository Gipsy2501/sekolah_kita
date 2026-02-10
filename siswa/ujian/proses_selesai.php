<?php
session_start();
include '../../config/koneksi.php';

if(isset($_POST['selesai']) || isset($_POST['id_ujian'])){
    $id_ujian = $_POST['id_ujian'];
    $id_siswa = $_SESSION['id_user'];
    $jawaban_siswa = isset($_POST['jawaban']) ? $_POST['jawaban'] : []; // Array Jawaban

    $benar = 0;
    $total_soal = 0;

    // Ambil Kunci Jawaban dari Database
    $cek_soal = mysqli_query($koneksi, "SELECT * FROM tb_soal_ujian WHERE id_ujian='$id_ujian'");
    $total_soal = mysqli_num_rows($cek_soal);

    while($soal = mysqli_fetch_array($cek_soal)){
        $id_soal_ini = $soal['id_soal'];
        $kunci = $soal['kunci_jawaban'];

        // Cek apakah siswa menjawab soal ini?
        if(isset($jawaban_siswa[$id_soal_ini])){
            // Jika jawaban siswa SAMA dengan kunci
            if($jawaban_siswa[$id_soal_ini] == $kunci){
                $benar++;
            }
        }
    }

    // Hitung Nilai (Skala 100)
    $nilai_akhir = ($total_soal > 0) ? ($benar / $total_soal) * 100 : 0;

    // Simpan ke Database Nilai
    $simpan = mysqli_query($koneksi, "INSERT INTO tb_nilai_ujian (id_ujian, id_siswa, jml_benar, nilai_akhir) 
                                      VALUES ('$id_ujian', '$id_siswa', '$benar', '$nilai_akhir')");

    if($simpan){
        echo "<script>
            alert('Ujian Selesai! Nilai Kamu: $nilai_akhir');
            window.location='../index.php'; // Kembali ke Dashboard Siswa
        </script>";
    } else {
        echo "Gagal menyimpan nilai.";
    }
}
?>