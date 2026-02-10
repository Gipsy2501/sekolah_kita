<?php
session_start();
include '../../config/koneksi.php';
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

// 1. Ambil ID Tugas dari URL
if(!isset($_GET['id'])){
    echo "<script>alert('Pilih tugas dulu!'); window.location='lihat_tugas.php';</script>";
    exit();
}

$id_tugas = $_GET['id'];
$id_siswa = $_SESSION['id_user'];

// 2. Ambil Detail Tugas dari Database
$query_tugas = mysqli_query($koneksi, "SELECT * FROM tb_tugas 
                                       JOIN tb_guru ON tb_tugas.id_guru = tb_guru.id_guru 
                                       WHERE id_tugas='$id_tugas'");
$tugas = mysqli_fetch_array($query_tugas);

// 3. Cek Apakah Siswa Sudah Pernah Mengumpulkan?
$query_cek = mysqli_query($koneksi, "SELECT * FROM tb_pengumpulan WHERE id_tugas='$id_tugas' AND id_siswa='$id_siswa'");
$sudah_kumpul = mysqli_fetch_array($query_cek);
$cek_ada = mysqli_num_rows($query_cek);

// --- LOGIKA UPLOAD JAWABAN ---
if(isset($_POST['kirim_jawaban'])){
    $tgl_upload = date('Y-m-d H:i:s');
    
    // Validasi File
    $ekstensi_diperbolehkan = array('pdf','doc','docx','jpg','png','jpeg','zip');
    $nama_file = $_FILES['file_jawaban']['name'];
    $x = explode('.', $nama_file);
    $ekstensi = strtolower(end($x));
    $ukuran = $_FILES['file_jawaban']['size'];
    
    if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
        if($ukuran < 5048000){ // Max 5 MB
            
            // Rename File Biar Rapi: JAWABAN_[NAMA SISWA]_[JUDUL TUGAS].ext
            $nama_baru = "JAWABAN_" . str_replace(' ', '_', $_SESSION['nama']) . "_" . $id_tugas . "." . $ekstensi;
            $file_tmp = $_FILES['file_jawaban']['tmp_name'];
            
            // Pindahkan File
            move_uploaded_file($file_tmp, '../../uploads/tugas/'.$nama_baru);

            if($cek_ada > 0){
                // Kalau sudah pernah kumpul, UPDATE data lama
                $query_kirim = "UPDATE tb_pengumpulan SET file_jawaban='$nama_baru', tgl_upload='$tgl_upload', status='Menunggu' WHERE id_pengumpulan='$sudah_kumpul[id_pengumpulan]'";
            } else {
                // Kalau belum, INSERT data baru
                $query_kirim = "INSERT INTO tb_pengumpulan VALUES (NULL, '$id_tugas', '$id_siswa', '$nama_baru', '$tgl_upload', 0, 'Menunggu')";
            }

            $simpan = mysqli_query($koneksi, $query_kirim);
            if($simpan){
                echo "<script>alert('Berhasil Mengirim Jawaban!'); window.location='lihat_tugas.php';</script>";
            }

        } else {
            echo "<script>alert('UKURAN FILE TERLALU BESAR! Maksimal 5 MB');</script>";
        }
    } else {
        echo "<script>alert('EKSTENSI FILE TIDAK DIPERBOLEHKAN! Harap upload PDF/DOC/GAMBAR');</script>";
    }
}
?>

<div class="container-fluid p-4">
    
    <a href="lihat_tugas.php" class="btn btn-secondary btn-sm mb-3"><i class="fas fa-arrow-left me-2"></i> Kembali</a>

    <div class="row">
        <div class="col-md-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-angkasa text-white">
                    <h5 class="m-0"><i class="fas fa-file-alt me-2"></i> Detail Tugas</h5>
                </div>
                <div class="card-body">
                    <h3><?php echo $tugas['judul_tugas']; ?></h3>
                    <div class="mb-3">
                        <span class="badge bg-info text-dark"><i class="fas fa-user me-1"></i> <?php echo $tugas['nama_guru']; ?></span>
                        <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Deadline: <?php echo date('d F Y, H:i', strtotime($tugas['tgl_deadline'])); ?></span>
                    </div>
                    
                    <hr>
                    
                    <div class="alert alert-light border">
                        <h6 class="fw-bold">Instruksi:</h6>
                        <p style="white-space: pre-wrap;"><?php echo $tugas['deskripsi']; ?></p>
                    </div>

                    <?php if($tugas['file_soal'] != ""){ ?>
                        <div class="mt-3">
                            <label class="fw-bold mb-2">Lampiran Soal:</label><br>
                            <a href="../../uploads/tugas/<?php echo $tugas['file_soal']; ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-download me-2"></i> Download Soal
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm border-0 border-top border-4 border-warning">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="fas fa-upload me-2"></i> Pengumpulan Tugas</h5>
                    
                    <?php if($cek_ada > 0 && $sudah_kumpul['status'] == 'Dinilai'){ ?>
                        
                        <div class="alert alert-success text-center">
                            <h4><i class="fas fa-check-circle fa-2x mb-2"></i><br>TUGAS SELESAI</h4>
                            <p>Guru sudah menilai tugas ini.</p>
                            <h1 class="display-4 fw-bold"><?php echo $sudah_kumpul['nilai']; ?></h1>
                            <small class="text-muted">Nilai Akhir</small>
                        </div>
                        <a href="../../uploads/tugas/<?php echo $sudah_kumpul['file_jawaban']; ?>" target="_blank" class="btn btn-outline-success w-100">
                            <i class="fas fa-eye me-2"></i> Lihat Jawaban Saya
                        </a>

                    <?php } else { ?>
                        
                        <form method="POST" enctype="multipart/form-data">
                            
                            <?php if($cek_ada > 0){ ?>
                                <div class="alert alert-warning small">
                                    <i class="fas fa-info-circle me-1"></i> Anda sudah mengirimkan file: 
                                    <b><?php echo $sudah_kumpul['file_jawaban']; ?></b>.<br>
                                    Upload ulang jika ingin mengganti jawaban (selama belum dinilai).
                                </div>
                            <?php } ?>

                            <div class="mb-3">
                                <label class="form-label">Upload File Jawaban</label>
                                <input type="file" name="file_jawaban" class="form-control" required>
                                <div class="form-text">Format: PDF, DOCX, JPG, PNG. Max: 5MB.</div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" name="kirim_jawaban" class="btn btn-primary bg-angkasa py-2">
                                    <i class="fas fa-paper-plane me-2"></i> KIRIM JAWABAN
                                </button>
                            </div>
                        </form>

                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../layouts/footer.php'; ?>