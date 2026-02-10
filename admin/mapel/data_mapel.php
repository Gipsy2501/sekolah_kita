<?php
session_start();
include '../../config/koneksi.php';
include '../../layouts/header.php';
include '../../layouts/sidebar.php';
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid p-4 mobile-p-0">
    
    <div class="d-flex justify-content-between align-items-center mb-4 mobile-sticky-header">
        <div>
            <h4 class="mb-0 fw-bold text-dark"><i class="fas fa-book me-2 text-warning"></i> Data Mata Pelajaran</h4>
            <p class="text-muted small mb-0 d-none d-md-block">Manajemen pelajaran dan guru pengampu.</p>
        </div>
        <button type="button" class="btn btn-primary bg-navy rounded-pill px-4 shadow-sm border-0 btn-sm-mobile" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fas fa-plus me-2"></i> Tambah Mapel
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center py-3" width="50">No</th>
                            <th class="py-3">Mata Pelajaran</th>
                            <th class="py-3">Guru Pengampu</th>
                            <th class="text-center py-3" width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        // Join manual karena di databasemu belum ada Foreign Key
                        $query = mysqli_query($koneksi, "SELECT m.*, g.nama_guru 
                                                         FROM tb_mapel m 
                                                         LEFT JOIN tb_guru g ON m.id_guru = g.id_guru 
                                                         ORDER BY m.nama_mapel ASC");
                        
                        if(mysqli_num_rows($query) > 0){
                            while($data = mysqli_fetch_array($query)){
                        ?>
                        <tr>
                            <td class="text-center fw-bold text-secondary td-no"><?php echo $no++; ?></td>
                            <td>
                                <div class="fw-bold text-dark mobile-nama"><?php echo $data['nama_mapel']; ?></div>
                            </td>
                            <td class="text-muted">
                                <?php if($data['nama_guru']){ ?>
                                    <i class="fas fa-chalkboard-teacher text-warning me-1"></i> <?php echo $data['nama_guru']; ?>
                                <?php } else { ?>
                                    <span class="text-danger small fst-italic">Guru dihapus/kosong</span>
                                <?php } ?>
                            </td>
                            <td class="text-center td-aksi">
                                <button type="button" 
                                        class="btn btn-sm btn-light border text-warning rounded-circle shadow-sm btn-edit" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEdit"
                                        data-id="<?php echo $data['id_mapel']; ?>"
                                        data-nama="<?php echo $data['nama_mapel']; ?>"
                                        data-guru="<?php echo $data['id_guru']; ?>">
                                    <i class="fas fa-pen"></i>
                                </button>
                                
                                <a href="hapus_mapel.php?id=<?php echo $data['id_mapel']; ?>" class="btn btn-sm btn-light border text-danger rounded-circle shadow-sm btn-hapus">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo '<tr><td colspan="4" class="text-center py-5 text-muted">Belum ada data mapel.</td></tr>';
                        }
                        ?>
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
                <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle me-2"></i> Tambah Mapel</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="proses_tambah.php" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">NAMA MATA PELAJARAN</label>
                        <input type="text" name="nama_mapel" class="form-control" placeholder="Contoh: Bahasa Indonesia" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">GURU PENGAMPU</label>
                        <select name="id_guru" class="form-select" required>
                            <option value="">-- Pilih Guru --</option>
                            <?php
                            $q_guru = mysqli_query($koneksi, "SELECT * FROM tb_guru ORDER BY nama_guru ASC");
                            while($g = mysqli_fetch_array($q_guru)){
                                echo "<option value='$g[id_guru]'>$g[nama_guru]</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" name="simpan" class="btn btn-primary bg-navy rounded-pill px-4 w-100">SIMPAN</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-warning text-dark rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i> Edit Mapel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="proses_edit.php" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" name="id_mapel" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">NAMA MATA PELAJARAN</label>
                        <input type="text" name="nama_mapel" id="edit_nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">GURU PENGAMPU</label>
                        <select name="id_guru" id="edit_guru" class="form-select" required>
                            <option value="">-- Pilih Guru --</option>
                            <?php
                            $q_guru2 = mysqli_query($koneksi, "SELECT * FROM tb_guru ORDER BY nama_guru ASC");
                            while($g2 = mysqli_fetch_array($q_guru2)){
                                echo "<option value='$g2[id_guru]'>$g2[nama_guru]</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" name="update" class="btn btn-warning rounded-pill px-4 w-100 fw-bold">UPDATE</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Isi Modal Edit
    const editBtns = document.querySelectorAll('.btn-edit');
    editBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('edit_id').value = this.getAttribute('data-id');
            document.getElementById('edit_nama').value = this.getAttribute('data-nama');
            document.getElementById('edit_guru').value = this.getAttribute('data-guru');
        });
    });

    // Notif & Hapus
    <?php if(isset($_SESSION['notif'])): ?>
        Swal.fire({ icon: '<?php echo $_SESSION['jenis']; ?>', title: '<?php echo $_SESSION['pesan']; ?>', timer: 2000, showConfirmButton: false });
        <?php unset($_SESSION['notif']); unset($_SESSION['jenis']); unset($_SESSION['pesan']); ?>
    <?php endif; ?>

    const hapusBtns = document.querySelectorAll('.btn-hapus');
    hapusBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            Swal.fire({ title: 'Hapus Mapel?', text: "Data nilai terkait mungkin hilang!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, Hapus!' }).then((result) => { if (result.isConfirmed) { window.location.href = href; } })
        });
    });
</script>

<style>
    .bg-navy { background-color: #0a192f; }
    .bg-navy:hover { background-color: #112240; }
    @media (max-width: 768px) {
        .container-fluid { padding: 0 !important; }
        .mobile-sticky-header { position: sticky; top: 60px; z-index: 99; background: #fff; padding: 15px; border-bottom: 1px solid #eee; }
        .table thead { display: none; }
        .table tbody tr { display: flex; align-items: center; justify-content: space-between; padding: 15px; border-bottom: 1px solid #f0f0f0; width: 100%; }
        .table td { border: none !important; padding: 0 !important; }
        .td-no { display: none !important; }
        .td-aksi { display: flex; gap: 8px; }
    }
</style>

<?php include '../../layouts/footer.php'; ?>