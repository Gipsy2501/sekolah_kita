<?php
session_start();
// Cek Role: Kalau bukan admin, tendang keluar!
if($_SESSION['role'] != 'admin'){
    header("location:../login.php");
}

include '../config/koneksi.php';
include '../layouts/header.php';  // Panggil Kepala
include '../layouts/sidebar.php'; // Panggil Menu Samping

// --- LOGIKA HITUNG DATA (Untuk Widget Statistik) ---
$jml_siswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_siswa"));
$jml_guru  = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_guru"));
$jml_kelas = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_kelas"));
?>

<div class="container-fluid p-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-tachometer-alt me-2"></i> Dashboard Admin</h3>
        <span class="badge bg-secondary">Tahun Ajaran 2025/2026</span>
    </div>

    <div class="row g-4">
        
        <div class="col-md-4">
            <div class="card text-white bg-primary h-100 shadow-sm border-0" style="background: linear-gradient(45deg, #00264d, #004080);">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">TOTAL SISWA</h6>
                        <h2 class="fw-bold my-2"><?php echo $jml_siswa; ?></h2>
                        <small>Kadet Aktif</small>
                    </div>
                    <i class="fas fa-user-graduate fa-4x opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success h-100 shadow-sm border-0" style="background: linear-gradient(45deg, #2ecc71, #27ae60);">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">TOTAL GURU</h6>
                        <h2 class="fw-bold my-2"><?php echo $jml_guru; ?></h2>
                        <small>Pengajar</small>
                    </div>
                    <i class="fas fa-chalkboard-teacher fa-4x opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-warning h-100 shadow-sm border-0" style="background: linear-gradient(45deg, #f1c40f, #f39c12);">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="text-dark"> <h6 class="card-title mb-0">TOTAL KELAS</h6>
                        <h2 class="fw-bold my-2"><?php echo $jml_kelas; ?></h2>
                        <small>Rombel</small>
                    </div>
                    <i class="fas fa-school fa-4x text-dark opacity-25"></i>
                </div>
            </div>
        </div>

    </div>
    
    <div class="card mt-4 shadow-sm border-0">
        <div class="card-body p-5 text-center">
            <img src="../assets/img/logo-angkasa.png" width="100" class="mb-3">
            <h4>Selamat Datang di Sistem Informasi Sekolah Angkasa</h4>
            <p class="text-muted">Anda login sebagai Administrator. Silahkan kelola data melalui menu di samping.</p>
        </div>
    </div>

</div>
<?php
include '../layouts/footer.php'; // Panggil Kaki
?>