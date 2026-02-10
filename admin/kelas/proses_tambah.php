<?php
session_start();
include '../../config/koneksi.php';
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

if(isset($_POST['simpan'])){
    $nama_kelas = mysqli_real_escape_string($koneksi, $_POST['nama_kelas']);

    // Cek Duplikat
    $cek = mysqli_query($koneksi, "SELECT * FROM tb_kelas WHERE nama_kelas='$nama_kelas'");
    if(mysqli_num_rows($cek) > 0){
        echo "<script>
            alert('Nama Kelas sudah ada!');
        </script>";
    } else {
        $simpan = mysqli_query($koneksi, "INSERT INTO tb_kelas VALUES (NULL, '$nama_kelas')");
        if($simpan){
            $_SESSION['notif'] = true;
            $_SESSION['jenis'] = 'success';
            $_SESSION['pesan'] = 'Kelas Berhasil Ditambahkan';
            echo "<script>window.location='data_kelas.php';</script>";
        }
    }
}
?>

<div class="container-fluid p-4">
    <div class="card border-0 shadow-sm rounded-4" style="max-width: 500px; margin: 0 auto;">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <h5 class="fw-bold text-primary"><i class="fas fa-plus-circle me-2"></i> Tambah Kelas</h5>
        </div>
        <div class="card-body px-4 pb-4">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">NAMA KELAS</label>
                    <input type="text" name="nama_kelas" class="form-control form-control-lg" placeholder="Contoh: XII RPL 1" required autofocus>
                </div>
                
                <div class="d-flex gap-2">
                    <a href="data_kelas.php" class="btn btn-light w-50 py-2 rounded-pill">Batal</a>
                    <button type="submit" name="simpan" class="btn btn-primary bg-navy w-50 py-2 rounded-pill border-0">SIMPAN</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .bg-navy { background-color: #0a192f; }
</style>

<?php include '../../layouts/footer.php'; ?>