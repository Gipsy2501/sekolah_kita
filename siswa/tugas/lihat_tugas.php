<?php
session_start();
include '../../config/koneksi.php';
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

$id_kelas_siswa = $_SESSION['id_kelas']; // Kunci Filternya Ada Di Sini!
$id_siswa       = $_SESSION['id_user'];
?>

<div class="container-fluid p-4">
    <h4 class="mb-4 text-gray-800"><i class="fas fa-book-open me-2"></i> Tugas Saya</h4>

    <div class="row">
        <?php
        // LOGIKA CANGGIH:
        // Ambil Tugas sesuai Kelas Siswa
        // DAN cek apakah siswa SUDAH mengumpulkan atau BELUM (Left Join)
        $query = "SELECT t.*, g.nama_guru, m.nama_mapel, p.status, p.nilai 
                  FROM tb_tugas t
                  JOIN tb_guru g ON t.id_guru = g.id_guru
                  -- Left Join ke pengumpulan untuk cek status
                  LEFT JOIN tb_pengumpulan p ON t.id_tugas = p.id_tugas AND p.id_siswa = '$id_siswa'
                  LEFT JOIN tb_mapel m ON t.id_guru = m.id_guru -- (Opsional kalau sudah ada mapel)
                  WHERE t.id_kelas = '$id_kelas_siswa'
                  ORDER BY t.tgl_deadline ASC";
                  
        $tampil = mysqli_query($koneksi, $query);
        $cek = mysqli_num_rows($tampil);

        if($cek > 0){
            while($data = mysqli_fetch_array($tampil)){
                // Tentukan Status Warna
                $badge_status = "";
                if($data['status'] == ""){
                    $badge_status = "<span class='badge bg-danger'>Belum Dikerjakan</span>";
                    $tombol = "<a href='kirim_tugas.php?id=$data[id_tugas]' class='btn btn-primary btn-sm w-100 bg-angkasa'>KERJAKAN SEKARANG</a>";
                } elseif($data['status'] == "Menunggu"){
                    $badge_status = "<span class='badge bg-warning text-dark'>Menunggu Nilai</span>";
                    $tombol = "<button class='btn btn-secondary btn-sm w-100' disabled>SUDAH DIKIRIM</button>";
                } else {
                    $badge_status = "<span class='badge bg-success'>Nilai: $data[nilai]</span>";
                    $tombol = "<button class='btn btn-success btn-sm w-100' disabled>SELESAI</button>";
                }
        ?>
        
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100 card-tugas"> <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-light text-primary border border-primary">TUGAS BARU</span>
                        <small class="text-muted text-end">
                            Deadline:<br>
                            <b class="text-danger"><?php echo date('d M Y, H:i', strtotime($data['tgl_deadline'])); ?></b>
                        </small>
                    </div>
                    
                    <h5 class="fw-bold mt-2"><?php echo $data['judul_tugas']; ?></h5>
                    <h6 class="text-muted small"><i class="fas fa-user-tie me-1"></i> <?php echo $data['nama_guru']; ?></h6>
                    
                    <p class="card-text mt-3 text-secondary">
                        <?php echo substr($data['deskripsi'], 0, 100); ?>...
                    </p>
                    
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>Status: <?php echo $badge_status; ?></div>
                        
                        <?php if($data['file_soal'] != ""){ ?>
                             <a href="../../uploads/tugas/<?php echo $data['file_soal']; ?>" target="_blank" class="text-decoration-none small">
                                <i class="fas fa-paperclip"></i> Lihat Soal
                             </a>
                        <?php } ?>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 pb-3">
                    <?php echo $tombol; ?>
                </div>
            </div>
        </div>

        <?php 
            } // End While
        } else {
        ?>
            <div class="col-12 text-center py-5">
                <img src="../../assets/img/logo-angkasa.png" width="100" style="opacity: 0.2; filter: grayscale(100%);">
                <h5 class="mt-3 text-muted">Hore! Tidak ada tugas aktif.</h5>
                <p class="small">Selamat beristirahat, Kadet.</p>
            </div>
        <?php } ?>
    </div>
</div>

<?php include '../../layouts/footer.php'; ?>