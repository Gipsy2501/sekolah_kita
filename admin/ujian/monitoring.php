<?php
session_start();
include '../../config/koneksi.php';
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

$id_ujian = $_GET['id'];
$ujian = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM tb_ujian WHERE id_ujian='$id_ujian'"));
?>

<div class="container-fluid p-4">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <a href="data_ujian.php" class="text-decoration-none text-muted"><i class="fas fa-arrow-left me-2"></i> Kembali</a>
            <h4 class="fw-bold mt-2">Hasil: <?php echo $ujian['judul_ujian']; ?></h4>
        </div>
        <button onclick="window.print()" class="btn btn-outline-dark rounded-pill"><i class="fas fa-print me-2"></i> Cetak Laporan</button>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Ranking</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th class="text-center">Benar</th>
                        <th class="text-center">Nilai Akhir</th>
                        <th>Waktu Submit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $q_nilai = mysqli_query($koneksi, "SELECT n.*, s.nama_siswa, k.nama_kelas 
                                                       FROM tb_nilai_ujian n 
                                                       JOIN tb_siswa s ON n.id_siswa = s.id_siswa 
                                                       JOIN tb_kelas k ON s.id_kelas = k.id_kelas
                                                       WHERE n.id_ujian='$id_ujian' 
                                                       ORDER BY n.nilai_akhir DESC");
                    
                    if(mysqli_num_rows($q_nilai) > 0){
                        while($n = mysqli_fetch_array($q_nilai)){
                    ?>
                    <tr>
                        <td class="ps-4 fw-bold text-secondary">#<?php echo $no++; ?></td>
                        <td class="fw-bold"><?php echo $n['nama_siswa']; ?></td>
                        <td><span class="badge bg-light text-dark border"><?php echo $n['nama_kelas']; ?></span></td>
                        <td class="text-center text-success fw-bold"><?php echo $n['jml_benar']; ?> Soal</td>
                        <td class="text-center">
                            <span class="badge bg-primary fs-6 rounded-pill"><?php echo $n['nilai_akhir']; ?></span>
                        </td>
                        <td class="text-muted small"><?php echo date('H:i:s, d M Y', strtotime($n['tgl_mengerjakan'])); ?></td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center py-5 text-muted'>Belum ada siswa yang menyelesaikan ujian ini.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include '../../layouts/footer.php'; ?>