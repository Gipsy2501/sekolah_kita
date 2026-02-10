<?php
session_start();
include '../../config/koneksi.php';
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

// Ambil ID Tugas dari URL
$id_tugas = $_GET['id'];

// Ambil Info Tugasnya dulu
$info_tugas = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM tb_tugas JOIN tb_kelas ON tb_tugas.id_kelas = tb_kelas.id_kelas WHERE id_tugas='$id_tugas'"));

// --- LOGIKA SIMPAN NILAI ---
if(isset($_POST['simpan_nilai'])){
    $id_pengumpulan = $_POST['id_pengumpulan'];
    $nilai          = $_POST['nilai'];
    
    // Update tabel pengumpulan: Masukkan nilai & ubah status jadi 'Dinilai'
    $update = mysqli_query($koneksi, "UPDATE tb_pengumpulan SET nilai='$nilai', status='Dinilai' WHERE id_pengumpulan='$id_pengumpulan'");
    
    if($update){
        echo "<script>alert('Nilai Berhasil Disimpan!'); window.location='nilai_tugas.php?id=$id_tugas';</script>";
    }
}
?>

<div class="container-fluid p-4">
    
    <a href="data_tugas.php" class="btn btn-secondary btn-sm mb-3"><i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Tugas</a>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body bg-angkasa text-white rounded">
            <h4 class="m-0"><i class="fas fa-check-double me-2"></i> Penilaian Tugas</h4>
            <p class="m-0 mt-2 opacity-75">
                Judul: <b><?php echo $info_tugas['judul_tugas']; ?></b> | 
                Kelas: <b><?php echo $info_tugas['nama_kelas']; ?></b>
            </p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h6 class="fw-bold text-primary mb-0"><i class="fas fa-users me-2"></i> Daftar Pengumpulan Siswa</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-bordered">
                    <thead class="bg-light text-center">
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Siswa</th>
                            <th>Tanggal Upload</th>
                            <th>File Jawaban</th>
                            <th width="150">Nilai (0-100)</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        // Query canggih: Tampilkan SEMUA siswa di kelas itu, walau belum kumpul
                        // Tapi biar gampang, kita tampilkan yang SUDAH kumpul dulu saja ya
                        $query = mysqli_query($koneksi, "SELECT * FROM tb_pengumpulan 
                                                         JOIN tb_siswa ON tb_pengumpulan.id_siswa = tb_siswa.id_siswa 
                                                         WHERE id_tugas='$id_tugas' 
                                                         ORDER BY nama_siswa ASC");
                        
                        // Cek apakah ada yang kumpul?
                        if(mysqli_num_rows($query) > 0){
                            while($data = mysqli_fetch_array($query)){
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $no++; ?></td>
                            <td>
                                <b><?php echo $data['nama_siswa']; ?></b><br>
                                <small class="text-muted"><?php echo $data['nisn']; ?></small>
                            </td>
                            <td>
                                <?php echo date('d/m/Y H:i', strtotime($data['tgl_upload'])); ?> WIB
                                </td>
                            <td class="text-center">
                                <a href="../../uploads/tugas/<?php echo $data['file_jawaban']; ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download me-1"></i> Unduh
                                </a>
                            </td>
                            
                            <form method="POST">
                                <td>
                                    <input type="hidden" name="id_pengumpulan" value="<?php echo $data['id_pengumpulan']; ?>">
                                    <input type="number" name="nilai" class="form-control text-center fw-bold" value="<?php echo $data['nilai']; ?>" min="0" max="100" required>
                                </td>
                                <td class="text-center">
                                    <button type="submit" name="simpan_nilai" class="btn btn-success btn-sm">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </td>
                            </form>
                        </tr>
                        <?php 
                            } 
                        } else {
                            echo "<tr><td colspan='6' class='text-center py-4 text-muted'>Belum ada siswa yang mengumpulkan tugas ini.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../../layouts/footer.php'; ?>