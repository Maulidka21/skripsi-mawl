<?php
include '../../config/koneksi.php';
// Ambil data guru terbaik (peringkat 1 dari tabel ranking)
$query = "SELECT a.name AS nama_guru, a.code, u.username
          FROM results r
          JOIN alternatives a ON r.alternative_id = a.id
          JOIN users u ON a.user_id = u.id
          ORDER BY r. preference_value DESC
          LIMIT 1";

$hasil = mysqli_query($koneksidb, $query);
$terbaik = mysqli_fetch_assoc($hasil);
?>

<div class="card">
    <div class="card-header bg-success text-white">
        <h3 class="card-title">Guru Terbaik Saat Ini</h3>
    </div>
    <div class="card-body">
        <?php if ($terbaik): ?>
            <p><strong>Nama:</strong> <?= htmlspecialchars($terbaik['nama_guru']) ?></p>
            <p><strong>Kode:</strong> <?= htmlspecialchars($terbaik['code']) ?></p>
            <p><strong>Username:</strong> <?= htmlspecialchars($terbaik['username']) ?></p>
        <?php else: ?>
            <p>Belum ada data perhitungan ranking.</p>
        <?php endif; ?>
    </div>
    <div class="card-footer">
        <span class="badge badge-success">#1 - Terbaik</span>
    </div>
</div>
