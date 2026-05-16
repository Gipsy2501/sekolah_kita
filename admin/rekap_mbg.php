<?php
include 'cek_akses.php';
include '../config/koneksi.php';

$hari_ini = date('Y-m-d');

// SQL CANGGIH: Menghitung porsi box makanan secara realtime. Status 'Hadir' dan 'Terlambat' dihitung berhak mendapat makanan
$query_mbg = mysqli_query($koneksi, "
    SELECT k.nama_kelas, 
           COUNT(s.id_siswa) as total_siswa,
           SUM(CASE WHEN a.status IN ('Hadir', 'Terlambat') THEN 1 ELSE 0 END) as porsi_masak,
           SUM(CASE WHEN a.status IN ('Sakit', 'Izin', 'Alpha') THEN 1 ELSE 0 END) as porsi_batal,
           SUM(CASE WHEN a.status IS NULL THEN 1 ELSE 0 END) as belum_absen
    FROM tb_kelas k
    JOIN tb_siswa s ON k.id_kelas = s.id_kelas
    LEFT JOIN tb_absensi a ON s.id_siswa = a.id_siswa AND a.tanggal = '$hari_ini'
    GROUP BY k.id_kelas
    ORDER BY k.nama_kelas ASC
");

$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$base_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . "/sekolah_kita/";

include '../layouts/header.php';
include '../layouts/sidebar.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
    .heading-title { font-family: 'Outfit', sans-serif; color: #0a192f; font-weight: 700; }
    @media print {
        body * { visibility: hidden; background: white !important; color: black !important; }
        .print-area, .print-area * { visibility: visible; }
        .print-area { position: absolute; left: 0; top: 0; width: 100%; }
        .no-print { display: none !important; }
    }
</style>

<div class="container-fluid p-3 p-md-5" style="margin-top: 60px;">
    
    <div class="print-area">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 border-bottom pb-3">
            <div>
                <h3 class="heading-title mb-1"><i class="fas fa-utensils text-warning me-2"></i> Manifestasi Logistik Makan Bergizi Gratis (MBG)</h3>
                <p class="text-muted small mb-0">Laporan porsi box konsumsi realtime berdasarkan data validasi kehadiran siswa.</p>
            </div>
            <div class="text-md-end">
                <span class="badge bg-dark rounded-pill px-3 py-2 font-monospace" style="background-color:#0a192f !important;">
                    HARI INI: <?php echo date('d F Y'); ?>
                </span>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0 text-center">
                        <thead class="table-light text-secondary small fw-bold">
                            <tr>
                                <th class="text-start ps-4 py-3">NAMA RUANG KELAS</th>
                                <th>TOTAL MURID</th>
                                <th class="table-success text-success fw-bold">WAJIB MASAK (HADIR/TELAT)</th>
                                <th class="table-danger text-danger">DIBATALKAN (SAKIT/IZIN/ALPHA)</th>
                                <th class="table-warning text-warning">BELUM ABSEN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $grand_total_masak = 0;
                            $grand_total_siswa = 0;
                            while($row = mysqli_fetch_assoc($query_mbg)) { 
                                // Keamanan Logistik: Jika belum absen sama sekali (pagi hari), anggap wajib masak demi aman jatah pangan anak
                                $porsi_real_masak = ($row['porsi_masak'] == 0 && $row['porsi_batal'] == 0) ? $row['total_siswa'] : $row['porsi_masak'];
                                $grand_total_masak += $porsi_real_masak;
                                $grand_total_siswa += $row['total_siswa'];
                            ?>
                            <tr>
                                <td class="text-start ps-4 fw-bold text-dark"><?php echo $row['nama_kelas']; ?></td>
                                <td><?php echo $row['total_siswa']; ?> Anak</td>
                                <td class="table-success text-success fw-bold" style="font-size:1.05rem;"><?php echo $porsi_real_masak; ?> Box</td>
                                <td class="table-danger text-danger fw-normal"><?php echo $row['porsi_batal']; ?> Box</td>
                                <td class="table-warning text-warning"><?php echo $row['belum_absen']; ?> Anak</td>
                            </tr>
                            <?php } ?>
                            <tr class="table-dark" style="background-color:#0a192f !important;">
                                <td class="text-start ps-4 fw-bold">GRAND TOTAL KESELURUHAN</td>
                                <td class="fw-bold"><?php echo $grand_total_siswa; ?> Siswa</td>
                                <td class="fw-bold text-warning" style="font-size:1.15rem;"><?php echo $grand_total_masak; ?> Porsi Box</td>
                                <td colspan="2" class="bg-secondary bg-opacity-10"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="no-print pt-2">
        <button onclick="window.print()" class="btn btn-success btn-lg w-100 py-3 rounded-4 fw-bold shadow-sm" style="font-family:'Outfit'; font-size:1.2rem;">
            <i class="fas fa-print me-2"></i> CETAK NOTA REKAPITULASI DAPUR MBG
        </button>
    </div>

</div>

<?php include '../layouts/footer.php'; ?>