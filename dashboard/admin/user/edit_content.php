<?php
include '../../../config/koneksi.php';
cek_admin();

$id = (int)$_GET['id'];
$result = mysqli_query($koneksidb, "SELECT * FROM users WHERE id = $id");
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "User tidak ditemukan.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($koneksidb, $_POST['username']);
    $password = $_POST['password'];

    // Update dengan password baru jika diisi
    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $query = mysqli_query($koneksidb, "UPDATE users SET username = '$username', password = '$hashed' WHERE id = $id");
    } else {
        $query = mysqli_query($koneksidb, "UPDATE users SET username = '$username' WHERE id = $id");
    }

    if ($query) {
        header('Location: ' . base_url('dashboard/admin/user/index.php?success=update'));
        exit;
    } else {
        echo "Gagal mengupdate user.";
    }
}
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Data User</h3>
        <div class="card-tools">
            <a href="<?= base_url('dashboard/admin/user/index.php') ?>" class="btn btn-primary">Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']); ?>" required>
            </div>

            <div class="form-group">
                <label>Password (kosongkan jika tidak diubah)</label>
                <input type="password" name="password" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
