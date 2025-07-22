<?php
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksidb, $_GET['search']) : '';
$query = "SELECT * FROM criteria";

if (!empty($search)) {
    $query .= " WHERE name LIKE '%$search%' OR code LIKE '%$search%'";
}

$kriteria = mysqli_query($koneksidb, $query);
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="create.php" class="btn btn-primary mb-3">Tambah Kriteria</a>
        <form class="form-inline" method="GET">
            <input type="text" name="search" class="form-control" placeholder="Cari kode/nama..."
                   value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            <button type="submit" class="btn btn-secondary ml-2">Cari</button>
        </form>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Bobot</th>
                <th>Tipe</th>
                <th>Created At</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($kriteria)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['code']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= $row['weight'] ?></td>
                    <td><?= ucfirst($row['type']) ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="#" class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $row['id'] ?>)">Hapus</a>
                        <a href="<?= base_url('dashboard/admin/subkriteria/index.php?criterion_id=' . $row['id']) ?>"
                           class="btn btn-sm btn-info">Sub Kriteria</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Bobot</th>
                <th>Tipe</th>
                <th>Created At</th>
                <th>Aksi</th>
            </tr>
        </tfoot>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        const baseUrl = "<?= base_url('dashboard/admin/kriteria/delete.php') ?>";
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data ini tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = baseUrl + '?id=' + id;
            }
        })
    }
</script>
