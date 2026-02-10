<?php
session_start();
include '../../config/koneksi.php';
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

$id_ujian = $_GET['id'];
$ujian = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM tb_ujian WHERE id_ujian='$id_ujian'"));

// PROSES SIMPAN SOAL
if(isset($_POST['simpan_soal'])){
    $tanya = mysqli_real_escape_string($koneksi, $_POST['pertanyaan']);
    $a = mysqli_real_escape_string($koneksi, $_POST['opsi_a']);
    $b = mysqli_real_escape_string($koneksi, $_POST['opsi_b']);
    $c = mysqli_real_escape_string($koneksi, $_POST['opsi_c']);
    $d = mysqli_real_escape_string($koneksi, $_POST['opsi_d']);
    $kunci = $_POST['kunci'];

    mysqli_query($koneksi, "INSERT INTO tb_soal_ujian VALUES (NULL, '$id_ujian', '$tanya', '$a', '$b', '$c', '$d', '$kunci')");
    echo "<script>window.location='input_soal.php?id=$id_ujian';</script>";
}
?>

<div class="container-fluid p-4">
    <div class="mb-4">
        <a href="data_ujian.php" class="text-decoration-none text-muted"><i class="fas fa-arrow-left me-2"></i> Kembali</a>
        <h4 class="fw-bold mt-2">Bank Soal: <?php echo $ujian['judul_ujian']; ?></h4>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px; z-index: 1;">
                <div class="card-header bg-navy text-white rounded-top-4">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-plus-circle me-2"></i> Tambah Soal Baru</h6>
                </div>
                <div class="card-body p-4">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="fw-bold small text-muted">PERTANYAAN</label>
                            <textarea name="pertanyaan" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-2">
                            <div class="input-group">
                                <span class="input-group-text fw-bold">A</span>
                                <input type="text" name="opsi_a" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="input-group">
                                <span class="input-group-text fw-bold">B</span>
                                <input type="text" name="opsi_b" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="input-group">
                                <span class="input-group-text fw-bold">C</span>
                                <input type="text" name="opsi_c" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text fw-bold">D</span>
                                <input type="text" name="opsi_d" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="fw-bold small text-muted">KUNCI JAWABAN</label>
                            <select name="kunci" class="form-select bg-light border-warning" required>
                                <option value="">-- Pilih Kunci Benar --</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>
                        <button type="submit" name="simpan_soal" class="btn btn-primary bg-navy w-100 rounded-pill">SIMPAN SOAL</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <?php
            $q_soal = mysqli_query($koneksi, "SELECT * FROM tb_soal_ujian WHERE id_ujian='$id_ujian' ORDER BY id_soal ASC");
            $no = 1;
            while($s = mysqli_fetch_array($q_soal)){
            ?>
            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h6 class="fw-bold text-navy">Soal No. <?php echo $no++; ?></h6>
                        <a href="hapus_soal.php?id=<?php echo $s['id_soal']; ?>&ujian=<?php echo $id_ujian; ?>" class="text-danger" onclick="return confirm('Hapus soal ini?')"><i class="fas fa-trash"></i></a>
                    </div>
                    <p class="mb-3"><?php echo $s['pertanyaan']; ?></p>
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item <?php echo ($s['kunci_jawaban']=='A')?'bg-success text-white rounded':''; ?>">A. <?php echo $s['opsi_a']; ?></li>
                        <li class="list-group-item <?php echo ($s['kunci_jawaban']=='B')?'bg-success text-white rounded':''; ?>">B. <?php echo $s['opsi_b']; ?></li>
                        <li class="list-group-item <?php echo ($s['kunci_jawaban']=='C')?'bg-success text-white rounded':''; ?>">C. <?php echo $s['opsi_c']; ?></li>
                        <li class="list-group-item <?php echo ($s['kunci_jawaban']=='D')?'bg-success text-white rounded':''; ?>">D. <?php echo $s['opsi_d']; ?></li>
                    </ul>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<style> .bg-navy { background-color: #0a192f; } </style>
<?php include '../../layouts/footer.php'; ?>