<?php
// Validasi Session
if(!isset($_SESSION['status']) || $_SESSION['status'] != "login"){
    header("location:../login.php?pesan=belum_login");
    exit();
}

// --- TAMBAHAN: AMBIL FOTO PROFIL (Hanya 5 baris) ---
$id_u = $_SESSION['id_user'];
$role_u = $_SESSION['role'];
$foto_nav = "default.jpg"; // Foto default

// Pastikan $koneksi nyambung
if(isset($koneksi)){
    $tabel = ($role_u == 'siswa') ? 'tb_siswa' : (($role_u == 'guru') ? 'tb_guru' : 'tb_admin');
    $kolom = "id_" . $role_u;
    $q_foto = mysqli_query($koneksi, "SELECT foto FROM $tabel WHERE $kolom='$id_u'");
    if($d_foto = mysqli_fetch_assoc($q_foto)){ 
        if(!empty($d_foto['foto'])) $foto_nav = $d_foto['foto']; 
    }
}

// Helper untuk mendeteksi halaman aktif (biar menu nyala)
function isActive($pageName) {
    $current = basename($_SERVER['PHP_SELF']);
    return ($current == $pageName) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | SMK Angkasa</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --navy-utama: #0a192f;
            --navy-terang: #112240;
            --navy-hover: #233554;
            --emas: #fbbf24;
            --putih: #e6f1ff;
            --abu: #8892b0;
            --biru-code: #64ffda;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5; /* Abu sangat muda biar kontras sama sidebar gelap */
            overflow-x: hidden;
        }

        /* --- NAVBAR (TOP) --- */
        .navbar-custom {
            background: var(--navy-utama);
            height: 70px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            z-index: 1050;
        }
        
        .brand-logo {
            font-weight: 800;
            color: var(--putih);
            letter-spacing: 1px;
            font-size: 1.2rem;
        }
        .brand-logo span { color: var(--emas); }

        /* User Dropdown */
        .user-pill {
            background: var(--navy-terang);
            padding: 5px 15px 5px 5px;
            border-radius: 30px;
            border: 1px solid var(--navy-hover);
            transition: 0.3s;
        }
        .user-pill:hover { border-color: var(--emas); }
        
        .user-avatar {
            width: 35px; height: 35px;
            background: var(--emas);
            color: var(--navy-utama);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: bold;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 260px;
            background: var(--navy-utama);
            min-height: 100vh;
            position: fixed;
            top: 70px; left: 0;
            transition: all 0.3s ease;
            z-index: 1000;
            border-right: 1px solid var(--navy-terang);
            padding-bottom: 50px; /* Space bawah */
        }

        /* Profile Card di Sidebar */
        .profile-card {
            background: var(--navy-terang);
            margin: 20px;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.05);
        }
        .profile-img-big {
            width: 70px; height: 70px;
            border-radius: 50%;
            border: 3px solid var(--emas);
            padding: 3px;
            margin: 0 auto 10px;
        }
        .profile-img-big img { width: 100%; border-radius: 50%; }

        /* Menu Items */
        .nav-link-custom {
            color: var(--abu);
            padding: 12px 25px;
            display: flex; align-items: center;
            font-size: 0.9rem;
            border-left: 4px solid transparent;
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .nav-link-custom i { width: 25px; text-align: center; margin-right: 10px; transition: 0.3s; }
        
        .nav-link-custom:hover {
            background: var(--navy-terang);
            color: var(--putih);
        }

        /* Menu Aktif (Menyala) */
        .nav-link-custom.active {
            background: linear-gradient(90deg, rgba(251, 191, 36, 0.1) 0%, transparent 100%);
            color: var(--emas);
            border-left-color: var(--emas);
        }
        .nav-link-custom.active i { transform: scale(1.2); text-shadow: 0 0 10px rgba(251, 191, 36, 0.5); }

        /* Logout Button */
        .btn-logout {
            margin: 20px;
            display: block;
            text-align: center;
            padding: 10px;
            border-radius: 10px;
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
            font-weight: 600;
            text-decoration: none;
            transition: 0.3s;
        }
        .btn-logout:hover { background: #ef4444; color: white; transform: translateY(-2px); }

        /* Main Content Wrapper */
        .main-wrapper {
            margin-left: 260px;
            margin-top: 70px;
            padding: 30px;
            transition: 0.3s;
            min-height: calc(100vh - 70px);
        }

        /* --- MOBILE RESPONSIVE --- */
        @media (max-width: 991px) {
            .sidebar { left: -270px; } /* Sembunyi ke kiri */
            .sidebar.show { left: 0; box-shadow: 10px 0 30px rgba(0,0,0,0.5); }
            .main-wrapper { margin-left: 0; padding: 20px; }
            .overlay {
                display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                background: rgba(10, 25, 47, 0.8); z-index: 999; backdrop-filter: blur(3px);
            }
            .overlay.active { display: block; }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
  <div class="container-fluid">
    <button class="btn text-white d-lg-none me-2" id="sidebarToggle">
        <i class="fas fa-bars fa-lg"></i>
    </button>

    <a class="navbar-brand brand-logo d-flex align-items-center" href="#">
        <img src="/sekolah_kita/assets/img/logo-angkasa.png" width="35" class="me-2 filter-shadow">
        SMK <span>ANGKASA</span>
    </a>

    <div class="ms-auto d-flex align-items-center">
        <div class="dropdown">
            <a class="nav-link d-flex align-items-center user-pill" href="#" role="button" data-bs-toggle="dropdown">
            <img src="/sekolah_kita/uploads/profil/<?php echo $foto_nav; ?>" 
                class="rounded-circle shadow-sm me-2" 
                style="width: 35px; height: 35px; object-fit: cover; border: 2px solid #fbbf24; background: white;">
                <div class="d-none d-md-block text-white small me-2">
                    <div class="fw-bold"><?php echo substr($_SESSION['nama'], 0, 10); ?></div>
                    <div style="font-size: 10px; color: var(--emas); line-height: 1;"><?php echo strtoupper($_SESSION['role']); ?></div>
                </div>
                <i class="fas fa-chevron-down text-white small"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-3" style="background: var(--navy-terang);">
                <li><a class="dropdown-item text-white hover-gold" href="../profil.php"><i class="fas fa-id-badge me-2 text-warning"></i> Profil Saya</a></li>
                <li><hr class="dropdown-divider bg-secondary"></li>
                <li><a class="dropdown-item text-danger" href="../logout.php"><i class="fas fa-power-off me-2"></i> Logout</a></li>
            </ul>
        </div>
    </div>
  </div>
</nav>

<div class="overlay" id="sidebarOverlay"></div>

<div class="d-flex">