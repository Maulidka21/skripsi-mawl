<?php
include '../../../config/koneksi.php';
cek_admin();

$alert = '';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Ambil data kriteria
$query = mysqli_query($koneksidb, "SELECT * FROM criteria WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>
        Swal.fire('Error!', 'Data tidak ditemukan.', 'error').then(() => {
            window.location.href = '" . base_url('dashboard/admin/kriteria/index.php') . "';
        });
    </script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code']);
    $name = trim($_POST['name']);
    $weight = floatval($_POST['weight']);
    $type = $_POST['type'];

    if ($code === '' || $name === '' || $weight <= 0 || !in_array($type, ['benefit', 'cost'])) {
        $alert = "Swal.fire('Gagal!', 'Data tidak lengkap atau tidak valid.', 'error');";
    } else {
        // Cek kode kriteria unik selain dirinya sendiri
        $cek = mysqli_query($koneksidb, "SELECT * FROM criteria WHERE code = '$code' AND id != $id");
        if (mysqli_num_rows($cek) > 0) {
            $alert = "Swal.fire('Gagal!', 'Kode kriteria sudah digunakan oleh kriteria lain.', 'error');";
        } else {
            $update = mysqli_query($koneksidb, "UPDATE criteria SET code = '$code', name = '$name', weight = '$weight', type = '$type' WHERE id = $id");
            if ($update) {
                header('Location: ' . base_url('dashboard/admin/kriteria/index.php?success=update'));
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
        <h3 class="card-title">Edit Kriteria</h3>
        <div class="card-tools">
            <a href="<?= base_url('dashboard/admin/kriteria/index.php') ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="form-group">
                <label>Kode Kriteria</label>
                <input type="text" name="code" class="form-control" required value="<?= htmlspecialchars($data['code']) ?>">
            </div>

            <div class="form-group">
                <label>Nama Kriteria</label>
                <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($data['name']) ?>">
            </div>

            <div class="form-group">
                <label>Bobot</label>
                <input type="number" step="0.01" min="0" name="weight" class="form-control" required value="<?= htmlspecialchars($data['weight']) ?>">
            </div>

            <div class="form-group">
                <label>Tipe</label>
                <select name="type" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    <option value="benefit" <?= $data['type'] === 'benefit' ? 'selected' : '' ?>>Benefit</option>
                    <option value="cost" <?= $data['type'] === 'cost' ? 'selected' : '' ?>>Cost</option>
                </select>
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
