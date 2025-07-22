<?php
// Ambil data kriteria & subkriteria
$criterion_id = isset($_GET['criterion_id']) ? (int) $_GET['criterion_id'] : 0;
$edit_id = isset($_GET['edit']) ? (int) $_GET['edit'] : null;

$kriteria = mysqli_query($koneksidb, "SELECT * FROM criteria WHERE id = $criterion_id");
$k = mysqli_fetch_assoc($kriteria);
if (!$k) {
    echo "<script>alert('Kriteria tidak ditemukan'); window.location.href='" . base_url('dashboard/admin/kriteria/index.php') . "';</script>";
    exit;
}

// Jika form dikirim
$alert = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $value = floatval($_POST['value']);

    if ($name === '' || $value <= 0) {
        $alert = "Swal.fire('Gagal!', 'Nama dan nilai harus diisi dengan benar.', 'error');";
    } else {
        if (isset($_POST['subkriteria_id']) && $_POST['subkriteria_id']) {
            // Update
            $id = (int) $_POST['subkriteria_id'];
            $query = mysqli_query($koneksidb, "UPDATE sub_criteria SET name = '$name', value = $value WHERE id = $id AND criterion_id = $criterion_id");
            if ($query) {
                header("Location: " . base_url("dashboard/admin/subkriteria/index.php?criterion_id=$criterion_id&success=update"));
                exit;
            } else {
                $alert = "Swal.fire('Gagal!', 'Gagal menyimpan perubahan.', 'error');";
            }
        } else {
            // Insert
            $query = mysqli_query($koneksidb, "INSERT INTO sub_criteria (criterion_id, name, value) VALUES ($criterion_id, '$name', $value)");
            if ($query) {
                header("Location: " . base_url("dashboard/admin/subkriteria/index.php?criterion_id=$criterion_id&success=simpan"));
                exit;
            } else {
                $alert = "Swal.fire('Gagal!', 'Gagal menambahkan data.', 'error');";
            }
        }
    }
}

// Ambil data edit jika ada
$edit_data = null;
if ($edit_id) {
    $edit_query = mysqli_query($koneksidb, "SELECT * FROM sub_criteria WHERE id = $edit_id AND criterion_id = $criterion_id");
    $edit_data = mysqli_fetch_assoc($edit_query);
}

// Ambil semua subkriteria
$subs = mysqli_query($koneksidb, "SELECT * FROM sub_criteria WHERE criterion_id = $criterion_id ORDER BY value DESC");
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="<?= base_url('dashboard/admin/kriteria/index.php') ?>" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <?= $edit_data ? 'Edit Subkriteria' : 'Tambah Subkriteria' ?>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="subkriteria_id" value="<?= $edit_data['id'] ?? '' ?>">
                <div class="form-group">
                    <label>Nama Subkriteria</label>
                    <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($edit_data['name'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Nilai</label>
                    <input type="number" step="0.01" min="0" name="value" class="form-control" required value="<?= htmlspecialchars($edit_data['value'] ?? '') ?>">
                </div>
                <button type="submit" class="btn btn-primary"><?= $edit_data ? 'Update' : 'Simpan' ?></button>
                <?php if ($edit_data): ?>
                    <a href="<?= base_url('dashboard/admin/subkriteria/index.php?criterion_id=' . $criterion_id) ?>" class="btn btn-secondary ml-2">Batal</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Nama Subkriteria</th>
                <th>Nilai</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($subs)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= (int) $row['value'] ?></td>
                    <td>
                        <a href="?criterion_id=<?= $criterion_id ?>&edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="#" class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $row['id'] ?>)">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (!empty($alert)): ?>
<script><?= $alert ?></script>
<?php endif; ?>

<?php if (isset($_GET['success'])): ?>
<script>
    <?php if ($_GET['success'] == 'simpan'): ?>
        Swal.fire('Berhasil!', 'Subkriteria berhasil ditambahkan.', 'success');
    <?php elseif ($_GET['success'] == 'update'): ?>
        Swal.fire('Berhasil!', 'Subkriteria berhasil diperbarui.', 'success');
    <?php elseif ($_GET['success'] == 'delete'): ?>
        Swal.fire('Berhasil!', 'Subkriteria berhasil dihapus.', 'success');
    <?php endif; ?>
</script>
<?php endif; ?>

<script>
    function confirmDelete(id) {
        const baseUrl = "<?= base_url('dashboard/admin/subkriteria/delete.php?criterion_id=' . $criterion_id) ?>";
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = baseUrl + '&id=' + id;
            }
        });
    }
</script>
