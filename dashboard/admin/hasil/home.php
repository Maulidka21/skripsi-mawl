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
$matriks_x = $matrix;
echo "<h5>Matriks Keputusan</h5><table class='table table-bordered'><thead><tr><th>Alternatif</th>";
foreach ($krit as $k) echo "<th>{$k['code']}</th>";
echo "</tr></thead><tbody>";
foreach ($matrix as $aid => $nilai) {
    echo "<tr><td>{$alts[$aid]['code']}</td>";
    foreach ($nilai as $v) echo "<td>" . (float)$v . "</td>";
    echo "</tr>";
}
echo "</tbody></table>";

// -----------------------------------------------------------------------------
// LANGKAH 2: Menentukan Nilai Optimal untuk Setiap Kriteria (x_0j)
// -----------------------------------------------------------------------------
$optimal_row = [];
foreach ($krit as $k_id => $k) {
    $column = array_column($matriks_x, $k_id);
    if (empty($column)) continue; // Lanjut jika kolom kosong

    if (strtolower($k['type']) === 'benefit') {
        $optimal_row[$k_id] = max($column);
    } else { // cost
        $optimal_row[$k_id] = min($column);
    }
}

// -----------------------------------------------------------------------------
// LANGKAH 3: Normalisasi Matriks Keputusan (R)
// -----------------------------------------------------------------------------
$matriks_r = [];
$extended_matriks = $matriks_x;
// Tambahkan baris optimal ke awal matriks untuk perhitungan. Gunakan key '0' untuk menandainya.
$extended_matriks[0] = $optimal_row;
ksort($extended_matriks); // Urutkan array berdasarkan key agar baris 0 ada di paling atas

// Hitung total per kolom untuk normalisasi
$column_sums = [];
foreach ($krit as $k_id => $k) {
    $column_sums[$k_id] = 0;
    foreach ($extended_matriks as $row) {
        if (strtolower($k['type']) === 'cost') {
            // Hindari pembagian dengan nol jika ada nilai 0
            $column_sums[$k_id] += ($row[$k_id] > 0) ? (1 / $row[$k_id]) : 0;
        } else { // benefit
            $column_sums[$k_id] += $row[$k_id];
        }
    }
}

// Lakukan normalisasi
foreach ($extended_matriks as $alt_id => $row) {
    foreach ($krit as $k_id => $k) {
        if (strtolower($k['type']) === 'cost') {
            $inversed_val = ($row[$k_id] > 0) ? (1 / $row[$k_id]) : 0;
            $matriks_r[$alt_id][$k_id] = ($column_sums[$k_id] > 0) ? ($inversed_val / $column_sums[$k_id]) : 0;
        } else { // benefit
            $matriks_r[$alt_id][$k_id] = ($column_sums[$k_id] > 0) ? ($row[$k_id] / $column_sums[$k_id]) : 0;
        }
    }
}


// -----------------------------------------------------------------------------
// LANGKAH 4: Normalisasi Terbobot (V)
// -----------------------------------------------------------------------------
$matriks_v = [];
foreach ($matriks_r as $alt_id => $row) {
    foreach ($krit as $k_id => $k) {
        $matriks_v[$alt_id][$k_id] = $row[$k_id] * $k['weight'];
    }
}

// -----------------------------------------------------------------------------
// LANGKAH 5: Menghitung Fungsi Optimalitas (S_i)
// -----------------------------------------------------------------------------
$fungsi_s = [];
foreach ($matriks_v as $alt_id => $row) {
    $fungsi_s[$alt_id] = array_sum($row);
}

// Ambil nilai S untuk baris optimal (S0)
$s_optimal = $fungsi_s[0];

// -----------------------------------------------------------------------------
// LANGKAH 6: Menghitung Tingkat Utilitas (K_i)
// -----------------------------------------------------------------------------
$utilitas_k = [];
// Loop hanya pada alternatif asli (skip baris optimal dengan key '0')
foreach ($matriks_x as $alt_id => $row) {
    $s_i = $fungsi_s[$alt_id];
    $k_i = ($s_optimal > 0) ? ($s_i / $s_optimal) : 0;
    $utilitas_k[$alt_id] = round($k_i, 4);
}

// -----------------------------------------------------------------------------
// PERANKINGAN DAN PENYIMPANAN HASIL
// -----------------------------------------------------------------------------
mysqli_query($koneksidb, "DELETE FROM results"); // Kosongkan tabel hasil
arsort($utilitas_k); // Urutkan skor Ki dari tertinggi ke terendah

$rank = 1;
foreach ($utilitas_k as $alt_id => $score) {
    mysqli_query($koneksidb, "
        INSERT INTO results (alternative_id, preference_value, ranking) 
        VALUES ($alt_id, $score, $rank)
    ");
    $rank++;
}

// Ambil dan tampilkan hasil ranking (kode Anda untuk ini sudah benar)
$ranking = mysqli_query($koneksidb, "
    SELECT r.*, a.code, a.name 
    FROM results r 
    JOIN alternatives a ON r.alternative_id = a.id 
    ORDER BY r.ranking ASC
");

// Tampilkan hasil ranking
echo "<h5>Ranking Preferensi (ARAS - Final)</h5><table class='table table-bordered'><thead>
<tr><th>Peringkat</th><th>Kode</th><th>Nama</th><th>Skor Utilitas (K<sub>i</sub>)</th></tr></thead><tbody>";
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