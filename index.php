<?php
session_start();
// Pastikan file koneksi ada, jika error suppress dulu agar tampilan tetap muncul
include 'config/koneksi.php';

// Deteksi protokol (http atau https)
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");

// Deteksi Host/IP yang sedang diakses (bisa localhost, 192.168.x.x, atau domain)
$host = $_SERVER['HTTP_HOST'];

// Gabungkan menjadi Base URL yang benar-benar dinamis
$base_url = $protocol . "://" . $host . "/sekolah_kita/";
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
    
    <link rel="stylesheet" href="assets/css/index/style.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand d-flex align-items-center gap-3" href="#">
                <img src="https://www.sustainable.dtech-engineering.com/img/logosekolah/huseinsastranegara.png" width="50" alt="Logo">
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
                    <li class="nav-item"><a class="nav-link" href="#kesiswaan">Kesiswaan</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section" style="background-image: url('https://harianberita.id/uploads/images/image_750x_6834663ea150b.jpg');">
        <div class="hero-overlay"></div>
        
        <div class="container px-4 px-lg-5 hero-content text-center text-lg-start">
            <div class="row align-items-center">
                <div class="col-lg-8" data-aos="fade-up">
                    <div class="d-inline-flex align-items-center border border-secondary rounded-pill px-3 py-1 mb-4 mt-5 mt-lg-0" style="background: rgba(255,255,255,0.05);">
                        <span class="badge bg-warning text-dark me-2 rounded-pill">OPEN</span>
                        <small class="text-light">Penerimaan Peserta Didik Baru 2026/2027</small>
                    </div>
                    
                    <h1 class="hero-title mb-4">Membangun Generasi <br> <span style="color: var(--sky-blue); -webkit-text-fill-color: var(--sky-blue);">Teknologi & Dirgantara</span></h1>
                    
                    <p class="lead text-light opacity-75 mb-5" style="max-width: 600px; margin: 0 auto; line-height: 1.8;">
                        Bergabunglah dengan SMK Angkasa. Paduan disiplin semi-militer, pendidikan karakter, dan keahlian teknologi terkini untuk masa depan gemilang.
                    </p>
                    
                    <div class="d-flex gap-3 justify-content-center justify-content-lg-start flex-column flex-sm-row">
                        <a href="#jurusan" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-lg" style="background: var(--sky-blue); color: var(--primary-navy); border: none;">
                            Lihat Jurusan
                        </a>
                        <a href="halaman_segera.php" class="btn btn-outline-light btn-lg rounded-pill px-4">
                            <i class="fas fa-play me-2"></i> Video Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="d-none d-lg-block" style="position: absolute; bottom: 0; left: 0; width: 100%; overflow: hidden; line-height: 0;">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none" style="position: relative; display: block; width: calc(100% + 1.3px); height: 100px;">
                <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" fill="#f3f4f6"></path>
            </svg>
        </div>
    </section>

    <section class="container px-4 px-lg-5" style="position: relative;">
        <div class="stats-box">
            <div class="row m-0 text-center">
                <div class="col-md-4 custom-border-end">
                    <div class="stat-item px-md-3" data-aos="zoom-in" data-aos-delay="100">
                        <i class="fas fa-user-graduate fa-2x mb-3 text-warning"></i>
                        <h3 class="display-5">1.200+</h3>
                        <p class="text-muted mb-0">Siswa Aktif</p>
                    </div>
                </div>
                <div class="col-md-4 custom-border-end">
                    <div class="stat-item px-md-3" data-aos="zoom-in" data-aos-delay="200">
                        <i class="fas fa-chalkboard-teacher fa-2x mb-3 text-primary"></i>
                        <h3 class="display-5">85+</h3>
                        <p class="text-muted mb-0">Guru & Staff Profesional</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-item px-md-3" data-aos="zoom-in" data-aos-delay="300">
                        <i class="fas fa-handshake fa-2x mb-3 text-success"></i>
                        <h3 class="display-5">50+</h3>
                        <p class="text-muted mb-0">Mitra Industri (DUDI)</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="profil" class="py-5">
        <div class="container px-4 px-lg-5 py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 order-2 order-lg-1 mt-5 mt-lg-0" data-aos="fade-right">
                    <div class="position-relative p-lg-4">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-primary rounded-4 opacity-10 d-none d-lg-block" style="transform: rotate(-3deg);"></div>
                        
                        <img src="https://instagram.fbdo2-1.fna.fbcdn.net/v/t51.82787-15/637090970_18378013924092440_7833421274585548350_n.webp?_nc_cat=104&ig_cache_key=MzA5NjU2NzcwNTEzNzE3NjIzMg%3D%3D.3-ccb7-5&ccb=7-5&_nc_sid=58cdad&efg=eyJ2ZW5jb2RlX3RhZyI6IkZFRUQueHBpZHMuMTQ0MC5zZHIucmVndWxhcl9waG90by5DMyJ9&_nc_ohc=Jg7M0HgIJksQ7kNvwFKtSoM&_nc_oc=AdonMTEnLxut3R_kTRZ1lS4qwUxUbnMLrayHSv1lpW55ibYJFwN4S9NYdORs_DpklPs&_nc_ad=z-m&_nc_cid=0&_nc_zt=23&_nc_ht=instagram.fbdo2-1.fna&_nc_gid=rt8fs5hSc9jaQLbyzN6IGw&_nc_ss=7a22e&oh=00_Af74IdrgOsxN0HRvh1_CFr5-P3X6pq1IA0A0HjzNf1seWA&oe=6A0DEF3D" loading="lazy" class="img-profil rounded-4 shadow-lg position-relative" alt="School Building">
                        
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
                <div class="col-lg-6 ps-lg-5 order-1 order-lg-2" data-aos="fade-left">
                    <h6 class="text-uppercase fw-bold text-primary mb-2">Tentang Kami</h6>
                    <h2 class="fw-bold mb-4" style="color: var(--primary-navy); font-family: var(--font-heading);">Sekolahnya Para Juara <br> di Jantung Kota Bandung</h2>
                    <p class="text-muted mb-4 lead" style="font-size: 1rem; line-height: 1.8;">
                        SMK Angkasa Lanud Husein Sastranegara tidak hanya mencetak lulusan yang cerdas secara akademik, 
                        tapi juga memiliki kedisiplinan tinggi layaknya prajurit dan keterampilan teknologi yang dibutuhkan industri 4.0.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex align-items-start">
                            <i class="fas fa-check-circle text-success mt-1 me-3"></i>
                            <div>
                                <strong>Kurikulum Link & Match</strong>
                                <p class="small text-muted mb-0">Pembelajaran disesuaikan langsung dengan kebutuhan industri penerbangan, manufaktur, dan IT digital.</p>
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="fas fa-check-circle text-success mt-1 me-3"></i>
                            <div>
                                <strong>Ekstrakurikuler Lengkap</strong>
                                <p class="small text-muted mb-0">Paskibra, Pramuka, Futsal, Band, hingga Coding & Esports Club.</p>
                            </div>
                        </li>
                    </ul>
                    <a href="halaman_segera.php" class="btn btn-outline-dark rounded-pill mt-3 px-4">Selengkapnya</a>
                </div>
            </div>
        </div>
    </section>

    <section id="jurusan" class="py-5" style="background-color: var(--primary-navy); position: relative; overflow: hidden;">
        <div class="position-absolute top-0 end-0 opacity-10 d-none d-md-block">
            <i class="fas fa-cog fa-10x text-white fa-spin" style="animation-duration: 20s;"></i>
        </div>

        <div class="container px-4 px-lg-5 py-5 position-relative">
            <div class="text-center mb-5" data-aos="fade-up">
                <h6 class="text-warning fw-bold text-uppercase">Program Keahlian</h6>
                <h2 class="text-white fw-bold display-5" style="font-family: var(--font-heading);">Pilih Masa Depanmu</h2>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="jurusan-card">
                        <div class="jurusan-bg" style="background-image: url('https://images.unsplash.com/photo-1621905251189-08b45d6a269e?q=80&w=800&auto=format&fit=crop');"></div>
                        <div class="jurusan-overlay"></div>
                        <div class="jurusan-content text-white">
                            <div class="mb-3">
                                <i class="fas fa-bolt fa-2x text-warning"></i>
                            </div>
                            <h3 class="fw-bold h5">Teknik Instalasi Tenaga Listrik</h3>
                            <p class="small opacity-75 mb-4">Mempelajari instalasi penerangan, tenaga listrik, kontrol motor, PLC, serta otomasi industri modern.</p>
                            <a href="halaman_segera.php" class="text-warning text-decoration-none fw-bold small">TITL <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="jurusan-card">
                        <div class="jurusan-bg" style="background-image: url('https://images.unsplash.com/photo-1537462715879-360eeb61a0bc?q=80&w=800&auto=format&fit=crop');"></div>
                        <div class="jurusan-overlay"></div>
                        <div class="jurusan-content text-white">
                            <div class="mb-3">
                                <i class="fas fa-industry fa-2x text-info"></i>
                            </div>
                            <h3 class="fw-bold h5">Teknik Mesin Industri</h3>
                            <p class="small opacity-75 mb-4">Fokus pada pemeliharaan mesin produksi pabrik, fabrikasi logam, serta sistem hidrolik & pneumatik.</p>
                            <a href="halaman_segera.php" class="text-info text-decoration-none fw-bold small">TMI <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="jurusan-card">
                        <div class="jurusan-bg" style="background-image: url('https://images.unsplash.com/photo-1486006920555-c77dce18193b?q=80&w=800&auto=format&fit=crop');"></div>
                        <div class="jurusan-overlay"></div>
                        <div class="jurusan-content text-white">
                            <div class="mb-3">
                                <i class="fas fa-wrench fa-2x text-danger"></i>
                            </div>
                            <h3 class="fw-bold h5">Teknik Kendaraan Ringan</h3>
                            <p class="small opacity-75 mb-4">Pendalaman perawatan kendaraan otomotif roda empat, sistem sasis, kelistrikan mobil, dan engine tuning.</p>
                            <a href="halaman_segera.php" class="text-danger text-decoration-none fw-bold small">TKR <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="jurusan-card">
                        <div class="jurusan-bg" style="background-image: url('https://images.unsplash.com/photo-1587620962725-abab7fe55159?q=80&w=800&auto=format&fit=crop');"></div>
                        <div class="jurusan-overlay"></div>
                        <div class="jurusan-content text-white">
                            <div class="mb-3">
                                <i class="fas fa-gamepad fa-2x text-success"></i>
                            </div>
                            <h3 class="fw-bold h5">Pengembangan Perangkat Lunak & Gim</h3>
                            <p class="small opacity-75 mb-4">Pembuatan aplikasi web/mobile, rekayasa kode basis data, UI/UX desain, serta pengembangan industri gim digital.</p>
                            <a href="halaman_segera.php" class="text-success text-decoration-none fw-bold small">PPLG <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="kesiswaan" class="py-5" style="background-color: #f8f9fa;">
        <div class="container px-4 px-lg-5 py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <h6 class="text-primary fw-bold text-uppercase">Kesiswaan & Ekstrakurikuler</h6>
                <h2 class="fw-bold display-6" style="color: var(--primary-navy); font-family: var(--font-heading);">Aktif, Kreatif, Berprestasi</h2>
                <p class="text-muted">Update kegiatan terbaru dari OSIS dan Ekstrakurikuler SMK Angkasa.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1529070538774-1843cb1665e8?q=80&w=800&auto=format&fit=crop" class="card-img-top img-kesiswaan" alt="OSIS Kabinet Raung" loading="lazy">
                        <div class="card-body p-4">
                            <span class="badge bg-warning text-dark mb-2">Info OSIS</span>
                            <h5 class="fw-bold">Open Recruitment Kabinet Raung</h5>
                            <p class="text-muted small">Mari bergabung menjadi agen perubahan! Pendaftaran pengurus OSIS Kabinet Raung masa bakti 2026-2027 resmi dibuka untuk siswa-siswi terbaik.</p>
                            <a href="halaman_segera.php" class="text-primary text-decoration-none fw-bold small">DAFTAR SEKARANG <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=800&auto=format&fit=crop" class="card-img-top img-kesiswaan" alt="E-Sport Tournament" loading="lazy">
                        <div class="card-body p-4">
                            <span class="badge bg-primary mb-2">Turnamen</span>
                            <h5 class="fw-bold">Mobile Legends E-Sport Tournament</h5>
                            <p class="text-muted small">Kemeriahan turnamen antar kelas tingkat sekolah yang diselenggarakan oleh OSIS untuk menjaring talenta unggul atlet e-sport SMK Angkasa.</p>
                            <a href="halaman_segera.php" class="text-primary text-decoration-none fw-bold small">LIHAT GALERI <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=800&auto=format&fit=crop" class="card-img-top img-kesiswaan" alt="Ekstrakurikuler Paskibra" loading="lazy">
                        <div class="card-body p-4">
                            <span class="badge bg-success mb-2">Ekstrakurikuler</span>
                            <h5 class="fw-bold">Persiapan Lomba Ketangkasan Baris Berbaris</h5>
                            <p class="text-muted small">Potret semangat pasukan Paskibra SMK Angkasa dalam latihan intensif persiapan kejuaraan baris-berbaris tingkat provinsi tahun ini.</p>
                            <a href="halaman_segera.php" class="text-primary text-decoration-none fw-bold small">BACA BERITA <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="pt-5 pb-3">
        <div class="container px-4 px-lg-5">
            <div class="row g-4 mb-5">
                <div class="col-lg-4">
                    <h4 class="fw-bold text-white mb-4" style="font-family: var(--font-heading);">SMK ANGKASA</h4>
                    <p class="text-white-50 small mb-4 lh-lg">
                        Sekolah Menengah Kejuruan di bawah naungan Yasarini Lanud Husein Sastranegara. Berdedikasi mencetak lulusan kompeten, berkarakter, dan berwawasan kebangsaan.
                    </p>
                    <div class="d-flex gap-2">
                        <a href="halaman_segera.php" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="halaman_segera.php" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="halaman_segera.php" class="social-link"><i class="fab fa-youtube"></i></a>
                        <a href="halaman_segera.php" class="social-link"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-6">
                    <h5 class="text-white fw-bold mb-3 h6">Tautan Cepat</h5>
                    <ul class="list-unstyled small text-white-50 lh-lg">
                        
                        <li class="mb-3">
                            <?php if(isset($_SESSION['status']) && $_SESSION['status'] == "login"){ 
                                $role = $_SESSION['role'];
                                $link = ($role == 'admin') ? "admin/index.php" : (($role == 'guru') ? "guru/index.php" : "siswa/index.php");
                            ?>
                                <a href="<?php echo $base_url . $link; ?>" class="text-decoration-none text-warning fw-bold hover-text-white d-inline-block border border-warning rounded px-2 py-1">
                                    <i class="fas fa-columns me-1"></i> Dashboard <?php echo ucfirst($role); ?>
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo $base_url; ?>auth/login.php" class="text-decoration-none text-warning fw-bold hover-text-white d-inline-block border border-warning rounded px-2 py-1">
                                    <i class="fas fa-sign-in-alt me-1"></i> Portal Login
                                </a>
                            <?php } ?>
                        </li>
                        <li><a href="halaman_segera.php" class="text-decoration-none text-white-50 hover-text-white">PPDB Online</a></li>
                        <li><a href="halaman_segera.php" class="text-decoration-none text-white-50 hover-text-white">E-Learning</a></li>
                        <li><a href="halaman_segera.php" class="text-decoration-none text-white-50 hover-text-white">Info Alumni</a></li>
                        <li><a href="halaman_segera.php" class="text-decoration-none text-white-50 hover-text-white">Bursa Kerja</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-6">
                    <h5 class="text-white fw-bold mb-3 h6">Program</h5>
                    <ul class="list-unstyled small text-white-50 lh-lg">
                        <li>Teknik Instalasi Tenaga Listrik</li>
                        <li>Teknik Mesin Industri</li>
                        <li>Teknik Kendaraan Ringan</li>
                        <li>Pengembangan Perangkat Lunak & Gim</li>
                    </ul>
                </div>

                <div class="col-lg-4">
                    <h5 class="text-white fw-bold mb-3 h6">Kontak Kami</h5>
                    <ul class="list-unstyled small text-white-50 lh-lg">
                        <li class="d-flex align-items-start mb-2"><i class="fas fa-map-marker-alt mt-1 me-3 text-warning"></i> Jl. Pajajaran, Lanud Husein Sastranegara, Bandung</li>
                        <li class="d-flex align-items-start mb-2"><i class="fas fa-phone-alt mt-1 me-3 text-warning"></i> (022) 12345678</li>
                        <li class="d-flex align-items-start mb-2"><i class="fas fa-envelope mt-1 me-3 text-warning"></i> info@smkangkasa-husein.sch.id</li>
                    </ul>
                </div>
            </div>

            <hr style="border-color: rgba(255,255,255,0.1);">
            
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="small text-white-50 mb-0">&copy; 2026 SMK Angkasa Lanud Husein Sastranegara.</p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
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
            once: true,
            disable: 'mobile' /* Opsi: 'mobile', 'tablet', atau true (mati total) */
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