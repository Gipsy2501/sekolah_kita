<?php
// 1. WAJIB panggil satpam di baris paling pertama sebelum kode HTML apa pun!
include '../cek_akses.php';
include '../../config/koneksi.php';

// Ambil data konfigurasi saat ini dari database
$config = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_pengaturan_absen WHERE id_pengaturan = 1"));

$pesan_sukses = false;
if (isset($_POST['update_config'])) {
    $jam_mulai   = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $lat         = mysqli_real_escape_string($koneksi, $_POST['latitude_sekolah']);
    $lon         = mysqli_real_escape_string($koneksi, $_POST['longitude_sekolah']);
    $radius      = intval($_POST['radius_meter']);

    // Prepared Statement untuk keamanan tingkat tinggi
    $stmt = $koneksi->prepare("UPDATE tb_pengaturan_absen SET jam_mulai=?, jam_selesai=?, latitude_sekolah=?, longitude_sekolah=?, radius_meter=? WHERE id_pengaturan=1");
    $stmt->bind_param("ssssi", $jam_mulai, $jam_selesai, $lat, $lon, $radius);
    
    if ($stmt->execute()) {
        $pesan_sukses = true;
        // Refresh data lokal setelah berhasil update
        $config = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_pengaturan_absen WHERE id_pengaturan = 1"));
    }
    $stmt->close();
}

$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$base_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . "/sekolah_kita/";

