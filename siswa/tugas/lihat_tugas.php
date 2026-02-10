<?php
session_start();
include '../../config/koneksi.php';
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

// Pastikan Siswa Login
if($_SESSION['role'] != 'siswa'){
    header("location:../../login.php");
    exit();
}

$id_kelas_siswa = $_SESSION['id_kelas']; // ID Kelas dari Session Login
$id_siswa       = $_SESSION['id_user'];  // ID Siswa Login
?>

<div class="container-fluid p-4">
    <h4 class="mb-4 text-gray-800"><i class="fas fa-book-open me-2"></i> Tugas Saya</h4>

    <div class="row">
        <?php
        // --- PERBAIKAN QUERY ---
        // 1. Filter WHERE pakai variable $id_kelas_siswa yang benar
        // 2. JOIN dipastikan mengunci ID Siswa biar tidak double
        $sql = "SELECT t.*, g.nama_guru, p.status as status_kumpul, p.nilai 
                FROM tb_tugas t
                JOIN tb_guru g ON t.id_guru = g.id_guru
                LEFT JOIN tb_pengumpulan p ON t.id_tugas = p.id_tugas AND p.id_siswa = '$id_siswa'
                WHERE t.id_kelas = '$id_kelas_siswa'
                ORDER BY t.id_tugas DESC";
                  
        $tampil = mysqli_query($koneksi, $sql);
        $cek = mysqli_num_rows($tampil);

        if($cek > 0){
            while($data = mysqli_fetch_array($tampil)){
                
                // --- PERBAIKAN LOGIKA STATUS ---
                // Gunakan 'status_kumpul' sesuai alias di query SQL, bukan 'status' biasa
                $status_sekarang = isset($data['status_kumpul']) ? $data['status_kumpul'] : "";
                
                $badge_status = "";
                $tombol = "";

                if($status_sekarang == ""){
                    // Belum ada data di tabel pengumpulan
                    $badge_status = "<span class='badge bg-danger'>Belum Dikerjakan</span>";
                    $tombol = "<a href='kirim_tugas.php?id=$data[id_tugas]' class='btn btn-primary btn-sm w-100 bg-navy'>KERJAKAN SEKARANG</a>";
                } 
                elseif($status_sekarang == "Menunggu"){
                    // Sudah kirim tapi belum dinilai
                    $badge_status = "<span class='badge bg-warning text-dark'>Menunggu Nilai</span>";
                    $tombol = "<button class='btn btn-secondary btn-sm w-100' disabled><i class='fas fa-check'></i> SUDAH DIKIRIM</button>";
                } 
                else {
                    // Sudah dinilai
                    $badge_status = "<span class='badge bg-success'>Nilai: $data[nilai]</span>";
                    $tombol = "<button class='btn btn-success btn-sm w-100' disabled><i class='fas fa-star'></i> SELESAI</button>";
                }
        ?>
        
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100 card-tugas rounded-4"> 
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-light text-primary border border-primary">TUGAS</span>
                        <small class="text-muted text-end">
                            Deadline:<br>
                            <b class="text-danger"><?php echo date('d M Y, H:i', strtotime($data['tgl_deadline'])); ?></b>
                        </small>
                    </div>
                    
                    <h5 class="fw-bold mt-2 text-dark"><?php echo $data['judul_tugas']; ?></h5>
                    <h6 class="text-muted small mb-3"><i class="fas fa-user-tie me-1"></i> <?php echo $data['nama_guru']; ?></h6>
                    
                    <div class="p-3 bg-light rounded-3 mb-3 small text-secondary fst-italic border">
                        <?php echo substr($data['deskripsi'], 0, 120); ?>...
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div><?php echo $badge_status; ?></div>
                        
                        <?php if(!empty($data['file_soal'])){ ?>
                             <a href="../../uploads/tugas/<?php echo $data['file_soal']; ?>" target="_blank" class="text-decoration-none small fw-bold text-primary">
                                <i class="fas fa-paperclip"></i> Lihat Soal
                             </a>
                        <?php } ?>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 pb-3 pt-0">
                    <?php echo $tombol; ?>
                </div>
            </div>
        </div>

        <?php 
            } // End While
        } else {
        ?>
            <div class="col-12 text-center py-5">
                <div class="opacity-50 mb-3">
                    <i class="fas fa-clipboard-check fa-4x text-muted"></i>
                </div>
                <h5 class="mt-3 text-muted">Hore! Tidak ada tugas aktif.</h5>
                <p class="small text-muted">Kamu bisa bersantai sejenak.</p>
            </div>
        <?php } ?>
    </div>
</div>

<style>
    .bg-navy { background-color: #0a192f; border-color: #0a192f; }
    .bg-navy:hover { background-color: #112240; }
    .card-tugas:hover { transform: translateY(-5px); transition: 0.3s; }
</style>

<?php include '../../layouts/footer.php'; ?>