<?php
// Ambil data
$alternatif = mysqli_query($koneksidb, "SELECT * FROM alternatives ORDER BY code ASC");
$criteria = mysqli_query($koneksidb, "SELECT * FROM criteria ORDER BY id ASC");

// Build array alternatif dan kriteria
$alts = [];
while ($a = mysqli_fetch_assoc($alternatif)) {
    $alts[$a['id']] = $a;
}

$krit = [];
while ($k = mysqli_fetch_assoc($criteria)) {
    $krit[$k['id']] = $k;
}

// Ambil semua nilai
$matrix = [];
foreach ($alts as $alt_id => $alt) {
    foreach ($krit as $k_id => $k) {
        $score = mysqli_query($koneksidb, "SELECT * FROM scores WHERE alternative_id = $alt_id AND criterion_id = $k_id");
        $row = mysqli_fetch_assoc($score);
        if ($row) {
            if ($row['sub_criterion_id']) {
                $sub = mysqli_fetch_assoc(mysqli_query($koneksidb, "SELECT value FROM sub_criteria WHERE id = " . $row['sub_criterion_id']));
                $value = $sub['value'];
            } else {
                $value = $row['score'];
            }
        } else {
            $value = 0;
        }
        $matrix[$alt_id][$k_id] = $value;
    }
}

// 1. Matriks Keputusan
echo "<h5>Matriks Keputusan</h5><table class='table table-bordered'><thead><tr><th>Alternatif</th>";
foreach ($krit as $k) echo "<th>{$k['code']}</th>";
echo "</tr></thead><tbody>";
foreach ($matrix as $aid => $nilai) {
    echo "<tr><td>{$alts[$aid]['code']}</td>";
    foreach ($nilai as $v) echo "<td>" . (float)$v . "</td>";
    echo "</tr>";
}
echo "</tbody></table>";

// 2. Normalisasi ARAS (xij / sum xij per kolom)
$normalized = [];
$sums = [];
foreach ($krit as $k_id => $k) {
    $sum = 0;
    foreach ($matrix as $alt) {
        $sum += $alt[$k_id];
    }
    $sums[$k_id] = $sum;
}

foreach ($matrix as $alt_id => $nilai) {
    foreach ($nilai as $k_id => $val) {
        $normalized[$alt_id][$k_id] = $sums[$k_id] != 0 ? $val / $sums[$k_id] : 0;
    }
}

// 3. Normalisasi Terbobot
$weighted = [];
foreach ($normalized as $alt_id => $nilai) {
    foreach ($nilai as $k_id => $val) {
        $weighted[$alt_id][$k_id] = $val * $krit[$k_id]['weight'];
    }
}

// 4. Hitung nilai preferensi (Si = Σ terbobot)
$preferensi = [];
foreach ($weighted as $alt_id => $nilai) {
    $preferensi[$alt_id] = round(array_sum($nilai), 4);
}

// Simpan ke tabel results
mysqli_query($koneksidb, "DELETE FROM results"); // Kosongkan dulu
arsort($preferensi); // Urutkan dari tertinggi
$rank = 1;
foreach ($preferensi as $aid => $val) {
    mysqli_query($koneksidb, "
        INSERT INTO results (alternative_id, preference_value, ranking) 
        VALUES ($aid, $val, $rank)
    ");
    $rank++;
}

// Ambil hasil ranking
$ranking = mysqli_query($koneksidb, "
    SELECT r.*, a.code, a.name 
    FROM results r 
    JOIN alternatives a ON r.alternative_id = a.id 
    ORDER BY r.ranking ASC
");

// Tampilkan hasil ranking
echo "<h5>Ranking Preferensi (ARAS - Tersimpan)</h5><table class='table table-bordered'><thead>
<tr><th>Peringkat</th><th>Kode</th><th>Nama</th><th>Nilai Preferensi (S<sub>i</sub>)</th></tr></thead><tbody>";
while ($row = mysqli_fetch_assoc($ranking)) {
    $badge = ($row['ranking'] == 1) ? " <span class='badge bg-success'>Terbaik</span>" : "";
    echo "<tr>
        <td>{$row['ranking']}</td>
        <td>{$row['code']}</td>
        <td>{$row['name']}{$badge}</td>
        <td>{$row['preference_value']}</td>
    </tr>";
}
echo "</tbody></table>";

?>