include '../../layouts/header.php';
include '../../layouts/sidebar.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="container-fluid p-3 p-md-5" style="margin-top: 60px;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1" style="font-family: 'Outfit';"><i class="fas fa-sliders-h text-primary me-2"></i> Pengaturan Sesi & Gerbang Absensi</h3>
            <p class="text-muted small mb-0">Kelola batas waktu operasional logistik dan koordinat geofencing secara terpusat.</p>
        </div>
    </div>

    <?php if ($pesan_sukses) { ?>
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4"><i class="fas fa-check-circle me-2"></i> Konfigurasi absensi dan peta geofencing berhasil diperbarui secara realtime!</div>
    <?php } ?>

    <div class="row g-4">
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100">
                <form method="POST">
                    <h5 class="fw-bold text-dark mb-4 border-bottom pb-2" style="font-family: 'Outfit';"><i class="fas fa-clock text-warning me-2"></i> Pembatasan Waktu Server</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-sm-6">
                            <label class="form-label small fw-bold text-muted">JAM MULAI ABSEN</label>
                            <input type="time" name="jam_mulai" class="form-control rounded-3" value="<?php echo $config['jam_mulai']; ?>" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label small fw-bold text-muted">JAM SELESAI (TIME-LOCK)</label>
                            <input type="time" name="jam_selesai" class="form-control rounded-3" value="<?php echo $config['jam_selesai']; ?>" required>
                        </div>
                    </div>

                    <h5 class="fw-bold text-dark mb-4 border-bottom pb-2" style="font-family: 'Outfit';"><i class="fas fa-map-marked-alt text-success me-2"></i> Koordinat Geofencing</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-sm-6">
                            <label class="form-label small fw-bold text-muted">LATITUDE SEKOLAH</label>
                            <input type="text" id="latInput" name="latitude_sekolah" class="form-control rounded-3 font-monospace" value="<?php echo $config['latitude_sekolah']; ?>" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label small fw-bold text-muted">LONGITUDE SEKOLAH</label>
                            <input type="text" id="lonInput" name="longitude_sekolah" class="form-control rounded-3 font-monospace" value="<?php echo $config['longitude_sekolah']; ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">RADIUS MAKSIMAL</label>
                            <div class="input-group">
                                <input type="number" id="radiusInput" name="radius_meter" class="form-control rounded-start-3" value="<?php echo $config['radius_meter']; ?>" required>
                                <span class="input-group-text rounded-end-3 bg-light text-muted fw-bold">Meter</span>
                            </div>
                        </div>
                    </div>

                    <div class="text-end pt-2">
                        <button type="submit" name="update_config" class="btn btn-primary w-100 py-2.5 rounded-3 fw-bold" style="background-color: #0a192f; border:none;">
                            <i class="fas fa-save me-2"></i> Terapkan Konfigurasi Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100">
                <h5 class="fw-bold text-dark mb-1" style="font-family: 'Outfit';"><i class="fas fa-eye text-info me-2"></i> Live Preview Geofencing Radius</h5>
                <p class="text-muted small mb-3">Klik langsung pada peta untuk menggeser lokasi sekolah atau ubah angka radius di form sebelah kiri untuk melihat visual jangkauan lingkaran secara interaktif.</p>
                
                <div id="map" class="rounded-4 border" style="height: 380px; width: 100%; z-index: 1;"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Ambil nilai koordinat awal dari database via PHP
    let initialLat = parseFloat("<?php echo $config['latitude_sekolah']; ?>");
    let initialLon = parseFloat("<?php echo $config['longitude_sekolah']; ?>");
    let initialRadius = parseInt("<?php echo $config['radius_meter']; ?>");

    // 1. Inisialisasi Peta (Koordinat awal, tingkat kedekatan zoom: 16)
    const map = L.map('map').setView([initialLat, initialLon], 16);

    // 2. Pasang Layer Desain Gambar Peta Terbuka OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // 3. Pasang Penanda Marker Tengah (Dibuat Draggable = bisa digeser pakai mouse)
    let marker = L.marker([initialLat, initialLon], { draggable: true }).addTo(map);

    // 4. Gambarkan Lingkaran Jangkauan Absen (Gaya SaaS Navy & Emas)
    let circle = L.circle([initialLat, initialLon], {
        color: '#0a192f',       // Garis luar Navy
        fillColor: '#fbbf24',   // Isian Emas
        fillOpacity: 0.15,      // Transparansi tipis biar jalanan tetap kelihatan
        radius: initialRadius
    }).addTo(map);

    // Fungsi pembantu untuk menyamakan perubahan komponen peta ke dalam kotak teks input form
    function updateInputs(lat, lon) {
        document.getElementById('latInput').value = lat.toFixed(6);
        document.getElementById('lonInput').value = lon.toFixed(6);
    }

    // EVENT 1: Ketika Admin mengetik atau mengubah angka radius di kotak form
    const radiusInput = document.getElementById('radiusInput');
    radiusInput.addEventListener('input', function() {
        let newRadius = parseInt(this.value) || 0;
        circle.setRadius(newRadius); // Visual lingkaran di peta langsung membesar/mengecil saat itu juga!
    });

    // EVENT 2: Sinkronisasi jika koordinat latitude/longitude diketik manual di form
    const latInput = document.getElementById('latInput');
    const lonInput = document.getElementById('lonInput');
    function syncMapFromInputs() {
        let nLat = parseFloat(latInput.value) || initialLat;
        let nLon = parseFloat(lonInput.value) || initialLon;
        const newLatLng = new L.LatLng(nLat, nLon);
        marker.setLatLng(newLatLng);
        circle.setLatLng(newLatLng);
        map.panTo(newLatLng);
    }
    latInput.addEventListener('input', syncMapFromInputs);
    lonInput.addEventListener('input', syncMapFromInputs);

    // EVENT 3: Ketika Peta di-klik langsung di titik manapun oleh Admin
    map.on('click', function(e) {
        const coords = e.latlng;
        marker.setLatLng(coords);
        circle.setLatLng(coords);
        updateInputs(coords.lat, coords.lng); // Form langsung terisi otomatis
    });

    // EVENT 4: Ketika Pin Marker digeser manual menggunakan mouse
    marker.on('dragend', function(e) {
        const coords = e.target.getLatLng();
        circle.setLatLng(coords);
        updateInputs(coords.lat, coords.lng); // Form langsung terisi otomatis
    });
</script>

<?php include '../../layouts/footer.php'; ?>