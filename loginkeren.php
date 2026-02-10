<?php
session_start();
include '../../config/koneksi.php';
include '../../layouts/header.php';
include '../../layouts/sidebar.php';
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid p-4">

    <!-- HEADER (Desktop & Mobile) -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-chalkboard-teacher me-2 text-warning"></i> Data Guru
                </h4>
                <p class="text-muted small mb-0">Manajemen data pengajar dan staff.</p>
            </div>
            <button type="button" class="btn btn-primary bg-navy rounded-pill px-5 py-3 shadow-sm border-0" 
                    data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fas fa-plus me-2"></i> Tambah Guru
            </button>
        </div>
    </div>

    <!-- ==================== DESKTOP VIEW (sama persis seperti sebelumnya) ==================== -->
    <div class="d-none d-lg-block">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center py-3" width="50">No</th>
                                <th class="py-3">Profil Guru</th>
                                <th class="py-3">Username</th>
                                <th class="text-center py-3" width="150">Aksi</th>
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
                                <td class="text-center fw-bold text-secondary"><?php echo $no++; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="../../uploads/profil/<?php echo $data['foto']; ?>" class="rounded-circle border border-2 border-light shadow-sm me-3" width="45" height="45" style="object-fit: cover;">
                                        <div>
                                            <div class="fw-bold text-dark"><?php echo $data['nama_guru']; ?></div>
                                            <div class="small text-muted">NIP: <?php echo $data['nip']; ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted"><?php echo $data['username']; ?></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-warning text-white rounded-circle me-1 shadow-sm btn-edit" 
                                            data-bs-toggle="modal" data-bs-target="#modalEdit"
                                            data-id="<?php echo $data['id_guru']; ?>"
                                            data-nip="<?php echo $data['nip']; ?>"
                                            data-nama="<?php echo $data['nama_guru']; ?>"
                                            data-username="<?php echo $data['username']; ?>"
                                            data-foto="<?php echo $data['foto']; ?>">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <a href="hapus_guru.php?id=<?php echo $data['id_guru']; ?>" class="btn btn-sm btn-danger rounded-circle shadow-sm btn-hapus">
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

    <!-- ==================== MOBILE VIEW (Android) ==================== -->
    <div class="d-lg-none">
        <?php
        // Query ulang (lebih ringan daripada fetch_all untuk kasus ini)
        $queryMobile = mysqli_query($koneksi, "SELECT * FROM tb_guru ORDER BY id_guru DESC");
        if(mysqli_num_rows($queryMobile) > 0){
            while($data = mysqli_fetch_array($queryMobile)){
        ?>
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <img src="../../uploads/profil/<?php echo $data['foto']; ?>" 
                         class="rounded-3 border border-2 border-light shadow-sm me-4" 
                         width="80" height="80" style="object-fit: cover;">
                    <div class="flex-grow-1">
                        <h5 class="fw-bold text-dark mb-1"><?php echo $data['nama_guru']; ?></h5>
                        <p class="text-muted mb-1">NIP: <span class="fw-medium"><?php echo $data['nip']; ?></span></p>
                        <div class="text-muted small">Username</div>
                        <div class="fw-medium text-dark"><?php echo $data['username']; ?></div>
                    </div>
                </div>
            </div>
            <div class="card-footer border-0 bg-transparent p-3 d-flex gap-3">
                <button type="button" 
                        class="btn btn-warning flex-grow-1 rounded-pill py-3 fw-bold btn-edit"
                        data-bs-toggle="modal" data-bs-target="#modalEdit"
                        data-id="<?php echo $data['id_guru']; ?>"
                        data-nip="<?php echo $data['nip']; ?>"
                        data-nama="<?php echo $data['nama_guru']; ?>"
                        data-username="<?php echo $data['username']; ?>"
                        data-foto="<?php echo $data['foto']; ?>">
                    <i class="fas fa-pen me-2"></i> Edit
                </button>
                <a href="hapus_guru.php?id=<?php echo $data['id_guru']; ?>" 
                   class="btn btn-danger flex-grow-1 rounded-pill py-3 fw-bold btn-hapus">
                    <i class="fas fa-trash me-2"></i> Hapus
                </a>
            </div>
        </div>
        <?php 
            }
        } else {
        ?>
        <div class="card border-0 shadow-sm rounded-4 text-center py-5">
            <i class="fas fa-chalkboard-teacher fa-4x text-muted mb-3"></i>
            <p class="text-muted">Belum ada d ata guru.</p>
        </div>
        <?php } ?>
    </div>

</div>

<!-- Modal Tambah & Edit tetap sama persis -->
<?php include 'modal_tambah_edit.php'; ?>   <!-- atau paste modal Anda di sini -->

<script>
// Script edit, hapus, sweetalert tetap sama (otomatis menangkap semua .btn-edit & .btn-hapus)
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

// SweetAlert & konfirmasi hapus (sama seperti sebelumnya)
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
            title: 'Yakin hapus guru ini?',
            text: "Data tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => { if (result.isConfirmed) window.location.href = href; });
    });
});
</script>

<style>
    .bg-navy { background-color: #0a192f; }
    .bg-navy:hover { background-color: #112240; }
    /* Tambahan kecil biar lebih smooth di mobile */
    .card { transition: transform 0.2s; }
    .card:hover { transform: translateY(-3px); }
</style>

<?php include '../../layouts/footer.php'; ?>