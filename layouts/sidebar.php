<?php
// Ambil data user untuk sidebar (Copy logic header biar aman)
$id_sb = $_SESSION['id_user'];
$role_sb = $_SESSION['role'];
?>

<div class="sidebar d-none d-lg-flex">
    
    <a href="index.php" class="sidebar-brand">
        <img src="/sekolah_kita/assets/img/logo-angkasa.png" width="40" class="filter-shadow">
        <div class="brand-text">
            <h5>ANGKASA</h5>
            <span>School System</span>
        </div>
    </a>

    <?php if($role_sb == 'admin'){ ?>
        <div class="menu-label">Master Data</div>
        <a href="/sekolah_kita/admin/index.php" class="nav-item-custom <?= isActive('index.php') ?>">
            <i class="fas fa-rocket"></i> <span>Dashboard</span>
        </a>
        <a href="/sekolah_kita/admin/siswa/data_siswa.php" class="nav-item-custom <?= isActive('data_siswa.php') ?>">
            <i class="fas fa-user-graduate"></i> <span>Data Siswa</span>
        </a>
        <a href="/sekolah_kita/admin/guru/data_guru.php" class="nav-item-custom <?= isActive('data_guru.php') ?>">
            <i class="fas fa-chalkboard-teacher"></i> <span>Data Guru</span>
        </a>

        <div class="menu-label">Akademik</div>
        <a href="/sekolah_kita/admin/kelas/data_kelas.php" class="nav-item-custom <?= isActive('data_kelas.php') ?>">
            <i class="fas fa-school"></i> <span>Kelas & Rombel</span>
        </a>
        <a href="/sekolah_kita/admin/mapel/data_mapel.php" class="nav-item-custom <?= isActive('data_mapel.php') ?>">
            <i class="fas fa-book"></i> <span>Mata Pelajaran</span>
        </a>
        <a href="/sekolah_kita/admin/ujian/data_ujian.php" class="nav-item-custom <?= isActive('data_ujian.php') ?>">
            <i class="fas fa-file-signature"></i> <span>Jadwal Ujian</span>
        </a>
        
        <div class="menu-label">Laporan</div>
        <a href="/sekolah_kita/admin/absensi/rekap_absensi.php" class="nav-item-custom <?= isActive('rekap_absensi.php') ?>">
            <i class="fas fa-calendar-check"></i> <span>Rekap Absensi</span>
        </a>

    <?php } elseif($role_sb == 'guru'){ ?>
        <div class="menu-label">Utama</div>
        <a href="/sekolah_kita/guru/index.php" class="nav-item-custom <?= isActive('index.php') ?>">
            <i class="fas fa-home"></i> <span>Beranda</span>
        </a>
        
        <div class="menu-label">Kegiatan Belajar</div>
        <a href="/sekolah_kita/guru/tugas/data_tugas.php" class="nav-item-custom <?= isActive('data_tugas.php') ?>">
            <i class="fas fa-tasks"></i> <span>Kelola Tugas</span>
        </a>
        <a href="/sekolah_kita/guru/nilai/input_nilai.php" class="nav-item-custom <?= isActive('input_nilai.php') ?>">
            <i class="fas fa-edit"></i> <span>Input Nilai</span>
        </a>
        
        <div class="menu-label">Pribadi</div>
        <a href="/sekolah_kita/guru/profil/saya.php" class="nav-item-custom <?= isActive('saya.php') ?>">
            <i class="fas fa-user-circle"></i> <span>Profil Saya</span>
        </a>

    <?php } elseif($role_sb == 'siswa'){ ?>
        <div class="menu-label">Menu Siswa</div>
        <a href="/sekolah_kita/siswa/index.php" class="nav-item-custom <?= isActive('index.php') ?>">
            <i class="fas fa-laptop-code"></i> <span>Dashboard</span>
        </a>
        <a href="/sekolah_kita/siswa/tugas/lihat_tugas.php" class="nav-item-custom <?= isActive('lihat_tugas.php') ?>">
            <i class="fas fa-book-open"></i> <span>Tugas Sekolah</span>
        </a>
        <a href="/sekolah_kita/siswa/ujian/daftar_ujian.php" class="nav-item-custom <?= isActive('daftar_ujian.php') ?>">
            <i class="fas fa-file-contract"></i> <span>Ujian Online</span>
        </a>
        <a href="/sekolah_kita/siswa/absensi/rekap_saya.php" class="nav-item-custom <?= isActive('rekap_saya.php') ?>">
            <i class="fas fa-user-clock"></i> <span>Riwayat Absen</span>
        </a>
        <a href="/sekolah_kita/siswa/nilai/transkrip_saya.php" class="nav-item-custom <?= isActive('transkrip_saya.php') ?>">
            <i class="fas fa-medal"></i> <span>Nilai Saya</span>
        </a>
    <?php } ?>

    <div class="sidebar-footer mt-auto">
        <a href="../logout.php" onclick="return confirm('Yakin ingin keluar?')" class="text-white text-decoration-none d-flex align-items-center gap-2 w-100">
            <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                <i class="fas fa-power-off"></i>
            </div>
            <div style="font-size: 0.9rem; font-weight: 600;">Log Out</div>
        </a>
    </div>
