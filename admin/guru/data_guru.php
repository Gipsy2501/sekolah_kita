<?php
session_start();
include '../../config/koneksi.php';
include '../../layouts/header.php';
include '../../layouts/sidebar.php';
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* DESKTOP DEFAULT */
    .bg-navy { background-color: #0a192f; }
    .bg-navy:hover { background-color: #112240; }
    
    /* MOBILE VIEW (Max Width 768px) */
    @media (max-width: 768px) {
        /* 1. Reset Container biar Full Width */
        .container-fluid { padding: 0 !important; }
        
        /* 2. Header Sticky (Nempel di atas) */
        .mobile-sticky-header {
            position: sticky; top: 60px; /* Sesuaikan tinggi navbar */
            z-index: 99;
            background: #fff;
            padding: 15px;
            border-bottom: 1px solid #eee;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        /* 3. Transformasi Tabel ke List */
        .card { border: none !important; border-radius: 0 !important; box-shadow: none !important; }
        .table thead { display: none; } /* Sembunyikan Header Tabel */
        .table tbody tr {
            display: flex; /* Ubah baris jadi Flexbox */
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            width: 100%;
        }
        
        .table td { border: none !important; padding: 0 !important; }

        /* Sembunyikan Kolom No & Username Desktop */
        .td-no, .td-username-desktop { display: none !important; }

        /* Atur Kolom Profil (Foto + Nama) biar menuhin tempat */
        .td-profil { flex-grow: 1; margin-right: 10px; }
        
        /* Atur Kolom Aksi (Tombol) di Kanan */
        .td-aksi { display: flex; gap: 8px; }

        /* Styling Teks Mobile */
        .mobile-nama { font-size: 15px; font-weight: bold; color: #333; margin-bottom: 2px; }
        .mobile-sub { font-size: 12px; color: #6c757d; display: flex; align-items: center; gap: 5px; }
        .mobile-username { font-size: 11px; color: #0a192f; background: #e6f1ff; padding: 2px 6px; border-radius: 4px; margin-top: 4px; display: inline-block; font-weight: 600; }
    }
</style>

<div class="container-fluid p-4 mobile-p-0">
    
    <div class="d-flex justify-content-between align-items-center mb-4 mobile-sticky-header">
        <div>
            <h4 class="mb-0 fw-bold text-dark"><i class="fas fa-chalkboard-teacher me-2 text-warning"></i> Data Guru</h4>
            <p class="text-muted small mb-0 d-none d-md-block">Manajemen data pengajar dan staff.</p>
        </div>
        <button type="button" class="btn btn-primary bg-navy rounded-pill px-4 shadow-sm border-0 btn-sm-mobile" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fas fa-plus me-2"></i> <span class="d-none d-md-inline">Tambah Guru</span><span class="d-md-none">Tambah</span>
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center py-3" width="50">No</th>
                            <th class="py-3">Profil Guru</th>
                            <th class="py-3 td-username-desktop">Username</th>
                            <th class="text-center py-3" width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $query = mysqli_query($koneksi, "SELECT * FROM tb_guru ORDER BY id_guru DESC");
                        
                        if(mysqli_num_rows($query) > 0){
                            while($data = mysqli_fetch_array($query)){
                        ?>
                        <tr>
                            <td class="text-center fw-bold text-secondary td-no"><?php echo $no++; ?></td>
                            
                            <td class="td-profil">
                                <div class="d-flex align-items-center">
                                    <img src="../../uploads/profil/<?php echo $data['foto']; ?>" class="rounded-circle border shadow-sm me-3" width="50" height="50" style="object-fit: cover;">
                                    <div>
                                        <div class="mobile-nama"><?php echo $data['nama_guru']; ?></div>
                                        <div class="mobile-sub">
                                            <i class="fas fa-id-badge text-warning" style="font-size: 10px;"></i> <?php echo $data['nip']; ?>
                                        </div>
                                        <div class="d-md-none mobile-username">
                                            @<?php echo $data['username']; ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="text-muted td-username-desktop"><?php echo $data['username']; ?></td>
                            
                            <td class="text-center td-aksi">
                                <button type="button" 
                                        class="btn btn-sm btn-light border text-warning rounded-circle shadow-sm btn-edit" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEdit"
                                        data-id="<?php echo $data['id_guru']; ?>"
                                        data-nip="<?php echo $data['nip']; ?>"
                                        data-nama="<?php echo $data['nama_guru']; ?>"
                                        data-username="<?php echo $data['username']; ?>"
                                        data-foto="<?php echo $data['foto']; ?>"
                                        style="width: 35px; height: 35px;">
                                    <i class="fas fa-pen"></i>
                                </button>
                                
                                <a href="hapus_guru.php?id=<?php echo $data['id_guru']; ?>" class="btn btn-sm btn-light border text-danger rounded-circle shadow-sm btn-hapus" style="width: 35px; height: 35px; display:inline-flex; align-items:center; justify-content:center;">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo '<tr><td colspan="4" class="text-center py-5 text-muted">Belum ada data guru.</td></tr>';
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
                <h5 class="modal-title fw-bold"><i class="fas fa-user-plus me-2"></i> Tambah Guru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="proses_tambah.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">NIP</label>
                            <input type="number" name="nip" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">NAMA LENGKAP</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
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
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="simpan" class="btn btn-primary bg-navy rounded-pill px-4 border-0">SIMPAN</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-warning text-dark rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-edit me-2"></i> Edit Guru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <input type="hidden" name="id_guru" id="edit_id">
                    <input type="hidden" name="foto_lama" id="edit_foto_lama">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">NIP</label>
                            <input type="number" name="nip" id="edit_nip" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">NAMA LENGKAP</label>
                            <input type="text" name="nama" id="edit_nama" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">USERNAME</label>
                            <input type="text" name="username" id="edit_username" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">PASSWORD BARU</label>
                            <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tetap">
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
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="update" class="btn btn-warning rounded-pill px-4 fw-bold">UPDATE</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const editBtns = document.querySelectorAll('.btn-edit');
    editBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('edit_id').value = this.getAttribute('data-id');
            document.getElementById('edit_nip').value = this.getAttribute('data-nip');
            document.getElementById('edit_nama').value = this.getAttribute('data-nama');
            document.getElementById('edit_username').value = this.getAttribute('data-username');
            let foto = this.getAttribute('data-foto');
            document.getElementById('edit_foto_lama').value = foto;
            document.getElementById('preview_foto').src = '../../uploads/profil/' + foto;
        });
    });

    <?php if(isset($_SESSION['notif'])): ?>
        Swal.fire({ icon: '<?php echo $_SESSION['jenis']; ?>', title: '<?php echo $_SESSION['pesan']; ?>', timer: 2000, showConfirmButton: false });
        <?php unset($_SESSION['notif']); unset($_SESSION['jenis']); unset($_SESSION['pesan']); ?>
    <?php endif; ?>

    const hapusBtns = document.querySelectorAll('.btn-hapus');
    hapusBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            Swal.fire({
                title: 'Yakin hapus?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, Hapus!'
            }).then((result) => { if (result.isConfirmed) { window.location.href = href; } })
        });
    });
</script>

<?php include '../../layouts/footer.php'; ?>