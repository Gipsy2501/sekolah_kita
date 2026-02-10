<?php
session_start();
include '../../config/koneksi.php';
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

$id_guru = $_SESSION['id_user'];
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 text-gray-800"><i class="fas fa-tasks me-2"></i> Daftar Tugas Anda</h4>
        <a href="buat_tugas.php" class="btn btn-primary bg-angkasa">
            <i class="fas fa-plus-circle me-2"></i> Buat Tugas Baru
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>No</th>
                            <th>Judul Tugas</th>
                            <th>Kelas Target</th>
                            <th>Deadline</th>
                            <th>File Soal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        // Join ke tabel kelas biar muncul nama kelasnya (bukan cuma ID)
                        $query = mysqli_query($koneksi, "SELECT * FROM tb_tugas 
                                                         JOIN tb_kelas ON tb_tugas.id_kelas = tb_kelas.id_kelas 
                                                         WHERE id_guru='$id_guru' 
                                                         ORDER BY id_tugas DESC");
                        
                        while($data = mysqli_fetch_array($query)){
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <b><?php echo $data['judul_tugas']; ?></b><br>
                                <small class="text-muted text-truncate" style="max-width: 200px; display:inline-block;">
                                    <?php echo substr($data['deskripsi'], 0, 50); ?>...
                                </small>
                            </td>
                            <td><span class="badge bg-info text-dark"><?php echo $data['nama_kelas']; ?></span></td>
                            <td>
                                <?php 
                                    $tgl = date('d M Y H:i', strtotime($data['tgl_deadline']));
                                    echo "<span class='text-danger fw-bold'><i class='far fa-clock me-1'></i> $tgl</span>";
                                ?>
                            </td>
                            <td>
                                <?php if($data['file_soal'] != ""){ ?>
                                    <a href="../../uploads/tugas/<?php echo $data['file_soal']; ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-download"></i> Unduh
                                    </a>
                                <?php } else { echo "-"; } ?>
                            </td>
                            <td>
                                <a href="nilai_tugas.php?id=<?php echo $data['id_tugas']; ?>" class="btn btn-sm btn-success mb-1">
                                    <i class="fas fa-check-circle me-1"></i> Penilaian
                                </a>
                                <a href="hapus_tugas.php?id=<?php echo $data['id_tugas']; ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Hapus tugas ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                
                <?php if(mysqli_num_rows($query) == 0){ ?>
                    <div class="text-center p-4 text-muted">
                        <i class="fas fa-folder-open fa-3x mb-3"></i><br>
                        Belum ada tugas yang dibuat.
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
</div>

<?php include '../../layouts/footer.php'; ?>