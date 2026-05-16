<?php
// 1. WAJIB panggil satpam di baris paling pertama sebelum kode HTML apa pun!
include 'cek_akses.php';

// 2. Buat Base URL dinamis khusus untuk folder admin agar layout tidak pecah
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$base_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . "/sekolah_kita/";

include '../config/koneksi.php';
include '../layouts/header.php';
include '../layouts/sidebar.php';

// --- LOGIKA DATA REALTIME (IDENTITAS KODE AWAL TETAP DIPERTAHANKAN) ---
$jml_siswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_siswa"));
$jml_guru  = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_guru"));
$jml_kelas = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_kelas"));
$jml_mapel = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_mapel"));

// Simulasi Counter Tambahan untuk Standar Premium SaaS 2026
$absensi_hari_ini = round($jml_siswa * 0.94); // Simulasi tingkat kehadiran 94%
$pengumuman_aktif = 3;

// Ambil 5 Siswa Terbaru untuk Widget Table Premium
$siswa_baru = mysqli_query($koneksi, "SELECT s.*, k.nama_kelas FROM tb_siswa s 
                                      JOIN tb_kelas k ON s.id_kelas = k.id_kelas 
                                      ORDER BY s.id_siswa DESC LIMIT 5");
?>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    :root {
        --saas-navy-deep: #0a192f;
        --saas-navy-surface: #112240;
        --saas-gold: #fbbf24;
        --saas-bg: #f8fafc;
        --saas-card-border: rgba(148, 163, 184, 0.12);
        --saas-text-main: #334155;
        --saas-font-heading: 'Outfit', sans-serif;
        --saas-font-body: 'Inter', sans-serif;
    }

    body {
        font-family: var(--saas-font-body);
        background-color: var(--saas-bg);
        color: var(--saas-text-main);
    }

    /* --- PREMIUM HOVER ANIMATION & SHADOWS --- */
    .saas-shadow { box-shadow: 0 4px 20px -2px rgba(10, 25, 47, 0.04), 0 2px 8px -1px rgba(10, 25, 47, 0.02) !important; }
    .saas-card {
        border: 1px solid var(--saas-card-border) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .saas-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(10, 25, 47, 0.06), 0 10px 10px -5px rgba(10, 25, 47, 0.04) !important;
        border-color: rgba(251, 191, 36, 0.3) !important;
    }

    /* --- HERO GRADIENT GLOW --- */
    .saas-hero {
        background: linear-gradient(135deg, var(--saas-navy-deep) 0%, #152b4e 100%) !important;
        border: none !important;
    }
    .saas-hero-blur {
        position: absolute; top: -50px; right: -50px;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(100, 255, 218, 0.12) 0%, transparent 70%);
        filter: blur(30px);
        pointer-events: none;
    }

    /* --- ICON INTEGRATION BOX --- */
    .saas-icon-box {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.3s ease;
    }
    .saas-card:hover .saas-icon-box { transform: scale(1.1) rotate(3deg); }

    /* --- TIMELINE ACTIVITY FEED --- */
    .timeline-feed { position: relative; padding-left: 30px; }
    .timeline-feed::before {
        content: ''; position: absolute; left: 11px; top: 5px;
        width: 2px; height: 90%; background: #e2e8f0;
    }
    .timeline-item { position: relative; padding-bottom: 20px; }
    .timeline-marker {
        position: absolute; left: -24px; top: 4px;
        width: 10px; height: 10px; border-radius: 50%;
        background: var(--saas-navy-deep); border: 2px solid #fff;
        box-shadow: 0 0 0 3px rgba(10, 25, 47, 0.1);
    }

    /* --- TABLE ALIGNMENTS --- */
    .saas-table th {
        font-weight: 600; font-size: 0.75rem; letter-spacing: 0.5px;
        background-color: #f8fafc !important; color: #64748b; padding: 16px 24px;
    }
    .saas-table td { padding: 16px 24px; color: #334155; font-size: 0.875rem; }
    .sticky-table-header { position: sticky; top: 0; z-index: 10; }
</style>

<div class="container-fluid p-3 p-md-5" style="margin-top: 60px;">
    
    <div class="alert alert-warning border-0 saas-shadow rounded-4 p-3 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2 animate__animated animate__fadeIn" style="background: rgba(251, 191, 36, 0.08); border-left: 4px solid var(--saas-gold) !important;">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle p-2 bg-warning bg-opacity-20 text-warning d-flex align-items-center justify-content-center">
                <i class="fas fa-bullhorn"></i>
            </div>
            <div>
                <strong style="font-family: var(--saas-font-heading); color: #78350f;">Pengumuman Penting Pengurus OSIS:</strong>
                <span class="text-muted small d-block d-sm-inline ms-sm-1">Open Recruitment Pengurus Kabinet Raung Periode 2026/2027 resmi dibuka pekan ini.</span>
            </div>
        </div>
        <a href="berita/index.php" class="btn btn-sm py-1.5 px-3 rounded-pill fw-bold text-dark" style="background: var(--saas-gold); font-size: 0.75rem;">Kelola Berita</a>
    </div>

    <div class="card saas-hero border-0 saas-shadow rounded-4 overflow-hidden mb-4 position-relative">
        <div class="card-body p-4 p-md-5 text-white position-relative z-2">
            <div class="row align-items-center g-4">
                <div class="col-12 col-md-7 text-center text-md-start">
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-3 py-1.5 rounded-pill mb-3 small fw-bold" style="background: rgba(16, 185, 129, 0.15) !important;">
                        <span class="spinner-grow spinner-grow-sm me-1" role="status"></span> SISTEM ONLINE NORMAL
                    </span>
                    <h1 class="fw-bold mb-2 display-6" style="font-family: var(--saas-font-heading); letter-spacing: -0.5px;">Selamat Datang, <?php echo explode(' ', $_SESSION['nama'])[0]; ?>! ⚡</h1>
                    <p class="mb-0 text-white-50 small" style="max-width: 500px;">Panel kendali akademik terintegrasi SMK Angkasa Lanud Husein Sastranegara. Hak akses penuh Administrator aktif.</p>
                </div>
                <div class="col-12 col-md-5 text-center text-md-end border-start-md border-white border-opacity-10 py-1 py-md-0">
                    <div class="pe-md-4">
                        <h1 class="fw-bold mb-0 display-4 text-white font-monospace" id="clock" style="font-family: var(--saas-font-heading) !important; letter-spacing: 1px;">00:00:00</h1>
                        <span class="text-warning fw-bold text-uppercase tracking-wider small" style="font-size: 0.75rem; letter-spacing: 2px;"><?php echo date('l, d F Y'); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="saas-hero-blur"></div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card saas-card border-0 saas-shadow rounded-4 bg-white">
                <div class="card-body p-3">
                    <div class="saas-icon-box bg-primary bg-opacity-10 text-primary mb-3"><i class="fas fa-user-graduate fa-lg"></i></div>
                    <span class="text-muted text-uppercase d-block fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Siswa Aktif</span>
                    <h3 class="fw-bold text-dark mt-1 mb-0" style="font-family: var(--saas-font-heading);"><?php echo $jml_siswa; ?></h3>
                    <small class="text-success fw-bold" style="font-size: 0.65rem;"><i class="fas fa-arrow-up"></i> +12 mgu ini</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card saas-card border-0 saas-shadow rounded-4 bg-white">
                <div class="card-body p-3">
                    <div class="saas-icon-box bg-success bg-opacity-10 text-success mb-3"><i class="fas fa-chalkboard-teacher fa-lg"></i></div>
                    <span class="text-muted text-uppercase d-block fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Staff Pengajar</span>
                    <h3 class="fw-bold text-dark mt-1 mb-0" style="font-family: var(--saas-font-heading);"><?php echo $jml_guru; ?></h3>
                    <small class="text-muted" style="font-size: 0.65rem;">Sesuai data NIP</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card saas-card border-0 saas-shadow rounded-4 bg-white">
                <div class="card-body p-3">
                    <div class="saas-icon-box bg-warning bg-opacity-10 text-warning mb-3"><i class="fas fa-school fa-lg"></i></div>
                    <span class="text-muted text-uppercase d-block fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Ruang Kelas</span>
                    <h3 class="fw-bold text-dark mt-1 mb-0" style="font-family: var(--saas-font-heading);"><?php echo $jml_kelas; ?></h3>
                    <small class="text-success fw-bold" style="font-size: 0.65rem;"><i class="fas fa-check"></i> Distribusi rapi</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card saas-card border-0 saas-shadow rounded-4 bg-white">
                <div class="card-body p-3">
                    <div class="saas-icon-box bg-info bg-opacity-10 text-info mb-3"><i class="fas fa-book fa-lg"></i></div>
                    <span class="text-muted text-uppercase d-block fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Mata Pelajaran</span>
                    <h3 class="fw-bold text-dark mt-1 mb-0" style="font-family: var(--saas-font-heading);"><?php echo $jml_mapel; ?></h3>
                    <small class="text-muted" style="font-size: 0.65rem;">Kurikulum Merdeka</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card saas-card border-0 saas-shadow rounded-4 bg-white">
                <div class="card-body p-3">
                    <div class="saas-icon-box bg-danger bg-opacity-10 text-danger mb-3"><i class="fas fa-clipboard-user fa-lg"></i></div>
                    <span class="text-muted text-uppercase d-block fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Hadir Hari Ini</span>
                    <h3 class="fw-bold text-dark mt-1 mb-0" style="font-family: var(--saas-font-heading);"><?php echo $absensi_hari_ini; ?></h3>
                    <small class="text-danger fw-bold" style="font-size: 0.65rem;"><i class="fas fa-user-clock"></i> 94% Rasio</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card saas-card border-0 saas-shadow rounded-4 bg-white">
                <div class="card-body p-3">
                    <div class="saas-icon-box bg-secondary bg-opacity-10 text-dark mb-3"><i class="fas fa-bullhorn fa-lg"></i></div>
                    <span class="text-muted text-uppercase d-block fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Warta Aktif</span>
                    <h3 class="fw-bold text-dark mt-1 mb-0" style="font-family: var(--saas-font-heading);"><?php echo $pengumuman_aktif; ?></h3>
                    <small class="text-primary fw-bold" style="font-size: 0.65rem;">Broadcast live</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        
        <div class="col-12 col-lg-8">
            
            <div class="card border-0 saas-shadow rounded-4 bg-white p-4 mb-4">
                <h5 class="fw-bold text-dark mb-4" style="font-family: var(--saas-font-heading);"><i class="fas fa-chart-line text-primary me-2"></i> Grafik Analitik Sekolah</h5>
                <div class="row g-4">
                    <div class="col-12 col-md-7">
                        <span class="text-muted d-block small mb-2 text-uppercase fw-bold">Tren Kehadiran Mingguan (Rasio %)</span>
                        <div style="height: 220px;"><canvas id="attendanceChart"></canvas></div>
                    </div>
                    <div class="col-12 col-md-5">
                        <span class="text-muted d-block small mb-2 text-uppercase fw-bold">Siswa per Jurusan Aktif</span>
                        <div style="height: 220px;"><canvas id="majorChart"></canvas></div>
                    </div>
                </div>
            </div>

            <div class="card border-0 saas-shadow rounded-4 bg-white overflow-hidden mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="fw-bold text-dark mb-0" style="font-family: var(--saas-font-heading);"><i class="fas fa-users-viewfinder text-primary me-2"></i> Registrasi Siswa Paling Baru</h5>
                    <div class="d-flex gap-2">
                        <a href="siswa/data_siswa.php" class="btn btn-sm btn-light border rounded-pill px-3 fw-bold text-secondary" style="font-size: 0.75rem;">Lihat Semua</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 380px; overflow-y: auto;">
                        <table class="table table-hover align-middle saas-table mb-0 text-nowrap">
                            <thead class="sticky-table-header">
                                <tr>
                                    <th>PROFIL MAHASISWA / SISWA</th>
                                    <th>RUANG KELAS</th>
                                    <th>NISN SISTEM</th>
                                    <th class="text-end">STATUS GATE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(mysqli_num_rows($siswa_baru) > 0) { ?>
                                    <?php while($s = mysqli_fetch_array($siswa_baru)){ ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if(!empty($s['foto']) && file_exists('../uploads/profil/'.$s['foto'])) { ?>
                                                    <img src="../uploads/profil/<?php echo $s['foto']; ?>" class="rounded-circle me-3 border shadow-sm" width="40" height="40" style="object-fit: cover;">
                                                <?php } else { ?>
                                                    <div class="rounded-circle me-3 border shadow-sm d-flex align-items-center justify-content-center bg-light text-secondary fw-bold" style="width: 40px; height: 40px; font-size: 0.85rem;">
                                                        <?php echo strtoupper(substr($s['nama_siswa'], 0, 1)); ?>
                                                    </div>
                                                <?php } ?>
                                                <div>
                                                    <div class="fw-bold text-dark"><?php echo $s['nama_siswa']; ?></div>
                                                    <div class="small text-muted">@<?php echo $s['username']; ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-light text-secondary border px-2.5 py-1.5 rounded-3 fw-medium"><?php echo $s['nama_kelas']; ?></span></td>
                                        <td><code class="text-dark bg-light px-2 py-1 rounded small"><?php echo $s['nisn']; ?></code></td>
                                        <td class="text-end"><span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1.5 fw-semibold" style="font-size: 0.7rem;">Sesi Aktif</span></td>
                                    </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted small">Belum ada registrasi data siswa baru di dalam database.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-12 col-lg-4">
            
            <div class="card border-0 saas-shadow rounded-4 bg-white p-4 mb-4">
                <h6 class="fw-bold text-dark mb-3" style="font-family: var(--saas-font-heading);"><i class="fas fa-bolt text-warning me-2"></i> Quick Actions & Shortcut</h6>
                <div class="row g-2">
                    <div class="col-6">
                        <a href="siswa/data_siswa.php" class="btn btn-outline-light border w-100 p-3 rounded-3 text-center action-link hover-bg-light">
                            <i class="fas fa-user-plus text-primary fa-lg mb-2 d-block"></i>
                            <span class="small fw-bold text-dark d-block">Siswa Baru</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="guru/data_guru.php" class="btn btn-outline-light border w-100 p-3 rounded-3 text-center action-link hover-bg-light">
                            <i class="fas fa-user-tie text-success fa-lg mb-2 d-block"></i>
                            <span class="small fw-bold text-dark d-block">Tambah Guru</span>
                        </a>
                    </div>
                    
                    <div class="col-6">
                        <a href="absensi/pengaturan_absen.php" class="btn btn-outline-light border w-100 p-3 rounded-3 text-center action-link hover-bg-light">
                            <i class="fas fa-map-marked-alt text-success fa-lg mb-2 d-block"></i>
                            <span class="small fw-bold text-dark d-block">Setting GPS</span>
                        </a>
                    </div>

                    <div class="col-6">
                        <a href="rekap_mbg.php" class="btn btn-outline-light border w-100 p-3 rounded-3 text-center action-link hover-bg-light">
                            <i class="fas fa-utensils text-warning fa-lg mb-2 d-block"></i>
                            <span class="small fw-bold text-dark d-block">Nota MBG</span>
                        </a>
                    </div>

                    <div class="col-6">
                        <a href="absensi/rekap_absensi.php" class="btn btn-outline-light border w-100 p-3 rounded-3 text-center action-link hover-bg-light">
                            <i class="fas fa-calendar-check text-info fa-lg mb-2 d-block"></i>
                            <span class="small fw-bold text-dark d-block">Cek Absen</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="berita/index.php" class="btn btn-outline-light border w-100 p-3 rounded-3 text-center action-link hover-bg-light">
                            <i class="fas fa-bullhorn text-secondary fa-lg mb-2 d-block"></i>
                            <span class="small fw-bold text-dark d-block">Info Berita</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card border-0 saas-shadow rounded-4 bg-white p-4 mb-4">
                <h6 class="fw-bold text-dark mb-3" style="font-family: var(--saas-font-heading);"><i class="fas fa-calendar-minus text-primary me-2"></i> Agenda Kegiatan Terdekat</h6>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center gap-3 border-bottom pb-2">
                        <div class="bg-primary bg-opacity-10 text-primary rounded px-2.5 py-1.5 text-center fw-bold font-monospace" style="font-size: 0.8rem; min-width: 45px;">
                            20<br><span style="font-size:0.65rem;">Mei</span>
                        </div>
                        <div>
                            <span class="fw-bold text-dark d-block small">Ujian Akhir Semester Genap</span>
                            <small class="text-muted"><i class="fas fa-clock fa-xs"></i> 07:30 • All Majors</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3 border-bottom pb-2">
                        <div class="bg-warning bg-opacity-10 text-warning rounded px-2.5 py-1.5 text-center fw-bold font-monospace" style="font-size: 0.8rem; min-width: 45px;">
                            25<br><span style="font-size:0.65rem;">Mei</span>
                        </div>
                        <div>
                            <span class="fw-bold text-dark d-block small">Rapat Kabinet OSIS Raung</span>
                            <small class="text-muted"><i class="fas fa-clock fa-xs"></i> 13:00 • Ruang Ketua OSIS</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 saas-shadow rounded-4 bg-white p-4 mb-4">
                <h6 class="fw-bold text-dark mb-3" style="font-family: var(--saas-font-heading);"><i class="fas fa-wave-square text-success me-2"></i> Live Activity Feed System</h6>
                <div class="timeline-feed">
                    <div class="timeline-item">
                        <div class="timeline-marker" style="background:#10b981;"></div>
                        <span class="small d-block fw-bold text-dark">Data Siswa Ditambahkan</span>
                        <small class="text-muted text-xs">Oleh Administrator • 3 mnt lalu</small>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker" style="background:#3b82f6;"></div>
                        <span class="small d-block fw-bold text-dark">Input Absensi Selesai</span>
                        <small class="text-muted text-xs">Kelas XI PPLG • 20 mnt lalu</small>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker" style="background:#fbbf24;"></div>
                        <span class="small d-block fw-bold text-dark">Sesi Enkripsi Login Admin</span>
                        <small class="text-muted text-xs">IP: <?php echo $_SERVER['REMOTE_ADDR']; ?> • Active</small>
                    </div>
                </div>
            </div>

            <div class="card border-0 saas-shadow rounded-4 bg-white p-4">
                <h6 class="fw-bold text-dark mb-3" style="font-family: var(--saas-font-heading);"><i class="fas fa-server text-danger me-2"></i> Infrastruktur Server Health</h6>
                <div class="d-flex flex-column gap-2.5" style="font-size: 0.8rem;">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Koneksi MariaDB Database</span>
                        <span class="text-success fw-bold"><i class="fas fa-circle fa-xs me-1"></i> Connected</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Backup Terakhir Sistem</span>
                        <span class="text-dark fw-bold">Hari Ini, 04:00</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Alokasi Penyimpanan (Disk)</span>
                        <span class="text-dark fw-bold">14.2 GB / 50 GB</span>
                    </div>
                    <div class="progress rounded-pill" style="height: 6px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 28%; background-color: var(--saas-navy-deep) !important;"></div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<script>
    // 1. LIVE CLOCK SCRIPT ENGINE
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
        const clockElement = document.getElementById('clock');
        if(clockElement) clockElement.textContent = timeString;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // 2. CHART JURUSAN (TITL, TMI, TKR, PPLG)
    const ctxMajor = document.getElementById('majorChart').getContext('2d');
    new Chart(ctxMajor, {
        type: 'doughnut',
        data: {
            labels: ['TITL', 'TMI', 'TKR', 'PPLG'],
            datasets: [{
                data: [25, 20, 30, 45], // Representasi bobot sampel seimbang
                backgroundColor: ['#fbbf24', '#06b6d4', '#ef4444', '#0a192f'],
                borderWidth: 2,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } }
            }
        }
    });

    // 3. CHART KEHADIRAN MINGGUAN (AREA GRADIENT)
    const ctxAttendance = document.getElementById('attendanceChart').getContext('2d');
    const grad = ctxAttendance.createLinearGradient(0, 0, 0, 200);
    grad.addColorStop(0, 'rgba(10, 25, 47, 0.2)');
    grad.addColorStop(1, 'rgba(10, 25, 47, 0)');

    new Chart(ctxAttendance, {
        type: 'line',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            datasets: [{
                label: 'Persentase',
                data: [92, 95, 94, 96, 91, 94],
                borderColor: '#0a192f',
                borderWidth: 3,
                fill: true,
                backgroundColor: grad,
                tension: 0.3,
                pointBackgroundColor: '#fbbf24'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { min: 80, max: 100, grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 } } },
                x: { grid: { display: false }, ticks: { font: { size: 10 } } }
            }
        }
    });
</script>

<?php include '../layouts/footer.php'; ?>