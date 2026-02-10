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
            <h4 class="mb-0 fw-bold text-dark"><i class="fas fa-school me-2 text-warning"></i> Data Kelas</h4>
            <p class="text-muted small mb-0">Manajemen daftar kelas siswa.</p>
        </div>
        <button type="button" class="btn btn-primary bg-navy rounded-pill px-4 shadow-sm border-0" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fas fa-plus me-2"></i> Tambah Kelas
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center py-3" width="50">No</th>
                            <th class="py-3">Nama Kelas</th>
                            <th class="text-center py-3" width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $query = mysqli_query($koneksi, "SELECT * FROM tb_kelas ORDER BY nama_kelas ASC");
                        
                        if(mysqli_num_rows($query) > 0){
                            while($data = mysqli_fetch_array($query)){
                        ?>
                        <tr>
                            <td class="text-center fw-bold text-secondary"><?php echo $no++; ?></td>
                            <td>
                                <span class="badge bg-white text-dark border px-3 py-2 rounded-pill">
                                    <?php echo $data['nama_kelas']; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <button type="button" 
                                        class="btn btn-sm btn-warning text-white rounded-circle me-1 shadow-sm btn-edit" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEdit"
                                        data-id="<?php echo $data['id_kelas']; ?>"
                                        data-nama="<?php echo $data['nama_kelas']; ?>">
                                    <i class="fas fa-pen"></i>
                                </button>
                                
                                <a href="hapus_kelas.php?id=<?php echo $data['id_kelas']; ?>" class="btn btn-sm btn-danger rounded-circle shadow-sm btn-hapus">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo '<tr><td colspan="3" class="text-center py-5 text-muted">Belum ada data kelas.</td></tr>';
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
                <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle me-2"></i> Tambah Kelas</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="proses_tambah.php" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">NAMA KELAS</label>
                        <input type="text" name="nama_kelas" class="form-control form-control-lg" placeholder="Contoh: XII RPL 1" required autocomplete="off">
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-warning text-dark rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i> Edit Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="proses_edit.php" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" name="id_kelas" id="id_kelas_edit">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">NAMA KELAS</label>
                        <input type="text" name="nama_kelas" id="nama_kelas_edit" class="form-control form-control-lg" required>
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
    // 1. Script untuk memasukkan data ke Modal Edit
    const editBtns = document.querySelectorAll('.btn-edit');
    editBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Ambil data dari atribut tombol
            let id = this.getAttribute('data-id');
            let nama = this.getAttribute('data-nama');
            
            // Masukkan ke dalam input modal
            document.getElementById('id_kelas_edit').value = id;
            document.getElementById('nama_kelas_edit').value = nama;
        });
    });

    // 2. Notifikasi SweetAlert dari Session PHP
    <?php if(isset($_SESSION['notif'])): ?>
        Swal.fire({
            icon: '<?php echo $_SESSION['jenis']; ?>',
            title: '<?php echo $_SESSION['pesan']; ?>',
            timer: 2000,
            showConfirmButton: false
        });
        <?php unset($_SESSION['notif']); unset($_SESSION['jenis']); unset($_SESSION['pesan']); ?>
    <?php endif; ?>

    // 3. Konfirmasi Hapus
    const hapusBtns = document.querySelectorAll('.btn-hapus');
    hapusBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            Swal.fire({
                title: 'Yakin hapus?',
                text: "Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            })
        });
    });
</script>

<style>
    .bg-navy { background-color: #0a192f; }
    .bg-navy:hover { background-color: #112240; }
</style>

<?php include '../../layouts/footer.php'; ?>