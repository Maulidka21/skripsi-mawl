<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="#" class="brand-link">
    <img src="<?= base_url('config/assets/') ?>dist/img/logo-sekolah.png" alt="Logo"
      class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">SPK Guru Terbaik</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- User Panel -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="<?= base_url('config/assets/') ?>dist/img/super-user.jpg" class="img-circle elevation-2"
          alt="User">
      </div>
      <div class="info">
        <a href="#" class="d-block"><?= htmlspecialchars($_SESSION['user']['username'] ?? 'User') ?></a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <?php $current_path = $_SERVER['REQUEST_URI']; ?>

    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
        <!-- Dashboard -->
        <li class="nav-item">
          <a href="<?= base_url('dashboard/' . $_SESSION['user']['role'] . '/') ?>"
            class="nav-link <?= rtrim($current_path, '/') === '/dashboard/' . $_SESSION['user']['role'] ? 'active' : '' ?>">
            <i class="nav-icon fas fa-home"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
          <!-- Menu Admin: Data SPK -->
          <li class="nav-item">
            <a href="<?= base_url('dashboard/admin/alternatif/index.php') ?>"
              class="nav-link <?= strpos($current_path, '/admin/alternatif') !== false ? 'active' : '' ?>">
              <i class="nav-icon fas fa-users"></i>
              <p>Data Guru</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="<?= base_url('dashboard/admin/kriteria/index.php') ?>"
              class="nav-link <?= strpos($current_path, '/admin/kriteria') !== false ? 'active' : '' ?>">
              <i class="nav-icon fas fa-list-alt"></i>
              <p>Data Kriteria</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="<?= base_url('dashboard/admin/penilaian/index.php') ?>"
              class="nav-link <?= strpos($current_path, '/admin/penilaian') !== false ? 'active' : '' ?>">
              <i class="nav-icon fas fa-edit"></i>
              <p>Penilaian</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="<?= base_url('dashboard/admin/hasil/index.php') ?>"
              class="nav-link <?= strpos($current_path, '/admin/hasil') !== false ? 'active' : '' ?>">
              <i class="nav-icon fas fa-poll"></i>
              <p>Hasil ARAS</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="<?= base_url('dashboard/admin/user/index.php') ?>"
              class="nav-link <?= strpos($current_path, '/admin/user') !== false ? 'active' : '' ?>">
              <i class="nav-icon fas fa-user-cog"></i>
              <p>Kelola Admin</p>
            </a>
          </li>
        <?php endif; ?>

        <?php if ($_SESSION['user']['role'] === 'user'): ?>
          <!-- Menu User -->
          <li class="nav-item">
            <a href="<?= base_url('dashboard/admin/hasil/index.php') ?>"
              class="nav-link <?= strpos($current_path, '/admin/hasil') !== false ? 'active' : '' ?>">
              <i class="nav-icon fas fa-poll-h"></i>
              <p>Hasil Perhitungan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('dashboard/user/profile/index.php') ?>"
              class="nav-link <?= strpos($current_path, '/user/profile') !== false ? 'active' : '' ?>">
              <i class="nav-icon fas fa-user"></i>
              <p>Profile</p>
            </a>
          </li>
        <?php endif; ?>

        <!-- Logout -->
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="return confirmLogout(event)">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>Logout</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>

<!-- SweetAlert2 Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function confirmLogout(event) {
    event.preventDefault();
    Swal.fire({
      title: 'Yakin ingin logout?',
      text: "Sesi Anda akan diakhiri.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, logout',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = '<?= base_url("login/logout.php") ?>';
      }
    });
  }
</script>