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

$id_siswa = $_SESSION['id_user'];

// --- LOGIKA CEK TOKEN ---
if(isset($_POST['masuk_ujian'])){
    $id_ujian_input = $_POST['id_ujian'];
    $token_input    = strtoupper($_POST['token']); // Paksa huruf besar

    // Cek Token di Database
    $cek = mysqli_query($koneksi, "SELECT * FROM tb_ujian WHERE id_ujian='$id_ujian_input'");
    $data_ujian = mysqli_fetch_array($cek);

    if($token_input == $data_ujian['token_ujian']){
        // Token Benar -> Masuk ke Ruang Ujian
        echo "<script>
            window.location='lembar_ujian.php?id=$id_ujian_input';
        </script>";
    } else {
        // Token Salah
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Token Salah!',
                text: 'Silahkan tanya pengawas ujian.',
                timer: 2000,
                showConfirmButton: false
            });
        </script>";
    }
}
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid p-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold text-dark"><i class="fas fa-file-contract me-2 text-warning"></i> Daftar Ujian</h4>
            <p class="text-muted small mb-0">Silahkan kerjakan ujian sesuai jadwal yang ditentukan.</p>
        </div>
        <div class="text-end">
            <span class="badge bg-navy p-2 rounded-pill"><i class="fas fa-clock me-1"></i> <?php echo date('H:i'); ?> WIB</span>
        </div>
    </div>

    <div class="row">
        <?php
        // Ambil Data Ujian + Mapel + Guru
        $query = mysqli_query($koneksi, "SELECT u.*, m.nama_mapel, g.nama_guru 
                                         FROM tb_ujian u
                                         JOIN tb_mapel m ON u.id_mapel = m.id_mapel
                                         JOIN tb_guru g ON u.id_guru = g.id_guru
                                         ORDER BY u.id_ujian DESC");

        if(mysqli_num_rows($query) > 0){
            while($d = mysqli_fetch_array($query)){
                
                // --- LOGIKA STATUS UJIAN ---
                $sekarang = time();
                $mulai    = strtotime($d['tanggal_mulai']);
                $selesai  = strtotime($d['waktu_selesai']); 
                
                // Cek apakah siswa SUDAH mengerjakan?
                $cek_nilai = mysqli_query($koneksi, "SELECT * FROM tb_nilai_ujian WHERE id_ujian='$d[id_ujian]' AND id_siswa='$id_siswa'");
                $sudah_mengerjakan = mysqli_num_rows($cek_nilai) > 0;
                $data_nilai = mysqli_fetch_array($cek_nilai);

                // Tentukan Status Warna & Tombol
                if($sudah_mengerjakan){
                    $status_badge = '<span class="badge bg-primary w-100 py-2">SUDAH DIKERJAKAN</span>';
                    $tombol_aksi  = '<button class="btn btn-outline-primary w-100 fw-bold" disabled>Nilai: '.$data_nilai['nilai_akhir'].'</button>';
                } 
                elseif($sekarang < $mulai){
                    $status_badge = '<span class="badge bg-warning text-dark w-100 py-2">BELUM DIMULAI</span>';
                    $tombol_aksi  = '<button class="btn btn-secondary w-100" disabled><i class="fas fa-lock me-2"></i> Belum Dibuka</button>';
                } 
                elseif($sekarang > $selesai){
                    $status_badge = '<span class="badge bg-danger w-100 py-2">UJIAN DITUTUP</span>';
                    $tombol_aksi  = '<button class="btn btn-danger w-100" disabled><i class="fas fa-times-circle me-2"></i> Waktu Habis</button>';
                } 
                else {
                    $status_badge = '<span class="badge bg-success w-100 py-2 animate-pulse">SEDANG BERLANGSUNG</span>';
                    // Tombol memicu Modal Token
                    $tombol_aksi  = '<button type="button" class="btn btn-primary bg-navy w-100 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalToken'.$d['id_ujian'].'">
                                        <i class="fas fa-pen-nib me-2"></i> KERJAKAN
                                     </button>';
                }
        ?>
        
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 hover-top">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-light text-primary border border-primary"><?php echo $d['nama_mapel']; ?></span>
                        <small class="text-muted"><i class="fas fa-calendar-alt me-1"></i> <?php echo date('d M', strtotime($d['tanggal_mulai'])); ?></small>
                    </div>
                    
                    <h5 class="fw-bold text-dark mb-1"><?php echo $d['judul_ujian']; ?></h5>
                    <p class="text-muted small mb-3"><i class="fas fa-chalkboard-teacher me-1"></i> <?php echo $d['nama_guru']; ?></p>
                    
                    <div class="row g-2 mb-3 small bg-light p-2 rounded-3 mx-0">
                        <div class="col-6 border-end text-center">
                            <div class="fw-bold text-dark">Durasi</div>
                            <div class="text-muted"><?php echo $d['durasi_menit']; ?> Menit</div>
                        </div>
                        <div class="col-6 text-center">
                            <div class="fw-bold text-dark">Soal</div>
                            <div class="text-muted">Pilihan Ganda</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <?php echo $status_badge; ?>
                    </div>

                    <?php echo $tombol_aksi; ?>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalToken<?php echo $d['id_ujian']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0">
                    <div class="modal-header bg-navy text-white rounded-top-4">
                        <h5 class="modal-title fw-bold"><i class="fas fa-key me-2"></i> Masukkan Token</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST">
                        <div class="modal-body p-4 text-center">
                            <p class="text-muted mb-3">Silahkan masukkan token yang diberikan oleh pengawas untuk memulai ujian.</p>
                            <input type="hidden" name="id_ujian" value="<?php echo $d['id_ujian']; ?>">
                            
                            <input type="text" name="token" class="form-control form-control-lg text-center fw-bold text-uppercase letter-spacing-2" placeholder="TOKEN" autocomplete="off" required style="letter-spacing: 5px; font-size: 1.5rem;">
                            
                            <div class="alert alert-warning small mt-3 mb-0 text-start">
                                <i class="fas fa-exclamation-triangle me-1"></i> 
                                Waktu ujian akan berjalan otomatis setelah Anda menekan tombol "Mulai".
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0 justify-content-center">
                            <button type="submit" name="masuk_ujian" class="btn btn-primary bg-navy rounded-pill px-5 fw-bold w-100">
                                MULAI MENGERJAKAN <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php 
            }
        } else {
            echo '<div class="col-12 text-center py-5 text-muted"><img src="../../assets/img/empty.svg" width="150" class="mb-3 opacity-50"><br>Belum ada jadwal ujian.</div>';
        }
        ?>
    </div>
</div>

<style>
    .bg-navy { background-color: #0a192f; }
    .bg-navy:hover { background-color: #112240; }
    .hover-top { transition: transform 0.2s; }
    .hover-top:hover { transform: translateY(-5px); }
    .animate-pulse { animation: pulse 2s infinite; }
    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.7; } 100% { opacity: 1; } }
</style>

<?php include '../../layouts/footer.php'; ?>