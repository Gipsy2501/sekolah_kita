<?php
session_start();
// Cek Role: Kalau bukan admin, tendang keluar!
if($_SESSION['role'] != 'admin'){
    header("location:../login.php");
    exit();
}

include '../config/koneksi.php';
include '../layouts/header.php';
include '../layouts/sidebar.php';

// --- LOGIKA DATA REALTIME ---
$jml_siswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_siswa"));
$jml_guru  = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_guru"));
$jml_kelas = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_kelas"));
$jml_mapel = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_mapel"));

// Ambil 5 Siswa Terbaru untuk Widget
$siswa_baru = mysqli_query($koneksi, "SELECT s.*, k.nama_kelas FROM tb_siswa s 
                                      JOIN tb_kelas k ON s.id_kelas = k.id_kelas 
                                      ORDER BY s.id_siswa DESC LIMIT 5");
?>

<div class="container-fluid p-3 p-md-4">
    
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden position-relative hero-card">
        <div class="card-body p-4 p-md-5 text-white position-relative z-1">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div>
                    <h2 class="fw-bold mb-1">Halo, <?php echo explode(' ', $_SESSION['nama'])[0]; ?>! 👋</h2>
                    <p class="mb-0 opacity-75">Selamat datang kembali di Panel Administrator SMK Angkasa.</p>
                </div>
                <div class="text-end d-none d-md-block">
                    <h1 class="fw-bold mb-0" id="clock">00:00</h1>
                    <small class="text-warning fw-bold"><?php echo date('l, d F Y'); ?></small>
                </div>
            </div>
        </div>
        <div class="circle-decoration"></div>
        <div class="circle-decoration-2"></div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 hover-up">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box bg-light-primary text-primary">
                            <i class="fas fa-user-graduate fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="text-muted small fw-bold mb-0">TOTAL SISWA</h6>
                            <h3 class="fw-bold mb-0 text-dark"><?php echo $jml_siswa; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 hover-up">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box bg-light-success text-success">
                            <i class="fas fa-chalkboard-teacher fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="text-muted small fw-bold mb-0">TOTAL GURU</h6>
                            <h3 class="fw-bold mb-0 text-dark"><?php echo $jml_guru; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 hover-up">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box bg-light-warning text-warning">
                            <i class="fas fa-school fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="text-muted small fw-bold mb-0">TOTAL KELAS</h6>
                            <h3 class="fw-bold mb-0 text-dark"><?php echo $jml_kelas; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 hover-up">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box bg-light-info text-info">
                            <i class="fas fa-book fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="text-muted small fw-bold mb-0">MAPEL</h6>
                            <h3 class="fw-bold mb-0 text-dark"><?php echo $jml_mapel; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h6 class="fw-bold text-dark mb-0"><i class="fas fa-rocket me-2 text-warning"></i> Aksi Cepat</h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="d-grid gap-3">
                        <a href="siswa/data_siswa.php" class="btn btn-outline-light text-dark text-start p-3 border shadow-sm rounded-3 d-flex align-items-center hover-bg-light">
                            <div class="bg-navy text-white rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                <i class="fas fa-user-plus fa-lg"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Tambah Siswa</div>
                                <div class="small text-muted">Input data siswa baru</div>
                            </div>
                        </a>
                        
                        <a href="guru/data_guru.php" class="btn btn-outline-light text-dark text-start p-3 border shadow-sm rounded-3 d-flex align-items-center hover-bg-light">
                            <div class="bg-warning text-dark rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                <i class="fas fa-chalkboard-teacher fa-lg"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Tambah Guru</div>
                                <div class="small text-muted">Input data pengajar</div>
                            </div>
                        </a>

                        <a href="absensi/rekap_absensi.php" class="btn btn-outline-light text-dark text-start p-3 border shadow-sm rounded-3 d-flex align-items-center hover-bg-light">
                            <div class="bg-success text-white rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                <i class="fas fa-clipboard-check fa-lg"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Cek Absensi</div>
                                <div class="small text-muted">Monitoring kehadiran</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0"><i class="fas fa-clock me-2 text-warning"></i> 5 Siswa Terbaru</h6>
                    <a href="siswa/data_siswa.php" class="btn btn-sm btn-light text-primary rounded-pill px-3">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3">Nama Siswa</th>
                                    <th class="py-3">Kelas</th>
                                    <th class="py-3 text-end pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($s = mysqli_fetch_array($siswa_baru)){ ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <img src="../uploads/profil/<?php echo $s['foto']; ?>" class="rounded-circle me-3 border" width="40" height="40" style="object-fit: cover;">
                                            <div>
                                                <div class="fw-bold text-dark"><?php echo $s['nama_siswa']; ?></div>
                                                <div class="small text-muted"><?php echo $s['nisn']; ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><?php echo $s['nama_kelas']; ?></span></td>
                                    <td class="text-end pe-4"><span class="badge bg-success bg-opacity-10 text-success rounded-pill">Aktif</span></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    /* HERO CARD */
    .hero-card {
        background: linear-gradient(135deg, #0a192f 0%, #112240 100%);
        min-height: 180px;
    }
    .circle-decoration {
        position: absolute; top: -50px; right: -50px;
        width: 200px; height: 200px;
        background: rgba(251, 191, 36, 0.1); /* Emas Transparan */
        border-radius: 50%;
    }
    .circle-decoration-2 {
        position: absolute; bottom: -30px; left: 50px;
        width: 100px; height: 100px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    /* CARDS */
    .icon-box {
        width: 50px; height: 50px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
    }
    .bg-light-primary { background: #e6f1ff; }
    .bg-light-success { background: #e6fffa; }
    .bg-light-warning { background: #fffbeb; }
    .bg-light-info { background: #e0f7fa; }

    /* WARNA CUSTOM NAVY (PENTING BIAR ICON MUNCUL) */
    .bg-navy { background-color: #0a192f !important; color: white; }

    .hover-up { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .hover-up:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    
    .hover-bg-light:hover { background-color: #f8f9fa; border-color: #fbbf24 !important; }
</style>

<script>
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        const clockElement = document.getElementById('clock');
        if(clockElement) clockElement.textContent = timeString;
    }
    setInterval(updateClock, 1000);
    updateClock(); // Run immediately
</script>

<?php include '../layouts/footer.php'; ?>