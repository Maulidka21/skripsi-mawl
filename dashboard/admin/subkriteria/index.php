<?php
include '../../../config/config.php';
include '../../../config/koneksi.php';
session_start();
cek_admin();

// Ambil kriteria terkait
$criterion_id = isset($_GET['criterion_id']) ? (int) $_GET['criterion_id'] : 0;

$kriteria = mysqli_query($koneksidb, "SELECT * FROM criteria WHERE id = $criterion_id");
$k = mysqli_fetch_assoc($kriteria);

if (!$k) {
    echo "<script>
        alert('Kriteria tidak ditemukan.');
        window.location.href = '" . base_url('dashboard/admin/kriteria/index.php') . "';
    </script>";
    exit;
}

// Ambil subkriteria
$subs = mysqli_query($koneksidb, "SELECT * FROM sub_criteria WHERE criterion_id = $criterion_id ORDER BY value DESC");

$page_title = 'Subkriteria dari ' . $k['name'];
$breadcrumbs = [
    ['title' => 'Home', 'link' => base_url('dashboard/admin')],
    ['title' => 'Manajemen Kriteria', 'link' => base_url('dashboard/admin/kriteria/index.php')],
    ['title' => 'Subkriteria']
];

$content = base_path('dashboard/admin/subkriteria/home.php');
include base_path('layout/main.php');
?>
