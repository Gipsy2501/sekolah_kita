<?php
session_start();
// Pastikan file koneksi ada, jika error suppress dulu agar tampilan tetap muncul
include 'config/koneksi.php';

// Base URL otomatis mendeteksi http/https dan host
$base_url = "http://localhost/sekolah_kita/";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMK Angkasa Lanud Husein Sastranegara - The Sky is The Limit</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary-navy: #0a192f;
            --secondary-blue: #112240;
            --accent-gold: #fbbf24;
            --sky-blue: #64ffda;
            --text-light: #ccd6f6;
            --font-main: 'Poppins', sans-serif;
            --font-heading: 'Outfit', sans-serif;
        }

        body { 
            font-family: var(--font-main); 
            background-color: #f3f4f6; 
            color: #333;
            overflow-x: hidden;
        }

        /* --- NAVBAR GLASSMORPHISM --- */
        .navbar-custom {
            background: rgba(10, 25, 47, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 20px 0;
            transition: all 0.4s ease;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .navbar-custom.scrolled {
            background: rgba(10, 25, 47, 0.95);
            padding: 10px 0;
            box-shadow: 0 10px 30px -10px rgba(2,12,27,0.7);
        }
        .nav-link {
            color: var(--text-light) !important;
            font-weight: 500;
            position: relative;
            margin: 0 10px;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0; height: 2px;
            bottom: 0; left: 0;
            background-color: var(--sky-blue);
            transition: width 0.3s;
        }
        .nav-link:hover::after { width: 100%; }
        .nav-link.active { color: var(--sky-blue) !important; }

        /* --- HERO SECTION --- */
        .hero-section {
            position: relative;
            height: 100vh;
            min-height: 700px;
            background-attachment: fixed; /* Parallax Effect */
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            display: flex;
            align-items: center;
        }
        .hero-overlay {
            position: absolute;
            top: 0; left: 0; w-100; h-100;
            width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(10,25,47,0.95) 0%, rgba(10,25,47,0.7) 100%);
            z-index: 1;
        }
        .hero-content { z-index: 2; position: relative; }
        
        h1.hero-title {
            font-family: var(--font-heading);
            font-size: 4rem;
            font-weight: 800;
            line-height: 1.1;
            background: linear-gradient(to right, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* --- STATS BOX --- */
        .stats-box {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
            margin-top: -80px;
            position: relative;
            z-index: 10;
            padding: 40px;
        }
        .stat-item h3 { font-family: var(--font-heading); font-weight: 800; color: var(--primary-navy); }

        /* --- JURUSAN CARDS (Modern) --- */
        .jurusan-card {
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            height: 350px;
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
        }
        .jurusan-bg {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-size: cover;
            background-position: center;
            transition: transform 0.5s;
        }
        .jurusan-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(to top, rgba(10,25,47,0.95), rgba(10,25,47,0.3));
            opacity: 0.9;
            transition: opacity 0.3s;
        }
        .jurusan-content {
            position: absolute;
            bottom: 0;
            padding: 30px;
            width: 100%;
            z-index: 2;
        }
        .jurusan-card:hover { transform: translateY(-10px); }
        .jurusan-card:hover .jurusan-bg { transform: scale(1.1); }
        .jurusan-card:hover .jurusan-overlay { opacity: 0.7; }
        
        /* --- BUTTONS --- */
        .btn-glow {
            background: transparent;
            color: var(--sky-blue);
            border: 1px solid var(--sky-blue);
            padding: 12px 30px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .btn-glow:hover {
            background: rgba(100, 255, 218, 0.1);
            box-shadow: 0 0 15px rgba(100, 255, 218, 0.3);
            color: var(--sky-blue);
        }

        /* --- FOOTER --- */
        footer { background-color: var(--primary-navy); color: var(--text-light); }
        .social-link {
            width: 40px; height: 40px;
            display: inline-flex;
            align-items: center; justify-content: center;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 50%;
            color: var(--text-light);
            transition: all 0.3s;
            text-decoration: none;
        }
        .social-link:hover {
            border-color: var(--sky-blue);
            color: var(--sky-blue);
            transform: translateY(-3px);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-3" href="#">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b2/Logo_TNI_AU.png" width="50" alt="Logo">
                <div class="text-white lh-1">
                    <span class="d-block fw-bold" style="font-family: var(--font-heading); letter-spacing: 1px;">SMK ANGKASA</span>
                    <small style="font-size: 0.75rem; color: var(--sky-blue);">LANUD HUSEIN SASTRANEGARA</small>
                </div>
            </a>
            <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#profil">Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="#jurusan">Jurusan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#fasilitas">Fasilitas</a></li>
                    
                    <li class="nav-item ms-lg-4">
                        <?php if(isset($_SESSION['status']) && $_SESSION['status'] == "login"){ 
                            $role = $_SESSION['role'];
                            $link = ($role == 'admin') ? "admin/index.php" : (($role == 'guru') ? "guru/index.php" : "siswa/index.php");
                        ?>
                            <a href="<?php echo $base_url . $link; ?>" class="btn btn-glow rounded-pill px-4">
                                Dashboard <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        <?php } else { ?>
                            <a href="<?php echo $base_url; ?>login.php" class="btn btn-primary rounded-pill px-4 fw-bold shadow-lg" style="background: var(--sky-blue); color: var(--primary-navy); border:none;">
                                Login Area
                            </a>
                        <?php } ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section" style="background-image: url('https://images.unsplash.com/photo-1559828753-48b4cb048cd3?q=80&w=1920&auto=format&fit=crop');">
        <div class="hero-overlay"></div>
        
        <div class="container hero-content text-center text-lg-start">
            <div class="row align-items-center">
                <div class="col-lg-8" data-aos="fade-up">
                    <div class="d-inline-flex align-items-center border border-secondary rounded-pill px-3 py-1 mb-4" style="background: rgba(255,255,255,0.05);">
                        <span class="badge bg-warning text-dark me-2 rounded-pill">OPEN</span>
                        <small class="text-light">Penerimaan Peserta Didik Baru 2026/2027</small>
                    </div>
                    
                    <h1 class="hero-title mb-4">Membangun Generasi <br> <span style="color: var(--sky-blue); -webkit-text-fill-color: var(--sky-blue);">Teknologi & Dirgantara</span></h1>
                    
                    <p class="lead text-light opacity-75 mb-5" style="max-width: 600px;">
                        Bergabunglah dengan SMK Angkasa. Paduan disiplin semi-militer, pendidikan karakter, dan keahlian teknologi terkini untuk masa depan gemilang.
                    </p>
                    
                    <div class="d-flex gap-3 justify-content-center justify-content-lg-start">
                        <a href="#jurusan" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-lg" style="background: var(--sky-blue); color: var(--primary-navy); border: none;">
                            Lihat Jurusan
                        </a>
                        <a href="#video-profil" class="btn btn-outline-light btn-lg rounded-pill px-4">
                            <i class="fas fa-play me-2"></i> Video Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div style="position: absolute; bottom: 0; left: 0; width: 100%; overflow: hidden; line-height: 0;">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none" style="position: relative; display: block; width: calc(100% + 1.3px); height: 100px;">
                <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" fill="#f3f4f6"></path>
            </svg>
        </div>
    </section>

    <section class="container" style="position: relative;">
        <div class="stats-box">
            <div class="row text-center">
                <div class="col-md-4 mb-4 mb-md-0 border-end">
                    <div class="stat-item" data-aos="zoom-in" data-aos-delay="100">
                        <i class="fas fa-user-graduate fa-2x mb-3 text-warning"></i>
                        <h3 class="display-5">1.200+</h3>
                        <p class="text-muted mb-0">Siswa Aktif</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0 border-end">
                    <div class="stat-item" data-aos="zoom-in" data-aos-delay="200">
                        <i class="fas fa-chalkboard-teacher fa-2x mb-3 text-primary"></i>
                        <h3 class="display-5">85+</h3>
                        <p class="text-muted mb-0">Guru & Staff Profesional</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-item" data-aos="zoom-in" data-aos-delay="300">
                        <i class="fas fa-handshake fa-2x mb-3 text-success"></i>
                        <h3 class="display-5">50+</h3>
                        <p class="text-muted mb-0">Mitra Industri (DUDI)</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="profil" class="py-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right">
                    <div class="position-relative p-4">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-primary rounded-4 opacity-10" style="transform: rotate(-3deg);"></div>
                        <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=800&auto=format&fit=crop" class="img-fluid rounded-4 shadow-lg position-relative" alt="School Building">
                        
                        <div class="position-absolute bottom-0 end-0 bg-white p-3 rounded-4 shadow-lg m-4 d-none d-md-block">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-warning rounded-circle p-2">
                                    <i class="fas fa-star text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Terakreditasi A</h6>
                                    <small class="text-muted">Unggul & Berprestasi</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 ps-lg-5" data-aos="fade-left">
                    <h6 class="text-uppercase fw-bold text-primary mb-2">Tentang Kami</h6>
                    <h2 class="fw-bold mb-4" style="color: var(--primary-navy); font-family: var(--font-heading);">Sekolahnya Para Juara <br> di Jantung Kota Bandung</h2>
                    <p class="text-muted mb-4 lead" style="font-size: 1rem;">
                        SMK Angkasa Lanud Husein Sastranegara tidak hanya mencetak lulusan yang cerdas secara akademik, 
                        tapi juga memiliki kedisiplinan tinggi layaknya prajurit dan keterampilan teknologi yang dibutuhkan industri 4.0.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex align-items-start">
                            <i class="fas fa-check-circle text-success mt-1 me-3"></i>
                            <div>
                                <strong>Kurikulum Link & Match</strong>
                                <p class="small text-muted mb-0">Pembelajaran disesuaikan langsung dengan kebutuhan industri penerbangan dan teknologi.</p>
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="fas fa-check-circle text-success mt-1 me-3"></i>
                            <div>
                                <strong>Ekstrakurikuler Lengkap</strong>
                                <p class="small text-muted mb-0">Paskibra, Pramuka, Futsal, Band, hingga Coding Club.</p>
                            </div>
                        </li>
                    </ul>
                    <a href="#" class="btn btn-outline-dark rounded-pill mt-3">Selengkapnya</a>
                </div>
            </div>
        </div>
    </section>

    <section id="jurusan" class="py-5" style="background-color: var(--primary-navy); position: relative; overflow: hidden;">
        <div class="position-absolute top-0 end-0 opacity-10">
            <i class="fas fa-cog fa-10x text-white fa-spin" style="animation-duration: 20s;"></i>
        </div>

        <div class="container py-5 position-relative">
            <div class="text-center mb-5" data-aos="fade-up">
                <h6 class="text-warning fw-bold text-uppercase">Program Keahlian</h6>
                <h2 class="text-white fw-bold display-5" style="font-family: var(--font-heading);">Pilih Masa Depanmu</h2>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="jurusan-card">
                        <div class="jurusan-bg" style="background-image: url('https://images.unsplash.com/photo-1587620962725-abab7fe55159?q=80&w=800&auto=format&fit=crop');"></div>
                        <div class="jurusan-overlay"></div>
                        <div class="jurusan-content text-white">
                            <div class="mb-3">
                                <i class="fas fa-code fa-2x text-warning"></i>
                            </div>
                            <h3 class="fw-bold">Rekayasa Perangkat Lunak</h3>
                            <p class="small opacity-75 mb-4">Web Development, Android Apps, Database Management, Game Dev.</p>
                            <a href="#" class="text-warning text-decoration-none fw-bold small">LIHAT DETAIL <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="jurusan-card">
                        <div class="jurusan-bg" style="background-image: url('https://images.unsplash.com/photo-1558494949-ef526b0042a0?q=80&w=800&auto=format&fit=crop');"></div>
                        <div class="jurusan-overlay"></div>
                        <div class="jurusan-content text-white">
                            <div class="mb-3">
                                <i class="fas fa-server fa-2x text-info"></i>
                            </div>
                            <h3 class="fw-bold">Teknik Jaringan Komputer</h3>
                            <p class="small opacity-75 mb-4">Mikrotik, Cisco, Fiber Optic, Server Administration, Cyber Security.</p>
                            <a href="#" class="text-info text-decoration-none fw-bold small">LIHAT DETAIL <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="jurusan-card">
                        <div class="jurusan-bg" style="background-image: url('https://images.unsplash.com/photo-1497215728101-856f4ea42174?q=80&w=800&auto=format&fit=crop');"></div>
                        <div class="jurusan-overlay"></div>
                        <div class="jurusan-content text-white">
                            <div class="mb-3">
                                <i class="fas fa-briefcase fa-2x text-success"></i>
                            </div>
                            <h3 class="fw-bold">Manajemen Perkantoran</h3>
                            <p class="small opacity-75 mb-4">Digital Arsip, Public Speaking, English for Business, Administration.</p>
                            <a href="#" class="text-success text-decoration-none fw-bold small">LIHAT DETAIL <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="pt-5 pb-3">
        <div class="container">
            <div class="row g-4 mb-5">
                <div class="col-lg-4">
                    <h4 class="fw-bold text-white mb-4" style="font-family: var(--font-heading);">SMK ANGKASA</h4>
                    <p class="text-white-50 small mb-4">
                        Sekolah Menengah Kejuruan di bawah naungan Yasarini Lanud Husein Sastranegara. Berdedikasi mencetak lulusan kompeten, berkarakter, dan berwawasan kebangsaan.
                    </p>
                    <div class="d-flex gap-2">
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-6">
                    <h5 class="text-white fw-bold mb-3 h6">Tautan Cepat</h5>
                    <ul class="list-unstyled small text-white-50">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-white-50 hover-text-white">PPDB Online</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-white-50 hover-text-white">E-Learning</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-white-50 hover-text-white">Info Alumni</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-white-50 hover-text-white">Bursa Kerja</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-6">
                    <h5 class="text-white fw-bold mb-3 h6">Program</h5>
                    <ul class="list-unstyled small text-white-50">
                        <li class="mb-2">Rekayasa Perangkat Lunak</li>
                        <li class="mb-2">Teknik Komputer Jaringan</li>
                        <li class="mb-2">Manajemen Perkantoran</li>
                    </ul>
                </div>

                <div class="col-lg-4">
                    <h5 class="text-white fw-bold mb-3 h6">Kontak Kami</h5>
                    <ul class="list-unstyled small text-white-50">
                        <li class="mb-3 d-flex"><i class="fas fa-map-marker-alt mt-1 me-3 text-warning"></i> Jl. Pajajaran, Lanud Husein Sastranegara, Bandung</li>
                        <li class="mb-3 d-flex"><i class="fas fa-phone-alt mt-1 me-3 text-warning"></i> (022) 12345678</li>
                        <li class="mb-3 d-flex"><i class="fas fa-envelope mt-1 me-3 text-warning"></i> info@smkangkasa-husein.sch.id</li>
                    </ul>
                </div>
            </div>

            <hr style="border-color: rgba(255,255,255,0.1);">
            
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="small text-white-50 mb-0">&copy; 2026 SMK Angkasa Lanud Husein Sastranegara.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="small text-white-50 mb-0">
                        Designed & Developed by <span class="text-warning fw-bold">Al Gyfrans</span> (RPL)
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Inisialisasi Animasi AOS
        AOS.init({
            duration: 1000,
            once: true
        });

        // Script untuk Navbar berubah warna saat scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-custom');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>