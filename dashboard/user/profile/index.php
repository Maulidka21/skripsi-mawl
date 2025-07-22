<?php
include '../../../config/config.php';
include '../../../config/koneksi.php';
session_start();

$page_title = 'Halaman Profile';
$breadcrumbs = [
    ['title' => 'Home', 'link' => base_url('dashboard/user')],
    ['title' => 'Profile']
];

$content = base_path('dashboard/user/profile/home.php');
include base_path('layout/main.php');
?>

<?php if (isset($_GET['success']) && $_GET['success'] == 'update'): ?>
<script>
Swal.fire({
    title: 'Berhasil!',
    text: 'Data berhasil dihapus.',
    icon: 'success',
    confirmButtonText: 'Oke'
})
</script>
<?php endif; ?>