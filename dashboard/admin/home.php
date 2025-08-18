<?php
include '../../config/koneksi.php';
// Ambil kriteria
$kriteriaData = mysqli_query($koneksidb, "SELECT id, code, name FROM criteria ORDER BY id");
$labels = [];
$dataJumlah = [];

while ($row = mysqli_fetch_assoc($kriteriaData)) {
    $labels[] = $row['code'] . ' - ' . $row['name'];

    $jumlahNilai = mysqli_num_rows(mysqli_query($koneksidb, "
        SELECT * FROM scores WHERE criterion_id = {$row['id']}
    "));

    $dataJumlah[] = $jumlahNilai;
}

$query = mysqli_query($koneksidb, "
    SELECT a.name, r.vi 
    FROM ranking r
    JOIN alternatives a ON r.alternative_id = a.id
    ORDER BY r.vi DESC
");

$labels = [];
$data = [];
while ($row = mysqli_fetch_assoc($query)) {
    $labels[] = $row['name'];
    $data[] = $row['vi'];
}

$kriteriaRadar = mysqli_query($koneksidb, "SELECT * FROM criteria ORDER BY id");
$kLabel = [];
$kRata = [];

while ($k = mysqli_fetch_assoc($kriteriaRadar)) {
    $kLabel[] = $k['code'];

    $nilai_query = mysqli_query($koneksidb, "
        SELECT 
            CASE 
                WHEN s.sub_criterion_id IS NOT NULL THEN sc.value 
                ELSE s.score 
            END AS nilai
        FROM scores s
        LEFT JOIN sub_criteria sc ON s.sub_criterion_id = sc.id
        WHERE s.criterion_id = {$k['id']}
    ");

    $total = 0;
    $count = 0;
    while ($n = mysqli_fetch_assoc($nilai_query)) {
        $total += $n['nilai'];
        $count++;
    }
    $kRata[] = $count > 0 ? round($total / $count, 2) : 0;
}

$queryTerbaik = "SELECT a.name AS nama_guru, a.code, u.username
                 FROM results r
                 JOIN alternatives a ON r.alternative_id = a.id
                 JOIN users u ON a.user_id = u.id
                 ORDER BY r.preference_value DESC
                 LIMIT 1";

$hasilTerbaik = mysqli_query($koneksidb, $queryTerbaik);
$terbaik = mysqli_fetch_assoc($hasilTerbaik);
?>
<div class="row">
    <!-- Jumlah Alternatif -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= mysqli_num_rows(mysqli_query($koneksidb, "SELECT * FROM alternatives")) ?></h3>
                <p>Jumlah Alternatif</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="<?= base_url('dashboard/admin/alternatif/index.php') ?>" class="small-box-footer">
                Lihat Alternatif <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Jumlah Kriteria -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= mysqli_num_rows(mysqli_query($koneksidb, "SELECT * FROM criteria")) ?></h3>
                <p>Jumlah Kriteria</p>
            </div>
            <div class="icon">
                <i class="fas fa-layer-group"></i>
            </div>
            <a href="<?= base_url('dashboard/admin/kriteria/index.php') ?>" class="small-box-footer">
                Lihat Kriteria <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Jumlah Penilaian -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= mysqli_num_rows(mysqli_query($koneksidb, "SELECT * FROM scores")) ?></h3>
                <p>Data Penilaian</p>
            </div>
            <div class="icon">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <a href="<?= base_url('dashboard/admin/penilaian/index.php') ?>" class="small-box-footer">
                Lihat Penilaian <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Jumlah Pengguna -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?= mysqli_num_rows(mysqli_query($koneksidb, "SELECT * FROM users")) ?></h3>
                <p>Jumlah Admin</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <a href="<?= base_url('dashboard/admin/user/index.php') ?>" class="small-box-footer">
                Lihat User <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="card mb-4">
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

<!-- <div class="row mt-4">
    Chart 1: Bar
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title">Visualisasi Jumlah Penilaian per Kriteria</h3>
            </div>
            <div class="card-body">
                <canvas id="chartKriteria" height="300"></canvas>
            </div>
        </div>
    </div>

    Chart 2: Pie
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title">Pie Chart Ranking</h3>
            </div>
            <div class="card-body">
                <canvas id="pieChartRanking" height="200"></canvas>
            </div>
        </div>
    </div>

    Chart 3: Radar
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title">Radar Nilai Rata-rata per Kriteria</h3>
            </div>
            <div class="card-body">
                <canvas id="radarChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div> -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('chartKriteria').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'Jumlah Penilaian',
                data: <?= json_encode($dataJumlah) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y', // Horizontal bar
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }

    });
</script>

<script>
    const ctxPie = document.getElementById('pieChartRanking').getContext('2d');
    const pieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: <?= json_encode($labels); ?>,
            datasets: [{
                label: 'Preferensi Kandidat',
                data: <?= json_encode($data); ?>,
                backgroundColor: [
                    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>

<script>
    const radarCtx = document.getElementById('radarChart').getContext('2d');
    new Chart(radarCtx, {
        type: 'radar',
        data: {
            labels: <?= json_encode($kLabel) ?>,
            datasets: [{
                label: 'Rata-rata Nilai',
                data: <?= json_encode($kRata) ?>,
                fill: true,
                backgroundColor: 'rgba(78, 115, 223, 0.2)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointBackgroundColor: 'rgba(78, 115, 223, 1)'
            }]
        },
        options: {
            responsive: true,
            elements: {
                line: {
                    borderWidth: 2
                }
            },
            scales: {
                r: {
                    beginAtZero: true,
                    min: 0
                }
            }
        }
    });
</script>