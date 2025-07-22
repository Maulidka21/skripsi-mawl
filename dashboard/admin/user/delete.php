<?php
session_start(); 
include '../../../config/koneksi.php';
include '../../../config/config.php';
cek_admin();

$id = intval($_GET['id']);

$query = mysqli_query($koneksidb, "DELETE FROM users WHERE id=$id");

if ($query) {
    header('Location: ' . base_url('dashboard/admin/user/index.php?success=delete'));
    exit();
} else {
    echo "Gagal menghapus user.";
}
?>