</div>


<nav class="bottom-nav d-flex d-lg-none">
    
    <?php if($role_sb == 'admin'){ ?>
        <a href="/sekolah_kita/admin/index.php" class="b-nav-item <?= isActive('index.php') ?>">
            <i class="fas fa-rocket"></i> <span>Home</span>
        </a>
        <a href="/sekolah_kita/admin/siswa/data_siswa.php" class="b-nav-item <?= isActive('data_siswa.php') ?>">
            <i class="fas fa-users"></i> <span>Siswa</span>
        </a>
        <a href="#" data-bs-toggle="modal" data-bs-target="#menuLengkapModal" class="b-nav-item">
            <div class="bg-navy text-white rounded-circle shadow d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; margin-top: -20px; border: 3px solid white; background-color: #0a192f;">
                <i class="fas fa-th-large" style="font-size: 1.2rem; margin:0;"></i>
            </div>
        </a>
        <a href="/sekolah_kita/admin/guru/data_guru.php" class="b-nav-item <?= isActive('data_guru.php') ?>">
            <i class="fas fa-chalkboard-teacher"></i> <span>Guru</span>
        </a>
        <a href="/sekolah_kita/admin/profil/saya.php" class="b-nav-item <?= isActive('saya.php') ?>">
            <i class="fas fa-user"></i> <span>Akun</span>
        </a>

    <?php } elseif($role_sb == 'guru'){ ?>
        <a href="/sekolah_kita/guru/index.php" class="b-nav-item <?= isActive('index.php') ?>">
            <i class="fas fa-home"></i> <span>Home</span>
        </a>
        <a href="/sekolah_kita/guru/tugas/data_tugas.php" class="b-nav-item <?= isActive('data_tugas.php') ?>">
            <i class="fas fa-tasks"></i> <span>Tugas</span>
        </a>
        <a href="/sekolah_kita/guru/nilai/input_nilai.php" class="b-nav-item <?= isActive('input_nilai.php') ?>">
            <i class="fas fa-edit"></i> <span>Nilai</span>
        </a>
        <a href="/sekolah_kita/guru/profil/saya.php" class="b-nav-item <?= isActive('saya.php') ?>">
            <i class="fas fa-user"></i> <span>Profil</span>
        </a>

    <?php } elseif($role_sb == 'siswa'){ ?>
        <a href="/sekolah_kita/siswa/index.php" class="b-nav-item <?= isActive('index.php') ?>">
            <i class="fas fa-home"></i> <span>Home</span>
        </a>
        <a href="/sekolah_kita/siswa/tugas/lihat_tugas.php" class="b-nav-item <?= isActive('lihat_tugas.php') ?>">
            <i class="fas fa-book-open"></i> <span>Tugas</span>
        </a>
        <a href="/sekolah_kita/siswa/ujian/daftar_ujian.php" class="b-nav-item <?= isActive('daftar_ujian.php') ?>">
            <i class="fas fa-file-contract"></i> <span>Ujian</span>
        </a>
        <a href="/sekolah_kita/siswa/absensi/rekap_saya.php" class="b-nav-item <?= isActive('rekap_saya.php') ?>">
            <i class="fas fa-clock"></i> <span>Absen</span>
        </a>
    <?php } ?>

</nav>

<div class="modal fade" id="menuLengkapModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0">
                <h6 class="modal-title fw-bold">Menu Lainnya</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 d-grid gap-3 text-center">
                <a href="/sekolah_kita/admin/kelas/data_kelas.php" class="btn btn-light py-3 rounded-3 fw-bold"><i class="fas fa-school me-2 text-warning"></i> Data Kelas</a>
                <a href="/sekolah_kita/admin/mapel/data_mapel.php" class="btn btn-light py-3 rounded-3 fw-bold"><i class="fas fa-book me-2 text-info"></i> Mapel</a>
                <a href="/sekolah_kita/admin/ujian/data_ujian.php" class="btn btn-light py-3 rounded-3 fw-bold"><i class="fas fa-file-signature me-2 text-success"></i> Ujian</a>
            </div>
        </div>
    </div>
</div>

<div class="main-content w-100">