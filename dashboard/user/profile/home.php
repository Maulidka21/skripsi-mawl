<?php
include '../../../config/koneksi.php';

$userId = $_SESSION['user']['id'];

$query = "SELECT u.username, u.role, a.name, a.code, a.alamat, a.no_hp, a.umur
          FROM users u
          LEFT JOIN alternatives a ON a.user_id = u.id
          WHERE u.id = '$userId'";

$result = mysqli_query($koneksidb, $query);

$data = mysqli_fetch_assoc($result);
?>

<div class="card">
    <div class="card-header bg-info text-white">
        <h3 class="card-title">Informasi Akun</h3>
    </div>
    <div class="card-body">
        <p><strong>Nama:</strong> <?= htmlspecialchars($data['name'] ?? '-') ?></p>
        <p><strong>Kode:</strong> <?= htmlspecialchars($data['code'] ?? '-') ?></p>
        <p><strong>Alamat:</strong> <?= htmlspecialchars($data['alamat'] ?? '-') ?></p>
        <p><strong>No HP:</strong> <?= htmlspecialchars($data['no_hp'] ?? '-') ?></p>
        <p><strong>Umur:</strong> <?= htmlspecialchars($data['umur'] ?? '-') ?></p>
        <hr>
        <p><strong>Username:</strong> <?= htmlspecialchars($data['username']) ?></p>
        <p><strong>Role:</strong> <?= htmlspecialchars($data['role']) ?></p>
    </div>
    <div class="card-footer">
        <a href="edit.php" class="btn btn-primary btn-sm">Edit Profil</a>
    </div>

</div>