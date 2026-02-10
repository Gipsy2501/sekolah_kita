<?php
// --- LOGIKA AMBIL FOTO PROFIL UNTUK SIDEBAR ---
// (Sama persis seperti di Header)
$id_sb   = $_SESSION['id_user'];
$role_sb = $_SESSION['role'];
$foto_sb = "default.jpg"; // Default kalau belum upload

// Cek koneksi database (Jaga-jaga kalau file ini di-load terpisah)
if (!isset($koneksi)) {
    if (file_exists('../config/koneksi.php')) include '../config/koneksi.php';
}

if(isset($koneksi)){
    // Tentukan tabel berdasarkan role
    $tabel_sb = ($role_sb == 'siswa') ? 'tb_siswa' : (($role_sb == 'guru') ? 'tb_guru' : 'tb_admin');
    $kolom_sb = "id_" . $role_sb;
    
    $q_sb = mysqli_query($koneksi, "SELECT foto FROM $tabel_sb WHERE $kolom_sb='$id_sb'");
    if($d_sb = mysqli_fetch_assoc($q_sb)){
        if(!empty($d_sb['foto'])) $foto_sb = $d_sb['foto'];
    }
}
?>

<div class="sidebar" id="sidebarMenu">
    
    <div class="profile-card">
        <div class="profile-img-big shadow">
            <img src="/sekolah_kita/uploads/profil/<?php echo $foto_sb; ?>" alt="User" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
        </div>
        <h6 class="text-white fw-bold mb-0"><?php echo substr($_SESSION['nama'], 0, 18); ?></h6>
        <span class="badge bg-warning text-dark mt-2 rounded-pill px-3"><?php echo strtoupper($_SESSION['role']); ?></span>
    </div>

    <div class="px-4 mb-2 text-uppercase fw-bold" style="font-size: 11px; color: var(--abu); letter-spacing: 1px;">Main Menu</div>

    <nav class="nav flex-column">
        
        <?php if($_SESSION['role'] == 'admin'){ ?>
            <a href="/sekolah_kita/admin/index.php" class="nav-link-custom <?= isActive('index.php') ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="/sekolah_kita/admin/siswa/data_siswa.php" class="nav-link-custom <?= isActive('data_siswa.php') ?>">
                <i class="fas fa-user-graduate"></i> Data Siswa
            </a>
            <a href="/sekolah_kita/admin/guru/data_guru.php" class="nav-link-custom <?= isActive('data_guru.php') ?>">
                <i class="fas fa-chalkboard-teacher"></i> Data Guru
            </a>
            <a href="/sekolah_kita/admin/kelas/data_kelas.php" class="nav-link-custom <?= isActive('data_kelas.php') ?>">
                <i class="fas fa-school"></i> Data Kelas
            </a>
            <a href="/sekolah_kita/admin/absensi/rekap_absensi.php" class="nav-link-custom <?= isActive('rekap_absensi.php') ?>">
                <i class="fas fa-calendar-check"></i> Monitoring Absensi
            </a>

        <?php } elseif($_SESSION['role'] == 'guru'){ ?>
            <a href="../guru/index.php" class="nav-link-custom <?= isActive('index.php') ?>">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="../guru/tugas/data_tugas.php" class="nav-link-custom <?= isActive('data_tugas.php') ?>">
                <i class="fas fa-tasks"></i> Kelola Tugas
            </a>
            <a href="../guru/nilai/input_nilai.php" class="nav-link-custom <?= isActive('input_nilai.php') ?>">
                <i class="fas fa-edit"></i> Input Nilai
            </a>
            <a href="../guru/profil/saya.php" class="nav-link-custom <?= isActive('saya.php') ?>">
                <i class="fas fa-user-cog"></i> Profil Saya
            </a>

        <?php } elseif($_SESSION['role'] == 'siswa'){ ?>
            <a href="/sekolah_kita/siswa/index.php" class="nav-link-custom <?= isActive('index.php') ?>">
                <i class="fas fa-laptop-code"></i> Dashboard
            </a>
            <a href="/sekolah_kita/siswa/tugas/lihat_tugas.php" class="nav-link-custom <?= isActive('lihat_tugas.php') ?>">
                <i class="fas fa-book-open"></i> Tugas Sekolah
            </a>
            <a href="../absensi/rekap_saya.php" class="nav-link-custom <?= isActive('rekap_saya.php') ?>">
                <i class="fas fa-user-clock"></i> Presensi
            </a>
            <a href="../nilai/transkrip_saya.php" class="nav-link-custom <?= isActive('transkrip_saya.php') ?>">
                <i class="fas fa-medal"></i> Transkrip Nilai
            </a>
        <?php } ?>

    </nav>

    <div style="margin-top: auto;">
        <a href="../logout.php" class="btn-logout" onclick="return confirm('Yakin ingin log out dari sistem?')">
            <i class="fas fa-sign-out-alt me-2"></i> LOGOUT SYSTEM
        </a>
    </div>

</div>

<div class="main-wrapper w-100">

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebar = document.getElementById('sidebarMenu');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarToggle');

        // Fungsi buka tutup sidebar mobile
        function toggleSidebar() {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('active');
        }

        if(toggleBtn) {
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleSidebar();
            });
        }

        // Klik overlay untuk tutup sidebar
        if(overlay) {
            overlay.addEventListener('click', toggleSidebar);
        }
    });
</script>