<?php
session_start();
include '../cek_akses.php';
include '../../config/koneksi.php';

$id_sekretaris = $_SESSION['id_user'];
$id_kelas_sekretaris = $_SESSION['id_kelas'];
$hari_ini = date('Y-m-d');
$jam_sekarang = date('H:i:s');

// 1. GATE 1: Filter Identitas - Pastikan siswa ini valid ber-role Sekretaris (is_pengurus = 1)
$cek_pengurus = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT is_pengurus FROM tb_siswa WHERE id_siswa='$id_sekretaris'"));
if (!$cek_pengurus || $cek_pengurus['is_pengurus'] != 1) {
    header("location:../index.php");
    exit();
}

// Ambil aturan operasional global dari database pengaturan
$config = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_pengaturan_absen WHERE id_pengaturan = 1"));

// 2. GATE 2: Filter Batasan Waktu Server (Time-Lock System)
$is_time_locked = false;
if ($jam_sekarang < $config['jam_mulai'] || $jam_sekarang > $config['jam_selesai']) {
    $is_time_locked = true;
}

// ENGINE PROSES: SIMPAN DATA MASSAL SATU KELAS
if (isset($_POST['simpan_absen_massal']) && !$is_time_locked) {
    $statuses    = $_POST['status_absen'] ?? [];
    $keterangans = $_POST['keterangan_absen'] ?? [];

    foreach ($statuses as $id_siswa_target => $status_value) {
        $ket_value = mysqli_real_escape_string($koneksi, $keterangans[$id_siswa_target] ?? '');

        $cek_exist = mysqli_query($koneksi, "SELECT id_absensi FROM tb_absensi WHERE id_siswa='$id_siswa_target' AND tanggal='$hari_ini'");
        
        if (mysqli_num_rows($cek_exist) > 0) {
            mysqli_query($koneksi, "UPDATE tb_absensi SET jam_masuk='$jam_sekarang', status='$status_value', keterangan='$ket_value' \r
                                    WHERE id_siswa='$id_siswa_target' AND tanggal='$hari_ini'");
        } else {
            mysqli_query($koneksi, "INSERT INTO tb_absensi (id_siswa, tanggal, jam_masuk, status, keterangan) \r
                                    VALUES ('$id_siswa_target', '$hari_ini', '$jam_sekarang', '$status_value', '$ket_value')");
        }
    }
    header("location:rekap_saya.php?pesan=absen_kelas_sukses");
    exit();
}

$detail_kelas = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama_kelas FROM tb_kelas WHERE id_kelas='$id_kelas_sekretaris'"));
$daftar_siswa = mysqli_query($koneksi, "SELECT s.id_siswa, s.nama_siswa, s.nisn, a.status, a.keterangan 
                                        FROM tb_siswa s \r
                                        LEFT JOIN tb_absensi a ON s.id_siswa = a.id_siswa AND a.tanggal = '$hari_ini' 
                                        WHERE s.id_kelas = '$id_kelas_sekretaris' \r
                                        ORDER BY s.nama_siswa ASC");

include '../../layouts/header.php';
include '../../layouts/sidebar.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
    .heading-title { font-family: 'Outfit', sans-serif; color: #0a192f; font-weight: 700; }
    .saas-card { border: 1px solid rgba(148, 163, 184, 0.12) !important; box-shadow: 0 4px 20px rgba(10, 25, 47, 0.02); }
    .bg-navy-btn { background-color: #0a192f !important; color: white !important; border: none; }
    .bg-navy-btn:hover { background-color: #112240 !important; }
</style>

<div class="container-fluid p-3 p-md-5" style="margin-top: 50px;">
    
    <?php if ($is_time_locked) { ?>
        <div class="card border-0 shadow-sm rounded-4 p-4 text-white mb-4 animate__animated animate__headShake" style="background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white bg-opacity-20 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width:60px; height:60px;">
                    <i class="fas fa-lock fa-2x"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1" style="font-family:'Outfit';">Sistem Pengisian Absensi Terkunci!</h5>
                    <p class="mb-0 small text-white-50">Batas waktu operasional sinkronisasi kehadiran (Pukul <?php echo date('H:i', strtotime($config['jam_mulai'])); ?> s/d <?php echo date('H:i', strtotime($config['jam_selesai'])); ?> WIB) telah berakhir. Manifestasi jatah makanan MBG telah dikunci ke bagian dapur sekolah.</p>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div id="gps-status-bar" class="card border-0 shadow-sm rounded-4 p-3 mb-4 text-white bg-secondary">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div id="gps-icon-box" class="bg-white bg-opacity-20 rounded p-2"><i class="fas fa-satellite-dish fa-spin"></i></div>
                    <div>
                        <strong id="gps-title" class="d-block">Meminta Akses Lokasi (GPS)...</strong>
                        <small id="gps-subtitle" class="text-white-50">Menghitung jarak koordinat HP Anda ke Gerbang Satelit SMK Angkasa.</small>
                    </div>
                </div>
                <div id="gps-distance-badge" class="badge bg-white text-dark rounded-pill px-3 py-2 fw-bold shadow-sm">- meter</div>
            </div>
        </div>
    <?php } ?>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h3 class="heading-title mb-1"><i class="fas fa-edit text-primary me-2"></i> Manajemen Absen Kelas (<?php echo $detail_kelas['nama_kelas']; ?>)</h3>
            <p class="text-muted small mb-0">Hanya Sekretaris/Ketua Kelas yang memiliki otoritas memproses daftar ini.</p>
        </div>
    </div>

    <form id="mainFormAbsen" method="POST">
        <div class="card border-0 saas-shadow rounded-4 bg-white overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center py-3" width="5%">No</th>
                                <th class="py-3">Nama Siswa / NISN</th>
                                <th class="py-3" width="40%">Status Kehadiran</th>
                                <th class="py-3" width="25%">Catatan Alasan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while($row = mysqli_fetch_array($daftar_siswa)) {
                                $current_status = $row['status'] ?? 'Hadir';
                            ?>
                            <tr>
                                <td class="text-center text-muted small"><?php echo $no++; ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?php echo $row['nama_siswa']; ?></div>
                                    <code class="text-muted font-monospace" style="font-size:0.75rem;"><?php echo $row['nisn']; ?></code>
                                </td>
                                <td>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check" name="status_absen[<?php echo $row['id_siswa']; ?>]" id="H-<?php echo $row['id_siswa']; ?>" value="Hadir" <?php echo ($current_status == 'Hadir') ? 'checked' : ''; ?> <?php echo $is_time_locked ? 'disabled' : ''; ?>>
                                        <label class="btn btn-sm btn-outline-success py-2 fw-semibold" for="H-<?php echo $row['id_siswa']; ?>">Hadir</label>

                                        <input type="radio" class="btn-check" name="status_absen[<?php echo $row['id_siswa']; ?>]" id="T-<?php echo $row['id_siswa']; ?>" value="Terlambat" <?php echo ($current_status == 'Terlambat') ? 'checked' : ''; ?> <?php echo $is_time_locked ? 'disabled' : ''; ?>>
                                        <label class="btn btn-sm btn-outline-primary py-2 fw-semibold" for="T-<?php echo $row['id_siswa']; ?>">Telat</label>

                                        <input type="radio" class="btn-check" name="status_absen[<?php echo $row['id_siswa']; ?>]" id="S-<?php echo $row['id_siswa']; ?>" value="Sakit" <?php echo ($current_status == 'Sakit') ? 'checked' : ''; ?> <?php echo $is_time_locked ? 'disabled' : ''; ?>>
                                        <label class="btn btn-sm btn-outline-info py-2 fw-semibold" for="S-<?php echo $row['id_siswa']; ?>">Sakit</label>

                                        <input type="radio" class="btn-check" name="status_absen[<?php echo $row['id_siswa']; ?>]" id="I-<?php echo $row['id_siswa']; ?>" value="Izin" <?php echo ($current_status == 'Izin') ? 'checked' : ''; ?> <?php echo $is_time_locked ? 'disabled' : ''; ?>>
                                        <label class="btn btn-sm btn-outline-warning py-2 fw-semibold" for="I-<?php echo $row['id_siswa']; ?>">Izin</label>

                                        <input type="radio" class="btn-check" name="status_absen[<?php echo $row['id_siswa']; ?>]" id="A-<?php echo $row['id_siswa']; ?>" value="Alpha" <?php echo ($current_status == 'Alpha') ? 'checked' : ''; ?> <?php echo $is_time_locked ? 'disabled' : ''; ?>>
                                        <label class="btn btn-sm btn-outline-danger py-2 fw-semibold" for="A-<?php echo $row['id_siswa']; ?>">Alpha</label>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="keterangan_absen[<?php echo $row['id_siswa']; ?>]" class="form-control form-control-sm rounded-3" placeholder="Alasan..." value="<?php echo $row['keterangan']; ?>" autocomplete="off" <?php echo $is_time_locked ? 'disabled' : ''; ?>>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if (!$is_time_locked) { ?>
                <div class="card-footer bg-light p-4 border-0 text-end">
                    <button type="submit" name="simpan_absen_massal" id="btnSubmitMassal" class="btn bg-navy-btn px-5 py-2.5 rounded-3 fw-bold shadow-sm" disabled>
                        <i class="fas fa-cloud-upload-alt me-2"></i> Kunci & Kirim Data Kelas
                    </button>
                </div>
            <?php } ?>
        </div>
    </form>
</div>

<script>
    const TARGET_LAT = parseFloat("<?php echo $config['latitude_sekolah']; ?>");
    const TARGET_LON = parseFloat("<?php echo $config['longitude_sekolah']; ?>");
    const MAX_RADIUS = parseInt("<?php echo $config['radius_meter']; ?>");
    const isTimeLocked = <?php echo $is_time_locked ? 'true' : 'false'; ?>;

    if (!isTimeLocked && navigator.geolocation) {
        const statusBar = document.getElementById('gps-status-bar');
        const gpsTitle = document.getElementById('gps-title');
        const gpsSubtitle = document.getElementById('gps-subtitle');
        const gpsIconBox = document.getElementById('gps-icon-box');
        const distanceBadge = document.getElementById('gps-distance-badge');
        const btnSubmit = document.getElementById('btnSubmitMassal');

        function getMetres(lat1, lon1, lat2, lon2) {
            const R = 6371000;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLon/2) * Math.sin(dLon/2);
            return R * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)));
        }

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const dist = Math.round(getMetres(pos.coords.latitude, pos.coords.longitude, TARGET_LAT, TARGET_LON));
                distanceBadge.textContent = dist + " meter";

                if (dist <= MAX_RADIUS) {
                    statusBar.classList.replace('bg-secondary', 'bg-success');
                    gpsIconBox.innerHTML = '<i class="fas fa-map-marker-alt fa-lg"></i>';
                    gpsTitle.textContent = "Akses Diverifikasi (Di Dalam Area Sekolah)";
                    gpsSubtitle.textContent = "Jarak Anda aman. Silakan lakukan penyelarasan kehadiran kelas.";
                    if(btnSubmit) btnSubmit.removeAttribute('disabled');
                } else {
                    statusBar.classList.replace('bg-secondary', 'bg-danger');
                    gpsIconBox.innerHTML = '<i class="fas fa-user-slash fa-lg"></i>';
                    gpsTitle.textContent = "Akses Ditolak! Anda Berada di Luar Radius Sekolah";
                    gpsSubtitle.textContent = "Sistem mengunci form ini. Pengisian wajib dilakukan secara fisik di area sekolah.";
                }
            },
            (err) => {
                statusBar.classList.replace('bg-secondary', 'bg-danger');
                gpsIconBox.innerHTML = '<i class="fas fa-exclamation-triangle fa-lg"></i>';
                gpsTitle.textContent = "Gagal Mengambil Lokasi GPS";
                gpsSubtitle.textContent = "Harap aktifkan dan izinkan akses lokasi pada browser handphone Anda.";
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    }
</script>

<?php include '../../layouts/footer.php'; ?>