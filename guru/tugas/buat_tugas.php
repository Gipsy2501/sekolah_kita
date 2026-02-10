<?php
session_start();
include '../../config/koneksi.php';
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

// Pastikan Guru yang login punya ID
$id_guru = $_SESSION['id_user'];

if(isset($_POST['kirim_tugas'])){
    $judul      = $_POST['judul'];
    $deskripsi  = $_POST['deskripsi'];
    $id_kelas   = $_POST['id_kelas'];
    $deadline   = $_POST['deadline']; // Format input datetime-local
    $tgl_buat   = date('Y-m-d');

    // Upload File Soal (PDF/Doc)
    $file_soal = "";
    if($_FILES['file_soal']['name'] != ""){
        $file_soal = date('dmYHis')."_".$_FILES['file_soal']['name'];
        move_uploaded_file($_FILES['file_soal']['tmp_name'], "../../uploads/tugas/".$file_soal);
    }

    $query = "INSERT INTO tb_tugas (judul_tugas, deskripsi, id_kelas, id_guru, tgl_buat, tgl_deadline, file_soal) 
              VALUES ('$judul', '$deskripsi', '$id_kelas', '$id_guru', '$tgl_buat', '$deadline', '$file_soal')";
    
    $simpan = mysqli_query($koneksi, $query);

    if($simpan){
        echo "<script>alert('Tugas Berhasil Dikirim ke Siswa!'); window.location='data_tugas.php';</script>";
    }
}
?>

<div class="container-fluid p-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-angkasa text-white">
            <h5 class="m-0"><i class="fas fa-edit me-2"></i> Buat Tugas Baru</h5>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="fw-bold">Judul Tugas</label>
                            <input type="text" name="judul" class="form-control" placeholder="Cth: Latihan HTML Dasar" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="fw-bold">Deskripsi / Instruksi</label>
                            <textarea name="deskripsi" class="form-control" rows="5" placeholder="Jelaskan detail tugas di sini..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Upload File Soal (Opsional)</label>
                            <input type="file" name="file_soal" class="form-control">
                            <small class="text-muted">Bisa berupa PDF, Word, atau Gambar.</small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card bg-light border-0 p-3">
                            <h6 class="fw-bold mb-3">Pengaturan Tugas</h6>
                            
                            <div class="mb-3">
                                <label>Target Kelas</label>
                                <select name="id_kelas" class="form-select" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php
                                    $kelas = mysqli_query($koneksi, "SELECT * FROM tb_kelas ORDER BY nama_kelas ASC");
                                    while($k = mysqli_fetch_array($kelas)){
                                        echo "<option value='$k[id_kelas]'>$k[nama_kelas]</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Deadline Pengumpulan</label>
                                <input type="datetime-local" name="deadline" class="form-control" required>
                            </div>

                            <hr>
                            <button type="submit" name="kirim_tugas" class="btn btn-primary bg-angkasa w-100">
                                <i class="fas fa-paper-plane me-2"></i> PUBLISH TUGAS
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<?php include '../../layouts/footer.php'; ?>