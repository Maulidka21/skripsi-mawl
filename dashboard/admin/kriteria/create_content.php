<?php
include '../../../config/koneksi.php';
cek_admin();

$alert = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code']);
    $name = trim($_POST['name']);
    $weight = floatval($_POST['weight']);
    $type = $_POST['type'];

    if ($code === '' || $name === '' || $weight <= 0 || !in_array($type, ['benefit', 'cost'])) {
        $alert = "Swal.fire('Gagal!', 'Data tidak lengkap atau tidak valid.', 'error');";
    } else {
        $cek = mysqli_query($koneksidb, "SELECT * FROM criteria WHERE code = '$code'");
        if (mysqli_num_rows($cek) > 0) {
            $alert = "Swal.fire('Gagal!', 'Kode kriteria sudah digunakan.', 'error');";
        } else {
            $query = mysqli_query($koneksidb, "INSERT INTO criteria (code, name, weight, type) VALUES ('$code', '$name', '$weight', '$type')");
            if ($query) {
                header('Location: ' . base_url('dashboard/admin/kriteria/index.php?success=simpan'));
                exit;
            } else {
                $alert = "Swal.fire('Gagal!', 'Gagal menambahkan kriteria.', 'error');";
            }
        }
    }
}
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Kriteria Penilaian</h3>
        <div class="card-tools">
            <a href="<?= base_url('dashboard/admin/kriteria/index.php') ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="form-group">
                <label>Kode Kriteria</label>
                <input type="text" name="code" class="form-control" required placeholder="Contoh: C1">
            </div>

            <div class="form-group">
                <label>Nama Kriteria</label>
                <input type="text" name="name" class="form-control" required placeholder="Contoh: Pendidikan">
            </div>

            <div class="form-group">
                <label>Bobot</label>
                <input type="number" name="weight" class="form-control" step="0.01" min="0" required placeholder="Contoh: 0.30">
            </div>

            <div class="form-group">
                <label>Tipe Kriteria</label>
                <select name="type" class="form-control" required>
                    <option value="">-- Pilih Tipe --</option>
                    <option value="benefit">Benefit</option>
                    <option value="cost">Cost</option>
                </select>
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
