<?php
session_start();
include '../../config/koneksi.php';
include '../../layouts/header.php';
include '../../layouts/sidebar.php';

// 1. AMBIL ID DARI URL
$id = $_GET['id'];

// 2. AMBIL DATA SISWA BERDASARKAN ID
$query_siswa = mysqli_query($koneksi, "SELECT * FROM tb_siswa WHERE id_siswa='$id'");
$data = mysqli_fetch_array($query_siswa);

// 3. LOGIKA UPDATE DATA
if(isset($_POST['update'])){
    $nisn     = $_POST['nisn'];
    $nama     = $_POST['nama'];
    $id_kelas = $_POST['id_kelas'];
    $username = $_POST['username'];
    
    // Logika Password: Jika kosong, pakai password lama. Jika isi, enkripsi baru.
    if(empty($_POST['password'])){
        $password = $data['password']; // Tetap password lama (sudah hash)
    } else {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash baru
    }

    // Logika Foto
    $foto = $data['foto']; // Default foto lama
    if($_FILES['foto']['name'] != ""){
        // Upload foto baru
        $foto = date('dmYHis')."_".$_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "../../uploads/profil/".$foto);
        
        // (Opsional) Hapus foto lama biar hemat memori server
        if($data['foto'] != 'default.jpg' && file_exists("../../uploads/profil/".$data['foto'])){
            unlink("../../uploads/profil/".$data['foto']);
        }
    }

    // Query Update
    $update = mysqli_query($koneksi, "UPDATE tb_siswa SET 
                nisn='$nisn', 
                nama_siswa='$nama', 
                id_kelas='$id_kelas', 
                username='$username', 
                password='$password', 
                foto='$foto' 
                WHERE id_siswa='$id'");

    if($update){
        echo "<script>alert('Data Berhasil Diupdate!'); window.location='data_siswa.php';</script>";
    } else {
        echo "<script>alert('Gagal update');</script>";
    }
}
?>

<div class="container-fluid p-4">
    <div class="card shadow-sm border-0" style="max-width: 600px; margin: auto;">
        <div class="card-header bg-warning text-dark">
            <h5 class="m-0"><i class="fas fa-edit me-2"></i> Edit Data Siswa</h5>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                
                <div class="mb-3">
                    <label>NISN</label>
                    <input type="number" name="nisn" class="form-control" value="<?php echo $data['nisn']; ?>" required>
                </div>

                <div class="mb-3">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="<?php echo $data['nama_siswa']; ?>" required>
                </div>

                <div class="mb-3">
                    <label>Kelas</label>
                    <select name="id_kelas" class="form-select" required>
                        <option value="">-- Pilih Kelas --</option>
                        <?php
                        $kelas = mysqli_query($koneksi, "SELECT * FROM tb_kelas ORDER BY nama_kelas ASC");
                        while($k = mysqli_fetch_array($kelas)){
                            // Logika agar kelas siswa terpilih otomatis (SELECTED)
                            if($data['id_kelas'] == $k['id_kelas']){
                                $selected = "selected";
                            } else {
                                $selected = "";
                            }
                            echo "<option value='$k[id_kelas]' $selected>$k[nama_kelas]</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" value="<?php echo $data['username']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diganti">
                        <small class="text-muted text-danger">*Isi hanya jika ingin ganti password</small>
                    </div>
                </div>

                <div class="mb-4">
                    <label>Ganti Foto</label><br>
                    <img src="../../uploads/profil/<?php echo $data['foto']; ?>" width="80" class="mb-2 rounded">
                    <input type="file" name="foto" class="form-control">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="data_siswa.php" class="btn btn-secondary">Batal</a>
                    <button type="submit" name="update" class="btn btn-primary bg-angkasa">UPDATE DATA</button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php include '../../layouts/footer.php'; ?>