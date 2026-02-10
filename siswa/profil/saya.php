<?php
session_start();
include '../../config/koneksi.php';
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

$id_siswa = $_SESSION['id_user'];
$data = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM tb_siswa WHERE id_siswa='$id_siswa'"));

// --- LOGIKA UPDATE PROFIL ---
if(isset($_POST['update_profil'])){
    $password_baru = $_POST['password'];
    
    // 1. Cek Ganti Password
    if(empty($password_baru)){
        $pass_query = $data['password']; // Pakai lama
    } else {
        $pass_query = password_hash($password_baru, PASSWORD_DEFAULT); // Hash baru
    }

    // 2. Cek Ganti Foto
    $foto_query = $data['foto'];
    if($_FILES['foto']['name'] != ""){
        $foto_query = date('dmYHis')."_".$_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "../../uploads/profil/".$foto_query);
        // Hapus foto lama
        if($data['foto'] != 'default.jpg'){ unlink("../../uploads/profil/".$data['foto']); }
    }

    // 3. Eksekusi Update
    $update = mysqli_query($koneksi, "UPDATE tb_siswa SET password='$pass_query', foto='$foto_query' WHERE id_siswa='$id_siswa'");

    if($update){
        echo "<script>alert('Profil Berhasil Diupdate!'); window.location='saya.php';</script>";
    }
}
?>

<div class="container-fluid p-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-angkasa text-white">
                    <h5 class="m-0"><i class="fas fa-user-cog me-2"></i> Pengaturan Profil</h5>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        
                        <div class="text-center mb-4">
                            <img src="../../uploads/profil/<?php echo $data['foto']; ?>" class="rounded-circle shadow" width="120" height="120" style="object-fit: cover;">
                        </div>

                        <div class="mb-3">
                            <label>Nama Lengkap (Tidak dapat diubah)</label>
                            <input type="text" class="form-control" value="<?php echo $data['nama_siswa']; ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" class="form-control" value="<?php echo $data['username']; ?>" readonly>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="fw-bold">Ganti Foto Profil</label>
                            <input type="file" name="foto" class="form-control">
                            <small class="text-muted">Gunakan foto seragam sekolah yang rapi.</small>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Ganti Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengganti password">
                        </div>

                        <button type="submit" name="update_profil" class="btn btn-primary bg-angkasa w-100">
                            <i class="fas fa-save me-2"></i> SIMPAN PERUBAHAN
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../layouts/footer.php'; ?>