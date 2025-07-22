<?php
include '../../../config/koneksi.php';
cek_admin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Ambil data alternatif beserta user
$query = mysqli_query($koneksidb, "
    SELECT a.*, u.username 
    FROM alternatives a
    JOIN users u ON a.user_id = u.id
    WHERE a.id = $id
");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>
        Swal.fire('Error!', 'Data tidak ditemukan.', 'error').then(() => {
            window.location.href = '" . base_url('dashboard/admin/alternatif/index.php') . "';
        });
    </script>";
    exit;
}

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code']);
    $name = trim($_POST['name']);
    $alamat = trim($_POST['alamat']);
    $no_hp = trim($_POST['no_hp']);
    $umur = (int) $_POST['umur'];

    if ($code === '' || $name === '' || $alamat === '' || $no_hp === '' || !$umur) {
        $alert = "Swal.fire('Gagal!', 'Semua field wajib diisi.', 'error');";
    } else {
        // Cek apakah kode dipakai oleh guru lain
        $cek = mysqli_query($koneksidb, "SELECT * FROM alternatives WHERE code = '$code' AND id != $id");
        if (mysqli_num_rows($cek) > 0) {
            $alert = "Swal.fire('Gagal!', 'Kode guru sudah digunakan oleh data lain.', 'error');";
        } else {
            $update = mysqli_query($koneksidb, "
                UPDATE alternatives 
                SET code = '$code', name = '$name', alamat = '$alamat', no_hp = '$no_hp', umur = '$umur' 
                WHERE id = $id
            ");
            if ($update) {
                header('Location: ' . base_url('dashboard/admin/alternatif/index.php?success=update'));
                exit;
            } else {
                $alert = "Swal.fire('Gagal!', 'Gagal menyimpan perubahan.', 'error');";
            }
        }
    }
}
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Data Guru</h3>
        <div class="card-tools">
            <a href="<?= base_url('dashboard/admin/alternatif/index.php') ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="form-group">
                <label>Kode Guru</label>
                <input type="text" name="code" class="form-control" required value="<?= htmlspecialchars($data['code']) ?>">
            </div>

            <div class="form-group">
                <label>Nama Guru</label>
                <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($data['name']) ?>">
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" class="form-control" readonly value="<?= htmlspecialchars($data['username']) ?>">
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" required><?= htmlspecialchars($data['alamat']) ?></textarea>
            </div>

            <div class="form-group">
                <label>No HP</label>
                <input type="text" name="no_hp" class="form-control" required value="<?= htmlspecialchars($data['no_hp']) ?>">
            </div>

            <div class="form-group">
                <label>Umur</label>
                <input type="number" name="umur" class="form-control" required value="<?= htmlspecialchars($data['umur']) ?>">
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (!empty($alert)): ?>
    <script><?= $alert ?></script>
<?php endif; ?>
