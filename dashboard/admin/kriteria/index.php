<?php
include '../../../config/config.php';
include '../../../config/koneksi.php';
session_start();
cek_admin();

$page_title = 'Manajemen Kriteria';
$breadcrumbs = [
    ['title' => 'Home', 'link' => base_url('dashboard/admin')],
    ['title' => 'Manajemen Kriteria']
];

$content = base_path('dashboard/admin/kriteria/home.php');
include base_path('layout/main.php');
?>

<?php if (isset($_GET['success'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        <?php if ($_GET['success'] == 'delete'): ?>
            Swal.fire('Berhasil!', 'Kriteria berhasil dihapus.', 'success');
        <?php elseif ($_GET['success'] == 'simpan'): ?>
            Swal.fire('Berhasil!', 'Kriteria berhasil ditambah.', 'success');
        <?php elseif ($_GET['success'] == 'update'): ?>
            Swal.fire('Berhasil!', 'Kriteria berhasil diubah.', 'success');
        <?php endif; ?>
    </script>
<?php endif; ?>
