<?php
include '../../../config/koneksi.php';
cek_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($koneksidb, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek username sudah ada?
    $cek = mysqli_query($koneksidb, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>
            alert('Username sudah digunakan!');
            window.location.href = 'create.php';
        </script>";
        exit;
    }

    $query = mysqli_query($koneksidb, "INSERT INTO users (username, password, role) VALUES ('$username', '$password', 'admin')");

    if ($query) {
        header('Location: ' . base_url('dashboard/admin/user/index.php?success=simpan'));
        exit;
    } else {
        echo "Gagal menambahkan user.";
    }
}
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Isikan Data User</h3>
        <div class="card-tools">
            <a href="<?= base_url('dashboard/admin/user/index.php') ?>" class="btn btn-primary">Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
