<?php
session_start();
// Cek Role Guru
if($_SESSION['role'] != 'guru'){
    header("location:../login.php");
}

include '../config/koneksi.php';
include '../layouts/header.php';
include '../layouts/sidebar.php';

$id_guru = $_SESSION['id_user'];

// Hitung Statistik Tugas yang dibuat guru ini
$jml_tugas = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_tugas WHERE id_guru='$id_guru'"));

// Hitung Total Materi yang diupload
// (Asumsi nanti ada tabel materi, sementara 0 dulu)
$jml_materi = 0; 
?>

<div class="container-fluid p-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 text-gray-800">Dashboard Pengajar</h4>
            <p class="text-muted small">Selamat Datang, Bapak/Ibu <b><?php echo $_SESSION['nama']; ?></b></p>
        </div>
        <span class="badge bg-angkasa p-2"><i class="fas fa-calendar-alt me-1"></i> Tahun Ajaran 2025/2026</span>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-0"><?php echo $jml_tugas; ?></h2>
                        <span class="small">Tugas Dibuat</span>
                    </div>
                    <i class="fas fa-clipboard-list fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card bg-success text-white border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-0"><?php echo $jml_materi; ?></h2>
                        <span class="small">Materi Diupload</span>
                    </div>
                    <i class="fas fa-book-reader fa-3x opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-warning text-dark border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-0" id="jam-digital">00:00</h4>
                        <span class="small">Waktu Server</span>
                    </div>
                    <i class="fas fa-clock fa-3x opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 fw-bold text-primary"><i class="fas fa-calendar-week me-2"></i> Jadwal Mengajar Hari Ini</h6>
        </div>
        <div class="card-body">
            <div class="alert alert-info border-0">
                <i class="fas fa-info-circle me-2"></i> Fitur jadwal otomatis sedang dalam pengembangan. Berikut adalah jadwal reguler Anda.
            </div>
            <table class="table table-bordered">
                <thead class="bg-light">
                    <tr>
                        <th>Jam Ke</th>
                        <th>Waktu</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1 - 3</td>
                        <td>07:00 - 09:30</td>
                        <td><b>XI RPL 1</b></td>
                        <td>Pemrograman Web</td>
                        <td><span class="badge bg-success">Selesai</span></td>
                    </tr>
                    <tr>
                        <td>4 - 6</td>
                        <td>09:45 - 12:00</td>
                        <td><b>XI RPL 2</b></td>
                        <td>Pemrograman Web</td>
                        <td><span class="badge bg-warning text-dark">Sedang Berlangsung</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    setInterval(() => {
        let now = new Date();
        let time = now.toLocaleTimeString();
        document.getElementById('jam-digital').innerText = time;
    }, 1000);
</script>

<?php include '../layouts/footer.php'; ?>