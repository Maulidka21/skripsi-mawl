<?php
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksidb, $_GET['search']) : '';
$query = "SELECT a.*, u.username 
          FROM alternatives a 
          JOIN users u ON a.user_id = u.id";

if (!empty($search)) {
    $query .= " WHERE a.name LIKE '%$search%' OR a.code LIKE '%$search%' OR u.username LIKE '%$search%'";
}

$alternatif = mysqli_query($koneksidb, $query);
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="create.php" class="btn btn-primary mb-3">Tambah Guru</a>
        <form class="form-inline" method="GET">
            <input type="text" name="search" class="form-control" placeholder="Cari kode/nama/username..."
                value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            <button type="submit" class="btn btn-secondary ml-2">Cari</button>
        </form>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>Kode</th>
                <th>Username</th>
                <th>Nama Guru</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($alternatif)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['code']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-info" 
                                data-toggle="modal"
                                data-target="#detailModal<?= $row['id'] ?>">
                            Detail
                        </button>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="#" class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $row['id'] ?>)">Hapus</a>
                    </td>
                </tr>

                <!-- Modal Detail -->
                <div class="modal fade" id="detailModal<?= $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel<?= $row['id'] ?>" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel<?= $row['id'] ?>">Detail Guru</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Kode:</strong> <?= htmlspecialchars($row['code']) ?></p>
                                <p><strong>Username:</strong> <?= htmlspecialchars($row['username']) ?></p>
                                <p><strong>Nama:</strong> <?= htmlspecialchars($row['name']) ?></p>
                                <p><strong>Alamat:</strong> <?= htmlspecialchars($row['alamat']) ?></p>
                                <p><strong>No HP:</strong> <?= htmlspecialchars($row['no_hp']) ?></p>
                                <p><strong>Umur:</strong> <?= htmlspecialchars($row['umur']) ?> tahun</p>
                                <p><strong>Created At:</strong> <?= htmlspecialchars($row['created_at']) ?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        const baseUrl = "<?= base_url('dashboard/admin/alternatif/delete.php') ?>";
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

<!-- Bootstrap JS (required for modal) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
