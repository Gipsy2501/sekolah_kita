<?php
session_start();
include '../../config/koneksi.php';
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

// --- LOGIKA SIMPAN DATA ---
if(isset($_POST['simpan'])){
    $nisn       = $_POST['nisn'];
    $nama       = $_POST['nama'];
    $id_kelas   = $_POST['id_kelas'];
    $username   = $_POST['username'];
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi Password
    
    // Proses Upload Foto (Sederhana)
    $foto = "default.jpg"; // Default kalau gak upload
    if($_FILES['foto']['name'] != ""){
        $foto = date('dmYHis')."_".$_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "../../uploads/profil/".$foto);
    }

    $simpan = mysqli_query($koneksi, "INSERT INTO tb_siswa VALUES (NULL, '$nisn', '$nama', '$id_kelas', '$username', '$password', '$foto')");

    if($simpan){
        echo "<script>
                alert('Berhasil Menambahkan Siswa!');
                window.location='data_siswa.php';
              </script>";
    } else {
        echo "<script>alert('Gagal Simpan: ".mysqli_error($koneksi)."');</script>";
    }
}
?>

<div class="container-fluid p-4">
    <div class="card shadow-sm border-0" style="max-width: 600px; margin: auto;">
        <div class="card-header bg-angkasa text-white">
            <h5 class="m-0"><i class="fas fa-user-plus me-2"></i> Tambah Siswa Baru</h5>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                
                <div class="mb-3">
                    <label>NISN</label>
                    <input type="number" name="nisn" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Kelas</label>
                    <select name="id_kelas" class="form-select" required>
                        <option value="">-- Pilih Kelas --</option>
                        <?php
                        // Ambil data dari tabel kelas untuk dropdown
                        $kelas = mysqli_query($koneksi, "SELECT * FROM tb_kelas ORDER BY nama_kelas ASC");
                        while($k = mysqli_fetch_array($kelas)){
                            echo "<option value='$k[id_kelas]'>$k[nama_kelas]</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Username (Login)</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label>Foto Profil (Opsional)</label>
                    <input type="file" name="foto" class="form-control">
                    <small class="text-muted">Format: JPG/PNG. Jika kosong akan pakai foto default.</small>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="data_siswa.php" class="btn btn-secondary">Kembali</a>
                    <button type="submit" name="simpan" class="btn btn-primary bg-angkasa">SIMPAN DATA</button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php include '../../layouts/footer.php'; ?>