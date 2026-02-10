<?php
session_start();
include '../../config/koneksi.php';
include '../../layouts/header.php';
include '../../layouts/sidebar.php';
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 text-gray-800"><i class="fas fa-user-graduate me-2"></i> Data Siswa</h4>
        <a href="tambah.php" class="btn btn-primary bg-angkasa">
            <i class="fas fa-plus-circle me-2"></i> Tambah Siswa
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>No</th>
                            <th>Foto</th>
                            <th>NISN / Nama</th>
                            <th>Kelas</th>
                            <th>Username</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        // Join Tabel Siswa dengan Tabel Kelas biar nama kelasnya muncul
                        $query = "SELECT * FROM tb_siswa 
                                  JOIN tb_kelas ON tb_siswa.id_kelas = tb_kelas.id_kelas 
                                  ORDER BY id_siswa DESC";
                        $tampil = mysqli_query($koneksi, $query);
                        
                        while($data = mysqli_fetch_array($tampil)){
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <img src="../../uploads/profil/<?php echo $data['foto']; ?>" width="40" height="40" class="rounded-circle" style="object-fit: cover;">
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo $data['nama_siswa']; ?></div>
                                <small class="text-muted"><?php echo $data['nisn']; ?></small>
                            </td>
                            <td><span class="badge bg-info text-dark"><?php echo $data['nama_kelas']; ?></span></td>
                            <td><?php echo $data['username']; ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $data['id_siswa']; ?>" class="btn btn-sm btn-warning text-white"><i class="fas fa-edit"></i></a>
                                <a href="hapus.php?id=<?php echo $data['id_siswa']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus siswa ini?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../../layouts/footer.php'; ?>