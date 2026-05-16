<?php
session_start();
include '../../config/koneksi.php'; // Jalur koneksi
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

// Proteksi Ganda: Pastikan user sudah login
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("location:../../auth/login.php?pesan=belum_login");
    exit();
}

// Ambil data role aktif dari session
$role_aktif = $_SESSION['role'];
$id_user_aktif = $_SESSION['id_user'];

// Set Tanggal Default (Hari ini) untuk Admin/Guru
$tanggal_pilih = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
$kelas_pilih   = isset($_GET['kelas']) ? $_GET['kelas'] : '';

// --- LOGIKA HITUNG COUNTER STATISTIK (AKTIF REALTIME) ---
$count_hadir = 0; $count_sakit = 0; $count_izin = 0; $count_alpha = 0; $count_belum = 0;

if ($role_aktif == 'admin' || $role_aktif == 'guru') {
    // Jalur Query Perhitungan Statistik Realtime untuk Admin/Guru
    $sql_stat = "SELECT a.status, COUNT(*) as jumlah FROM tb_siswa s 
                 LEFT JOIN tb_absensi a ON s.id_siswa = a.id_siswa AND a.tanggal = '$tanggal_pilih' ";
    if ($kelas_pilih != '') { $sql_stat .= " WHERE s.id_kelas = '$kelas_pilih' "; }
    $sql_stat .= " GROUP BY a.status";
    
    $res_stat = mysqli_query($koneksi, $sql_stat);
    while ($st = mysqli_fetch_assoc($res_stat)) {
        if ($st['status'] == 'Hadir') $count_hadir = $st['jumlah'];
        elseif ($st['status'] == 'Sakit') $count_sakit = $st['jumlah'];
        elseif ($st['status'] == 'Izin') $count_izin = $st['jumlah'];
        elseif ($st['status'] == 'Alpha') $count_alpha = $st['jumlah'];
        else $count_belum = $st['jumlah'];
    }
} else {
    // Jalur Perhitungan Statistik Personal untuk Siswa
    $sql_stat = "SELECT status, COUNT(*) as jumlah FROM tb_absensi WHERE id_siswa = '$id_user_aktif' GROUP BY status";
    $res_stat = mysqli_query($koneksi, $sql_stat);
    while ($st = mysqli_fetch_assoc($res_stat)) {
        if ($st['status'] == 'Hadir') $count_hadir = $st['jumlah'];
        elseif ($st['status'] == 'Sakit') $count_sakit = $st['jumlah'];
        elseif ($st['status'] == 'Izin') $count_izin = $st['jumlah'];
        elseif ($st['status'] == 'Alpha') $count_alpha = $st['jumlah'];
    }
}
?>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
    .heading-title { font-family: 'Outfit', sans-serif; color: #0a192f; font-weight: 700; }
    .saas-card { border: 1px solid rgba(148, 163, 184, 0.12) !important; box-shadow: 0 4px 20px rgba(10, 25, 47, 0.02); }
    .bg-navy-btn { background-color: #0a192f !important; color: white !important; border: none; }
    .bg-navy-btn:hover { background-color: #112240 !important; }
    
    /* Status Badges Premium */
    .badge-hadir { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
    .badge-sakit { background: rgba(6, 182, 212, 0.1); color: #06b6d4; border: 1px solid rgba(6, 182, 212, 0.2); }
    .badge-izin { background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.2); }
    .badge-alpha { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
    .badge-belum { background: rgba(100, 116, 139, 0.1); color: #64748b; border: 1px solid rgba(100, 116, 139, 0.2); }

    @media (max-width: 768px) {
        .responsive-table header { display: none; }
        .saas-table thead { display: none; }
        .saas-table tbody tr { display: flex; flex-direction: column; padding: 16px; border-bottom: 1px solid #edf2f7; }
        .saas-table tbody td { border: none; padding: 6px 0; text-align: left !important; }
    }
</style>

<div class="container-fluid p-3 p-md-5" style="margin-top: 50px;">
    
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h3 class="heading-title mb-1"><i class="fas fa-calendar-check text-warning me-2"></i> Rekapitulasi Absensi Digital</h3>
            <p class="text-muted small mb-0">
                <?php echo ($role_aktif == 'siswa') ? 'Riwayat kehadiran Anda secara keseluruhan di sistem.' : 'Pantau dan filter kehadiran siswa SMK Angkasa secara realtime.'; ?>
            </p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-sm-4 col-md-2.5 col-xl-2">
            <div class="card saas-card rounded-4 p-3 bg-white">
                <small class="text-muted text-uppercase fw-bold d-block" style="font-size:0.65rem;">Hadir</small>
                <h4 class="fw-bold text-success mb-0 mt-1"><?php echo $count_hadir; ?> <span class="small text-muted fw-normal" style="font-size:10px;">kali</span></h4>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-md-2.5 col-xl-2">
            <div class="card saas-card rounded-4 p-3 bg-white">
                <small class="text-muted text-uppercase fw-bold d-block" style="font-size:0.65rem;">Sakit</small>
                <h4 class="fw-bold text-info mb-0 mt-1"><?php echo $count_sakit; ?> <span class="small text-muted fw-normal" style="font-size:10px;">kali</span></h4>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-md-2.5 col-xl-2">
            <div class="card saas-card rounded-4 p-3 bg-white">
                <small class="text-muted text-uppercase fw-bold d-block" style="font-size:0.65rem;">Izin</small>
                <h4 class="fw-bold text-warning mb-0 mt-1"><?php echo $count_izin; ?> <span class="small text-muted fw-normal" style="font-size:10px;">kali</span></h4>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-md-2.5 col-xl-2">
            <div class="card saas-card rounded-4 p-3 bg-white">
                <small class="text-muted text-uppercase fw-bold d-block" style="font-size:0.65rem;">Alpha</small>
                <h4 class="fw-bold text-danger mb-0 mt-1"><?php echo $count_alpha; ?> <span class="small text-muted fw-normal" style="font-size:10px;">kali</span></h4>
            </div>
        </div>
        <?php if($role_aktif != 'siswa') { ?>
        <div class="col-12 col-sm-4 col-md-2.5 col-xl-4">
            <div class="card saas-card rounded-4 p-3 bg-white border-start border-secondary border-3">
                <small class="text-muted text-uppercase fw-bold d-block" style="font-size:0.65rem;">Belum Melakukan Absen Hari Ini</small>
                <h5 class="fw-bold text-secondary mb-0 mt-1"><i class="fas fa-user-clock me-1"></i> Data Terpantau</h5>
            </div>
        </div>
        <?php } ?>
    </div>

    <?php if($role_aktif == 'admin' || $role_aktif == 'guru') { ?>
    <div class="card border-0 saas-shadow rounded-4 mb-4 bg-white">
        <div class="card-body p-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label fw-bold small text-muted">PILIH TANGGAL ABSENSI</label>
                    <input type="date" name="tanggal" class="form-control rounded-3" value="<?php echo $tanggal_pilih; ?>">
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label fw-bold small text-muted">SORTIR BERDASARKAN KELAS</label>
                    <select name="kelas" class="form-select rounded-3">
                        <option value="">-- Tampilkan Semua Kelas --</option>
                        <?php
                        $q_kelas = mysqli_query($koneksi, "SELECT * FROM tb_kelas ORDER BY nama_kelas ASC");
                        while($k = mysqli_fetch_array($q_kelas)){
                            $selected = ($kelas_pilih == $k['id_kelas']) ? 'selected' : '';
                            echo "<option value='$k[id_kelas]' $selected>$k[nama_kelas]</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <button type="submit" class="btn bg-navy-btn w-100 fw-bold py-2 rounded-3"><i class="fas fa-filter me-2"></i> Jalankan Filter Log</button>
                </div>
            </form>
        </div>
    </div>
    <?php } ?>

    <div class="card border-0 saas-shadow rounded-4 bg-white overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle saas-table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center py-3" width="5%">No</th>
                            <?php if($role_aktif != 'siswa') { ?>
                                <th class="py-3">Informasi Siswa</th>
                                <th class="py-3">Kelas</th>
                            <?php } else { ?>
                                <th class="py-3">Tanggal Pelaksanaan</th>
                            <?php } ?>
                            <th class="py-3">Stempel Jam Masuk</th>
                            <th class="text-center py-3">Status Kehadiran</th>
                            <th class="py-3">Catatan Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // PERBAIKAN LOGIC & BUG FIX KOLOM (MENGGUNAKAN NISN BUKAN NIS)
                        if ($role_aktif == 'admin' || $role_aktif == 'guru') {
                            // Query Global Admin/Guru
                            $query_sql = "SELECT s.nama_siswa, s.nisn, k.nama_kelas, a.jam_masuk, a.status, a.keterangan 
                                          FROM tb_siswa s 
                                          JOIN tb_kelas k ON s.id_kelas = k.id_kelas \r
                                          LEFT JOIN tb_absensi a ON s.id_siswa = a.id_siswa AND a.tanggal = '$tanggal_pilih' ";

                            if($kelas_pilih != ''){
                                $query_sql .= " WHERE s.id_kelas = '$kelas_pilih' ";
                            }
                            $query_sql .= " ORDER BY k.nama_kelas ASC, s.nama_siswa ASC";
                        } else {
                            // Query Tertutup Khusus Siswa (Melihat riwayat absensi miliknya sendiri dari awal-akhir)
                            $query_sql = "SELECT a.tanggal, a.jam_masuk, a.status, a.keterangan 
                                          FROM tb_absensi a 
                                          WHERE a.id_siswa = '$id_user_aktif' 
                                          ORDER BY a.tanggal DESC";
                        }

                        $tampil = mysqli_query($koneksi, $query_sql);
                        $no = 1;

                        if(mysqli_num_rows($tampil) > 0){
                            while($data = mysqli_fetch_array($tampil)){
                                
                                $status = $data['status'];
                                $badge_class = 'badge-belum';
                                $status_text = 'Belum Absen';

                                if($status == 'Hadir') { $badge_class = 'badge-hadir'; $status_text = 'HADIR'; }
                                elseif($status == 'Sakit') { $badge_class = 'badge-sakit'; $status_text = 'SAKIT'; }
                                elseif($status == 'Izin') { $badge_class = 'badge-izin'; $status_text = 'IZIN'; }
                                elseif($status == 'Alpha') { $badge_class = 'badge-alpha'; $status_text = 'ALPHA'; }
                        ?>
                        <tr>
                            <td class="text-center text-muted small"><?php echo $no++; ?></td>
                            
                            <?php if($role_aktif != 'siswa') { ?>
                                <td>
                                    <div class="fw-bold text-dark" style="font-size:0.9rem;"><?php echo $data['nama_siswa']; ?></div>
                                    <div class="small text-muted font-monospace" style="font-size:0.75rem;"><?php echo $data['nisn']; ?></div>
                                </td>
                                <td><span class="badge bg-light text-secondary border px-2.5 py-1.5 rounded-3 fw-medium"><?php echo $data['nama_kelas']; ?></span></td>
                            <?php } else { ?>
                                <td class="fw-bold text-dark"><i class="far fa-calendar-alt me-2 text-muted"></i><?php echo date('d M Y', strtotime($data['tanggal'])); ?></td>
                            <?php } ?>

                            <td class="font-monospace text-secondary small">
                                <?php echo ($data['jam_masuk']) ? '<i class="far fa-clock me-1"></i>' . $data['jam_masuk'] . ' WIB' : '<span class="text-muted">-</span>'; ?>
                            </td>
                            <td class="text-md-center">
                                <span class="badge <?php echo $badge_class; ?> rounded-pill px-3 py-1.5 fw-bold" style="font-size: 10px; letter-spacing:0.5px;">
                                    <?php echo $status_text; ?>
                                </span>
                            </td>
                            <td class="small text-muted">
                                <?php echo ($data['keterangan']) ? $data['keterangan'] : '<span class="text-white-50">-</span>'; ?>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center py-5 text-muted small'><i class='fas fa-folder-open fa-2x d-block mb-2 opacity-50'></i> Belum memiliki rekaman riwayat absensi harian.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../../layouts/footer.php'; ?>