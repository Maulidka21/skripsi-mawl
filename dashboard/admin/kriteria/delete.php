<?php
include '../../../config/config.php';
include '../../../config/koneksi.php';
session_start();
cek_admin();

// Pastikan parameter ID valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: ' . base_url('dashboard/admin/kriteria/index.php'));
    exit;
}

$id = (int) $_GET['id'];

// Cek apakah kriteria ada
$cek = mysqli_query($koneksidb, "SELECT * FROM criteria WHERE id = $id");
if (mysqli_num_rows($cek) === 0) {
    header('Location: ' . base_url('dashboard/admin/kriteria/index.php'));
    exit;
}

// Hapus data kriteria
$delete = mysqli_query($koneksidb, "DELETE FROM criteria WHERE id = $id");

if ($delete) {
    header('Location: ' . base_url('dashboard/admin/kriteria/index.php?success=delete'));
    exit;
} else {
    echo "<script>
        alert('Gagal menghapus data kriteria!');
        window.location.href = '" . base_url('dashboard/admin/kriteria/index.php') . "';
    </script>";
    exit;
}
