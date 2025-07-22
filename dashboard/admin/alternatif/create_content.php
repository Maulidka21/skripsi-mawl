<?php
include '../../../config/koneksi.php';
cek_admin();

$alert = ''; // digunakan untuk menyimpan skrip SweetAlert

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code']);
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $alamat = trim($_POST['alamat']);
    $no_hp = trim($_POST['no_hp']);
    $umur = (int) $_POST['umur'];

    if ($code === '' || $name === '' || $username === '' || $alamat === '' || $no_hp === '' || !$umur) {
        $alert = "Swal.fire('Gagal!', 'Semua field wajib diisi.', 'error');";
    } else {
        // Cek apakah username sudah ada
        $cek_user = mysqli_query($koneksidb, "SELECT * FROM users WHERE username = '$username'");
        if (mysqli_num_rows($cek_user) > 0) {
            $alert = "Swal.fire('Gagal!', 'Username sudah digunakan.', 'error');";
        } else {
            // Simpan ke table users
            $insert_user = mysqli_query($koneksidb, "INSERT INTO users (username, password, role) VALUES ('$username', '$password', 'user')");
            if ($insert_user) {
                $user_id = mysqli_insert_id($koneksidb); // ambil ID user yang baru disimpan

                // Cek kode alternatif (kode guru)
                $cek_alt = mysqli_query($koneksidb, "SELECT * FROM alternatives WHERE code = '$code'");
                if (mysqli_num_rows($cek_alt) > 0) {
                    $alert = "Swal.fire('Gagal!', 'Kode guru sudah digunakan.', 'error');";
                } else {
                    // Simpan ke table alternatives
                    $insert_alt = mysqli_query($koneksidb, "INSERT INTO alternatives 
                        (user_id, code, name, alamat, no_hp, umur) 
                        VALUES 
                        ('$user_id', '$code', '$name', '$alamat', '$no_hp', '$umur')");
                    
                    if ($insert_alt) {
                        header('Location: ' . base_url('dashboard/admin/alternatif/index.php?success=simpan'));
                        exit;
                    } else {
                        $alert = "Swal.fire('Gagal!', 'Gagal menyimpan data guru.', 'error');";
                    }
                }
            } else {
                $alert = "Swal.fire('Gagal!', 'Gagal menyimpan akun user.', 'error');";
            }
        }
    }
}

?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Data Guru</h3>
        <div class="card-tools">
            <a href="<?= base_url('dashboard/admin/alternatif/index.php') ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="form-group">
                <label>Kode Guru</label>
                <input type="text" name="code" class="form-control" required placeholder="Contoh: G01">
            </div>

            <div class="form-group">
                <label>Nama Guru</label>
                <input type="text" name="name" class="form-control" required placeholder="Contoh: Budi Santoso">
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required placeholder="Username login">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Password login">
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" required placeholder="Alamat lengkap"></textarea>
            </div>

            <div class="form-group">
                <label>No HP</label>
                <input type="text" name="no_hp" class="form-control" required placeholder="Contoh: 08123456789">
            </div>

            <div class="form-group">
                <label>Umur</label>
                <input type="number" name="umur" class="form-control" required placeholder="Contoh: 35">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>


<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (!empty($alert)): ?>
    <script><?= $alert ?></script>
<?php endif; ?>