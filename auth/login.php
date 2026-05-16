<?php
ob_start();
session_start();

// FIX UTAMA: Pasang deteksi Base URL dinamis di top file login
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$host = $_SERVER['HTTP_HOST'];
$base_url = $protocol . "://" . $host . "/sekolah_kita/";

// Jalur koneksi mundur satu folder
if (file_exists('../config/koneksi.php')) {
    include '../config/koneksi.php';
}

// Redirect otomatis jika session masih aktif menggunakan Base URL
if (isset($_SESSION['status']) && $_SESSION['status'] == "login") {
    switch ($_SESSION['role']) {
        case 'admin': header("location:" . $base_url . "admin/index.php"); exit;
        case 'guru':  header("location:" . $base_url . "guru/index.php"); exit;
        case 'siswa': header("location:" . $base_url . "siswa/index.php"); exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Portal Portal Login | SMK Angkasa</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        :root {
            --navy-utama: #0a192f;
            --navy-terang: #112240;
            --emas: #fbbf24;
            --putih: #e6f1ff;
            --abu: #8892b0;
            --biru-code: #64ffda;
        }

        body {
            height: 100vh;
            width: 100%;
            font-family: 'Poppins', sans-serif;
            background: var(--navy-utama);
            overflow: hidden;
        }

        .container {
            display: flex;
            height: 100%;
            width: 100%;
        }

        /* --- SISI KIRI: FORM LOGIN --- */
        .login-side {
            width: 40%;
            height: 100%;
            background: var(--navy-utama);
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 100;
            padding: 45px;
            justify-content: space-between; 
            border-right: 1px solid #1e2d4a;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 0 0 auto;
        }
        .brand img {
            width: 48px; height: 48px;
            filter: drop-shadow(0 4px 12px rgba(100, 255, 218, 0.15));
        }
        .brand-text h3 {
            font-weight: 600; font-size: 20px; color: var(--putih); margin: 0; letter-spacing: 0.5px;
        }
        .brand-text span {
            font-size: 11px; color: var(--emas); letter-spacing: 2px; text-transform: uppercase; font-weight: 500;
        }

        .login-content {
            width: 100%;
            max-width: 400px;
            margin: auto 0; /* Auto-centering vertikal yang fleksibel */
            display: flex;
            flex-direction: column;
        }

        .login-header h1 { 
            font-size: 32px; font-weight: 700; margin-bottom: 8px; color: var(--putih);
            letter-spacing: -0.5px;
        }
        .login-header p { 
            color: var(--abu); font-size: 14px; margin-bottom: 32px; line-height: 1.6;
        }

        .form-group { margin-bottom: 22px; position: relative; }
        
        .form-label { 
            display: block; margin-bottom: 10px; font-size: 11px; 
            font-weight: 600; color: var(--biru-code); text-transform: uppercase; letter-spacing: 1px;
        }
        
        .input-wrapper { position: relative; }

        .form-input {
            width: 100%; padding: 15px 20px 15px 48px;
            background: rgba(17, 34, 64, 0.75);
            border: 1px solid #233554;
            border-radius: 10px;
            color: var(--putih); font-size: 14px; outline: none; 
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .form-input:focus { 
            border-color: var(--emas); 
            background: rgba(21, 39, 69, 0.9);
            box-shadow: 0 0 20px rgba(251, 191, 36, 0.15);
        }

        .input-icon {
            position: absolute; left: 18px; top: 50%;
            transform: translateY(-50%); color: var(--abu); font-size: 16px;
            transition: color 0.3s;
        }
        .form-input:focus + .input-icon { color: var(--emas); }

        .toggle-password {
            position: absolute; right: 18px; top: 50%;
            transform: translateY(-50%); color: var(--abu); cursor: pointer; padding: 5px;
        }
        .toggle-password:hover { color: var(--putih); }

        .btn-submit {
            width: 100%; padding: 16px;
            background: linear-gradient(135deg, var(--emas), #d97706);
            color: #0a192f; border: none; border-radius: 10px;
            font-weight: 700; font-size: 14px; cursor: pointer; 
            transition: all 0.3s; margin-top: 12px;
            text-transform: uppercase; letter-spacing: 1.5px;
            box-shadow: 0 4px 14px rgba(251, 191, 36, 0.3);
        }
        .btn-submit:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 6px 20px rgba(251, 191, 36, 0.5); 
        }
        .btn-submit:active { transform: translateY(0); }

        .footer-link {
            text-align: center; font-size: 12px; color: var(--abu); line-height: 1.8;
            flex: 0 0 auto;
        }
        .footer-link a { color: var(--emas); text-decoration: none; font-weight: 500; }
        .footer-link a:hover { text-decoration: underline; }

        .alert {
            padding: 14px 18px; border-radius: 8px; font-size: 13px; margin-bottom: 24px;
            display: flex; align-items: center; gap: 10px; border-left: 4px solid;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .alert-danger { background: rgba(239, 68, 68, 0.12); border-color: #ef4444; color: #f87171; }
        .alert-success { background: rgba(16, 185, 129, 0.12); border-color: #10b981; color: #34d399; }
        .alert-warning { background: rgba(245, 158, 11, 0.12); border-color: #f59e0b; color: #fbbf24; }

        /* --- SISI KANAN: VISUAL (Premium Wireframe & Glassmorphism) --- */
        .visual-side {
            flex: 1;
            background: radial-gradient(circle at center, #172a45 0%, #0a192f 100%);
            position: relative;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
        }

        .grid-pattern {
            position: absolute; width: 100%; height: 100%;
            background-image: 
                linear-gradient(rgba(255, 255, 255, 0.015) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.015) 1px, transparent 1px);
            background-size: 45px 45px;
        }

        .code-block {
            background: rgba(17, 34, 64, 0.65);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(35, 53, 84, 0.7);
            border-radius: 16px;
            padding: 35px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: var(--abu);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
            max-width: 520px; width: 85%;
            transform: perspective(1000px) rotateY(-8deg) rotateX(2deg);
            z-index: 10;
        }

        .code-header { display: flex; gap: 8px; margin-bottom: 24px; padding-bottom: 14px; border-bottom: 1px solid #1e2d4a; }
        .dot { width: 11px; height: 11px; border-radius: 50%; }
        .red { background: #ff5f56; } .yellow { background: #ffbd2e; } .green { background: #27c93f; }
        
        .code-content { line-height: 1.9; }
        .kwd { color: #c678dd; } .str { color: #98c379; } .fnc { color: #61afef; }
        .var { color: #e5c07b; } .cmt { color: #5c6370; font-style: italic; }

        /* ========================================= */
        /* MEDIA QUERIES ULTRALIGHT & RESPONSIVE     */
        /* ========================================= */
        @media (max-width: 1024px) {
            .login-side { width: 50%; padding: 35px; }
        }

        @media (max-width: 900px) {
            .visual-side { display: none; }
            body { overflow: auto; } /* Aktifkan scroll container jika device terlalu pendek */
            .login-side {
                width: 100%;
                min-height: 100vh;
                padding: 30px 24px;
                justify-content: flex-start;
                gap: 40px; /* Distribusi space menggunakan layout gap terukur */
            }
            .login-content {
                margin: 0 auto;
                max-width: 100%;
                flex-grow: 1;
                justify-content: center;
            }
            .login-header { text-align: center; margin-bottom: 25px; }
            .login-header h1 { font-size: 26px; }
            .footer-link { margin-top: auto; padding-top: 20px; }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="login-side">
            
            <div class="brand">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b2/Logo_TNI_AU.png" alt="Logo">
                <div class="brand-text">
                    <h3>SMK ANGKASA</h3>
                    <span>Login System v2.1</span>
                </div>
            </div>
            
            <div class="login-content">
                <div class="login-header">
                    <h1>Selamat Datang</h1>
                    <p>Silakan login untuk mengakses akun E-Learning Anda.</p>
                </div>

                <?php 
                if(isset($_GET['pesan'])){
                    if($_GET['pesan'] == "gagal"){
                        echo "<div class='alert alert-danger'><i class='fas fa-exclamation-triangle'></i> Username atau Password salah!</div>";
                    } else if($_GET['pesan'] == "logout"){
                        echo "<div class='alert alert-success'><i class='fas fa-check-circle'></i> Anda berhasil keluar dari sistem.</div>";
                    } else if($_GET['pesan'] == "belum_login"){
                        echo "<div class='alert alert-warning'><i class='fas fa-lock'></i> Sesi kadaluarsa, silakan login kembali.</div>";
                    }
                }
                ?>

                <form action="cek_login.php" method="POST">
                    <div class="form-group">
                        <label class="form-label">Username / NISN / NIP</label>
                        <div class="input-wrapper">
                            <input type="text" name="username" class="form-input" placeholder="Masukkan ID Pengguna" required autocomplete="off">
                            <i class="fas fa-user input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-wrapper">
                            <input type="password" name="password" id="passInput" class="form-input" placeholder="Masukkan Kata Sandi" required>
                            <i class="fas fa-lock input-icon"></i>
                            <i class="fas fa-eye toggle-password" onclick="togglePass()"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        Masuk Sistem <i class="fas fa-sign-in-alt" style="margin-left:8px;"></i>
                    </button>
                </form>
            </div>

            <div class="footer-link">
                &copy; 2026 Tim IT SMK Angkasa Lanud Husein Sastranegara.<br>
                <a href="<?php echo $base_url; ?>"><i class="fas fa-arrow-left fa-xs"></i> Kembali ke Beranda</a>
            </div>
        </div>

        <div class="visual-side">
            <div class="grid-pattern"></div>
            
            <div class="code-block">
                <div class="code-header">
                    <div class="dot red"></div><div class="dot yellow"></div><div class="dot green"></div>
                    <div style="margin-left:auto; font-size:10px; opacity:0.4; font-family:sans-serif;">auth_logger.js</div>
                </div>
                
                <div class="code-content">
                    <span class="cmt">// Memulai Handshake Enkripsi Proteksi...</span><br>
                    <span class="kwd">const</span> <span class="var">sekolah</span> = {<br>
                    &nbsp;&nbsp;<span class="var">nama</span>: <span class="str">"SMK Angkasa"</span>,<br>
                    &nbsp;&nbsp;<span class="var">status</span>: <span class="str">"Terakreditasi A"</span><br>
                    };<br>
                    <br>
                    <span class="kwd">function</span> <span class="fnc">loginCheck</span>(<span class="var">user</span>) {<br>
                    &nbsp;&nbsp;<span class="kwd">if</span> (<span class="var">user</span>.valid) {<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="kwd">return</span> <span class="str">"Akses Diterima ✅"</span>;<br>
                    &nbsp;&nbsp;} <span class="kwd">else</span> {<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="kwd">return</span> <span class="str">"Akses Ditolak ❌"</span>;<br>
                    &nbsp;&nbsp;}<br>
                    }<br>
                    <span class="fnc">console</span>.log(<span class="str">"Secure Authentication Framework Ready."</span>);
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePass() {
            const x = document.getElementById("passInput");
            const icon = document.querySelector(".toggle-password");
            
            if (x.type === "password") {
                x.type = "text";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                x.type = "password";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }
    </script>
</body>
</html>