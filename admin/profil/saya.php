<?php
session_start();
include '../../config/koneksi.php';
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

// Validasi Admin
if($_SESSION['role'] != 'admin'){
    echo "<script>window.location='../../login.php';</script>";
    exit();
}

$id_admin = $_SESSION['id_user'];
$query = mysqli_query($koneksi, "SELECT * FROM tb_admin WHERE id_admin='$id_admin'");
$data = mysqli_fetch_array($query);

// --- LOGIKA UPDATE ---
if(isset($_POST['update_profil'])){
    $nama_baru = mysqli_real_escape_string($koneksi, $_POST['nama_admin']);
    $username_baru = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password_baru = $_POST['password'];
    $foto_lama = $data['foto'];
    
    // 1. Password
    if(empty($password_baru)){
        $pass_query = $data['password'];
    } else {
        $pass_query = password_hash($password_baru, PASSWORD_DEFAULT);
    }

    // 2. Foto
    $foto_query = $foto_lama;
    if($_FILES['foto']['name'] != ""){
        $nama_file = $_FILES['foto']['name'];
        $ukuran_file = $_FILES['foto']['size'];
        $tmp_file = $_FILES['foto']['tmp_name'];
        $ext_boleh = array('jpg','jpeg','png');
        $x = explode('.', $nama_file);
        $ext = strtolower(end($x));
        
        if(in_array($ext, $ext_boleh)){
            if($ukuran_file < 2000000){
                $foto_query = "ADMIN_".date('dmYHis')."_".$nama_file;
                move_uploaded_file($tmp_file, "../../uploads/profil/".$foto_query);
                if($foto_lama != 'default.jpg' && file_exists("../../uploads/profil/".$foto_lama)){ 
                    unlink("../../uploads/profil/".$foto_lama); 
                }
            } else {
                $_SESSION['notif'] = true; $_SESSION['jenis'] = 'error'; $_SESSION['pesan'] = 'File terlalu besar (Max 2MB)';
            }
        } else {
            $_SESSION['notif'] = true; $_SESSION['jenis'] = 'error'; $_SESSION['pesan'] = 'Format harus JPG/PNG';
        }
    }

    // 3. Update Database
    if(!isset($_SESSION['notif']) || $_SESSION['jenis'] != 'error'){
        $update = mysqli_query($koneksi, "UPDATE tb_admin SET nama_admin='$nama_baru', username='$username_baru', password='$pass_query', foto='$foto_query' WHERE id_admin='$id_admin'");
        
        if($update){
            $_SESSION['nama'] = $nama_baru; // Update session nama
            $_SESSION['notif'] = true; $_SESSION['jenis'] = 'success'; $_SESSION['pesan'] = 'Profil berhasil diperbarui!';
        }
    }
    echo "<script>window.location='saya.php';</script>";
    exit();
}
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid p-3 p-md-4">
    
    <div class="mb-4">
        <h4 class="fw-bold text-dark mb-1"><i class="fas fa-user-shield me-2 text-warning"></i> Akun Saya</h4>
        <p class="text-muted small">Kelola informasi profil admin.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-10"> <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <form method="POST" enctype="multipart/form-data">
                    
                    <div class="row g-0">
                        
                        <div class="col-md-4 bg-light d-flex flex-column align-items-center justify-content-center p-5 text-center border-end-md">
                            <div class="position-relative">
                                <div class="profile-circle shadow-lg">
                                    <img src="../../uploads/profil/<?php echo $data['foto']; ?>" id="preview_foto">
                                </div>
                                
                                <label for="upload_foto" class="btn-camera shadow-sm">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input type="file" name="foto" id="upload_foto" class="d-none" onchange="previewImage(this)">
                            </div>
                            
                            <h5 class="fw-bold mt-4 mb-0 text-navy"><?php echo $data['nama_admin']; ?></h5>
                            <span class="badge bg-warning text-dark rounded-pill px-3 mt-2">ADMINISTRATOR</span>
                            <p class="text-muted small mt-3 px-3">Ketuk ikon kamera untuk mengubah foto profil Anda.</p>
                        </div>

                        <div class="col-md-8 p-4 p-md-5 bg-white">
                            <h6 class="fw-bold text-navy mb-4 border-bottom pb-3"><i class="fas fa-edit me-2"></i> INFORMASI PRIBADI</h6>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-secondary">NAMA LENGKAP</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-user"></i></span>
                                    <input type="text" name="nama_admin" class="form-control border-start-0 ps-0" value="<?php echo $data['nama_admin']; ?>" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-secondary">USERNAME LOGIN</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-at"></i></span>
                                    <input type="text" name="username" class="form-control border-start-0 ps-0" value="<?php echo $data['username']; ?>" required>
                                </div>
                            </div>

                            <h6 class="fw-bold text-navy mb-4 mt-5 border-bottom pb-3"><i class="fas fa-lock me-2"></i> KEAMANAN</h6>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-secondary">PASSWORD BARU</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-key"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="••••••••">
                                </div>
                                <div class="form-text text-warning small"><i class="fas fa-info-circle"></i> Kosongkan jika tidak ingin mengganti password.</div>
                            </div>

                            <div class="d-flex gap-3 mt-5 pt-3">
                                <button type="reset" class="btn btn-light rounded-pill px-4 py-2 fw-bold flex-fill">Reset</button>
                                <button type="submit" name="update_profil" class="btn btn-primary bg-navy border-0 rounded-pill px-4 py-2 fw-bold flex-fill shadow-sm">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<style>
    /* Warna Tema */
    .bg-navy { background-color: #0a192f; }
    .text-navy { color: #0a192f; }
    
    /* Layout Responsif Kustom */
    @media (min-width: 768px) {
        .border-end-md { border-right: 1px solid #dee2e6; }
        .h-100-md { height: 100%; }
    }

    /* Foto Profil Bulat Besar */
    .profile-circle {
        width: 160px; height: 160px;
        border-radius: 50%;
        border: 5px solid white; /* Frame putih */
        overflow: hidden;
        position: relative;
        background: #eee;
    }
    .profile-circle img {
        width: 100%; height: 100%;
        object-fit: cover;
    }

    /* Tombol Kamera Floating (Google Style) */
    .btn-camera {
        position: absolute;
        bottom: 5px; right: 10px;
        width: 45px; height: 45px;
        background: #fbbf24; /* Warna Emas */
        color: #0a192f;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        font-size: 1.2rem;
        border: 3px solid white;
        transition: transform 0.2s;
    }
    .btn-camera:hover {
        transform: scale(1.1);
        background: #f59e0b;
    }

    /* Input Styling Clean */
    .form-control:focus {
        box-shadow: none;
        border-color: #fbbf24;
    }
    .input-group-text { background: transparent; }
</style>

<script>
    // Live Preview Foto
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('preview_foto').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // SweetAlert Notif
    <?php if(isset($_SESSION['notif'])): ?>
        Swal.fire({
            icon: '<?php echo $_SESSION['jenis']; ?>',
            title: '<?php echo ($_SESSION['jenis'] == 'success') ? 'Berhasil!' : 'Gagal!'; ?>',
            text: '<?php echo $_SESSION['pesan']; ?>',
            timer: 2000, showConfirmButton: false
        });
        <?php unset($_SESSION['notif']); unset($_SESSION['jenis']); unset($_SESSION['pesan']); ?>
    <?php endif; ?>
</script>

<?php include '../../layouts/footer.php'; ?>