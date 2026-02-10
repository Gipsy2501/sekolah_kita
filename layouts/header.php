<?php
// Validasi Session
if(!isset($_SESSION['status']) || $_SESSION['status'] != "login"){
    header("location:../login.php?pesan=belum_login");
    exit();
}

// Helper Deteksi Aktif
function isActive($pageName) {
    $current = basename($_SERVER['PHP_SELF']);
    return ($current == $pageName) ? 'active' : '';
}

// Ambil Foto Profil (Singkat & Aman)
$foto_nav = "default.jpg";
if(isset($koneksi)){
    $id_u = $_SESSION['id_user'];
    $role_u = $_SESSION['role'];
    $tabel = ($role_u == 'siswa') ? 'tb_siswa' : (($role_u == 'guru') ? 'tb_guru' : 'tb_admin');
    $kolom = "id_" . $role_u;
    
    $q_foto = mysqli_query($koneksi, "SELECT foto FROM $tabel WHERE $kolom='$id_u'");
    if($d_foto = mysqli_fetch_assoc($q_foto)){ 
        if(!empty($d_foto['foto'])) $foto_nav = $d_foto['foto']; 
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SMK Angkasa | E-Learning System</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #0a192f;      /* Navy Gelap (Brand) */
            --secondary: #112240;    /* Navy Terang (Sidebar) */
            --accent: #fbbf24;       /* Emas (Highlight) */
            --bg-body: #f3f5f9;      /* Abu sangat muda (Background) */
            --text-dark: #1e293b;
            --text-gray: #64748b;
            --danger: #ef4444;
            --success: #10b981;
            --radius: 16px;          /* Sudut membulat modern */
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-dark);
            overflow-x: hidden;
            padding-bottom: 80px; /* Space untuk Bottom Nav di HP */
        }

        /* --- 1. SIDEBAR (DESKTOP) --- */
        .sidebar {
            width: 280px;
            background: var(--primary);
            height: 100vh;
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            padding: 20px 15px;
            overflow-y: auto;
            transition: 0.3s;
            display: flex; flex-direction: column;
        }

        /* Logo Area */
        .sidebar-brand {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 15px 30px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            margin-bottom: 20px;
            text-decoration: none;
        }
        .brand-text h5 { color: white; margin: 0; font-weight: 800; letter-spacing: -0.5px; }
        .brand-text span { color: var(--accent); font-size: 0.8rem; font-weight: 500; }

        /* Menu Groups */
        .menu-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: rgba(255,255,255,0.4);
            margin: 20px 15px 10px;
            font-weight: 700;
        }

        /* Nav Items */
        .nav-item-custom {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 18px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.2s ease;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .nav-item-custom:hover {
            background: rgba(255,255,255,0.05);
            color: white; transform: translateX(3px);
        }
        .nav-item-custom.active {
            background: var(--accent);
            color: var(--primary);
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(251, 191, 36, 0.3);
        }
        .nav-item-custom i { width: 20px; text-align: center; font-size: 1.1rem; }

        /* Profile Mini di Bawah Sidebar */
        .sidebar-footer {
            margin-top: auto;
            background: rgba(0,0,0,0.2);
            padding: 15px;
            border-radius: 16px;
            display: flex; align-items: center; gap: 12px;
        }

        /* --- 2. TOPBAR (DESKTOP & MOBILE) --- */
        .topbar {
            margin-left: 280px;
            padding: 15px 30px;
            background: transparent; /* Glass effect nanti di konten */
            display: flex; justify-content: space-between; align-items: center;
        }
        
        /* --- 3. BOTTOM NAV (MOBILE ONLY) - "THE GAME CHANGER" --- */
        .bottom-nav {
            display: none; /* Default Hidden di Desktop */
            position: fixed;
            bottom: 0; left: 0; width: 100%;
            background: white;
            padding: 10px 20px;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.05);
            z-index: 9999;
            justify-content: space-around;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }
        .b-nav-item {
            display: flex; flex-direction: column; align-items: center;
            text-decoration: none; color: var(--text-gray);
            font-size: 0.7rem; gap: 4px;
        }
        .b-nav-item i { font-size: 1.4rem; margin-bottom: 2px; transition: 0.2s; }
        .b-nav-item.active { color: var(--primary); font-weight: 700; }
        .b-nav-item.active i { color: var(--accent); transform: translateY(-3px); }

        /* --- MAIN CONTENT WRAPPER --- */
        .main-content {
            margin-left: 280px;
            padding: 20px 30px;
            min-height: 100vh;
        }

        /* --- RESPONSIVE LOGIC --- */
        @media (max-width: 991px) {
            .sidebar { display: none; } /* Hilangkan Sidebar di HP */
            .topbar { margin-left: 0; padding: 15px 20px; background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
            .main-content { margin-left: 0; padding: 15px; padding-top: 15px; }
            .bottom-nav { display: flex; } /* Munculkan Bottom Nav di HP */
            
            /* Sembunyikan elemen dashboard yg ribet di HP */
            .d-none-mobile { display: none !important; }
        }

        /* --- UTILITY CLASSES BIAR KEREN --- */
        .card-modern {
            background: white;
            border: none;
            border-radius: var(--radius);
            box-shadow: 0 2px 15px rgba(0,0,0,0.03);
            transition: transform 0.2s;
        }
        .card-modern:hover { transform: translateY(-3px); box-shadow: 0 5px 20px rgba(0,0,0,0.06); }
        .btn-modern {
            border-radius: 50px;
            padding: 8px 24px;
            font-weight: 600;
            border: none;
            transition: 0.2s;
        }
    </style>
</head>
<body>

<div class="topbar">
    <div class="d-flex align-items-center gap-3">
        <a href="#" class="d-lg-none text-decoration-none d-flex align-items-center gap-2">
            <img src="/sekolah_kita/assets/img/logo-angkasa.png" width="32">
            <div>
                <h6 class="m-0 fw-bold text-dark" style="line-height:1">SMK ANGKASA</h6>
                <small class="text-muted" style="font-size:10px">E-Learning</small>
            </div>
        </a>
        
        <h4 class="m-0 fw-bold d-none d-lg-block text-dark">
            <?php 
                // Auto Title biar user gak bingung
                $page = basename($_SERVER['PHP_SELF'], ".php");
                $titles = [
                    'index' => 'Dashboard Overview',
                    'data_siswa' => 'Manajemen Siswa',
                    'data_guru' => 'Manajemen Guru',
                    'lihat_tugas' => 'Tugas Sekolah',
                    'data_ujian' => 'Ujian Online'
                ];
                echo isset($titles[$page]) ? $titles[$page] : 'Panel Sistem';
            ?>
        </h4>
    </div>

    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-decoration-none gap-3" data-bs-toggle="dropdown">
            <div class="text-end d-none d-lg-block">
                <div class="fw-bold text-dark small"><?php echo substr($_SESSION['nama'], 0, 15); ?></div>
                <div class="badge bg-light text-primary border border-primary rounded-pill" style="font-size: 10px;">
                    <?php echo strtoupper($_SESSION['role']); ?>
                </div>
            </div>
            <img src="/sekolah_kita/uploads/profil/<?php echo $foto_nav; ?>" 
                 class="rounded-circle shadow-sm border border-2 border-white"
                 style="width: 42px; height: 42px; object-fit: cover;">
        </a>
        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 mt-2 overflow-hidden">
            <li><a class="dropdown-item py-2" href="../profil/saya.php"><i class="fas fa-user-cog me-2 text-warning"></i> Edit Profil</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item py-2 text-danger fw-bold" href="../logout.php" onclick="return confirm('Keluar sistem?')"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
        </ul>
    </div>
</div>

<div class="d-flex">