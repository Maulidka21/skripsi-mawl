<?php
include '../../config/config.php';
$content = base_path('dashboard/admin/home.php');
include base_path('layout/main.php')
?>

<?php if (isset($_SESSION['login_success'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Login Berhasil',
            text: '<?php echo addslashes($_SESSION["login_success"]); ?>',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
    <?php unset($_SESSION['login_success']); ?>
<?php endif; ?>