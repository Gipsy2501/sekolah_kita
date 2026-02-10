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
            <h4 class="mb-0 fw-bold text-dark"><i class="fas fa-user-graduate me-2 text-warning"></i> Data Siswa</h4>
            <p class="text-muted small mb-0 d-none d-md-block">Manajemen peserta didik dan akun.</p>
        </div>
        <button type="button" class="btn btn-primary bg-navy rounded-pill px-4 shadow-sm border-0 btn-sm-mobile" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fas fa-plus me-2"></i> Tambah Siswa
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        
        <div class="card-header bg-white border-bottom-0 pt-4 px-4">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                <input type="text" id="searchInput" class="form-control border-start-0 bg-light" placeholder="Cari nama siswa atau NISN...">
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tabelSiswa">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center py-3" width="50">No</th>
                            <th class="py-3">Profil Siswa</th>
                            <th class="py-3">Kelas</th>
                            <th class="py-3 td-username">Username</th>
                            <th class="text-center py-3" width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        // JOIN KE TABEL KELAS
                        $query = mysqli_query($koneksi, "SELECT s.*, k.nama_kelas 
                                                         FROM tb_siswa s 
                                                         LEFT JOIN tb_kelas k ON s.id_kelas = k.id_kelas 
                                                         ORDER BY s.nama_siswa ASC");
                        
                        if(mysqli_num_rows($query) > 0){
                            while($data = mysqli_fetch_array($query)){
                        ?>
                        <tr>
                            <td class="text-center fw-bold text-secondary td-no"><?php echo $no++; ?></td>
                            
                            <td class="td-profil">
                                <div class="d-flex align-items-center">
                                    <img src="../../uploads/profil/<?php echo $data['foto']; ?>" class="rounded-circle border shadow-sm me-3" width="45" height="45" style="object-fit: cover;">
                                    <div>
                                        <div class="fw-bold text-dark mobile-nama"><?php echo $data['nama_siswa']; ?></div>
                                        <div class="small text-muted mobile-sub">
                                            <i class="fas fa-id-card text-warning me-1"></i> <?php echo $data['nisn']; ?>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-light text-dark border"><?php echo $data['nama_kelas']; ?></span>
                            </td>

                            <td class="text-muted td-username"><?php echo $data['username']; ?></td>
                            
                            <td class="text-center td-aksi">
                                <button type="button" 
                                        class="btn btn-sm btn-light border text-warning rounded-circle shadow-sm btn-edit" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEdit"
                                        data-id="<?php echo $data['id_siswa']; ?>"
                                        data-nisn="<?php echo $data['nisn']; ?>"
                                        data-nama="<?php echo $data['nama_siswa']; ?>"
                                        data-kelas="<?php echo $data['id_kelas']; ?>"
                                        data-username="<?php echo $data['username']; ?>"
                                        data-foto="<?php echo $data['foto']; ?>">
                                    <i class="fas fa-pen"></i>
                                </button>
                                
                                <a href="hapus_siswa.php?id=<?php echo $data['id_siswa']; ?>" class="btn btn-sm btn-light border text-danger rounded-circle shadow-sm btn-hapus">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo '<tr><td colspan="5" class="text-center py-5 text-muted">Belum ada data siswa.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-navy text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-plus me-2"></i> Tambah Siswa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="proses_tambah.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">NISN</label>
                            <input type="number" name="nisn" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">NAMA LENGKAP</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">KELAS</label>
                        <select name="id_kelas" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php
                            $q_kelas = mysqli_query($koneksi, "SELECT * FROM tb_kelas ORDER BY nama_kelas ASC");
                            while($k = mysqli_fetch_array($q_kelas)){
                                echo "<option value='$k[id_kelas]'>$k[nama_kelas]</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">USERNAME</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">PASSWORD</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">FOTO PROFIL</label>
                        <input type="file" name="foto" class="form-control">
                        <div class="form-text">Format: JPG/PNG. Kosongkan jika tidak ada.</div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" name="simpan" class="btn btn-primary bg-navy rounded-pill px-4 w-100">SIMPAN DATA</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-warning text-dark rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-edit me-2"></i> Edit Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <input type="hidden" name="id_siswa" id="edit_id">
                    <input type="hidden" name="foto_lama" id="edit_foto_lama">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">NISN</label>
                            <input type="number" name="nisn" id="edit_nisn" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">NAMA LENGKAP</label>
                            <input type="text" name="nama" id="edit_nama" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">KELAS</label>
                        <select name="id_kelas" id="edit_kelas" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php
                            $q_kelas2 = mysqli_query($koneksi, "SELECT * FROM tb_kelas ORDER BY nama_kelas ASC");
                            while($k2 = mysqli_fetch_array($q_kelas2)){
                                echo "<option value='$k2[id_kelas]'>$k2[nama_kelas]</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">USERNAME</label>
                            <input type="text" name="username" id="edit_username" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">PASSWORD BARU</label>
                            <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tetap">
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <img src="" id="preview_foto" width="50" height="50" class="rounded-circle border shadow-sm" style="object-fit: cover;">
                            <div class="w-100">
                                <label class="form-label fw-bold small text-muted">GANTI FOTO</label>
                                <input type="file" name="foto" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" name="update" class="btn btn-warning rounded-pill px-4 w-100 fw-bold">UPDATE DATA</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // 1. Live Search
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('#tabelSiswa tbody tr');
        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(value) ? '' : 'none';
        });
    });

    // 2. Isi Modal Edit
    const editBtns = document.querySelectorAll('.btn-edit');
    editBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('edit_id').value = this.getAttribute('data-id');
            document.getElementById('edit_nisn').value = this.getAttribute('data-nisn');
            document.getElementById('edit_nama').value = this.getAttribute('data-nama');
            document.getElementById('edit_kelas').value = this.getAttribute('data-kelas');
            document.getElementById('edit_username').value = this.getAttribute('data-username');
            
            let foto = this.getAttribute('data-foto');
            document.getElementById('edit_foto_lama').value = foto;
            document.getElementById('preview_foto').src = '../../uploads/profil/' + foto;
        });
    });

    // 3. Notifikasi
    <?php if(isset($_SESSION['notif'])): ?>
        Swal.fire({ icon: '<?php echo $_SESSION['jenis']; ?>', title: '<?php echo $_SESSION['pesan']; ?>', timer: 2000, showConfirmButton: false });
        <?php unset($_SESSION['notif']); unset($_SESSION['jenis']); unset($_SESSION['pesan']); ?>
    <?php endif; ?>

    // 4. Hapus
    const hapusBtns = document.querySelectorAll('.btn-hapus');
    hapusBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            Swal.fire({ title: 'Hapus Siswa?', text: "Data nilai & absensi siswa ini akan hilang!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, Hapus!' }).then((result) => { if (result.isConfirmed) { window.location.href = href; } })
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
        .td-no, .td-username { display: none !important; }
        .td-profil { flex-grow: 1; margin-right: 10px; }
        .td-aksi { display: flex; gap: 8px; }
        .mobile-nama { font-size: 15px; margin-bottom: 2px; }
    }
</style>

<?php include '../../layouts/footer.php'; ?>