<?php
session_start();
include '../../config/koneksi.php';

// Cek Login Siswa
if($_SESSION['role'] != 'siswa'){ header("location:../../login.php"); exit(); }

// Ambil ID Ujian dari URL
$id_ujian = isset($_GET['id']) ? $_GET['id'] : 0;
$id_siswa = $_SESSION['id_user'];

// Cek Data Ujian
$q_ujian = mysqli_query($koneksi, "SELECT * FROM tb_ujian WHERE id_ujian='$id_ujian'");
$ujian = mysqli_fetch_array($q_ujian);

// Jika ujian tidak ditemukan atau belum waktunya
if(!$ujian){ die("Ujian tidak ditemukan."); }

// Ambil Soal (Diacak biar tiap siswa beda urutan)
$q_soal = mysqli_query($koneksi, "SELECT * FROM tb_soal_ujian WHERE id_ujian='$id_ujian' ORDER BY RAND()");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UJIAN: <?php echo $ujian['judul_ujian']; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { 
            background-color: #f0f2f5; 
            user-select: none; /* Gak bisa blok teks */
            -webkit-user-select: none;
            overflow-x: hidden;
        }
        .soal-box {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            border-left: 5px solid #0a192f; /* Navy */
        }
        .timer-box {
            position: fixed;
            top: 20px; right: 20px;
            background: #d90429;
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            font-weight: bold;
            font-size: 1.2rem;
            box-shadow: 0 4px 15px rgba(217, 4, 41, 0.4);
            z-index: 9999;
            animation: pulse 1s infinite;
        }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.05); } 100% { transform: scale(1); } }
        
        /* Opsi Jawaban Radio Custom */
        .form-check-input:checked { background-color: #0a192f; border-color: #0a192f; }
        .form-check-label { cursor: pointer; width: 100%; display: block; padding: 5px; }
        .form-check:hover { background-color: #f8f9fa; border-radius: 5px; }
    </style>
</head>
<body oncontextmenu="return false;"> <div class="timer-box">
        <i class="fas fa-stopwatch me-2"></i> <span id="sisa_waktu">00:00:00</span>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <div class="text-center mb-5">
                    <h3 class="fw-bold text-dark"><?php echo $ujian['judul_ujian']; ?></h3>
                    <p class="text-muted">Jawablah dengan jujur. Segala bentuk kecurangan akan terdeteksi sistem.</p>
                    <div class="alert alert-warning small">
                        <i class="fas fa-exclamation-triangle me-2"></i> 
                        <b>PERINGATAN:</b> Dilarang pindah tab, minimize browser, atau screenshot. 
                        Sistem akan otomatis mengirim jawaban jika terdeteksi curang 3x!
                    </div>
                </div>

                <form action="proses_selesai.php" method="POST" id="formUjian">
                    <input type="hidden" name="id_ujian" value="<?php echo $id_ujian; ?>">
                    
                    <?php 
                    $no = 1;
                    while($s = mysqli_fetch_array($q_soal)){ 
                    ?>
                    <div class="soal-box">
                        <div class="d-flex justify-content-between">
                            <h5 class="fw-bold mb-3">Soal No. <?php echo $no; ?></h5>
                            <span class="badge bg-light text-secondary border">Pilihan Ganda</span>
                        </div>
                        <p class="lead mb-4"><?php echo $s['pertanyaan']; ?></p>
                        
                        <div class="d-grid gap-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jawaban[<?php echo $s['id_soal']; ?>]" value="A" id="opsiA_<?php echo $no; ?>">
                                <label class="form-check-label" for="opsiA_<?php echo $no; ?>">A. <?php echo $s['opsi_a']; ?></label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jawaban[<?php echo $s['id_soal']; ?>]" value="B" id="opsiB_<?php echo $no; ?>">
                                <label class="form-check-label" for="opsiB_<?php echo $no; ?>">B. <?php echo $s['opsi_b']; ?></label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jawaban[<?php echo $s['id_soal']; ?>]" value="C" id="opsiC_<?php echo $no; ?>">
                                <label class="form-check-label" for="opsiC_<?php echo $no; ?>">C. <?php echo $s['opsi_c']; ?></label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jawaban[<?php echo $s['id_soal']; ?>]" value="D" id="opsiD_<?php echo $no; ?>">
                                <label class="form-check-label" for="opsiD_<?php echo $no; ?>">D. <?php echo $s['opsi_d']; ?></label>
                            </div>
                        </div>
                    </div>
                    <?php $no++; } ?>

                    <div class="d-grid mt-5 mb-5">
                        <button type="submit" name="selesai" class="btn btn-primary btn-lg py-3 fw-bold rounded-pill" onclick="return confirm('Yakin ingin menyelesaikan ujian? Jawaban tidak bisa diubah lagi.')">
                            <i class="fas fa-paper-plane me-2"></i> KIRIM JAWABAN & SELESAI
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        // 1. HITUNG MUNDUR WAKTU (Dari PHP ke JS)
        // Kita set deadline berdasarkan waktu di database
        var countDownDate = new Date("<?php echo $ujian['waktu_selesai']; ?>").getTime();

        var x = setInterval(function() {
            var now = new Date().getTime();
            var distance = countDownDate - now;

            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("sisa_waktu").innerHTML = hours + "j " + minutes + "m " + seconds + "d ";

            // Kalau waktu habis
            if (distance < 0) {
                clearInterval(x);
                Swal.fire({
                    icon: 'error',
                    title: 'WAKTU HABIS!',
                    text: 'Jawaban Anda akan dikirim otomatis.',
                    allowOutsideClick: false,
                    timer: 3000,
                    timerProgressBar: true
                }).then((result) => {
                    document.getElementById("formUjian").submit();
                });
            }
        }, 1000);

        // 2. ANTI-CURANG SYSTEM (Jebakan Batman)
        let pelanggaran = 0;
        const maxPelanggaran = 3;

        // Deteksi Pindah Tab / Minimize Browser
        document.addEventListener("visibilitychange", function() {
            if (document.hidden) {
                pelanggaran++;
                checkPelanggaran();
            }
        });

        // Deteksi Blur (Klik di luar browser)
        window.onblur = function() {
            pelanggaran++;
            checkPelanggaran();
        };

        function checkPelanggaran() {
            if(pelanggaran < maxPelanggaran) {
                Swal.fire({
                    icon: 'warning',
                    title: 'PERINGATAN KERAS! (' + pelanggaran + '/' + maxPelanggaran + ')',
                    text: 'Anda terdeteksi meninggalkan halaman ujian! Jangan coba-coba membuka Google atau aplikasi lain.',
                    confirmButtonText: 'Saya Mengerti & Kembali Mengerjakan',
                    allowOutsideClick: false
                });
            } else {
                // HUKUMAN MATI (Auto Submit)
                Swal.fire({
                    icon: 'error',
                    title: 'PELANGGARAN BERAT!',
                    text: 'Sistem mendeteksi aktivitas mencurigakan berulang kali. Ujian Anda dihentikan paksa.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    timer: 3000
                });
                setTimeout(() => {
                    document.getElementById("formUjian").submit();
                }, 3000);
            }
        }

        // 3. BLOKIR TOMBOL (Inspect Element, Copy, Paste)
        document.onkeydown = function(e) {
            // Blok F12, Ctrl+U, Ctrl+Shift+I, Ctrl+C, Ctrl+V
            if (e.keyCode == 123 || 
                (e.ctrlKey && e.keyCode == 85) || 
                (e.ctrlKey && e.shiftKey && e.keyCode == 73) ||
                (e.ctrlKey && e.keyCode == 67) || 
                (e.ctrlKey && e.keyCode == 86)) {
                return false;
            }
        };
    </script>

</body>
</html>