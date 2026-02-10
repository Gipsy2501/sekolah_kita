<?php
session_start();
include '../../config/koneksi.php';
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

// Pastikan Admin Login
if($_SESSION['role'] != 'admin'){
    header("location:../../login.php");
    exit();
}

$id_admin = $_SESSION['id_user'];
$query = mysqli_query($koneksi, "SELECT * FROM tb_admin WHERE id_admin='$id_admin'");
$data = mysqli_fetch_array($query);

// --- LOGIKA UPDATE PROFIL ---
if(isset($_POST['update_profil'])){
    $nama_baru = $_POST['nama_admin'];
    $username_baru = $_POST['username'];
    $password_baru = $_POST['password'];
    
    // 1. Cek Ganti Password
    if(empty($password_baru)){
        $pass_query = $data['password']; // Pakai password lama
    } else {
        $pass_query = password_hash($password_baru, PASSWORD_DEFAULT); // Enkripsi password baru
    }

    // 2. Cek Ganti Foto
    $foto_query = $data['foto'];
    
    // Cek apakah user memilih file?
    if($_FILES['foto']['name'] != ""){
        $nama_file = $_FILES['foto']['name'];
        $ukuran_file = $_FILES['foto']['size'];
        $tmp_file = $_FILES['foto']['tmp_name'];
        
        // Cek Ekstensi
        $ext_boleh = array('jpg','jpeg','png');
        $x = explode('.', $nama_file);
        $ext = strtolower(end($x));
        
        if(in_array($ext, $ext_boleh)){
            // Cek Ukuran (Max 2MB)
            if($ukuran_file < 2000000){
                // Rename biar unik
                $foto_query = "ADMIN_".date('dmYHis')."_".$nama_file;
                
                // PROSES UPLOAD SEBENARNYA
                move_uploaded_file($tmp_file, "../../uploads/profil/".$foto_query);
                
                // Hapus foto lama kalau bukan default
                if($data['foto'] != 'default.jpg' && file_exists("../../uploads/profil/".$data['foto'])){ 
                    unlink("../../uploads/profil/".$data['foto']); 
                }
            } else {
                echo "<script>alert('Ukuran file terlalu besar! Max 2MB');</script>";
            }
        } else {
            echo "<script>alert('Format file tidak boleh! Harus JPG/PNG');</script>";
        }
    }

    // 3. Eksekusi Query Update ke Database
    $update = mysqli_query($koneksi, "UPDATE tb_admin SET 
                                      nama_admin='$nama_baru', 
                                      username='$username_baru', 
                                      password='$pass_query', 
                                      foto='$foto_query' 
                                      WHERE id_admin='$id_admin'");

    if($update){
        // Update Session Nama biar langsung berubah di header tanpa logout
        $_SESSION['nama'] = $nama_baru;
        echo "<script>alert('Profil Admin Berhasil Diupdate!'); window.location='saya.php';</script>";
    } else {
        echo "<script>alert('Gagal Update: ".mysqli_error($koneksi)."');</script>";
    }
}
?>

<div class="container-fluid p-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-angkasa text-white">
                    <h5 class="m-0"><i class="fas fa-user-cog me-2"></i> Pengaturan Akun Admin</h5>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        
                        <div class="text-center mb-4">
                            <img src="../../uploads/profil/<?php echo $data['foto']; ?>" class="rounded-circle shadow border border-3 border-secondary" width="120" height="120" style="object-fit: cover;">
                            <p class="mt-2 text-muted small">Foto Saat Ini</p>
                        </div>

                        <div class="mb-3">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama_admin" class="form-control" value="<?php echo $data['nama_admin']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" value="<?php echo $data['username']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold text-primary">Upload Foto Baru</label>
                            <input type="file" name="foto" class="form-control">
                            <small class="text-muted">Format: JPG/PNG. Maksimal 2MB.</small>
                        </div>

                        <div class="mb-4">
                            <label class="fw-bold">Ganti Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin ganti password">
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