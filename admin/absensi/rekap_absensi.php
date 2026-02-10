<?php
session_start();
include '../../config/koneksi.php';
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

// Set Tanggal Default (Hari ini)
$tanggal_pilih = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
$kelas_pilih   = isset($_GET['kelas']) ? $_GET['kelas'] : '';

?>

<div class="container-fluid p-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold text-dark"><i class="fas fa-calendar-check me-2 text-warning"></i> Monitoring Absensi</h4>
            <p class="text-muted small mb-0">Pantau kehadiran siswa secara realtime.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-muted">TANGGAL</label>
                    <input type="date" name="tanggal" class="form-control" value="<?php echo $tanggal_pilih; ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-muted">FILTER KELAS</label>
                    <select name="kelas" class="form-select">
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
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary bg-navy w-100 fw-bold"><i class="fas fa-filter me-2"></i> FILTER DATA</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center py-3">No</th>
                            <th class="py-3">Nama Siswa</th>
                            <th class="py-3">Kelas</th>
                            <th class="py-3">Jam Masuk</th>
                            <th class="text-center py-3">Status Kehadiran</th>
                            <th class="py-3">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // LOGIC QUERY CANGGIH (LEFT JOIN)
                        // Kita ambil SEMUA siswa dulu, baru dicocokkan dengan tabel absensi pada tanggal yg dipilih
                        // Jadi siswa yang BELUM ABSEN tetap muncul namanya
                        
                        $query_sql = "SELECT s.nama_siswa, s.nis, k.nama_kelas, a.jam_masuk, a.status, a.keterangan 
                                      FROM tb_siswa s 
                                      JOIN tb_kelas k ON s.id_kelas = k.id_kelas 
                                      LEFT JOIN tb_absensi a ON s.id_siswa = a.id_siswa AND a.tanggal = '$tanggal_pilih' ";

                        // Tambahan Filter Kelas jika dipilih
                        if($kelas_pilih != ''){
                            $query_sql .= " WHERE s.id_kelas = '$kelas_pilih' ";
                        }

                        $query_sql .= " ORDER BY k.nama_kelas ASC, s.nama_siswa ASC";

                        $tampil = mysqli_query($koneksi, $query_sql);
                        $no = 1;

                        if(mysqli_num_rows($tampil) > 0){
                            while($data = mysqli_fetch_array($tampil)){
                                // Tentukan Warna Badge Status
                                $status = $data['status'];
                                $badge_class = 'bg-secondary'; // Default (Belum Absen)
                                $status_text = 'Belum Absen';

                                if($status == 'Hadir') { $badge_class = 'bg-success'; $status_text = 'HADIR'; }
                                elseif($status == 'Sakit') { $badge_class = 'bg-info'; $status_text = 'SAKIT'; }
                                elseif($status == 'Izin') { $badge_class = 'bg-warning text-dark'; $status_text = 'IZIN'; }
                                elseif($status == 'Alpha') { $badge_class = 'bg-danger'; $status_text = 'ALPHA'; }
                        ?>
                        <tr>
                            <td class="text-center text-muted"><?php echo $no++; ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?php echo $data['nama_siswa']; ?></div>
                                <div class="small text-muted"><?php echo $data['nis']; ?></div>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?php echo $data['nama_kelas']; ?></span></td>
                            <td>
                                <?php echo ($data['jam_masuk']) ? $data['jam_masuk'] . ' WIB' : '-'; ?>
                            </td>
                            <td class="text-center">
                                <span class="badge <?php echo $badge_class; ?> rounded-pill px-3 py-2" style="font-size: 11px;">
                                    <?php echo $status_text; ?>
                                </span>
                            </td>
                            <td class="small text-muted">
                                <?php echo ($data['keterangan']) ? $data['keterangan'] : '-'; ?>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center py-5'>Tidak ada data siswa.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-navy { background-color: #0a192f; border:none; }
    .bg-navy:hover { background-color: #112240; }
    
    /* Responsive Mobile: List Style */
    @media (max-width: 768px) {
        .table thead { display: none; }
        .table tbody tr {
            display: flex; flex-direction: column;
            border-bottom: 1px solid #eee; padding: 15px;
        }
        .table td { border: none; padding: 2px 0; }
        .table td:first-child { display: none; } /* Hide No */
    }
</style>

<?php include '../../layouts/footer.php'; ?>