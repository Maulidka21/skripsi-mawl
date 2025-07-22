<?php
include '../../../config/config.php';
include '../../../config/koneksi.php';
session_start();
cek_admin();

// Validasi parameter
$criterion_id = isset($_GET['criterion_id']) ? (int) $_GET['criterion_id'] : 0;
$subkriteria_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (!$criterion_id || !$subkriteria_id) {
    header('Location: ' . base_url('dashboard/admin/kriteria/index.php'));
    exit;
}

// Cek apakah subkriteria valid dan milik kriteria tersebut
$cek = mysqli_query($koneksidb, "SELECT * FROM sub_criteria WHERE id = $subkriteria_id AND criterion_id = $criterion_id");
if (mysqli_num_rows($cek) === 0) {
    header('Location: ' . base_url("dashboard/admin/subkriteria/index.php?criterion_id=$criterion_id"));
    exit;
}

// Proses hapus
$delete = mysqli_query($koneksidb, "DELETE FROM sub_criteria WHERE id = $subkriteria_id");

if ($delete) {
    header('Location: ' . base_url("dashboard/admin/subkriteria/index.php?criterion_id=$criterion_id&success=delete"));
    exit;
} else {
    echo "<script>
        alert('Gagal menghapus data!');
        window.location.href = '" . base_url("dashboard/admin/subkriteria/index.php?criterion_id=$criterion_id") . "';
    </script>";
    exit;
}
