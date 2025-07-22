<?php
include '../../../config/config.php';
include '../../../config/koneksi.php';
session_start();
cek_admin();

$page_title = 'Manajemen Calon Guru';
$breadcrumbs = [
    ['title' => 'Home', 'link' => base_url('dashboard/admin')],
    ['title' => 'Management Guru']
];

$content = base_path('dashboard/admin/alternatif/home.php');

include base_path('layout/main.php');

?>
<?php if (isset($_GET['success'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        <?php if ($_GET['success'] == 'delete'): ?>
            Swal.fire('Berhasil!', 'Data Guru berhasil dihapus.', 'success');
        <?php elseif ($_GET['success'] == 'simpan'): ?>
            Swal.fire('Berhasil!', 'Data Guru berhasil ditambah.', 'success');
        <?php elseif ($_GET['success'] == 'update'): ?>
            Swal.fire('Berhasil!', 'Data Guru berhasil diubah.', 'success');
        <?php endif; ?>
    </script>
<?php endif; ?>