<?php
session_start();
// Cek apakah yang login benar-benar siswa?
if($_SESSION['role'] != 'siswa'){
    header("location:../login.php");
}

include '../config/koneksi.php';
include '../layouts/header.php';
include '../layouts/sidebar.php';

// Ambil ID Siswa yang sedang login
$id_siswa = $_SESSION['id_user'];

// Query Data Lengkap Siswa + Nama Kelas
$query = mysqli_query($koneksi, "SELECT * FROM tb_siswa 
                                 JOIN tb_kelas ON tb_siswa.id_kelas = tb_kelas.id_kelas 
                                 WHERE id_siswa='$id_siswa'");
$siswa = mysqli_fetch_array($query);

// Hitung Tugas Belum Dikerjakan (Nanti logikanya kita perbaiki setelah tabel tugas ada isinya)
// Sementara kita set 0 dulu biar gak error
$tugas_pending = 0; 
?>

<style>
    .kartu-pelajar {
        background: linear-gradient(135deg, #00264d 0%, #004080 100%);
        color: white;
        border-radius: 15px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0,38,77,0.3);
    }
    .kartu-pelajar::before {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 150px; height: 150px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .kartu-pelajar::after {
        content: '';
        position: absolute;
        bottom: -50px; left: -20px;
        width: 100px; height: 100px;
        background: rgba(241, 196, 15, 0.2); /* Warna Emas Transparan */
        border-radius: 50%;
    }
    .barcode-box {
        background: white;
        padding: 5px;
        display: inline-block;
        margin-top: 10px;
    }
    .barcode-lines {
        height: 30px;
        width: 100px;
        background: repeating-linear-gradient(
            to right,
            black 0px, black 2px,
            white 2px, white 4px
        );
    }
</style>

<div class="container-fluid p-4">
    
    <div class="alert alert-light border-0 shadow-sm d-flex align-items-center" role="alert">
        <i class="fas fa-bullhorn text-warning me-3 fa-2x"></i>
        <div>
            <h5 class="alert-heading m-0">Selamat Datang, Kadet <b><?php echo $siswa['nama_siswa']; ?></b>!</h5>
            <small class="text-muted">Semangat belajar dan raih prestasi setinggi langit.</small>
        </div>
    </div>

    <div class="row mt-4">
        
        <div class="col-md-5 mb-4">
            <h6 class="text-muted mb-3"><i class="fas fa-id-card me-2"></i> KARTU TANDA SISWA</h6>
            
            <div class="card kartu-pelajar p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <img src="/sekolah_kita/assets/img/logo-angkasa.png" width="50" style="invert(1);">
                    <div class="text-end">
                        <h6 class="mb-0 fw-bold">SMK ANGKASA</h6>
                        <small style="font-size: 10px;">LANUD HUSEIN SASTRANEGARA</small>
                    </div>
                </div>

                <div class="d-flex mt-4 align-items-center">
                    <div class="me-3">
                        <img src="../uploads/profil/<?php echo $siswa['foto']; ?>" 
                             class="rounded-circle border border-3 border-warning" 
                             width="80" height="80" style="object-fit: cover; background: white;">
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold text-warning"><?php echo $siswa['nama_siswa']; ?></h5>
                        <p class="mb-0 small opacity-75"><?php echo $siswa['nisn']; ?></p>
                        <span class="badge bg-light text-dark mt-2"><?php echo $siswa['nama_kelas']; ?></span>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-between align-items-end">
                    <div>
                        <small class="d-block opacity-50" style="font-size: 10px;">BERLAKU HINGGA</small>
                        <small>JULI 2026</small>
                    </div>
                    <div class="text-center">
                        <div class="barcode-box">
                            <div class="barcode-lines"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <h6 class="text-muted mb-3"><i class="fas fa-chart-pie me-2"></i> AKTIVITAS AKADEMIK</h6>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="fas fa-book-open text-primary fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="text-muted small mb-1">Tugas Belum Dikerjakan</h6>
                                <h3 class="mb-0 fw-bold text-dark"><?php echo $tugas_pending; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="fas fa-user-check text-success fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="text-muted small mb-1">Persentase Kehadiran</h6>
                                <h3 class="mb-0 fw-bold text-dark">100%</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4 border-0 shadow-sm bg-warning bg-opacity-10">
                <div class="card-body">
                    <figure class="mb-0">
                        <blockquote class="blockquote small">
                            <p class="mb-2">"Pendidikan adalah senjata paling mematikan di dunia, karena dengan pendidikan, Anda dapat mengubah dunia."</p>
                        </blockquote>
                        <figcaption class="blockquote-footer mb-0">
                            Nelson Mandela
                        </figcaption>
                    </figure>
                </div>
            </div>

        </div>

    </div>
</div>

<?php include '../layouts/footer.php'; ?>