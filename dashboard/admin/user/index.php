<?php
include '../../../config/config.php';
include '../../../config/koneksi.php';
session_start();
cek_admin();

$page_title = 'Manajemen User';
$breadcrumbs = [
    ['title' => 'Home', 'link' => base_url('dashboard/admin')],
    ['title' => 'User Management']
];

$content = base_path('dashboard/admin/user/home.php');

include base_path('layout/main.php');

?>

<?php if (isset($_GET['success']) && $_GET['success'] == 'delete'): ?>
<script>
Swal.fire({
    title: 'Berhasil!',
    text: 'User berhasil dihapus.',
    icon: 'success',
    confirmButtonText: 'Oke'
})
</script>
<?php endif; ?>

<?php if (isset($_GET['success']) && $_GET['success'] == 'simpan'): ?>
<script>
Swal.fire({
    title: 'Berhasil!',
    text: 'User berhasil ditambah.',
    icon: 'success',
    confirmButtonText: 'Oke'
})
</script>
<?php endif; ?>

<?php if (isset($_GET['success']) && $_GET['success'] == 'update'): ?>
<script>
Swal.fire({
    title: 'Berhasil!',
    text: 'User berhasil diubah.',
    icon: 'success',
    confirmButtonText: 'Oke'
})
</script>
<?php endif; ?>

