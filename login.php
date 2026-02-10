<?php
// 1. FIX HEADER & SESSION
ob_start();
session_start();

// File koneksi
if (file_exists('config/koneksi.php')) {
    include 'config/koneksi.php';
}

// Redirect jika sudah login
if(isset($_SESSION['status']) && $_SESSION['status'] == "login"){
    if($_SESSION['role'] == 'admin'){ header("location:admin/index.php"); }
    else if($_SESSION['role'] == 'guru'){ header("location:guru/index.php"); }
    else if($_SESSION['role'] == 'siswa'){ header("location:siswa/index.php"); }
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login System | SMK Angkasa</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* --- RESET CSS --- */
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
            overflow: hidden; /* Mencegah scroll yang tidak perlu */
        }

        .container {
            display: flex;
            height: 100%;
            width: 100%;
        }

        /* --- BAGIAN KIRI: LOGIN FORM --- */
        .login-side {
            width: 40%;
            height: 100%;
            background: var(--navy-utama);
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 100;
            padding: 40px;
            /* Default Desktop: Space Between biar Logo atas, Footer bawah */
            justify-content: space-between; 
            border-right: 1px solid #233554;
        }

        /* LOGO */
        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 0 0 auto; /* Ukuran tetap, jangan mengecil */
        }
        .brand img {
            width: 45px; height: 45px;
            filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.3));
        }
        .brand-text h3 {
            font-weight: 600; font-size: 22px; color: var(--putih); margin: 0;
        }
        .brand-text span {
            font-size: 11px; color: var(--emas); letter-spacing: 1.5px; text-transform: uppercase;
        }

        /* AREA FORM */
        .login-content {
            width: 100%;
            max-width: 450px;
            margin: 0 auto; /* Center horizontal */
            display: flex;
            flex-direction: column;
            justify-content: center; /* Center vertikal di desktop */
        }

        .login-header h1 { 
            font-size: 28px; font-weight: 700; margin-bottom: 10px; color: var(--putih);
        }
        .login-header p { 
            color: var(--abu); font-size: 14px; margin-bottom: 30px; line-height: 1.5;
        }

        /* INPUT FORM */
        .form-group { margin-bottom: 20px; position: relative; }
        
        .form-label { 
            display: block; margin-bottom: 8px; font-size: 12px; 
            font-weight: 600; color: var(--biru-code); text-transform: uppercase;
        }
        
        .input-wrapper { position: relative; }

        .form-input {
            width: 100%; padding: 14px 20px 14px 45px;
            background: var(--navy-terang);
            border: 1px solid #233554;
            border-radius: 8px;
            color: var(--putih); font-size: 14px; outline: none; transition: 0.3s;
        }

        .form-input:focus { 
            border-color: var(--emas); background: #152745;
            box-shadow: 0 0 10px rgba(251, 191, 36, 0.1);
        }

        .input-icon {
            position: absolute; left: 15px; top: 50%;
            transform: translateY(-50%); color: var(--abu); font-size: 16px;
        }

        .toggle-password {
            position: absolute; right: 15px; top: 50%;
            transform: translateY(-50%); color: var(--abu); cursor: pointer;
        }

        /* TOMBOL */
        .btn-submit {
            width: 100%; padding: 15px;
            background: linear-gradient(135deg, var(--emas), #d97706);
            color: #0a192f; border: none; border-radius: 8px;
            font-weight: 700; font-size: 14px; cursor: pointer; 
            transition: 0.3s; margin-top: 10px;
            text-transform: uppercase; letter-spacing: 1px;
            box-shadow: 0 4px 10px rgba(251, 191, 36, 0.2);
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(251, 191, 36, 0.4); }

        /* FOOTER */
        .footer-link {
            text-align: center; font-size: 12px; color: var(--abu);
            flex: 0 0 auto;
        }
        .footer-link a { color: var(--emas); text-decoration: none; }

        /* ALERT */
        .alert {
            padding: 12px; border-radius: 6px; font-size: 12px; margin-bottom: 20px;
            display: flex; align-items: center; gap: 8px; border-left: 4px solid;
        }
        .alert-danger { background: rgba(239, 68, 68, 0.1); border-color: #ef4444; color: #ef4444; }
        .alert-success { background: rgba(16, 185, 129, 0.1); border-color: #10b981; color: #10b981; }
        .alert-warning { background: rgba(245, 158, 11, 0.1); border-color: #f59e0b; color: #f59e0b; }

        /* --- BAGIAN KANAN: VISUAL (Animation) --- */
        .visual-side {
            flex: 1;
            background: linear-gradient(135deg, #0a192f 0%, #172a45 100%);
            position: relative;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
        }

        .grid-pattern {
            position: absolute; width: 100%; height: 100%;
            background-image: 
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .code-block {
            background: rgba(17, 34, 64, 0.95);
            border: 1px solid #233554;
            border-radius: 12px;
            padding: 30px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: var(--abu);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
            max-width: 500px; width: 85%;
            transform: perspective(1000px) rotateY(-5deg);
            z-index: 10;
        }

        .code-header { display: flex; gap: 6px; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid #233554; }
        .dot { width: 10px; height: 10px; border-radius: 50%; }
        .red { background: #ff5f56; } .yellow { background: #ffbd2e; } .green { background: #27c93f; }
        
        .kwd { color: #c678dd; } .str { color: #98c379; } .fnc { color: #61afef; }
        .var { color: #e5c07b; } .cmt { color: #5c6370; font-style: italic; } .num { color: #d19a66; }

        /* ========================================= */
        /* CSS KHUSUS ANDROID / HP          */
        /* ========================================= */
        @media (max-width: 900px) {
            .visual-side { display: none; } /* Kanan hilang */

            .login-side {
                width: 100%;
                padding: 25px 30px;
                display: flex;
                flex-direction: column;
                height: 100vh; /* Full Layar */
                justify-content: space-between; /* Memisahkan Logo(Atas) & Footer(Bawah) */
            }

            /* 1. LOGO & HEADER */
            .brand {
                justify-content: center;
                padding-top: 10px;
                flex: 0 0 auto;
                
                /* INI KUNCINYA: Memberi jarak 40px ke bawah (ke tulisan Selamat Datang) */
                margin-bottom: 20%; 
            }

            /* 2. AREA FORM (Selamat Datang s/d Tombol) */
            .login-content {
                /* Kita pakai flex-start biar dia nempel ke atas (setelah margin logo tadi) */
                justify-content: flex-start; 
                
                flex-grow: 1; /* Mengisi sisa ruang tengah */
                display: flex;
                flex-direction: column;
            }

            /* RAPETIN TULISAN & FORM */
            .login-header {
                text-align: center;
                margin-bottom: 15px; /* Jarak antara teks "Selamat Datang" ke Form Input */
            }
            .login-header h1 { font-size: 24px; margin-bottom: 5px; }
            .login-header p { font-size: 13px; margin-bottom: 0; }

            /* Jarak antar input biar rapi */
            .form-group { margin-bottom: 12px; }
            .btn-submit { margin-top: 5px; }

            /* 3. FOOTER (Tetap di Bawah) */
            .footer-link {
                margin-bottom: 10px; /* Jarak aman dari bibir bawah layar HP */
                flex: 0 0 auto;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="login-side">
            
            <div class="brand">
                <img src="https://upload.wikimedia.org/wikipedia/commons/1/1c/Lambang_TNI_AU.png" alt="Logo">
                <div class="brand-text">
                    <h3>SMK ANGKASA</h3>
                    <span>Login System v2.0</span>
                </div>
            </div>
            
            <div class="login-content">
                <div class="login-header">
                    <h1>Selamat Datang</h1>
                    <p>Silakan login untuk mengakses E-Learning.</p>
                </div>

                <?php 
                if(isset($_GET['pesan'])){
                    if($_GET['pesan'] == "gagal"){
                        echo "<div class='alert alert-danger'><i class='fas fa-exclamation-triangle'></i> Login Gagal!</div>";
                    } else if($_GET['pesan'] == "logout"){
                        echo "<div class='alert alert-success'><i class='fas fa-check-circle'></i> Berhasil Logout.</div>";
                    } else if($_GET['pesan'] == "belum_login"){
                        echo "<div class='alert alert-warning'><i class='fas fa-lock'></i> Sesi Habis.</div>";
                    }
                }
                ?>

                <form action="cek_login.php" method="POST">
                    <div class="form-group">
                        <label class="form-label">Username / NISN</label>
                        <div class="input-wrapper">
                            <input type="text" name="username" class="form-input" placeholder="ID Pengguna" required autocomplete="off">
                            <i class="fas fa-user input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-wrapper">
                            <input type="password" name="password" id="passInput" class="form-input" placeholder="Kata Sandi" required>
                            <i class="fas fa-lock input-icon"></i>
                            <i class="fas fa-eye toggle-password" onclick="togglePass()"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        MASUK SEKARANG <i class="fas fa-arrow-right" style="margin-left:8px;"></i>
                    </button>
                </form>
            </div>

            <div class="footer-link">
                &copy; 2026 Tim IT SMK Angkasa.<br>
                <a href="index.php">Kembali ke Beranda</a>
            </div>
        </div>

        <div class="visual-side">
            <div class="grid-pattern"></div>
            
            <div class="code-block">
                <div class="code-header">
                    <div class="dot red"></div><div class="dot yellow"></div><div class="dot green"></div>
                    <div style="margin-left:auto; font-size:10px; opacity:0.5;">auth.js</div>
                </div>
                
                <div class="code-content">
                    <span class="cmt">// Memulai Sistem...</span><br>
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
                    <span class="fnc">console</span>.log(<span class="str">"System Ready..."</span>);
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePass() {
            var x = document.getElementById("passInput");
            var icon = document.querySelector(".toggle-password");
            
            if (x.type === "password") {
                x.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                x.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>