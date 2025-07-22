<?php
include '../../../config/config.php';
include '../../../config/koneksi.php';
session_start();
cek_admin();

// Pastikan ada ID yang dikirim via GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: ' . base_url('dashboard/admin/alternatif/index.php'));
    exit;
}

$id = (int) $_GET['id'];

// Cek apakah data alternatif ada
$cek = mysqli_query($koneksidb, "SELECT * FROM alternatives WHERE id = $id");
if (mysqli_num_rows($cek) === 0) {
    header('Location: ' . base_url('dashboard/admin/alternatif/index.php'));
    exit;
}

// Hapus data
$delete = mysqli_query($koneksidb, "DELETE FROM alternatives WHERE id = $id");

if ($delete) {
    header('Location: ' . base_url('dashboard/admin/alternatif/index.php?success=delete'));
    exit;
} else {
    echo "<script>
        alert('Gagal menghapus data!');
        window.location.href = '" . base_url('dashboard/admin/alternatif/index.php') . "';
    </script>";
    exit;
}
