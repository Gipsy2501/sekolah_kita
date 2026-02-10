<?php
session_start();
include '../../config/koneksi.php';
include '../../layouts/header.php';
include '../../layouts/sidebar.php';
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid p-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold text-dark"><i class="fas fa-file-signature me-2 text-warning"></i> Kelola Ujian</h4>
            <p class="text-muted small mb-0">Buat jadwal, token, dan bank soal.</p>
        </div>
        <button type="button" class="btn btn-primary bg-navy rounded-pill px-4 shadow-sm border-0" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fas fa-plus me-2"></i> Jadwalkan Ujian
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Nama Ujian</th>
                            <th>Mapel</th>
                            <th>Waktu & Durasi</th>
                            <th class="text-center">Token</th>
                            <th class="text-center">Soal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($koneksi, "SELECT u.*, m.nama_mapel FROM tb_ujian u 
                                                         JOIN tb_mapel m ON u.id_mapel = m.id_mapel 
                                                         ORDER BY u.id_ujian DESC");
                        while($d = mysqli_fetch_array($query)){
                        ?>
                        <tr>
                            <td class="ps-4 fw-bold"><?php echo $d['judul_ujian']; ?></td>
                            <td><span class="badge bg-light text-dark border"><?php echo $d['nama_mapel']; ?></span></td>
                            <td>
                                <div class="small"><?php echo date('d M Y H:i', strtotime($d['tanggal_mulai'])); ?></div>
                                <span class="badge bg-info text-dark rounded-pill"><?php echo $d['durasi_menit']; ?> Menit</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-dark text-warning fs-6" style="letter-spacing: 2px;"><?php echo $d['token_ujian']; ?></span>
                            </td>
                            <td class="text-center">
                                <a href="input_soal.php?id=<?php echo $d['id_ujian']; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fas fa-list me-1"></i> Kelola Soal
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="monitoring.php?id=<?php echo $d['id_ujian']; ?>" class="btn btn-sm btn-success rounded-circle shadow-sm" title="Pantau Nilai">
                                    <i class="fas fa-chart-line"></i>
                                </a>
                                <a href="hapus_ujian.php?id=<?php echo $d['id_ujian']; ?>" class="btn btn-sm btn-danger rounded-circle shadow-sm btn-hapus">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-navy text-white rounded-top-4">
                <h5 class="modal-title fw-bold">Buat Jadwal Ujian</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="proses_tambah_ujian.php" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="fw-bold small text-muted">JUDUL UJIAN</label>
                        <input type="text" name="judul" class="form-control" placeholder="Contoh: UAS Semester Ganjil" required>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold small text-muted">MATA PELAJARAN</label>
                        <select name="id_mapel" class="form-select" required>
                            <?php
                            $qm = mysqli_query($koneksi, "SELECT * FROM tb_mapel");
                            while($m = mysqli_fetch_array($qm)){
                                echo "<option value='$m[id_mapel]'>$m[nama_mapel]</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="fw-bold small text-muted">MULAI</label>
                            <input type="datetime-local" name="tgl_mulai" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="fw-bold small text-muted">SELESAI</label>
                            <input type="datetime-local" name="tgl_selesai" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="fw-bold small text-muted">DURASI (MENIT)</label>
                            <input type="number" name="durasi" class="form-control" value="60" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="fw-bold small text-muted">TOKEN MASUK</label>
                            <input type="text" name="token" class="form-control text-uppercase fw-bold text-primary" placeholder="A1B2C" maxlength="6" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" name="simpan" class="btn btn-primary bg-navy w-100 rounded-pill">SIMPAN JADWAL</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style> .bg-navy { background-color: #0a192f; } </style>
<?php include '../../layouts/footer.php'; ?>